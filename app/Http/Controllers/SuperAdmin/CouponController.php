<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\SaasPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('saasPlan')->latest()->get();
        return view('superadmin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $plans = SaasPlan::orderBy('sort_order')->get();
        return view('superadmin.coupons.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['code'] = strtoupper($data['code']);
        Coupon::create($data);

        return redirect()->route('superadmin.coupons.index')->with('success', 'Cupón creado.');
    }

    public function edit(Coupon $coupon)
    {
        $plans = SaasPlan::orderBy('sort_order')->get();
        return view('superadmin.coupons.edit', compact('coupon', 'plans'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateData($request, $coupon->id);
        $data['code'] = strtoupper($data['code']);
        $coupon->update($data);

        return redirect()->route('superadmin.coupons.index')->with('success', 'Cupón actualizado.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Cupón eliminado.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'code'         => ['required', 'string', 'max:40', Rule::unique('coupons', 'code')->ignore($id)],
            'description'  => 'nullable|string|max:255',
            'type'         => 'required|in:percent,fixed',
            'value'        => 'required|numeric|min:0',
            'saas_plan_id' => 'nullable|exists:saas_plans,id',
            'max_uses'     => 'nullable|integer|min:1',
            'expires_at'   => 'nullable|date',
            'is_active'    => 'nullable|boolean',
        ]);
    }
}
