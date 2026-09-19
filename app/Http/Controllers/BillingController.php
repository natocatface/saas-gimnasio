<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\GymSubscription;
use App\Models\SaasPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BillingController extends Controller
{
    /**
     * Panel de facturación del gimnasio: plan actual, uso, prueba e historial.
     */
    public function index()
    {
        $gym = auth()->user()->gymnasium;

        $usage = [
            'members'  => ['used' => $gym->usage('members'),  'limit' => $gym->limitLabel('members'),  'pct' => $gym->usagePercent('members')],
            'trainers' => ['used' => $gym->usage('trainers'), 'limit' => $gym->limitLabel('trainers'), 'pct' => $gym->usagePercent('trainers')],
            'classes'  => ['used' => $gym->usage('classes'),  'limit' => $gym->limitLabel('classes'),  'pct' => $gym->usagePercent('classes')],
        ];

        $plans = SaasPlan::where('is_active', 1)->orderBy('sort_order')->get();

        $subscriptions = $gym->subscriptions()
            ->with('saasPlan')
            ->latest()
            ->take(12)
            ->get();

        return view('billing.index', compact('gym', 'usage', 'plans', 'subscriptions'));
    }

    /**
     * Cambiar / mejorar de plan (cobro simulado).
     */
    public function changePlan(Request $request)
    {
        // Solo el administrador del gimnasio puede cambiar el plan
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Solo el administrador del gimnasio puede cambiar el plan.');
        }

        $data = $request->validate([
            'saas_plan_id'  => 'required|exists:saas_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'coupon'        => 'nullable|string|max:40',
        ]);

        $gym  = auth()->user()->gymnasium;
        $plan = SaasPlan::findOrFail($data['saas_plan_id']);

        $start  = Carbon::today();
        $end    = $data['billing_cycle'] === 'yearly' ? $start->copy()->addYear() : $start->copy()->addMonth();
        $amount = $data['billing_cycle'] === 'yearly' ? $plan->price_yearly : $plan->price_monthly;

        // Aplicar cupón si corresponde
        $discount   = 0;
        $couponCode = null;
        $coupon     = null;
        if (!empty($data['coupon']) && Schema::hasTable('coupons')) {
            $coupon = Coupon::where('code', strtoupper(trim($data['coupon'])))->first();
            if (!$coupon || !$coupon->isValidFor($plan->id)) {
                return back()->withInput()->with('error', 'El cupón no es válido o ya no se puede usar.');
            }
            $discount   = $coupon->discountFor($amount);
            $couponCode = $coupon->code;
        }

        $finalAmount = max(0, $amount - $discount);

        // Registrar la suscripción (pago simulado)
        $payload = [
            'gymnasium_id'  => $gym->id,
            'saas_plan_id'  => $plan->id,
            'amount'        => $finalAmount,
            'billing_cycle' => $data['billing_cycle'],
            'starts_at'     => $start->toDateString(),
            'ends_at'       => $end->toDateString(),
            'status'        => 'active',
            'payment_ref'   => 'SIM-' . strtoupper(uniqid()),
            'notes'         => 'Cambio de plan desde el panel del gimnasio.',
        ];
        if (Schema::hasColumn('gym_subscriptions', 'coupon_code')) {
            $payload['coupon_code'] = $couponCode;
            $payload['discount']    = $discount;
        }
        GymSubscription::create($payload);

        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Activar el gimnasio con el nuevo plan
        $gym->update([
            'saas_plan_id'  => $plan->id,
            'status'        => 'active',
            'trial_ends_at' => null,
            'max_members'   => $plan->max_members,
        ]);

        $msg = '¡Listo! Tu gimnasio ahora está en el plan ' . $plan->name . '.';
        if ($discount > 0) {
            $msg .= ' Cupón aplicado: -$' . number_format($discount, 2) . '.';
        }

        return redirect()->route('billing.index')->with('success', $msg);
    }
}
