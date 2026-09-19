<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GymNotification;
use App\Models\Gymnasium;
use App\Models\SaasPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AnnouncementController extends Controller
{
    public function create()
    {
        $plans = SaasPlan::orderBy('sort_order')->get();
        $gymCount = Gymnasium::count();
        return view('superadmin.announcements.create', compact('plans', 'gymCount'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'body'    => 'required|string|max:1000',
            'target'  => 'required|in:all,plan',
            'plan_id' => 'required_if:target,plan|nullable|exists:saas_plans,id',
            'color'   => 'nullable|string|max:20',
        ]);

        if (!Schema::hasTable('gym_notifications')) {
            return back()->with('error', 'Primero ejecuta database/saas_features.sql para activar las notificaciones.');
        }

        $query = Gymnasium::query();
        if ($data['target'] === 'plan') {
            $query->where('saas_plan_id', $data['plan_id']);
        }
        $gyms = $query->pluck('id');

        $now = now();
        $rows = $gyms->map(fn ($id) => [
            'gymnasium_id' => $id,
            'user_id'      => null,
            'type'         => 'announcement',
            'title'        => $data['title'],
            'body'         => $data['body'],
            'icon'         => 'fa-bullhorn',
            'color'        => $data['color'] ?? '#ec4899',
            'url'          => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ])->all();

        if (!empty($rows)) {
            GymNotification::insert($rows);
        }

        return redirect()->route('superadmin.announcements.create')
            ->with('success', 'Comunicado enviado a ' . count($rows) . ' gimnasio(s).');
    }
}
