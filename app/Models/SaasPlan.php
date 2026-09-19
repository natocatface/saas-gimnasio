<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price_monthly', 'price_yearly',
        'max_members', 'max_trainers', 'max_classes',
        'features', 'color', 'is_popular', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function gymnasiums()    { return $this->hasMany(Gymnasium::class); }
    public function subscriptions() { return $this->hasMany(GymSubscription::class); }

    public function getFeaturesArrayAttribute(): array
    {
        if (!$this->features) return [];
        $decoded = json_decode($this->features, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getYearlySavingAttribute(): float
    {
        return round(($this->price_monthly * 12) - $this->price_yearly, 2);
    }
}
