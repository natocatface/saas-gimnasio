<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::withCount('classes')->latest()->paginate(12);
        return view('trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('trainers.create');
    }

    public function store(Request $request)
    {
        $gym = auth()->user()->gymnasium;
        if ($gym && !$gym->canAdd('trainers')) {
            return redirect()->route('billing.index')->with('error',
                'Alcanzaste el límite de ' . $gym->limitLabel('trainers') . ' entrenadores de tu plan. Mejora tu plan para agregar más.');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['nullable','email', Rule::unique('trainers','email')->where(fn($q) => $q->where('gymnasium_id', auth()->user()->gymnasium_id))],
            'phone'       => 'nullable|string|max:20',
            'speciality'  => 'nullable|string|max:255',
            'bio'         => 'nullable|string',
            'hire_date'   => 'nullable|date',
            'salary'      => 'nullable|numeric|min:0',
            'status'      => 'boolean',
        ]);
        $data['status'] = $request->boolean('status', true);
        Trainer::create($data);
        return redirect()->route('trainers.index')->with('success', 'Entrenador registrado.');
    }

    public function show(Trainer $trainer)
    {
        $trainer->load('classes');
        return view('trainers.show', compact('trainer'));
    }


    public function edit(Trainer $trainer)
    {
        return view('trainers.edit', compact('trainer'));
    }

    public function update(Request $request, Trainer $trainer)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['nullable','email', Rule::unique('trainers','email')->ignore($trainer->id)->where(fn($q) => $q->where('gymnasium_id', auth()->user()->gymnasium_id))],
            'phone'      => 'nullable|string|max:20',
            'speciality' => 'nullable|string|max:255',
            'bio'        => 'nullable|string',
            'hire_date'  => 'nullable|date',
            'salary'     => 'nullable|numeric|min:0',
        ]);
        $data['status'] = $request->boolean('status', true);
        $trainer->update($data);
        return redirect()->route('trainers.index')->with('success', 'Entrenador actualizado.');
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->delete();
        return redirect()->route('trainers.index')->with('success', 'Entrenador eliminado.');
    }
}
