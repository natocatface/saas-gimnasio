<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

/**
 * Comprobante electrónico (Factura 01, Boleta 03, Nota de Crédito 07, Nota de Débito 08).
 */
class ElectronicDocument extends Model
{
    use HasTenant;

    protected $fillable = [
        'gymnasium_id', 'payment_id', 'member_id',
        'tipo_doc', 'serie', 'correlativo', 'fecha_emision', 'moneda',
        'cliente_tipo_doc', 'cliente_num_doc', 'cliente_razon_social', 'cliente_direccion',
        'items', 'mto_oper_gravadas', 'mto_igv', 'total',
        'doc_afectado_tipo', 'doc_afectado_serie_num', 'cod_motivo', 'des_motivo',
        'estado', 'sunat_code', 'sunat_description', 'hash', 'xml_path', 'cdr_path',
        'created_by',
    ];

    protected $casts = [
        'items'             => 'array',
        'fecha_emision'     => 'datetime',
        'mto_oper_gravadas' => 'decimal:2',
        'mto_igv'           => 'decimal:2',
        'total'             => 'decimal:2',
    ];

    public const TIPOS = [
        '01' => 'Factura',
        '03' => 'Boleta',
        '07' => 'Nota de Crédito',
        '08' => 'Nota de Débito',
    ];

    public function payment() { return $this->belongsTo(Payment::class); }
    public function member()  { return $this->belongsTo(Member::class); }

    public function getTipoNombreAttribute(): string
    {
        return self::TIPOS[$this->tipo_doc] ?? $this->tipo_doc;
    }

    public function getNumeroAttribute(): string
    {
        return $this->serie . '-' . $this->correlativo;
    }

    public function estadoBadge(): string
    {
        return match ($this->estado) {
            'aceptado'  => 'success',
            'enviado'   => 'info',
            'pendiente' => 'warning',
            'rechazado', 'error' => 'danger',
            'anulado'   => 'gray',
            default     => 'gray',
        };
    }
}
