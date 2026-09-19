<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gymnasium;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * El Super Admin "entra como" el dueño (o un admin) de un gimnasio.
     */
    public function start(Gymnasium $gym)
    {
        // Buscar al usuario con el que iniciaremos sesión: dueño o primer admin
        $target = $gym->owner
            ?? User::where('gymnasium_id', $gym->id)->where('role', 'admin')->first()
            ?? User::where('gymnasium_id', $gym->id)->where('role', '!=', 'superadmin')->first();

        if (!$target) {
            return back()->with('error', 'Este gimnasio no tiene usuarios para impersonar.');
        }

        // Guardar quién es el Super Admin real para poder volver
        session(['impersonator_id' => Auth::id()]);

        Auth::login($target);

        return redirect()->route('dashboard')
            ->with('success', 'Ahora estás viendo el sistema como "' . $gym->name . '".');
    }

    /**
     * Volver a la sesión del Super Admin.
     */
    public function leave()
    {
        $impersonatorId = session('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('login');
        }

        $superAdmin = User::find($impersonatorId);
        session()->forget('impersonator_id');

        if (!$superAdmin || !$superAdmin->isSuperAdmin()) {
            Auth::logout();
            return redirect()->route('login');
        }

        Auth::login($superAdmin);

        return redirect()->route('superadmin.gyms.index')
            ->with('success', 'Volviste a tu sesión de Super Admin.');
    }
}
