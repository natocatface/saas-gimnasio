<?php

namespace App\Models\Concerns;

use App\Models\Gymnasium;
use App\Models\Scopes\TenantScope;

/**
 * Trait que convierte un modelo en "multi-tenant":
 *  1. Aplica el TenantScope global (lecturas filtradas por gimnasio).
 *  2. Auto-asigna gymnasium_id al crear nuevos registros.
 *  3. Expone la relación gymnasium().
 *
 * Úsalo en todos los modelos que pertenecen a un gimnasio:
 * Member, Plan, Payment, Trainer, GymClass, Attendance, Inventory, Setting.
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        // 1) Filtrado automático en todas las lecturas
        static::addGlobalScope(new TenantScope());

        // 2) Asignar el gimnasio del usuario autenticado al crear
        static::creating(function ($model) {
            if (empty($model->gymnasium_id) && auth()->hasUser()) {
                $user = auth()->user();
                if ($user->gymnasium_id) {
                    $model->gymnasium_id = $user->gymnasium_id;
                }
            }
        });
    }

    public function gymnasium()
    {
        return $this->belongsTo(Gymnasium::class);
    }
}
