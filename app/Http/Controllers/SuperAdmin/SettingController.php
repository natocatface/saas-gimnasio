<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Configuración global del SaaS (nivel plataforma).
 * Define la moneda única que se aplica a TODOS los módulos del sistema
 * (panel Super Admin, facturación, recibos y gimnasios).
 *
 * Los valores se guardan en la tabla `settings` con gymnasium_id = NULL
 * para diferenciarlos de la configuración por gimnasio.
 */
class SettingController extends Controller
{
    private const KEYS = [
        'currency', 'currency_symbol', 'currency_position',
        'decimals', 'thousands_sep', 'decimal_sep',
    ];

    public function index()
    {
        $settings = DB::table('settings')->whereNull('gymnasium_id')->pluck('value', 'key');
        return view('superadmin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'currency'          => 'nullable|string|max:10',
            'currency_symbol'   => 'nullable|string|max:6',
            'currency_position' => 'nullable|in:before,after',
            'decimals'          => 'nullable|integer|min:0|max:4',
            'thousands_sep'     => 'nullable|in:coma,punto,espacio,ninguno',
            'decimal_sep'       => 'nullable|in:punto,coma',
        ]);

        // Coherencia: los separadores de miles y decimales no pueden coincidir
        if (($data['thousands_sep'] ?? null) === 'punto' && ($data['decimal_sep'] ?? 'punto') === 'punto') {
            $data['thousands_sep'] = 'coma';
        }
        if (($data['thousands_sep'] ?? null) === 'coma' && ($data['decimal_sep'] ?? null) === 'coma') {
            $data['thousands_sep'] = 'punto';
        }

        foreach (self::KEYS as $key) {
            if (array_key_exists($key, $data)) {
                DB::table('settings')->updateOrInsert(
                    ['gymnasium_id' => null, 'key' => $key],
                    ['value' => (string) ($data[$key] ?? ''), 'group' => 'saas', 'updated_at' => now()]
                );
            }
        }

        if (function_exists('saas_settings_forget')) {
            saas_settings_forget();
        }

        return back()->with('success', 'Moneda del sistema actualizada. Se aplica a todos los módulos.');
    }
}
