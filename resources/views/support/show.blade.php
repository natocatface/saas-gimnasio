@extends('layouts.app')
@section('title', 'Ticket #'.$ticket->id)
@section('page-title', $ticket->subject)
@section('breadcrumb')<a href="{{ route('support.index') }}">Soporte</a> · <span class="current">Ticket #{{ $ticket->id }}</span>@endsection

@push('styles')
<style>
    .thread{background:#fff;border:1px solid var(--border);border-radius:16px;padding:22px;max-width:780px}
    .msg{display:flex;gap:12px;margin-bottom:18px}
    .msg .av{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0}
    .msg .bub{background:#f5f3ff;border-radius:12px;padding:13px 16px;flex:1}
    .msg.staff .av{background:linear-gradient(135deg,#3b82f6,#2563eb)}
    .msg.staff .bub{background:#eff6ff}
    .msg .who{font-weight:700;font-size:13px;margin-bottom:3px}
    .msg .who span{font-weight:500;color:var(--text-secondary);font-size:12px;margin-left:6px}
    .msg .bd{font-size:14px;line-height:1.6;white-space:pre-wrap}
    .tb{display:inline-block;padding:3px 11px;border-radius:20px;font-size:12px;font-weight:700}
    .tb.open{background:#dcfce7;color:#15803d}.tb.pending{background:#fef9c3;color:#a16207}.tb.closed{background:#f3f4f6;color:#6b7280}
</style>
@endpush

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;max-width:780px">
    <span class="tb {{ $ticket->status }}">{{ $ticket->statusLabel() }}</span>
    @if($ticket->isOpen())
        <form method="POST" action="{{ route('support.close',$ticket) }}" onsubmit="return confirm('¿Cerrar este ticket?')">@csrf
            <button class="btn btn-outline"><i class="fas fa-check"></i> Cerrar ticket</button>
        </form>
    @endif
</div>

<div class="thread">
    @foreach($ticket->messages as $m)
        <div class="msg {{ $m->is_staff ? 'staff':'' }}">
            <div class="av" @if(!$m->is_staff) style="background:linear-gradient(135deg,#7c3aed,#6d28d9)" @endif>
                {{ $m->is_staff ? 'GS' : strtoupper(substr($m->user->name ?? 'U',0,2)) }}
            </div>
            <div class="bub">
                <div class="who">{{ $m->is_staff ? 'Soporte GymSaaS' : ($m->user->name ?? 'Tú') }}<span>{{ $m->created_at?->diffForHumans() }}</span></div>
                <div class="bd">{{ $m->body }}</div>
            </div>
        </div>
    @endforeach

    @if($ticket->isOpen())
        <form method="POST" action="{{ route('support.reply',$ticket) }}" style="margin-top:18px;border-top:1px solid var(--border);padding-top:18px">
            @csrf
            <textarea name="body" placeholder="Escribe tu respuesta..." required style="width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;min-height:90px;resize:vertical"></textarea>
            <div style="margin-top:10px"><button class="btn btn-primary"><i class="fas fa-paper-plane"></i> Responder</button></div>
        </form>
    @else
        <div style="text-align:center;color:var(--text-secondary);font-size:13px;border-top:1px solid var(--border);padding-top:16px">Este ticket está cerrado.</div>
    @endif
</div>
@endsection
