<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gymnasium;
use App\Models\GymSubscription;
use App\Models\SaasPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = GymSubscription::with('gymnasium', 'saasPlan')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->gym) {
            $query->where('gymnasium_id', $request->gym);
        }

        $subscriptions = $query->paginate(15)->withQueryString();

        $stats = [
            'total'        => GymSubscription::count(),
            'active'       => GymSubscription::where('status', 'active')->count(),
            'revenue'      => GymSubscription::where('status', 'active')->sum('amount'),
            'this_month'   => GymSubscription::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->sum('amount'),
        ];

        $gyms = Gymnasium::orderBy('name')->get(['id', 'name']);

        return view('superadmin.subscriptions.index', compact('subscriptions', 'stats', 'gyms'));
    }

    public function create(Request $request)
    {
        $gyms  = Gymnasium::orderBy('name')->get();
        $plans = SaasPlan::orderBy('sort_order')->get();
        $gymId = $request->query('gym');

        return view('superadmin.subscriptions.create', compact('gyms', 'plans', 'gymId'));
    }

    /**
     * Registrar un cobro/renovación manual de suscripción SaaS.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'gymnasium_id'  => 'required|exists:gymnasiums,id',
            'saas_plan_id'  => 'required|exists:saas_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'amount'        => 'required|numeric|min:0',
            'starts_at'     => 'required|date',
            'payment_ref'   => 'nullable|string|max:100',
            'notes'         => 'nullable|string',
        ]);

        $start = Carbon::parse($data['starts_at']);
        $end   = $data['billing_cycle'] === 'yearly'
            ? $start->copy()->addYear()
            : $start->copy()->addMonth();

        GymSubscription::create([
            'gymnasium_id'  => $data['gymnasium_id'],
            'saas_plan_id'  => $data['saas_plan_id'],
            'amount'        => $data['amount'],
            'billing_cycle' => $data['billing_cycle'],
            'starts_at'     => $start->toDateString(),
            'ends_at'       => $end->toDateString(),
            'status'        => 'active',
            'payment_ref'   => $data['payment_ref'] ?? null,
            'notes'         => $data['notes'] ?? null,
        ]);

        // Activar el gimnasio y actualizar su plan
        $gym = Gymnasium::find($data['gymnasium_id']);
        $gym->update([
            'status'        => 'active',
            'saas_plan_id'  => $data['saas_plan_id'],
            'trial_ends_at' => null,
        ]);

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Suscripción registrada y gimnasio activado.');
    }

    /** Cancelar una suscripción. */
    public function cancel(GymSubscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);
        return back()->with('success', 'Suscripción cancelada.');
    }
}
