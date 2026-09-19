<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gymnasium;
use App\Models\GymSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        // ── Serie de 12 meses ──────────────────────────────────
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $months[$m->format('Y-m')] = $m->locale('es')->isoFormat('MMM YY');
        }

        // Ingresos por mes (suscripciones cobradas)
        $revByMonth = GymSubscription::selectRaw("DATE_FORMAT(created_at,'%Y-%m') ym, SUM(amount) total")
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')->pluck('total', 'ym');

        // Altas de gimnasios por mes
        $newByMonth = Gymnasium::selectRaw("DATE_FORMAT(created_at,'%Y-%m') ym, COUNT(*) total")
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')->pluck('total', 'ym');

        $revenueSeries = [];
        $newGymsSeries = [];
        foreach ($months as $key => $label) {
            $revenueSeries[] = ['label' => $label, 'total' => (float) ($revByMonth[$key] ?? 0)];
            $newGymsSeries[] = ['label' => $label, 'total' => (int) ($newByMonth[$key] ?? 0)];
        }

        // Ingresos por plan
        $revByPlan = GymSubscription::join('saas_plans', 'gym_subscriptions.saas_plan_id', '=', 'saas_plans.id')
            ->selectRaw('saas_plans.name, saas_plans.color, SUM(gym_subscriptions.amount) total, COUNT(*) cnt')
            ->groupBy('saas_plans.id', 'saas_plans.name', 'saas_plans.color')
            ->orderByDesc('total')
            ->get();

        // MRR actual
        $mrr = Gymnasium::where('gymnasiums.status', 'active')
            ->join('saas_plans', 'gymnasiums.saas_plan_id', '=', 'saas_plans.id')
            ->sum('saas_plans.price_monthly');

        $activeGyms = Gymnasium::where('status', 'active')->count();

        $kpis = [
            'total_revenue' => GymSubscription::sum('amount'),
            'revenue_month' => GymSubscription::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)->sum('amount'),
            'mrr'           => $mrr,
            'arpa'          => $activeGyms > 0 ? round($mrr / $activeGyms, 2) : 0, // ingreso medio por gym activo
            'churned'       => Gymnasium::whereIn('status', ['suspended', 'cancelled'])->count(),
            'active'        => $activeGyms,
        ];

        return view('superadmin.reports.index', compact('revenueSeries', 'newGymsSeries', 'revByPlan', 'kpis'));
    }

    /**
     * Exportar todas las suscripciones a CSV.
     */
    public function exportCsv(): StreamedResponse
    {
        $filename = 'suscripciones_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Fecha', 'Gimnasio', 'Plan', 'Ciclo', 'Monto', 'Inicio', 'Fin', 'Estado', 'Referencia']);

            GymSubscription::with('gymnasium', 'saasPlan')->orderBy('created_at')->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $s) {
                    fputcsv($out, [
                        optional($s->created_at)->format('Y-m-d'),
                        $s->gymnasium->name ?? '',
                        $s->saasPlan->name ?? '',
                        $s->billing_cycle,
                        $s->amount,
                        $s->starts_at,
                        $s->ends_at,
                        $s->status,
                        $s->payment_ref,
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
