<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoutineController extends Controller
{
    public function store(Request $request, Member $member)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'trainer_id'    => ['nullable', Rule::exists('trainers','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'description'   => 'nullable|string',
            'exercises_text'=> 'nullable|string',
        ]);

        Routine::create([
            'gymnasium_id' => $member->gymnasium_id,
            'member_id'    => $member->id,
            'trainer_id'   => $data['trainer_id'] ?? null,
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'exercises'    => $this->parseExercises($data['exercises_text'] ?? ''),
            'is_active'    => true,
        ]);

        return back()->with('success', 'Rutina asignada al socio.');
    }

    public function destroy(Routine $routine)
    {
        $member = $routine->member_id;
        $routine->delete();
        return back()->with('success', 'Rutina eliminada.');
    }

    /**
     * Convierte el textarea (una línea por ejercicio:
     * "Nombre | series | reps | nota") en un arreglo estructurado.
     */
    private function parseExercises(string $text): array
    {
        $out = [];
        foreach (preg_split('/\r\n|\r|\n/', $text) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $parts = array_map('trim', explode('|', $line));
            $out[] = [
                'name'  => $parts[0] ?? '',
                'sets'  => $parts[1] ?? '',
                'reps'  => $parts[2] ?? '',
                'notes' => $parts[3] ?? '',
            ];
        }
        return $out;
    }
}
