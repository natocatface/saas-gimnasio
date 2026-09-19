<?php

namespace App\Http\Controllers;

use App\Models\ClassEnrollment;
use App\Models\GymClass;
use App\Models\Member;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GymClassController extends Controller
{
    public function index()
    {
        $classes = GymClass::with('trainer')->withCount('enrollments')->latest()->get();
        $days = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
        return view('classes.index', compact('classes','days'));
    }

    public function create()
    {
        $trainers = Trainer::where('status',1)->orderBy('name')->get();
        return view('classes.create', compact('trainers'));
    }

    public function store(Request $request)
    {
        $gym = auth()->user()->gymnasium;
        if ($gym && !$gym->canAdd('classes')) {
            return redirect()->route('billing.index')->with('error',
                'Alcanzaste el límite de ' . $gym->limitLabel('classes') . ' clases de tu plan. Mejora tu plan para crear más.');
        }

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'trainer_id'       => ['nullable', Rule::exists('trainers','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'capacity'         => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:15',
            'schedule_day'     => 'nullable|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'schedule_time'    => 'nullable|date_format:H:i',
            'room'             => 'nullable|string|max:100',
            'color'            => 'nullable|string|max:10',
        ]);
        $data['status'] = $request->boolean('status', true);
        GymClass::create($data);
        return redirect()->route('classes.index')->with('success', 'Clase creada.');
    }

    public function show(GymClass $class)
    {
        $class->load(['trainer', 'enrollments.member']);

        $enrolledIds = $class->enrollments->where('status', 'activo')->pluck('member_id');
        $enrolledCount = $enrolledIds->count();

        // Socios activos que aún no están inscritos
        $availableMembers = Member::where('status', 'activo')
            ->whereNotIn('id', $enrolledIds)
            ->orderBy('first_name')
            ->get();

        return view('classes.show', compact('class', 'enrolledCount', 'availableMembers'));
    }

    /** Inscribir un socio a la clase (con control de cupo). */
    public function enroll(Request $request, GymClass $class)
    {
        $data = $request->validate(['member_id' => ['required', Rule::exists('members','id')->where('gymnasium_id', auth()->user()->gymnasium_id)]]);

        $active = $class->enrollments()->where('status', 'activo')->count();
        if ($active >= $class->capacity) {
            return back()->with('error', 'La clase está llena (cupo: ' . $class->capacity . ').');
        }

        ClassEnrollment::updateOrCreate(
            ['class_id' => $class->id, 'member_id' => $data['member_id']],
            ['gymnasium_id' => $class->gymnasium_id, 'status' => 'activo', 'enrolled_at' => now()]
        );

        return back()->with('success', 'Socio inscrito a la clase.');
    }

    /** Quitar la inscripción de un socio. */
    public function unenroll(GymClass $class, ClassEnrollment $enrollment)
    {
        if ($enrollment->class_id === $class->id) {
            $enrollment->delete();
        }
        return back()->with('success', 'Inscripción cancelada.');
    }

    /** Horario semanal de clases. */
    public function schedule()
    {
        $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

        $classes = GymClass::with('trainer')->withCount(['enrollments' => function ($q) {
            $q->where('status', 'activo');
        }])->where('status', 1)->orderBy('schedule_time')->get();

        $grid = [];
        foreach ($days as $d) {
            $grid[$d] = $classes->where('schedule_day', $d)->sortBy('schedule_time')->values();
        }

        return view('classes.schedule', compact('grid', 'days'));
    }

    public function edit(GymClass $class)
    {
        $trainers = Trainer::where('status',1)->orderBy('name')->get();
        return view('classes.edit', compact('class','trainers'));
    }

    public function update(Request $request, GymClass $class)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'trainer_id'       => ['nullable', Rule::exists('trainers','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'capacity'         => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:15',
            'schedule_day'     => 'nullable|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'schedule_time'    => 'nullable|date_format:H:i',
            'room'             => 'nullable|string|max:100',
            'color'            => 'nullable|string|max:10',
        ]);
        $data['status'] = $request->boolean('status', true);
        $class->update($data);
        return redirect()->route('classes.index')->with('success', 'Clase actualizada.');
    }

    public function destroy(GymClass $class)
    {
        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Clase eliminada.');
    }
}
