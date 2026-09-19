<?php

namespace App\Http\Controllers;

use App\Models\Gymnasium;
use App\Models\GymSubscription;
use App\Models\Plan;
use App\Models\SaasPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterGymController extends Controller
{
    /** Días de prueba gratis para gimnasios nuevos */
    const TRIAL_DAYS = 14;

    /**
     * Mostrar el formulario de registro de gimnasio.
     */
    public function show(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $plans = SaasPlan::where('is_active', 1)->orderBy('sort_order')->get();

        // Plan preseleccionado desde la landing (?plan=pro)
        $selectedPlan = $request->query('plan');

        return view('register-gym.create', compact('plans', 'selectedPlan'));
    }

    /**
     * Crear el gimnasio + usuario dueño + suscripción de prueba.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'gym_name'      => 'required|string|max:255',
            'owner_name'    => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email',
            'phone'         => 'nullable|string|max:30',
            'password'      => 'required|string|min:6|confirmed',
            'saas_plan_id'  => 'required|exists:saas_plans,id',
            'terms'         => 'accepted',
        ], [
            'gym_name.required'   => 'El nombre del gimnasio es obligatorio.',
            'owner_name.required' => 'Tu nombre es obligatorio.',
            'email.required'      => 'El correo es obligatorio.',
            'email.unique'        => 'Ya existe una cuenta con ese correo.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'La contraseña debe tener mínimo 6 caracteres.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
            'saas_plan_id.required' => 'Selecciona un plan.',
            'terms.accepted'      => 'Debes aceptar los términos y condiciones.',
        ]);

        $plan = SaasPlan::findOrFail($data['saas_plan_id']);

        $gym = DB::transaction(function () use ($data, $plan) {

            // 1) Crear el gimnasio en modo prueba
            $gym = Gymnasium::create([
                'name'          => $data['gym_name'],
                'slug'          => $this->uniqueSlug($data['gym_name']),
                'email'         => $data['email'],
                'phone'         => $data['phone'] ?? null,
                'country'       => 'PE',
                'primary_color' => $plan->color ?? '#7c3aed',
                'saas_plan_id'  => $plan->id,
                'status'        => 'trial',
                'trial_ends_at' => now()->addDays(self::TRIAL_DAYS),
                'max_members'   => $plan->max_members,
            ]);

            // 2) Crear el usuario dueño (admin del gym)
            $owner = User::create([
                'name'         => $data['owner_name'],
                'email'        => $data['email'],
                'password'     => Hash::make($data['password']),
                'role'         => 'admin',
                'status'       => 1,
                'phone'        => $data['phone'] ?? null,
                'gymnasium_id' => $gym->id,
            ]);

            // 3) Vincular dueño al gym
            $gym->update(['owner_id' => $owner->id]);

            // 4) Registrar la suscripción de prueba
            GymSubscription::create([
                'gymnasium_id' => $gym->id,
                'saas_plan_id' => $plan->id,
                'amount'       => 0,
                'billing_cycle'=> 'monthly',
                'starts_at'    => now()->toDateString(),
                'ends_at'      => now()->addDays(self::TRIAL_DAYS)->toDateString(),
                'status'       => 'active',
                'payment_ref'  => 'TRIAL',
                'notes'        => 'Periodo de prueba gratuito de ' . self::TRIAL_DAYS . ' días.',
            ]);

            // 5) Sembrar planes de membresía por defecto para el gym nuevo
            $this->seedDefaultPlans($gym->id);

            return $gym;
        });

        // 6) Notificación de bienvenida (no rompe el registro si la tabla no existe)
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('gym_notifications')) {
                \App\Models\GymNotification::create([
                    'gymnasium_id' => $gym->id,
                    'user_id'      => null,
                    'type'         => 'welcome',
                    'title'        => '¡Bienvenido a GymSaaS Pro!',
                    'body'         => 'Tu gimnasio tiene ' . self::TRIAL_DAYS . ' días de prueba. Completa tu configuración inicial.',
                    'icon'         => 'fa-rocket',
                    'color'        => '#7c3aed',
                    'url'          => null,
                ]);
            }
        } catch (\Throwable $e) { /* ignorar */ }

        // 7) Iniciar sesión automáticamente y enviar al dashboard
        Auth::loginUsingId($gym->owner_id);
        $request->session()->regenerate();

        return redirect()->route('onboarding')->with(
            'success',
            '¡Bienvenido a GymSaaS Pro! Tu gimnasio "' . $gym->name .
            '" tiene ' . self::TRIAL_DAYS . ' días de prueba gratis.'
        );
    }

    /**
     * Genera un slug único para el subdominio del gimnasio.
     */
    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'gym';
        }
        $slug = $base;
        $i = 1;
        while (Gymnasium::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    /**
     * Crea un par de planes de membresía base para que el gym
     * no empiece con el sistema vacío.
     */
    private function seedDefaultPlans(int $gymId): void
    {
        $defaults = [
            ['name' => 'Mensual',     'price' => 120, 'duration_days' => 30,  'color' => '#7c3aed'],
            ['name' => 'Trimestral',  'price' => 320, 'duration_days' => 90,  'color' => '#ec4899'],
            ['name' => 'Anual',       'price' => 1100,'duration_days' => 365, 'color' => '#10b981'],
        ];

        foreach ($defaults as $p) {
            Plan::create([
                'gymnasium_id'  => $gymId,
                'name'          => $p['name'],
                'description'   => 'Plan de membresía ' . strtolower($p['name']),
                'price'         => $p['price'],
                'duration_days' => $p['duration_days'],
                'color'         => $p['color'],
                'status'        => 1,
            ]);
        }
    }
}
