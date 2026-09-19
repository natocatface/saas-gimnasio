<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaasPlanController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::withCount('gymnasiums')->orderBy('sort_order')->get();
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['features'] = $this->featuresToJson($request->features);

        SaasPlan::create($data);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan SaaS creado correctamente.');
    }

    public function edit(SaasPlan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SaasPlan $plan)
    {
        $data = $this->validateData($request);
        $data['features'] = $this->featuresToJson($request->features);

        $plan->update($data);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan SaaS actualizado.');
    }

    public function destroy(SaasPlan $plan)
    {
        if ($plan->gymnasiums()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: hay gimnasios usando este plan.');
        }
        $plan->delete();
        return back()->with('success', 'Plan eliminado.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly'  => 'required|numeric|min:0',
            'max_members'   => 'required|integer|min:1',
            'max_trainers'  => 'required|integer|min:1',
            'max_classes'   => 'required|integer|min:1',
            'color'         => 'nullable|string|max:20',
            'is_popular'    => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
            'sort_order'    => 'nullable|integer',
        ]);
    }

    /** Convierte el textarea (una feature por línea) en JSON. */
    private function featuresToJson(?string $features): string
    {
        if (!$features) return json_encode([]);
        $lines = array_values(array_filter(array_map('trim', explode("\n", $features))));
        return json_encode($lines, JSON_UNESCAPED_UNICODE);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'plan';
        $slug = $base;
        $i = 1;
        while (SaasPlan::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
