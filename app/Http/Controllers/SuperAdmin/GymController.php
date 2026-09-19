<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gymnasium;
use App\Models\GymSubscription;
use App\Models\Member;
use App\Models\SaasPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GymController extends Controller
{
    public function index(Request $request)
    {
        $query = Gymnasium::with('saasPlan')->withCount('users')->latest();

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('slug', 'like', "%$s%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->plan) {
            $query->where('saas_plan_id', $request->plan);
        }

        $gyms  = $query->paginate(12)->withQueryString();
        $plans = SaasPlan::orderBy('sort_order')->get();

        $counts = [
            'total'     => Gymnasium::count(),
            'active'    => Gymnasium::where('status', 'active')->count(),
            'trial'     => Gymnasium::where('status', 'trial')->count(),
            'suspended' => Gymnasium::whereIn('status', ['suspended', 'cancelled'])->count(),
        ];

        return view('superadmin.gyms.index', compact('gyms', 'plans', 'counts'));
    }

    public function show(Gymnasium $gym)
    {
        $gym->load('saasPlan', 'owner', 'subscriptions.saasPlan');

        // Estadísticas reales del gym (sin tenant scope porque consultamos por gym_id explícito)
        $gymStats = [
            'members'  => Member::withoutGlobalScope('tenant')->where('gymnasium_id', $gym->id)->count(),
            'users'    => User::where('gymnasium_id', $gym->id)->count(),
        ];

        $subscriptions = $gym->subscriptions()->latest()->take(10)->get();

        return view('superadmin.gyms.show', compact('gym', 'gymStats', 'subscriptions'));
    }

    public function create()
    {
        $plans = SaasPlan::orderBy('sort_order')->get();
        return view('superadmin.gyms.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'owner_name'   => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'nullable|string|max:30',
            'password'     => 'required|string|min:6',
            'saas_plan_id' => 'required|exists:saas_plans,id',
            'status'       => 'required|in:active,trial,suspended,cancelled',
        ]);

        $plan = SaasPlan::findOrFail($data['saas_plan_id']);

        $gym = Gymnasium::create([
            'name'          => $data['name'],
            'slug'          => $this->uniqueSlug($data['name']),
            'email'         => $data['email'],
            'phone'         => $data['phone'] ?? null,
            'country'       => 'PE',
            'primary_color' => $plan->color ?? '#7c3aed',
            'saas_plan_id'  => $plan->id,
            'status'        => $data['status'],
            'trial_ends_at' => $data['status'] === 'trial' ? now()->addDays(14) : null,
            'max_members'   => $plan->max_members,
        ]);

        $owner = User::create([
            'name'         => $data['owner_name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'role'         => 'admin',
            'status'       => 1,
            'phone'        => $data['phone'] ?? null,
            'gymnasium_id' => $gym->id,
        ]);

        $gym->update(['owner_id' => $owner->id]);

        return redirect()->route('superadmin.gyms.show', $gym)
            ->with('success', 'Gimnasio "' . $gym->name . '" creado correctamente.');
    }

    public function edit(Gymnasium $gym)
    {
        $plans = SaasPlan::orderBy('sort_order')->get();
        return view('superadmin.gyms.edit', compact('gym', 'plans'));
    }

    public function update(Request $request, Gymnasium $gym)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string|max:30',
            'address'       => 'nullable|string',
            'city'          => 'nullable|string|max:100',
            'saas_plan_id'  => 'required|exists:saas_plans,id',
            'status'        => 'required|in:active,trial,suspended,cancelled',
            'max_members'   => 'required|integer|min:1',
            'trial_ends_at' => 'nullable|date',
        ]);

        $gym->update($data);

        return redirect()->route('superadmin.gyms.show', $gym)
            ->with('success', 'Gimnasio actualizado correctamente.');
    }

    /** Cambiar estado rápido (activar / suspender). */
    public function setStatus(Request $request, Gymnasium $gym)
    {
        $request->validate(['status' => 'required|in:active,trial,suspended,cancelled']);
        $gym->update(['status' => $request->status]);

        $labels = [
            'active'    => 'activado',
            'trial'     => 'puesto en prueba',
            'suspended' => 'suspendido',
            'cancelled' => 'cancelado',
        ];

        return back()->with('success', 'Gimnasio ' . ($labels[$request->status] ?? 'actualizado') . '.');
    }

    public function destroy(Gymnasium $gym)
    {
        $name = $gym->name;
        $gym->delete();

        return redirect()->route('superadmin.gyms.index')
            ->with('success', 'Gimnasio "' . $name . '" eliminado.');
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'gym';
        $slug = $base;
        $i = 1;
        while (Gymnasium::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
