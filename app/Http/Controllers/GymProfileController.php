<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GymProfileController extends Controller
{
    private function ensureAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Solo el administrador del gimnasio puede editar la marca.');
        }
    }

    public function edit()
    {
        $this->ensureAdmin();
        $gym = auth()->user()->gymnasium;
        return view('gym-profile.edit', compact('gym'));
    }

    public function update(Request $request)
    {
        $this->ensureAdmin();
        $gym = auth()->user()->gymnasium;

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:30',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'primary_color' => 'required|string|max:20',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
            'remove_logo'   => 'nullable|boolean',
        ], [
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.max'   => 'El logo no puede superar 1 MB.',
        ]);

        // Quitar logo
        if ($request->boolean('remove_logo') && $gym->logo) {
            Storage::disk('public')->delete($gym->logo);
            $gym->logo = null;
        }

        // Subir nuevo logo
        if ($request->hasFile('logo')) {
            if ($gym->logo) {
                Storage::disk('public')->delete($gym->logo);
            }
            $gym->logo = $request->file('logo')->store('logos', 'public');
        }

        $gym->name          = $data['name'];
        $gym->email         = $data['email'] ?? null;
        $gym->phone         = $data['phone'] ?? null;
        $gym->address       = $data['address'] ?? null;
        $gym->city          = $data['city'] ?? null;
        $gym->primary_color = $data['primary_color'];
        $gym->save();

        return redirect()->route('gym.profile.edit')->with('success', 'Marca de tu gimnasio actualizada.');
    }
}
