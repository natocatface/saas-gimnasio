<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::withCount('messages')->latest()->get();
        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject'  => 'required|string|max:255',
            'priority' => 'required|in:low,normal,high',
            'body'     => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'gymnasium_id'  => auth()->user()->gymnasium_id,
            'user_id'       => auth()->id(),
            'subject'       => $data['subject'],
            'priority'      => $data['priority'],
            'status'        => 'open',
            'last_reply_at' => now(),
        ]);

        $ticket->messages()->create([
            'user_id'  => auth()->id(),
            'is_staff' => false,
            'body'     => $data['body'],
        ]);

        return redirect()->route('support.show', $ticket)->with('success', 'Ticket creado. Te responderemos pronto.');
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('messages.user', 'user');
        return view('support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate(['body' => 'required|string']);

        $ticket->messages()->create([
            'user_id'  => auth()->id(),
            'is_staff' => false,
            'body'     => $data['body'],
        ]);

        $ticket->update(['status' => 'open', 'last_reply_at' => now()]);

        return back()->with('success', 'Respuesta enviada.');
    }

    public function close(SupportTicket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Ticket cerrado.');
    }
}
