<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gymnasium extends Model
{
    // Eloquent pluraliza "Gymnasium" como "gymnasia"; forzamos el nombre real.
    protected $table = 'gymnasiums';

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address', 'city', 'country',
        'logo', 'primary_color', 'saas_plan_id', 'owner_id',
        'status', 'trial_ends_at', 'max_members', 'settings',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'settings'      => 'array',
    ];

    // ── Relaciones ─────────────────────────────────────────────
    public function saasPlan()     { return $this->belongsTo(SaasPlan::class); }
    public function owner()        { return $this->belongsTo(User::class, 'owner_id'); }
    public function users()        { return $this->hasMany(User::class); }
    public function members()      { return $this->hasMany(Member::class); }
    public function plans()        { return $this->hasMany(Plan::class); }
    public function trainers()     { return $this->hasMany(Trainer::class); }
    public function classes()      { return $this->hasMany(GymClass::class); }
    public function payments()     { return $this->hasMany(Payment::class); }
    public function subscriptions(){ return $this->hasMany(GymSubscription::class); }

    // ── Helpers ────────────────────────────────────────────────
    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trial']);
    }

    public function isOnTrial(): bool
    {
        return $this->status === 'trial' &&
               $this->trial_ends_at &&
               $this->trial_ends_at->isFuture();
    }

    public function trialDaysLeft(): int
    {
        if (!$this->trial_ends_at) return 0;
        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : asset('images/default-gym.png');
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>=', today())
            ->latest()
            ->first();
    }

    // ── Límites por plan ───────────────────────────────────────
    /** Tope del plan para un recurso: members | trainers | classes. */
    public function planLimit(string $resource): int
    {
        $plan = $this->saasPlan;
        if (!$plan) return PHP_INT_MAX;

        return (int) match ($resource) {
            'members'  => $plan->max_members,
            'trainers' => $plan->max_trainers,
            'classes'  => $plan->max_classes,
            default    => PHP_INT_MAX,
        };
    }

    /** Uso actual real de un recurso (sin tenant scope). */
    public function usage(string $resource): int
    {
        $map = [
            'members'  => Member::class,
            'trainers' => Trainer::class,
            'classes'  => GymClass::class,
        ];
        if (!isset($map[$resource])) return 0;

        return $map[$resource]::withoutGlobalScope('tenant')
            ->where('gymnasium_id', $this->id)
            ->count();
    }

    /** ¿Puede agregar uno más de este recurso? */
    public function canAdd(string $resource): bool
    {
        return $this->usage($resource) < $this->planLimit($resource);
    }

    /** Porcentaje de uso (0-100) para barras de progreso. */
    public function usagePercent(string $resource): int
    {
        $limit = $this->planLimit($resource);
        if ($limit <= 0 || $limit === PHP_INT_MAX) return 0;
        return min(100, (int) round($this->usage($resource) / $limit * 100));
    }

    /** Etiqueta legible del límite (∞ para ilimitado). */
    public function limitLabel(string $resource): string
    {
        $limit = $this->planLimit($resource);
        return $limit >= 999999 ? '∞' : (string) $limit;
    }
}
