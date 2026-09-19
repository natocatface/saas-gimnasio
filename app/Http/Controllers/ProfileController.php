<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'            => 'nullable|string|max:30',
            'current_password' => 'nullable|required_with:password|string',
            'password'         => 'nullable|string|min:6|confirmed',
        ], [
            'current_password.required_with' => 'Ingresa tu contraseña actual para cambiarla.',
            'password.confirmed'             => 'La nueva contraseña no coincide con la confirmación.',
        ]);

        // Cambio de contraseña (si se solicitó)
        if (!empty($data['password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return back()->with('error', 'La contraseña actual es incorrecta.');
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado.');
    }
}
