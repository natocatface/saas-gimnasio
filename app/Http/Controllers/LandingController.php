<?php

namespace App\Http\Controllers;

use App\Models\SaasPlan;

class LandingController extends Controller
{
    /**
     * Landing pública con precios. Si el usuario ya está logueado,
     * lo enviamos a su panel correspondiente.
     */
    public function index()
    {
        if (auth()->check()) {
            return auth()->user()->isSuperAdmin()
                ? redirect()->route('superadmin.dashboard')
                : redirect()->route('dashboard');
        }

        $plans = SaasPlan::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        return view('landing.index', compact('plans'));
    }
}
