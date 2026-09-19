<?php

namespace App\Http\Controllers;

class OnboardingController extends Controller
{
    /**
     * Checklist de bienvenida tras registrar el gimnasio.
     */
    public function index()
    {
        $gym = auth()->user()->gymnasium;

        $steps = [
            [
                'key'   => 'plans',
                'icon'  => 'fa-clipboard-list',
                'title' => 'Revisa tus planes de membresía',
                'desc'  => 'Ya creamos 3 planes base. Ajústalos a tus precios.',
                'route' => route('plans.index'),
                'done'  => $gym->plans()->count() > 0,
            ],
            [
                'key'   => 'trainers',
                'icon'  => 'fa-user-tie',
                'title' => 'Agrega a tus entrenadores',
                'desc'  => 'Registra al equipo que dará las clases.',
                'route' => route('trainers.create'),
                'done'  => $gym->usage('trainers') > 0,
            ],
            [
                'key'   => 'members',
                'icon'  => 'fa-users',
                'title' => 'Registra a tu primer socio',
                'desc'  => 'Empieza a llenar tu base de socios.',
                'route' => route('members.create'),
                'done'  => $gym->usage('members') > 0,
            ],
            [
                'key'   => 'staff',
                'icon'  => 'fa-user-shield',
                'title' => 'Invita a tu equipo',
                'desc'  => 'Suma recepción y administradores con sus accesos.',
                'route' => route('staff.index'),
                'done'  => $gym->users()->count() > 1,
            ],
        ];

        $completed = count(array_filter($steps, fn ($s) => $s['done']));

        return view('onboarding.index', compact('gym', 'steps', 'completed'));
    }
}
