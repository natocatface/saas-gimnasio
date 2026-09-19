<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // SuperAdmin pasa directo — tiene acceso a todo
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Usuario normal debe tener un gymnasium_id
        if (!$user->gymnasium_id) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Tu cuenta no está asociada a ningún gimnasio.');
        }

        // Verificar que el gimnasio esté activo
        $gym = $user->gymnasium;

        if (!$gym) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'El gimnasio asociado no existe.');
        }

        // Estado de la suscripción: si está bloqueado, llevar a la página
        // de estado (sin cerrar sesión) para que pueda reactivar su plan.
        $trialExpired = $gym->status === 'trial'
            && $gym->trial_ends_at
            && $gym->trial_ends_at->isPast();

        $blocked = in_array($gym->status, ['suspended', 'cancelled']) || $trialExpired;

        if ($blocked && !$request->routeIs('account.blocked', 'billing.*', 'logout')) {
            return redirect()->route('account.blocked');
        }

        // Compartir datos del gym con todas las vistas
        view()->share('currentGym', $gym);
        view()->share('gymPlan', $gym->saasPlan);

        // Símbolo de moneda configurado en Ajustes (por gimnasio)
        $currency = '$';
        try {
            $currency = \App\Models\Setting::where('key', 'currency_symbol')->value('value') ?: '$';
        } catch (\Throwable $e) { /* usar default */ }
        view()->share('currency', $currency);

        return $next($request);
    }
}
