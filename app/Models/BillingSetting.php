<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

/**
 * Configuración del emisor electrónico (SUNAT) por gimnasio.
 */
class BillingSetting extends Model
{
    use HasTenant;

    protected $fillable = [
        'gymnasium_id',
        'ruc', 'razon_social', 'nombre_comercial', 'direccion',
        'ubigeo', 'urbanizacion', 'distrito', 'provincia', 'departamento',
        'sol_user', 'sol_pass', 'cert_path', 'cert_pass',
        'environment', 'igv_percent', 'moneda', 'driver', 'auto_emit',
        'serie_factura', 'serie_boleta', 'serie_nc', 'serie_nd',
        'correlativo_factura', 'correlativo_boleta', 'correlativo_nc', 'correlativo_nd',
        'enabled',
    ];

    protected $casts = [
        'enabled'     => 'boolean',
        'auto_emit'   => 'boolean',
        'igv_percent' => 'decimal:2',
    ];

    /** Devuelve el siguiente correlativo (sin incrementar) para un tipo de comprobante. */
    public function nextCorrelativo(string $tipoDoc): int
    {
        return match ($tipoDoc) {
            '01' => (int) $this->correlativo_factura + 1,
            '03' => (int) $this->correlativo_boleta + 1,
            '07' => (int) $this->correlativo_nc + 1,
            '08' => (int) $this->correlativo_nd + 1,
            default => 1,
        };
    }

    /** Serie configurada para un tipo de comprobante. */
    public function serieFor(string $tipoDoc): string
    {
        return match ($tipoDoc) {
            '01' => $this->serie_factura,
            '03' => $this->serie_boleta,
            '07' => $this->serie_nc,
            '08' => $this->serie_nd,
            default => 'F001',
        };
    }

    /** Persiste el avance del correlativo para el tipo indicado. */
    public function bumpCorrelativo(string $tipoDoc, int $value): void
    {
        $col = match ($tipoDoc) {
            '01' => 'correlativo_factura',
            '03' => 'correlativo_boleta',
            '07' => 'correlativo_nc',
            '08' => 'correlativo_nd',
            default => null,
        };
        if ($col) {
            $this->{$col} = $value;
            $this->save();
        }
    }
}
