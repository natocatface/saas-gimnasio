<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\GymClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['member','gymClass'])->latest('check_in');

        if ($request->search) {
            $q = $request->search;
            $query->whereHas('member', fn($mq) =>
                $mq->where('first_name','like',"%$q%")->orWhere('last_name','like',"%$q%")->orWhere('code','like',"%$q%")
            );
        }

        if ($request->date) {
            $query->whereDate('check_in', $request->date);
        } else {
            // Default: today
            $query->whereDate('check_in', today());
        }

        $attendance  = $query->paginate(20)->withQueryString();
        $todayCount  = Attendance::whereDate('check_in', today())->count();
        $weekCount   = Attendance::whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthCount  = Attendance::whereMonth('check_in', now()->month)->count();

        return view('attendance.index', compact('attendance','todayCount','weekCount','monthCount'));
    }

    public function create()
    {
        $members = Member::where('status','activo')->orderBy('first_name')->get();
        $classes = GymClass::where('status',1)->orderBy('name')->get();
        return view('attendance.create', compact('members','classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => ['required', Rule::exists('members','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'class_id'  => ['nullable', Rule::exists('gym_classes','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'check_in'  => 'required|date',
            'notes'     => 'nullable|string',
        ]);

        // Check if member already checked in today
        $existing = Attendance::where('member_id', $data['member_id'])
            ->whereDate('check_in', today())
            ->whereNull('check_out')
            ->first();

        if ($existing) {
            return back()->with('error', 'Este socio ya tiene un check-in activo hoy.');
        }

        Attendance::create($data);
        return redirect()->route('attendance.index')->with('success', 'Asistencia registrada.');
    }

    public function show(Attendance $attendance)
    {
        return redirect()->route('attendance.index');
    }

    public function edit(Attendance $attendance) { return back(); }
    public function update(Request $request, Attendance $attendance) { return back(); }

    public function checkout($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update(['check_out' => now()]);
        return back()->with('success', 'Check-out registrado exitosamente.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Registro eliminado.');
    }
}
