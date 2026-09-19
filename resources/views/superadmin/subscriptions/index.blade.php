@extends('layouts.superadmin')
@section('title','Suscripciones')
@section('page-title','Suscripciones')
@section('breadcrumb','Cobros e ingresos del SaaS')

@section('actions')
    <a href="{{ route('superadmin.subscriptions.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Registrar cobro</a>
@endsection

@section('content')

    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(190px,1fr));margin-bottom:20px">
        <div class="stat grad g-violet"><div class="ico"><i class="fas fa-file-invoice-dollar"></i></div><div class="val">{{ $stats['total'] }}</div><div class="lbl">Suscripciones</div></div>
        <div class="stat grad g-green"><div class="ico"><i class="fas fa-circle-check"></i></div><div class="val">{{ $stats['active'] }}</div><div class="lbl">Activas</div></div>
        <div class="stat grad g-blue"><div class="ico"><i class="fas fa-sack-dollar"></i></div><div class="val">{{ money($stats['revenue'],0) }}</div><div class="lbl">Ingreso acumulado</div></div>
        <div class="stat grad g-pink"><div class="ico"><i class="fas fa-calendar-day"></i></div><div class="val">{{ money($stats['this_month'],0) }}</div><div class="lbl">Este mes</div></div>
    </div>

    <div class="card" style="padding:0;overflow:hidden">
        <form method="GET" style="display:flex;gap:10px;padding:18px 20px;flex-wrap:wrap;border-bottom:1px solid var(--border)">
            <select name="gym" class="form-control" style="max-width:220px">
                <option value="">Todos los gimnasios</option>
                @foreach($gyms as $g)<option value="{{ $g->id }}" @selected(request('gym')==$g->id)>{{ $g->name }}</option>@endforeach
            </select>
            <select name="status" class="form-control" style="max-width:170px">
                <option value="">Todos los estados</option>
                @foreach(['active'=>'Activa','expired'=>'Vencida','cancelled'=>'Cancelada','pending'=>'Pendiente'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Filtrar</button>
            @if(request()->hasAny(['gym','status']))<a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-light">Limpiar</a>@endif
        </form>

        <table>
            <thead><tr><th>Fecha</th><th>Gimnasio</th><th>Plan</th><th>Ciclo</th><th>Monto</th><th>Vigencia</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            @forelse($subscriptions as $s)
                <tr>
                    <td style="color:var(--muted)">{{ $s->created_at->format('d/m/Y') }}</td>
                    <td style="font-weight:600">{{ $s->gymnasium->name ?? '—' }}</td>
                    <td>{{ $s->saasPlan->name ?? '—' }}</td>
                    <td>{{ $s->billing_cycle==='yearly'?'Anual':'Mensual' }}</td>
                    <td style="font-weight:700">{{ money($s->amount,2) }}</td>
                    <td style="font-size:13px;color:var(--muted)">{{ \Carbon\Carbon::parse($s->starts_at)->format('d/m/y') }} → {{ \Carbon\Carbon::parse($s->ends_at)->format('d/m/y') }}</td>
                    <td><span class="badge {{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                    <td style="text-align:right;white-space:nowrap">
                        <a href="{{ route('superadmin.subscriptions.receipt',$s) }}" target="_blank" class="btn btn-light btn-sm" title="Recibo"><i class="fas fa-receipt"></i></a>
                        @if($s->status==='active')
                        <form method="POST" action="{{ route('superadmin.subscriptions.cancel',$s) }}" style="display:inline" onsubmit="return confirm('¿Cancelar esta suscripción?')">@csrf @method('PATCH')<button class="btn btn-light btn-sm" title="Cancelar"><i class="fas fa-xmark"></i></button></form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty"><i class="fas fa-file-invoice-dollar"></i><div>Sin suscripciones registradas</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px">{{ $subscriptions->links() }}</div>

@endsection
