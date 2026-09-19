<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /** Claves de texto/numéricas que administra la página de Configuración. */
    private const KEYS = [
        // Datos de empresa
        'gym_name', 'gym_legal_name', 'gym_tax_id', 'gym_address', 'gym_city',
        'gym_phone', 'gym_email',
        // Horario
        'gym_opening', 'gym_closing',
        // Moneda y formato de número
        'currency', 'currency_symbol', 'currency_position',
        'decimals', 'thousands_sep', 'decimal_sep',
        // Impuestos
        'tax_name', 'tax_rate', 'tax_included',
        // Recibos / facturación
        'receipt_prefix', 'receipt_footer',
        // Regional
        'timezone', 'date_format', 'locale',
        // Apariencia
        'primary_color',
    ];

    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $gym = auth()->user()->gymnasium;
        return view('settings.index', compact('settings', 'gym'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'gym_name'          => 'nullable|string|max:255',
            'gym_legal_name'    => 'nullable|string|max:255',
            'gym_tax_id'        => 'nullable|string|max:40',
            'gym_address'       => 'nullable|string|max:255',
            'gym_city'          => 'nullable|string|max:120',
            'gym_phone'         => 'nullable|string|max:40',
            'gym_email'         => 'nullable|email|max:255',
            'gym_opening'       => 'nullable|string|max:10',
            'gym_closing'       => 'nullable|string|max:10',
            'currency'          => 'nullable|string|max:10',
            'currency_symbol'   => 'nullable|string|max:5',
            'currency_position' => 'nullable|in:before,after',
            'decimals'          => 'nullable|integer|min:0|max:4',
            'thousands_sep'     => 'nullable|in:coma,punto,espacio,ninguno',
            'decimal_sep'       => 'nullable|in:punto,coma',
            'tax_name'          => 'nullable|string|max:20',
            'tax_rate'          => 'nullable|numeric|min:0|max:100',
            'tax_included'      => 'nullable|boolean',
            'receipt_prefix'    => 'nullable|string|max:12',
            'receipt_footer'    => 'nullable|string|max:500',
            'timezone'          => 'nullable|string|max:60',
            'date_format'       => 'nullable|string|max:20',
            'locale'            => 'nullable|string|max:10',
            'primary_color'     => 'nullable|string|max:20',
            'logo'              => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
            'remove_logo'       => 'nullable|boolean',
        ], [
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.max'   => 'El logo no puede superar 1 MB.',
        ]);

        // Coherencia: los separadores de miles y decimales no pueden ser iguales
        if (($data['thousands_sep'] ?? null) === 'punto' && ($data['decimal_sep'] ?? 'punto') === 'punto') {
            $data['thousands_sep'] = 'coma';
        }
        if (($data['thousands_sep'] ?? null) === 'coma' && ($data['decimal_sep'] ?? null) === 'coma') {
            $data['thousands_sep'] = 'punto';
        }

        $data['tax_included'] = $request->boolean('tax_included') ? '1' : '0';

        // Guardar cada clave conocida en la tabla settings (por gimnasio)
        foreach (self::KEYS as $key) {
            if (array_key_exists($key, $data)) {
                Setting::updateOrCreate(['key' => $key], ['value' => (string) ($data[$key] ?? '')]);
            }
        }

        // ── Sincronizar identidad con la tabla gymnasiums (sidebar, recibos, etc.) ──
        $gym = auth()->user()->gymnasium;
        if ($gym) {
            // Logo: quitar
            if ($request->boolean('remove_logo') && $gym->logo) {
                Storage::disk('public')->delete($gym->logo);
                $gym->logo = null;
                Setting::updateOrCreate(['key' => 'logo'], ['value' => '']);
            }
            // Logo: subir nuevo
            if ($request->hasFile('logo')) {
                if ($gym->logo) {
                    Storage::disk('public')->delete($gym->logo);
                }
                $path = $request->file('logo')->store('logos', 'public');
                $gym->logo = $path;
                Setting::updateOrCreate(['key' => 'logo'], ['value' => $path]);
            }
            // Mantener nombre, color y contacto en sync
            if (!empty($data['gym_name']))      $gym->name = $data['gym_name'];
            if (!empty($data['primary_color'])) $gym->primary_color = $data['primary_color'];
            if (array_key_exists('gym_email', $data))   $gym->email = $data['gym_email'];
            if (array_key_exists('gym_phone', $data))   $gym->phone = $data['gym_phone'];
            if (array_key_exists('gym_address', $data)) $gym->address = $data['gym_address'];
            if (array_key_exists('gym_city', $data))    $gym->city = $data['gym_city'];
            $gym->save();
        }

        // Limpiar caché de settings del request actual
        if (function_exists('gym_settings_forget')) {
            gym_settings_forget();
        }

        return back()->with('success', 'Configuración guardada exitosamente.');
    }
}
