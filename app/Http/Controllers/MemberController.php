<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Plan;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('plan')->latest();

        if ($request->search) {
            $q = $request->search;
            $query->where(function($query) use ($q) {
                $query->where('first_name','like',"%$q%")
                      ->orWhere('last_name','like',"%$q%")
                      ->orWhere('email','like',"%$q%")
                      ->orWhere('code','like',"%$q%")
                      ->orWhere('phone','like',"%$q%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->plan_id) {
            $query->where('plan_id', $request->plan_id);
        }

        if ($request->filter === 'expiring') {
            $query->where('status','activo')
                  ->whereBetween('membership_end', [Carbon::today(), Carbon::today()->addDays(15)]);
        }

        $members = $query->paginate(15)->withQueryString();
        $plans   = Plan::where('status', 1)->get();

        $counts = [
            'total'    => Member::count(),
            'activo'   => Member::where('status','activo')->count(),
            'vencido'  => Member::where('status','vencido')->count(),
            'inactivo' => Member::where('status','inactivo')->count(),
        ];

        return view('members.index', compact('members','plans','counts'));
    }

    public function create()
    {
        $plans = Plan::where('status', 1)->get();
        return view('members.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $gym = auth()->user()->gymnasium;
        if ($gym && !$gym->canAdd('members')) {
            return redirect()->route('billing.index')->with('error',
                'Alcanzaste el límite de ' . $gym->limitLabel('members') . ' socios de tu plan ' .
                ($gym->saasPlan->name ?? '') . '. Mejora tu plan para registrar más socios.');
        }

        $data = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => ['nullable','email', Rule::unique('members','email')->where(fn($q) => $q->where('gymnasium_id', auth()->user()->gymnasium_id))],
            'phone'             => 'nullable|string|max:20',
            'birth_date'        => 'nullable|date',
            'gender'            => 'nullable|in:M,F,otro',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:20',
            'plan_id'           => 'nullable|exists:plans,id',
            'membership_start'  => 'nullable|date',
            'membership_end'    => 'nullable|date',
            'status'            => 'required|in:activo,inactivo,suspendido,vencido',
            'notes'             => 'nullable|string',
            'password'          => 'nullable|string|min:4',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Generate member code
        // Usar el máximo global (sin scope de tenant) para evitar colisiones
        // con el índice UNIQUE global de `code` entre gimnasios distintos.
        $lastId = Member::withoutGlobalScope('tenant')->max('id') ?? 0;
        do {
            $code = 'GYM-' . str_pad(++$lastId, 3, '0', STR_PAD_LEFT);
        } while (Member::withoutGlobalScope('tenant')->where('code', $code)->exists());
        $data['code'] = $code;

        // Auto-calculate end date from plan
        if (!empty($data['plan_id']) && !empty($data['membership_start']) && empty($data['membership_end'])) {
            $plan = Plan::find($data['plan_id']);
            if ($plan) {
                $data['membership_end'] = Carbon::parse($data['membership_start'])->addDays($plan->duration_days);
            }
        }

        Member::create($data);

        return redirect()->route('members.index')->with('success', 'Socio registrado exitosamente.');
    }

    public function show(Member $member)
    {
        $member->load([
            'plan', 'payments.plan', 'attendance.gymClass',
            'routines.trainer',
            'measurements' => fn ($q) => $q->orderBy('measured_on'),
            'enrollments.gymClass',
        ]);
        $trainers = Trainer::where('status', 1)->orderBy('name')->get();
        return view('members.show', compact('member', 'trainers'));
    }

    public function edit(Member $member)
    {
        $plans = Plan::where('status', 1)->get();
        return view('members.edit', compact('member','plans'));
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => ['nullable','email', Rule::unique('members','email')->ignore($member->id)->where(fn($q) => $q->where('gymnasium_id', auth()->user()->gymnasium_id))],
            'phone'             => 'nullable|string|max:20',
            'birth_date'        => 'nullable|date',
            'gender'            => 'nullable|in:M,F,otro',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:20',
            'plan_id'           => 'nullable|exists:plans,id',
            'membership_start'  => 'nullable|date',
            'membership_end'    => 'nullable|date',
            'status'            => 'required|in:activo,inactivo,suspendido,vencido',
            'notes'             => 'nullable|string',
            'password'          => 'nullable|string|min:4',
        ]);

        // Solo cambiar contraseña del portal si se ingresó una nueva
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $member->update($data);

        return redirect()->route('members.index')->with('success', 'Socio actualizado exitosamente.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Socio eliminado.');
    }
}
