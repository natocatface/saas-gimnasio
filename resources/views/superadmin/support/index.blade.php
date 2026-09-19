@extends('layouts.superadmin')
@section('title','Soporte')
@section('page-title','Tickets de soporte')
@section('breadcrumb','Atención a los gimnasios')

@section('content')

<div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:20px">
    <div class="stat grad g-green"><div class="ico"><i class="fas fa-envelope-open-text"></i></div><div class="val">{{ $counts['open'] }}</div><div class="lbl">Abiertos</div></div>
    <div class="stat grad g-amber"><div class="ico"><i class="fas fa-clock"></i></div><div class="val">{{ $counts['pending'] }}</div><div class="lbl">En espera</div></div>
    <div class="stat grad g-violet"><div class="ico"><i class="fas fa-circle-check"></i></div><div class="val">{{ $counts['closed'] }}</div><div class="lbl">Cerrados</div></div>
</div>

<div class="card" style="padding:0;overflow:hidden">
    <form method="GET" style="display:flex;gap:10px;padding:16px 20px;border-bottom:1px solid var(--border)">
        <select name="status" class="form-control" style="max-width:180px" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach(['open'=>'Abiertos','pending'=>'En espera','closed'=>'Cerrados'] as $k=>$v)
                <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
            @endforeach
        </select>
    </form>
    <table>
        <thead><tr><th>Asunto</th><th>Gimnasio</th><th>Prioridad</th><th>Mensajes</th><th>Última actividad</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        @forelse($tickets as $t)
            <tr>
                <td style="font-weight:600">{{ $t->subject }}<div style="font-size:12px;color:var(--muted)">#{{ $t->id }}</div></td>
                <td>{{ $t->gymnasium->name ?? '—' }}</td>
                <td><span class="badge" style="background:{{ ['high'=>'#fee2e2','normal'=>'#eef2ff','low'=>'#f3f4f6'][$t->priority] }};color:{{ ['high'=>'#b91c1c','normal'=>'#4f46e5','low'=>'#6b7280'][$t->priority] }}">{{ $t->priorityLabel() }}</span></td>
                <td>{{ $t->messages_count }}</td>
                <td style="color:var(--muted);font-size:13px">{{ $t->last_reply_at?->diffForHumans() }}</td>
                <td><span class="badge {{ $t->status==='open'?'active':($t->status==='pending'?'trial':'cancelled') }}">{{ $t->statusLabel() }}</span></td>
                <td style="text-align:right"><a href="{{ route('superadmin.support.show',$t) }}" class="btn btn-light btn-sm"><i class="fas fa-eye"></i></a></td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty"><i class="fas fa-headset"></i><div>Sin tickets de soporte</div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:18px">{{ $tickets->links() }}</div>
@endsection
