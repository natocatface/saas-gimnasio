<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code', 'description', 'type', 'value',
        'saas_plan_id', 'max_uses', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active'  => 'boolean',
    ];

    public function saasPlan() { return $this->belongsTo(SaasPlan::class); }

    /** ¿El cupón es usable para un plan dado? */
    public function isValidFor(?int $planId = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if (!is_null($this->max_uses) && $this->used_count >= $this->max_uses) return false;
        if ($this->saas_plan_id && $planId && $this->saas_plan_id != $planId) return false;
        return true;
    }

    /** Descuento en dinero para un monto base. */
    public function discountFor(float $amount): float
    {
        $d = $this->type === 'percent'
            ? $amount * ($this->value / 100)
            : $this->value;
        return round(min($d, $amount), 2);
    }

    public function labelValue(): string
    {
        return $this->type === 'percent'
            ? rtrim(rtrim(number_format($this->value, 2), '0'), '.') . '%'
            : '$' . number_format($this->value, 2);
    }
}
