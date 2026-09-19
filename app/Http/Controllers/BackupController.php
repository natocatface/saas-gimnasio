<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Mantenimiento de datos del gimnasio (multi-tenant).
 * -----------------------------------------------------
 * IMPORTANTE: cada gimnasio (cliente) SOLO puede tocar SUS PROPIOS datos.
 * Ninguna operación afecta a otros gimnasios que usan el sistema.
 *
 *  - Copia de seguridad  : exporta un .sql con los datos de ESTE gimnasio.
 *  - Restauración        : restaura un respaldo que pertenezca a ESTE gimnasio.
 *  - Reseteo             : borra los datos de ESTE gimnasio (empezar de cero).
 *
 * Solo accesible para el administrador del gimnasio.
 */
class BackupController extends Controller
{
    /**
     * Tablas operativas del gimnasio (todas tienen columna `gymnasium_id`).
     * `support_ticket_messages` NO aparece: se borra en cascada al borrar
     * sus tickets (ON DELETE CASCADE). `coupons` y `gym_subscriptions` son
     * de nivel plataforma (SaaS) y NO se tocan desde aquí.
     */
    private const OPERATIONAL_TABLES = [
        'attendance',
        'class_enrollments',
        'gym_classes',
        'inventory',
        'member_measurements',
        'members',
        'payments',
        'routines',
        'trainers',
        'gym_notifications',
        'support_tickets',
    ];

    /** Orden de exportación (padres antes que hijos). */
    private const BACKUP_TABLES = [
        'members',
        'trainers',
        'plans',
        'gym_classes',
        'attendance',
        'payments',
        'inventory',
        'routines',
        'member_measurements',
        'gym_notifications',
        'support_tickets',
        'class_enrollments',
        'settings',
    ];

    /** Marcador que identifica a qué gimnasio pertenece un respaldo. */
    private const MARKER = 'GYMSAAS-BACKUP gym_id=';

    /** Solo el administrador del gimnasio puede usar este módulo. */
    private function guardAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403,
            'Solo un administrador puede acceder al mantenimiento de datos.');
    }

    private function gymId(): ?int
    {
        return auth()->user()->gymnasium_id ?? null;
    }

    /** Carpeta de respaldos EXCLUSIVA de este gimnasio. */
    private function backupDir(): string
    {
        $dir = storage_path('app/backups/gym_' . (int) $this->gymId());
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        return $dir;
    }

    /* ===================================================================== */
    /*  Pantalla principal                                                   */
    /* ===================================================================== */
    public function index()
    {
        $this->guardAdmin();
        $gymId = $this->gymId();

        $backups = collect(File::files($this->backupDir()))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.sql'))
            ->map(fn ($f) => [
                'name' => $f->getFilename(),
                'size' => $this->humanSize($f->getSize()),
                'date' => date('d/m/Y H:i', $f->getMTime()),
                'ts'   => $f->getMTime(),
            ])
            ->sortByDesc('ts')
            ->values();

        // Estadísticas SOLO de este gimnasio
        $stats = [
            'gym'          => DB::table('gymnasiums')->where('id', $gymId)->value('name') ?? 'Mi gimnasio',
            'socios'       => $this->countGym('members', $gymId),
            'pagos'        => $this->countGym('payments', $gymId),
            'clases'       => $this->countGym('gym_classes', $gymId),
            'entrenadores' => $this->countGym('trainers', $gymId),
        ];

        return view('backup.index', compact('backups', 'stats'));
    }

    /* ===================================================================== */
    /*  1) Copia de seguridad (solo datos de este gimnasio)                  */
    /* ===================================================================== */
    public function download()
    {
        $this->guardAdmin();
        $file = 'backup_gym' . (int) $this->gymId() . '_' . date('Y-m-d_His') . '.sql';
        $path = $this->backupDir() . DIRECTORY_SEPARATOR . $file;

        File::put($path, $this->generateGymDump((int) $this->gymId()));

        return response()->download($path, $file, ['Content-Type' => 'application/sql']);
    }

    /** Descargar un respaldo propio ya existente. */
    public function downloadFile(string $file)
    {
        $this->guardAdmin();
        $path = $this->safeBackupPath($file);
        abort_unless($path && File::exists($path), 404);
        return response()->download($path, $file);
    }

    /** Eliminar un respaldo propio. */
    public function destroyFile(string $file)
    {
        $this->guardAdmin();
        $path = $this->safeBackupPath($file);
        if ($path && File::exists($path)) {
            File::delete($path);
            return back()->with('success', 'Respaldo eliminado correctamente.');
        }
        return back()->with('error', 'No se encontró el respaldo indicado.');
    }

    /* ===================================================================== */
    /*  2) Restaurar (solo un respaldo de ESTE gimnasio)                     */
    /* ===================================================================== */
    public function restore(Request $request)
    {
        $this->guardAdmin();
        $request->validate([
            'backup_file' => 'required|file',
            'confirm'     => 'required|in:RESTAURAR',
        ], [
            'confirm.in'           => 'Debes escribir RESTAURAR para confirmar.',
            'backup_file.required' => 'Selecciona un archivo .sql para restaurar.',
        ]);

        $upload = $request->file('backup_file');
        if (strtolower($upload->getClientOriginalExtension()) !== 'sql') {
            return back()->with('error', 'El archivo debe tener extensión .sql');
        }

        $sql   = File::get($upload->getRealPath());
        $gymId = (int) $this->gymId();

        // Seguridad multi-tenant: el respaldo DEBE pertenecer a este gimnasio.
        if (!preg_match('/' . preg_quote(self::MARKER, '/') . '(\d+)/', $sql, $m) || (int) $m[1] !== $gymId) {
            return back()->with('error',
                'Este respaldo no pertenece a tu gimnasio, por seguridad no se puede restaurar.');
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $this->deleteGymData($gymId, true, true);   // limpia el estado actual del gimnasio
            $count = $this->runSqlScript($sql);          // reinserta los datos del respaldo
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return back()->with('error', 'Error al restaurar: ' . $e->getMessage());
        }

        return back()->with('success', "Datos de tu gimnasio restaurados. Se ejecutaron {$count} sentencias.");
    }

    /* ===================================================================== */
    /*  3) Resetear (solo los datos de ESTE gimnasio)                        */
    /* ===================================================================== */
    public function reset(Request $request)
    {
        $this->guardAdmin();
        $request->validate([
            'confirm' => 'required|in:RESETEAR',
        ], [
            'confirm.in' => 'Debes escribir RESETEAR para confirmar el borrado.',
        ]);

        $gymId = (int) $this->gymId();
        if (!$gymId) {
            return back()->with('error', 'No se pudo determinar tu gimnasio.');
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $this->deleteGymData($gymId, $request->boolean('wipe_plans'), $request->boolean('wipe_settings'));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return back()->with('error', 'Error al resetear: ' . $e->getMessage());
        }

        return back()->with('success', 'Los datos de tu gimnasio fueron reseteados. Tu cuenta y acceso se conservan.');
    }

    /* ===================================================================== */
    /*  Helpers                                                              */
    /* ===================================================================== */

    /** Borra SOLO los datos del gimnasio indicado. */
    private function deleteGymData(int $gymId, bool $wipePlans, bool $wipeSettings): void
    {
        foreach (self::OPERATIONAL_TABLES as $t) {
            if ($this->tableExists($t)) {
                DB::table($t)->where('gymnasium_id', $gymId)->delete();
            }
        }
        if ($wipePlans && $this->tableExists('plans')) {
            DB::table('plans')->where('gymnasium_id', $gymId)->delete();
        }
        if ($wipeSettings && $this->tableExists('settings')) {
            // Solo la configuración de ESTE gimnasio; nunca la global (gymnasium_id NULL).
            DB::table('settings')->where('gymnasium_id', $gymId)->delete();
        }
    }

    /** Genera un volcado .sql SOLO con los datos del gimnasio. */
    private function generateGymDump(int $gymId): string
    {
        $pdo     = DB::connection()->getPdo();
        $gymName = DB::table('gymnasiums')->where('id', $gymId)->value('name') ?? 'Gimnasio';
        $now     = date('Y-m-d H:i:s');

        $out  = "-- ============================================================\n";
        $out .= "-- GymSaaS Pro — Copia de seguridad del gimnasio\n";
        $out .= "-- Gimnasio: {$gymName}\n";
        $out .= "-- " . self::MARKER . $gymId . "\n";
        $out .= "-- Generado: {$now}\n";
        $out .= "-- ============================================================\n\n";
        $out .= "SET NAMES utf8mb4;\n";
        $out .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach (self::BACKUP_TABLES as $table) {
            if (!$this->tableExists($table)) {
                continue;
            }
            $rows = DB::table($table)->where('gymnasium_id', $gymId)->get();
            if ($rows->isEmpty()) {
                continue;
            }

            $columns = array_keys((array) $rows->first());
            $colList = '`' . implode('`,`', $columns) . '`';
            $out .= "-- Datos de `{$table}`\n";

            foreach ($rows->chunk(100) as $chunk) {
                $values = [];
                foreach ($chunk as $row) {
                    $vals = [];
                    foreach ((array) $row as $v) {
                        if (is_null($v)) {
                            $vals[] = 'NULL';
                        } elseif (is_int($v) || is_float($v)) {
                            $vals[] = $v;
                        } else {
                            $vals[] = $pdo->quote((string) $v);
                        }
                    }
                    $values[] = '(' . implode(',', $vals) . ')';
                }
                $out .= "INSERT INTO `{$table}` ({$colList}) VALUES\n" . implode(",\n", $values) . ";\n";
            }
            $out .= "\n";
        }

        // Mensajes de tickets (van por su ticket, sin gymnasium_id propio)
        if ($this->tableExists('support_ticket_messages') && $this->tableExists('support_tickets')) {
            $ticketIds = DB::table('support_tickets')->where('gymnasium_id', $gymId)->pluck('id');
            if ($ticketIds->isNotEmpty()) {
                $rows = DB::table('support_ticket_messages')->whereIn('ticket_id', $ticketIds)->get();
                if ($rows->isNotEmpty()) {
                    $columns = array_keys((array) $rows->first());
                    $colList = '`' . implode('`,`', $columns) . '`';
                    $out .= "-- Datos de `support_ticket_messages`\n";
                    foreach ($rows->chunk(100) as $chunk) {
                        $values = [];
                        foreach ($chunk as $row) {
                            $vals = [];
                            foreach ((array) $row as $v) {
                                $vals[] = is_null($v) ? 'NULL' : ((is_int($v) || is_float($v)) ? $v : $pdo->quote((string) $v));
                            }
                            $values[] = '(' . implode(',', $vals) . ')';
                        }
                        $out .= "INSERT INTO `support_ticket_messages` ({$colList}) VALUES\n" . implode(",\n", $values) . ";\n";
                    }
                    $out .= "\n";
                }
            }
        }

        $out .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $out;
    }

    private function countGym(string $table, ?int $gymId): int
    {
        if (!$this->tableExists($table)) {
            return 0;
        }
        return (int) DB::table($table)->where('gymnasium_id', $gymId)->count();
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    /**
     * Ejecuta un script SQL sentencia por sentencia (consciente de comillas
     * y comentarios).
     */
    private function runSqlScript(string $sql): int
    {
        $pdo        = DB::connection()->getPdo();
        $statements = $this->splitSql($sql);

        $executed = 0;
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        try {
            foreach ($statements as $stmt) {
                $stmt = trim($stmt);
                if ($stmt === '') continue;
                $pdo->exec($stmt);
                $executed++;
            }
        } finally {
            $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
        }
        return $executed;
    }

    /** Divisor de sentencias SQL consciente de comillas y comentarios. */
    private function splitSql(string $sql): array
    {
        $statements = [];
        $buffer     = '';
        $len        = strlen($sql);
        $inString   = false;   // false | "'" | '"' | '`'
        $i          = 0;

        while ($i < $len) {
            $ch = $sql[$i];

            if ($inString !== false) {
                $buffer .= $ch;
                if ($ch === '\\' && $i + 1 < $len) {
                    $buffer .= $sql[$i + 1];
                    $i += 2;
                    continue;
                }
                if ($ch === $inString) {
                    if ($i + 1 < $len && $sql[$i + 1] === $inString) {
                        $buffer .= $sql[$i + 1];
                        $i += 2;
                        continue;
                    }
                    $inString = false;
                }
                $i++;
                continue;
            }

            if (($ch === '-' && $i + 1 < $len && $sql[$i + 1] === '-') || $ch === '#') {
                while ($i < $len && $sql[$i] !== "\n") $i++;
                continue;
            }

            if ($ch === '/' && $i + 1 < $len && $sql[$i + 1] === '*') {
                $bang  = ($i + 2 < $len && $sql[$i + 2] === '!');
                $start = $i;
                $i += 2;
                while ($i + 1 < $len && !($sql[$i] === '*' && $sql[$i + 1] === '/')) $i++;
                $i += 2;
                if ($bang) {
                    $buffer .= substr($sql, $start, $i - $start);
                }
                continue;
            }

            if ($ch === "'" || $ch === '"' || $ch === '`') {
                $inString = $ch;
                $buffer  .= $ch;
                $i++;
                continue;
            }

            if ($ch === ';') {
                $statements[] = $buffer;
                $buffer = '';
                $i++;
                continue;
            }

            $buffer .= $ch;
            $i++;
        }

        if (trim($buffer) !== '') {
            $statements[] = $buffer;
        }
        return $statements;
    }

    /** Ruta segura de un respaldo dentro de la carpeta del gimnasio. */
    private function safeBackupPath(?string $file): ?string
    {
        if (!$file) return null;
        $base = basename($file);                 // evita path traversal
        if (!Str::endsWith($base, '.sql')) return null;
        return $this->backupDir() . DIRECTORY_SEPARATOR . $base;
    }

    private function humanSize(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
