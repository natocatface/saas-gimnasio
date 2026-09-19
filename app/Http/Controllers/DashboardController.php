<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\GymClass;
use App\Models\Trainer;
use App\Models\Attendance;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $year  = $today->year;

        // ── KPI Stats ────────────────────────────────────────────
        $stats = [
            'totalMembers'   => Member::where('status', 'activo')->count(),
            'newThisMonth'   => Member::whereMonth('created_at', $today->month)
                                      ->whereYear('created_at', $year)->count(),
            'monthlyRevenue' => Payment::whereMonth('payment_date', $today->month)
                                       ->whereYear('payment_date', $year)
                                       ->where('status', 'pagado')->sum('amount'),
            'totalClasses'   => GymClass::where('status', 1)->count(),
            'totalTrainers'  => Trainer::where('status', 1)->count(),
            'expiringCount'  => Member::where('status', 'activo')
                                       ->whereBetween('membership_end', [$today, $today->copy()->addDays(7)])
                                       ->count(),
        ];

        // ── Plan Distribution ─────────────────────────────────────
        $plans = Plan::withCount(['members' => fn($q) => $q->where('status','activo')])->get();
        $totalActive = max($stats['totalMembers'], 1);
        $colors = ['#7c3aed','#ec4899','#10b981','#3b82f6','#f59e0b'];

        $planDistribution = $plans->map(fn($p, $i) => [
            'name'  => $p->name,
            'count' => $p->members_count,
            'pct'   => round($p->members_count / $totalActive * 100),
            'color' => $p->color ?? $colors[$i % count($colors)],
        ])->filter(fn($p) => $p['count'] > 0)->values();

        // ── Revenue by month (real DB data) ──────────────────────
        $revenueRaw = Payment::selectRaw('MONTH(payment_date) as mes, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->where('status', 'pagado')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $revenueByMonth = collect(range(1, 12))->map(fn($m) => round($revenueRaw[$m] ?? 0, 2))->values();

        // ── Members registered by month (real DB data) ───────────
        $membersRaw = Member::selectRaw('MONTH(created_at) as mes, COUNT(*) as cnt')
            ->whereYear('created_at', $year)
            ->groupBy('mes')
            ->pluck('cnt', 'mes');

        $membersByMonth = collect(range(1, 12))->map(fn($m) => $membersRaw[$m] ?? 0)->values();

        // ── Expiring Soon (next 15 days) ─────────────────────────
        $expiringSoon = Member::with('plan')
            ->where('status', 'activo')
            ->whereBetween('membership_end', [$today, $today->copy()->addDays(15)])
            ->orderBy('membership_end')
            ->limit(6)
            ->get();

        // ── Recent Members ────────────────────────────────────────
        $recentMembers = Member::with('plan')->latest()->limit(8)->get();

        // ── Recent Activity (payments + attendance) ───────────────
        $recentPayments   = Payment::with('member')->latest()->limit(4)->get();
        $recentAttendance = Attendance::with('member')->latest('check_in')->limit(3)->get();

        $recentActivity = collect();

        foreach ($recentPayments as $p) {
            $recentActivity->push([
                'icon'  => '💳',
                'bg'    => '#d1fae5',
                'color' => '#065f46',
                'title' => 'Pago registrado',
                'desc'  => optional($p->member)->first_name . ' ' . optional($p->member)->last_name . ' — $' . number_format($p->amount, 2),
                'time'  => Carbon::parse($p->created_at)->diffForHumans(),
            ]);
        }

        foreach ($recentAttendance as $a) {
            $recentActivity->push([
                'icon'  => '✅',
                'bg'    => '#dbeafe',
                'color' => '#1e40af',
                'title' => 'Check-in registrado',
                'desc'  => optional($a->member)->first_name . ' ' . optional($a->member)->last_name . ' — ' . Carbon::parse($a->check_in)->format('H:i'),
                'time'  => Carbon::parse($a->created_at)->diffForHumans(),
            ]);
        }

        $recentActivity = $recentActivity->take(6);

        // ── Extra stats for charts ─────────────────────────────────
        $extraStats = [
            'totalRevenue' => Payment::whereYear('payment_date', $year)->where('status','pagado')->sum('amount'),
            'totalMembers' => Member::count(),
            'pendingPayments' => Payment::where('status','pendiente')->count(),
            'todayAttendance' => Attendance::whereDate('check_in', $today)->count(),
        ];

        return view('dashboard.index', compact(
            'stats', 'planDistribution', 'expiringSoon',
            'recentMembers', 'recentActivity',
            'revenueByMonth', 'membersByMonth', 'extraStats'
        ));
    }
}
