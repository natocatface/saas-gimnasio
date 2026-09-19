<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('members')->get();
        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'color'         => 'nullable|string|max:10',
            'is_featured'   => 'boolean',
            'status'        => 'boolean',
        ]);

        // Handle features
        $features = collect(explode("\n", $request->features ?? ''))
            ->map(fn($f) => trim($f))
            ->filter()
            ->values()
            ->toArray();
        $data['features'] = json_encode($features);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['status'] = $request->boolean('status', true);

        Plan::create($data);
        return redirect()->route('plans.index')->with('success', 'Plan creado exitosamente.');
    }

    public function show(Plan $plan)
    {
        $plan->loadCount('members');
        return view('plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'color'         => 'nullable|string|max:10',
        ]);

        $features = collect(explode("\n", $request->features ?? ''))
            ->map(fn($f) => trim($f))->filter()->values()->toArray();
        $data['features']    = json_encode($features);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['status']      = $request->boolean('status', true);

        $plan->update($data);
        return redirect()->route('plans.index')->with('success', 'Plan actualizado.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->members()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el plan porque tiene socios asignados.');
        }
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan eliminado.');
    }
}
