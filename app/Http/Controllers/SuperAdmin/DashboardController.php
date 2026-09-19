<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gymnasium;
use App\Models\GymSubscription;
use App\Models\SaasPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGyms = Gymnasium::count();
        $activeGyms = Gymnasium::where('status', 'active')->count();

        $stats = [
            'total_gyms'     => $totalGyms,
            'active_gyms'    => $activeGyms,
            'trial_gyms'     => Gymnasium::where('status', 'trial')->count(),
            'suspended_gyms' => Gymnasium::whereIn('status', ['suspended', 'cancelled'])->count(),
            'total_users'    => User::where('role', '!=', 'superadmin')->count(),
            'saas_plans'     => SaasPlan::where('is_active', 1)->count(),
            'new_this_month' => Gymnasium::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)->count(),
            'conversion'     => $totalGyms > 0 ? round($activeGyms / $totalGyms * 100) : 0,
        ];

        // Ingreso recurrente mensual (MRR) estimado por planes activos
        $mrr = Gymnasium::where('gymnasiums.status', 'active')
            ->join('saas_plans', 'gymnasiums.saas_plan_id', '=', 'saas_plans.id')
            ->sum('saas_plans.price_monthly');

        // Ingreso cobrado este mes (suscripciones reales)
        $revenueThisMonth = GymSubscription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'active')
            ->sum('amount');

        // Distribución de gimnasios por plan
        $planDistribution = SaasPlan::leftJoin('gymnasiums', 'gymnasiums.saas_plan_id', '=', 'saas_plans.id')
            ->select('saas_plans.name', 'saas_plans.color', DB::raw('COUNT(gymnasiums.id) as total'))
            ->groupBy('saas_plans.id', 'saas_plans.name', 'saas_plans.color')
            ->get();

        // Crecimiento de gimnasios últimos 6 meses
        $growth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $growth[] = [
                'label' => $month->locale('es')->isoFormat('MMM'),
                'total' => Gymnasium::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        // Gimnasios recientes
        $recentGyms = Gymnasium::with('saasPlan')
            ->latest()
            ->take(6)
            ->get();

        // Pruebas por vencer en los próximos 7 días
        $expiringTrials = Gymnasium::where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->whereBetween('trial_ends_at', [now(), now()->addDays(7)])
            ->get();

        return view('superadmin.dashboard', compact(
            'stats', 'mrr', 'revenueThisMonth', 'planDistribution',
            'growth', 'recentGyms', 'expiringTrials'
        ));
    }
}
