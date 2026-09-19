<?php

namespace App\Http\Controllers;

use App\Models\GymNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** Marcar todas como leídas. */
    public function readAll(Request $request)
    {
        GymNotification::forUser(auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Notificaciones marcadas como leídas.');
    }

    /** Marcar una y redirigir a su destino (si tiene). */
    public function read(GymNotification $notification)
    {
        // Seguridad: solo del gimnasio del usuario
        if ($notification->gymnasium_id !== auth()->user()->gymnasium_id) {
            abort(404);
        }

        $notification->update(['read_at' => now()]);

        return $notification->url ? redirect($notification->url) : back();
    }
}
