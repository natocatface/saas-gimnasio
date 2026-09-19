<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('portal.index');
        }
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'El correo es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        // El email de socios solo es único POR gimnasio, no globalmente.
        // Por eso no usamos Auth::attempt() (que toma el primer registro por
        // email y puede pertenecer a otro gimnasio): buscamos entre TODOS los
        // socios con ese correo y validamos la contraseña uno por uno.
        $candidates = Member::withoutGlobalScope('tenant')
            ->where('email', $credentials['email'])
            ->whereNotNull('password')
            ->get()
            ->filter(fn ($m) => Hash::check($credentials['password'], $m->password));

        if ($candidates->isEmpty()) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Credenciales incorrectas.');
        }

        // Priorizar una membresía activa si hay coincidencias en varios gimnasios.
        $member = $candidates->firstWhere('status', 'activo') ?? $candidates->first();

        if ($member->status !== 'activo') {
            return back()->withInput($request->only('email'))
                ->with('error', 'Tu membresía no está activa. Acércate a recepción.');
        }

        Auth::guard('member')->login($member, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('portal.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login');
    }
}
