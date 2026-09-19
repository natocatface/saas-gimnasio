<?php

namespace App\Http\Controllers;

use App\Models\GymSubscription;

class ReceiptController extends Controller
{
    /**
     * Recibo imprimible de una suscripción (gym o super admin).
     */
    public function show(GymSubscription $subscription)
    {
        $user = auth()->user();

        // Permisos: super admin ve cualquiera; un gym solo los suyos
        if (!$user->isSuperAdmin() && $subscription->gymnasium_id !== $user->gymnasium_id) {
            abort(404);
        }

        $subscription->load('gymnasium', 'saasPlan');

        return view('receipts.show', ['sub' => $subscription]);
    }
}
