<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymSubscription extends Model
{
    protected $table = 'gym_subscriptions';

    protected $fillable = [
        'gymnasium_id', 'saas_plan_id', 'amount', 'billing_cycle',
        'starts_at', 'ends_at', 'status', 'payment_ref', 'notes',
        'coupon_code', 'discount',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public function gymnasium() { return $this->belongsTo(Gymnasium::class); }
    public function saasPlan()  { return $this->belongsTo(SaasPlan::class); }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }
}
