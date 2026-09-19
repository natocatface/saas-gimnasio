<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasTenant
 * Auto-scope all queries by gymnasium_id of the authenticated user.
 * Add this trait to all models that belong to a gymnasium.
 */
trait HasTenant
{
    /**
     * Boot the trait — apply global scope automatically.
     */
    protected static function bootHasTenant(): void
    {
        // Auto-filter queries by current gym
        static::addGlobalScope('tenant', function (Builder $query) {
            $gymId = static::currentGymId();
            if ($gymId) {
                $query->where(
                    (new static)->getTable() . '.gymnasium_id',
                    $gymId
                );
            }
        });

        // Auto-assign gymnasium_id on create
        static::creating(function ($model) {
            if (empty($model->gymnasium_id)) {
                $model->gymnasium_id = static::currentGymId();
            }
        });
    }

    /**
     * Get the current gymnasium_id from the authenticated user.
     */
    protected static function currentGymId(): ?int
    {
        if (!auth()->check()) return null;

        $user = auth()->user();

        // SuperAdmin sees everything — no scope
        if ($user->role === 'superadmin') return null;

        return $user->gymnasium_id;
    }

    /**
     * Scope: bypass tenant filter (for super admin queries).
     */
    public function scopeAllTenants(Builder $query): Builder
    {
        return $query->withoutGlobalScope('tenant');
    }

    /**
     * Scope: filter by specific gymnasium.
     */
    public function scopeForGym(Builder $query, int $gymId): Builder
    {
        return $query->withoutGlobalScope('tenant')
                     ->where($this->getTable() . '.gymnasium_id', $gymId);
    }

    public function gymnasium()
    {
        return $this->belongsTo(\App\Models\Gymnasium::class);
    }
}
