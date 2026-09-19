<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\GymClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    private function member()
    {
        return Auth::guard('member')->user();
    }

    public function index()
    {
        $member = $this->member();
        $member->load([
            'plan', 'gymnasium',
            'routines.trainer',
            'payments' => fn ($q) => $q->latest()->limit(6),
            'enrollments.gymClass.trainer',
        ]);

        // Clases del gimnasio del socio (sin tenant scope automático aquí)
        $gymId = $member->gymnasium_id;
        $enrolledClassIds = $member->enrollments->where('status', 'activo')->pluck('class_id');

        $availableClasses = GymClass::withoutGlobalScope('tenant')
            ->where('gymnasium_id', $gymId)
            ->where('status', 1)
            ->withCount(['enrollments' => fn ($q) => $q->where('status', 'activo')])
            ->whereNotIn('id', $enrolledClassIds)
            ->orderBy('schedule_day')
            ->get();

        return view('portal.index', compact('member', 'availableClasses'));
    }

    public function enroll(Request $request, GymClass $class)
    {
        $member = $this->member();

        // El socio solo puede inscribirse a clases de su propio gimnasio
        if ($class->gymnasium_id !== $member->gymnasium_id) {
            abort(404);
        }

        $active = $class->enrollments()->where('status', 'activo')->count();
        if ($active >= $class->capacity) {
            return back()->with('error', 'La clase está llena.');
        }

        ClassEnrollment::updateOrCreate(
            ['class_id' => $class->id, 'member_id' => $member->id],
            ['gymnasium_id' => $class->gymnasium_id, 'status' => 'activo', 'enrolled_at' => now()]
        );

        return back()->with('success', 'Te inscribiste a ' . $class->name . '.');
    }

    public function unenroll(ClassEnrollment $enrollment)
    {
        $member = $this->member();
        if ($enrollment->member_id !== $member->id) {
            abort(404);
        }
        $enrollment->delete();
        return back()->with('success', 'Cancelaste tu inscripción.');
    }
}
