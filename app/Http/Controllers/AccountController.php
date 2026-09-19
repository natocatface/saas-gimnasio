<?php

namespace App\Http\Controllers;

use App\Models\SaasPlan;

class AccountController extends Controller
{
    /**
     * Página de bloqueo cuando el gimnasio no está activo
     * (prueba vencida, suspendido o cancelado). El usuario sigue
     * autenticado para poder reactivar su plan.
     */
    public function blocked()
    {
        $gym = auth()->user()->gymnasium;

        // Si en realidad sí está activo, no hay nada que bloquear
        if (!$gym) {
            auth()->logout();
            return redirect()->route('login');
        }

        $trialExpired = $gym->status === 'trial'
            && $gym->trial_ends_at
            && $gym->trial_ends_at->isPast();
        $reason = match (true) {
            $gym->status === 'suspended' => 'Tu gimnasio está suspendido.',
            $gym->status === 'cancelled' => 'La suscripción de tu gimnasio fue cancelada.',
            $trialExpired                => 'Tu periodo de prueba gratuito terminó.',
            default                      => 'Tu suscripción no está activa.',
        };

        $plans = SaasPlan::where('is_active', 1)->orderBy('sort_order')->get();

        return view('account.blocked', compact('gym', 'reason', 'plans', 'trialExpired'));
    }
}
