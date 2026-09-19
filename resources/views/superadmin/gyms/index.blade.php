@extends('layouts.superadmin')
@section('title','Gimnasios')
@section('page-title','Gimnasios')
@section('breadcrumb','Todos los clientes del SaaS')

@section('actions')
    <a href="{{ route('superadmin.gyms.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo gimnasio</a>
@endsection

@section('content')

    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(190px,1fr));margin-bottom:20px">
        <div class="stat grad g-violet"><div class="ico"><i class="fas fa-dumbbell"></i></div><div class="val">{{ $counts['total'] }}</div><div class="lbl">Total de gimnasios</div></div>
        <div class="stat grad g-green"><div class="ico"><i class="fas fa-circle-check"></i></div><div class="val">{{ $counts['active'] }}</div><div class="lbl">Activos</div></div>
        <div class="stat grad g-amber"><div class="ico"><i class="fas fa-hourglass-half"></i></div><div class="val">{{ $counts['trial'] }}</div><div class="lbl">En prueba</div></div>
        <div class="stat grad g-pink"><div class="ico"><i class="fas fa-ban"></i></div><div class="val">{{ $counts['suspended'] }}</div><div class="lbl">Suspendidos</div></div>
    </div>

    <div class="card" style="padding:0;overflow:hidden">
        <form method="GET" style="display:flex;gap:10px;padding:18px 20px;flex-wrap:wrap;border-bottom:1px solid var(--border)">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="max-width:280px" placeholder="Buscar por nombre, email o slug...">
            <select name="status" class="form-control" style="max-width:170px">
                <option value="">Todos los estados</option>
                @foreach(['active'=>'Activo','trial'=>'Prueba','suspended'=>'Suspendido','cancelled'=>'Cancelado'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
            </select>
            <select name="plan" class="form-control" style="max-width:170px">
                <option value="">Todos los planes</option>
                @foreach($plans as $p)<option value="{{ $p->id }}" @selected(request('plan')==$p->id)>{{ $p->name }}</option>@endforeach
            </select>
            <button class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Filtrar</button>
            @if(request()->hasAny(['search','status','plan']))<a href="{{ route('superadmin.gyms.index') }}" class="btn btn-light">Limpiar</a>@endif
        </form>

        <table>
            <thead><tr><th>Gimnasio</th><th>Plan</th><th>Usuarios</th><th>Estado</th><th>Prueba/Registro</th><th></th></tr></thead>
            <tbody>
            @forelse($gyms as $g)
                <tr>
                    <td><div class="gym-mini">
                        <div class="gym-logo" style="background:{{ $g->primary_color ?? '#7c3aed' }}">{{ strtoupper(substr($g->name,0,2)) }}</div>
                        <div><div style="font-weight:600">{{ $g->name }}</div><div style="font-size:12px;color:var(--muted)">{{ $g->email ?? $g->slug }}</div></div>
                    </div></td>
                    <td>@if($g->saasPlan)<span class="badge" style="background:{{ $g->saasPlan->color }}22;color:{{ $g->saasPlan->color }}">{{ $g->saasPlan->name }}</span>@else — @endif</td>
                    <td>{{ $g->users_count }}</td>
                    <td><span class="badge {{ $g->status }}">{{ ucfirst($g->status) }}</span></td>
                    <td style="color:var(--muted);font-size:13px">
                        @if($g->status==='trial' && $g->trial_ends_at)
                            Vence {{ \Carbon\Carbon::parse($g->trial_ends_at)->format('d/m/Y') }}
                        @else
                            {{ $g->created_at->format('d/m/Y') }}
                        @endif
                    </td>
                    <td style="text-align:right"><a href="{{ route('superadmin.gyms.show',$g) }}" class="btn btn-light btn-sm"><i class="fas fa-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty"><i class="fas fa-dumbbell"></i><div>No se encontraron gimnasios</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:18px">{{ $gyms->links() }}</div>

@endsection
