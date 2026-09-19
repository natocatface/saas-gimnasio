<?php

namespace App\Console\Commands;

use App\Models\Gymnasium;
use App\Models\GymNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTrialReminders extends Command
{
    protected $signature = 'notifications:trials';
    protected $description = 'Crea avisos para gimnasios cuya prueba está por vencer o ya venció.';

    public function handle(): int
    {
        $today = Carbon::today();
        $count = 0;

        // Pruebas que vencen en los próximos 3 días
        $soon = Gymnasium::where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->whereBetween('trial_ends_at', [$today, $today->copy()->addDays(3)])
            ->get();

        foreach ($soon as $gym) {
            $days = max(0, $today->diffInDays(Carbon::parse($gym->trial_ends_at), false));
            $count += $this->notifyOncePerDay(
                $gym->id,
                'trial_soon',
                'Tu prueba está por terminar',
                "Te quedan {$days} día(s) de prueba. Activa un plan para no perder acceso.",
                'fa-hourglass-half',
                '#f59e0b'
            );
        }

        // Pruebas ya vencidas (status trial pero fecha pasada)
        $expired = Gymnasium::where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', $today)
            ->get();

        foreach ($expired as $gym) {
            $count += $this->notifyOncePerDay(
                $gym->id,
                'trial_expired',
                'Tu prueba gratuita terminó',
                'Elige un plan para reactivar tu gimnasio.',
                'fa-lock',
                '#ef4444'
            );
        }

        $this->info("Avisos creados: {$count}");
        return self::SUCCESS;
    }

    /** Evita duplicar el mismo tipo de aviso el mismo día para un gym. */
    private function notifyOncePerDay(int $gymId, string $type, string $title, string $body, string $icon, string $color): int
    {
        $exists = GymNotification::withoutGlobalScope('tenant')
            ->where('gymnasium_id', $gymId)
            ->where('type', $type)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($exists) {
            return 0;
        }

        GymNotification::create([
            'gymnasium_id' => $gymId,
            'user_id'      => null,
            'type'         => $type,
            'title'        => $title,
            'body'         => $body,
            'icon'         => $icon,
            'color'        => $color,
            'url'          => null,
        ]);

        return 1;
    }
}
