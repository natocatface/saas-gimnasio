@extends('layouts.superadmin')
@section('title','Ticket #'.$ticket->id)
@section('page-title',$ticket->subject)
@section('breadcrumb')<a href="{{ route('superadmin.support.index') }}">Soporte</a> · Ticket #{{ $ticket->id }}@endsection

@section('actions')
    <form method="POST" action="{{ route('superadmin.support.status',$ticket) }}" style="display:flex;gap:8px;align-items:center">
        @csrf @method('PATCH')
        <select name="status" class="form-control" style="padding:8px 12px" onchange="this.form.submit()">
            @foreach(['open'=>'Abierto','pending'=>'En espera','closed'=>'Cerrado'] as $k=>$v)
                <option value="{{ $k }}" @selected($ticket->status===$k)>{{ $v }}</option>
            @endforeach
        </select>
    </form>
@endsection

@section('content')
<div class="grid" style="grid-template-columns:1fr 280px;align-items:start;gap:20px">
    {{-- Conversación --}}
    <div class="card">
        @foreach($ticket->messages as $m)
            <div style="display:flex;gap:12px;margin-bottom:18px">
                <div style="width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0;background:{{ $m->is_staff ? 'linear-gradient(135deg,#7c3aed,#6d28d9)' : '#374151' }}">
                    {{ $m->is_staff ? 'GS' : strtoupper(substr($m->user->name ?? 'U',0,2)) }}
                </div>
                <div style="flex:1;background:{{ $m->is_staff ? '#f5f3ff' : '#f9fafb' }};border-radius:12px;padding:13px 16px">
                    <div style="font-weight:700;font-size:13px;margin-bottom:3px">{{ $m->is_staff ? 'Soporte (tú)' : ($m->user->name ?? 'Gimnasio') }}<span style="font-weight:500;color:var(--muted);font-size:12px;margin-left:6px">{{ $m->created_at?->diffForHumans() }}</span></div>
                    <div style="font-size:14px;line-height:1.6;white-space:pre-wrap">{{ $m->body }}</div>
                </div>
            </div>
        @endforeach

        @if($ticket->status !== 'closed')
            <form method="POST" action="{{ route('superadmin.support.reply',$ticket) }}" style="margin-top:18px;border-top:1px solid var(--border);padding-top:18px">
                @csrf
                <textarea name="body" placeholder="Escribe tu respuesta al gimnasio..." required class="form-control" style="min-height:100px;resize:vertical"></textarea>
                <div style="margin-top:10px"><button class="btn btn-primary"><i class="fas fa-paper-plane"></i> Responder</button></div>
            </form>
        @else
            <div style="text-align:center;color:var(--muted);font-size:13px;border-top:1px solid var(--border);padding-top:16px">Ticket cerrado.</div>
        @endif
    </div>

    {{-- Info --}}
    <div class="card">
        <h3 style="font-size:15px;font-weight:700;margin-bottom:14px">Detalles</h3>
        <div style="font-size:14px;line-height:2.2">
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Gimnasio</span><b>{{ $ticket->gymnasium->name ?? '—' }}</b></div>
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Abierto por</span><b>{{ $ticket->user->name ?? '—' }}</b></div>
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Prioridad</span><b>{{ $ticket->priorityLabel() }}</b></div>
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Creado</span><b>{{ $ticket->created_at->format('d/m/Y') }}</b></div>
        </div>
        <a href="{{ route('superadmin.gyms.show',$ticket->gymnasium_id) }}" class="btn btn-light btn-sm" style="width:100%;margin-top:14px;justify-content:center"><i class="fas fa-dumbbell"></i> Ver gimnasio</a>
    </div>
</div>
@endsection
