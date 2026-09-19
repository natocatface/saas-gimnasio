<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /** Roles que el dueño del gym puede asignar a su staff. */
    const ROLES = ['admin' => 'Administrador', 'trainer' => 'Entrenador', 'receptionist' => 'Recepción'];

    /** Solo el administrador del gimnasio puede gestionar al staff. */
    private function ensureAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Solo el administrador del gimnasio puede gestionar al equipo.');
        }
    }

    private function gymId(): int
    {
        return (int) auth()->user()->gymnasium_id;
    }

    public function index()
    {
        $this->ensureAdmin();
        $staff = User::where('gymnasium_id', $this->gymId())
            ->where('role', '!=', 'superadmin')
            ->orderByRaw("FIELD(role,'admin','trainer','receptionist')")
            ->get();

        $roles = self::ROLES;
        return view('staff.index', compact('staff', 'roles'));
    }

    public function create()
    {
        $this->ensureAdmin();
        $roles = self::ROLES;
        return view('staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:30',
            'role'     => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'] ?? null,
            'role'         => $data['role'],
            'password'     => Hash::make($data['password']),
            'status'       => 1,
            'gymnasium_id' => $this->gymId(),
        ]);

        return redirect()->route('staff.index')->with('success', 'Miembro del equipo agregado.');
    }

    public function edit(User $staff)
    {
        $this->ensureAdmin();
        $this->authorizeGym($staff);
        $roles = self::ROLES;
        return view('staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, User $staff)
    {
        $this->ensureAdmin();
        $this->authorizeGym($staff);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($staff->id)],
            'phone'    => 'nullable|string|max:30',
            'role'     => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => 'nullable|string|min:6',
        ]);

        $staff->name  = $data['name'];
        $staff->email = $data['email'];
        $staff->phone = $data['phone'] ?? null;
        $staff->role  = $data['role'];
        if (!empty($data['password'])) {
            $staff->password = Hash::make($data['password']);
        }
        $staff->save();

        return redirect()->route('staff.index')->with('success', 'Miembro del equipo actualizado.');
    }

    public function destroy(User $staff)
    {
        $this->ensureAdmin();
        $this->authorizeGym($staff);

        if ($staff->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        if ($staff->id === optional($staff->gymnasium)->owner_id) {
            return back()->with('error', 'No puedes eliminar al dueño del gimnasio.');
        }

        $staff->delete();
        return back()->with('success', 'Miembro del equipo eliminado.');
    }

    /** Garantiza que el usuario pertenece al gimnasio actual. */
    private function authorizeGym(User $staff): void
    {
        if ($staff->gymnasium_id !== $this->gymId() || $staff->role === 'superadmin') {
            abort(404);
        }
    }
}
