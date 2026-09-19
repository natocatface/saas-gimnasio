<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GymNotification;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with('gymnasium')->withCount('messages')->latest('last_reply_at');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(20)->withQueryString();

        $counts = [
            'open'    => SupportTicket::where('status', 'open')->count(),
            'pending' => SupportTicket::where('status', 'pending')->count(),
            'closed'  => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('superadmin.support.index', compact('tickets', 'counts'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('messages.user', 'gymnasium', 'user');
        return view('superadmin.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate(['body' => 'required|string']);

        $ticket->messages()->create([
            'user_id'  => auth()->id(),
            'is_staff' => true,
            'body'     => $data['body'],
        ]);

        $ticket->update(['status' => 'pending', 'last_reply_at' => now()]);

        // Notificar al gimnasio
        if (Schema::hasTable('gym_notifications')) {
            GymNotification::create([
                'gymnasium_id' => $ticket->gymnasium_id,
                'user_id'      => null,
                'type'         => 'support_reply',
                'title'        => 'Respuesta a tu ticket de soporte',
                'body'         => 'Soporte respondió: "' . \Illuminate\Support\Str::limit($ticket->subject, 50) . '".',
                'icon'         => 'fa-headset',
                'color'        => '#3b82f6',
                'url'          => route('support.show', $ticket),
            ]);
        }

        return back()->with('success', 'Respuesta enviada al gimnasio.');
    }

    public function setStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate(['status' => 'required|in:open,pending,closed']);
        $ticket->update(['status' => $request->status]);
        return back()->with('success', 'Estado del ticket actualizado.');
    }
}
