<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Global scope que filtra automáticamente cualquier consulta
 * por el gymnasium_id del usuario autenticado.
 *
 * - SuperAdmin: NO se aplica el filtro (ve todos los gimnasios).
 * - Sin sesión (CLI, seeders, jobs): NO se aplica el filtro.
 * - Usuario de gym: solo ve los registros de SU gimnasio.
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Sin usuario autenticado (consola, seeders) → sin filtro
        if (!auth()->hasUser()) {
            return;
        }

        $user = auth()->user();

        // SuperAdmin ve absolutamente todo
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return;
        }

        // Filtrar por el gimnasio del usuario
        if ($user->gymnasium_id) {
            $builder->where($model->getTable() . '.gymnasium_id', $user->gymnasium_id);
        }
    }
}
