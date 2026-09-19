<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
use App\Services\Sunat\SunatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Configuración de la Facturación Electrónica (SUNAT) del gimnasio.
 */
class BillingSettingController extends Controller
{
    private function guardAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
    }

    public function edit()
    {
        $this->guardAdmin();
        $setting    = BillingSetting::query()->firstOrNew([]);
        $greenter   = SunatService::greenterInstalled();
        $certExists = $setting->cert_path && Storage::disk('local')->exists($setting->cert_path);
        return view('billing_sunat.settings', compact('setting', 'greenter', 'certExists'));
    }

    /** Prueba la conexión / configuración con SUNAT. */
    public function test()
    {
        $this->guardAdmin();
        $setting = BillingSetting::query()->first();
        if (!$setting) {
            return back()->with('error', 'Primero guarda la configuración.');
        }
        $res = (new SunatService())->testConfig($setting);
        return back()->with($res['ok'] ? 'success' : 'error', $res['message']);
    }

    public function update(Request $request)
    {
        $this->guardAdmin();

        $data = $request->validate([
            'ruc'              => 'required|digits:11',
            'razon_social'     => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'direccion'        => 'nullable|string|max:255',
            'ubigeo'           => 'nullable|digits:6',
            'urbanizacion'     => 'nullable|string|max:120',
            'distrito'         => 'nullable|string|max:120',
            'provincia'        => 'nullable|string|max:120',
            'departamento'     => 'nullable|string|max:120',
            'sol_user'         => 'nullable|string|max:60',
            'sol_pass'         => 'nullable|string|max:255',
            'cert'             => 'nullable|file|max:2048',
            'cert_pass'        => 'nullable|string|max:255',
            'environment'      => 'required|in:beta,produccion',
            'driver'           => 'required|in:greenter,none',
            'auto_emit'        => 'nullable|boolean',
            'igv_percent'      => 'required|numeric|min:0|max:100',
            'serie_factura'    => 'required|string|max:4',
            'serie_boleta'     => 'required|string|max:4',
            'serie_nc'         => 'required|string|max:4',
            'serie_nd'         => 'required|string|max:4',
            'correlativo_factura' => 'nullable|integer|min:0',
            'correlativo_boleta'  => 'nullable|integer|min:0',
            'correlativo_nc'      => 'nullable|integer|min:0',
            'correlativo_nd'      => 'nullable|integer|min:0',
            'enabled'          => 'nullable|boolean',
        ]);

        $setting = BillingSetting::query()->firstOrNew([]);

        // No sobrescribir claves si el campo llega vacío (para no obligar a retipearlas)
        $keepIfEmpty = ['sol_pass', 'cert_pass'];
        foreach ($keepIfEmpty as $k) {
            if (empty($data[$k])) {
                unset($data[$k]);
            }
        }

        // Certificado digital
        if ($request->hasFile('cert')) {
            $ext = strtolower($request->file('cert')->getClientOriginalExtension());
            if (!in_array($ext, ['pem', 'pfx', 'p12'], true)) {
                return back()->with('error', 'El certificado debe ser .pem, .pfx o .p12');
            }
            $gymId = auth()->user()->gymnasium_id;
            // Borrar el anterior si existe
            if ($setting->cert_path && Storage::disk('local')->exists($setting->cert_path)) {
                Storage::disk('local')->delete($setting->cert_path);
            }
            $path = $request->file('cert')->storeAs("certs/gym_{$gymId}", 'certificado.' . $ext, 'local');
            $setting->cert_path = $path;
        }

        $setting->fill($data);
        $setting->enabled   = $request->boolean('enabled');
        $setting->auto_emit = $request->boolean('auto_emit');
        $setting->save();

        return back()->with('success', 'Configuración de facturación electrónica guardada.');
    }
}
