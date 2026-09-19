<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    /**
     * Redirige segun el rol: SuperAdmin al panel SaaS, resto al dashboard del gym.
     */
    protected function redirectByRole()
    {
        if (Auth::user()->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'El correo es obligatorio',
            'email.email'       => 'Ingresa un correo valido',
            'password.required' => 'La contrasena es obligatoria',
            'password.min'      => 'La contrasena debe tener minimo 6 caracteres',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $target = Auth::user()->isSuperAdmin()
                ? route('superadmin.dashboard')
                : route('dashboard');

            return redirect()->intended($target)
                ->with('success', 'Bienvenido de vuelta, ' . Auth::user()->name . '!');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Credenciales incorrectas. Verifica tu correo y contrasena.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesion cerrada exitosamente.');
    }
}
