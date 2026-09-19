@extends('layouts.superadmin')
@section('title',$gym->name)
@section('page-title',$gym->name)
@section('breadcrumb')<a href="{{ route('superadmin.gyms.index') }}">Gimnasios</a> · Detalle@endsection

@section('actions')
    <form method="POST" action="{{ route('superadmin.gyms.impersonate',$gym) }}" style="display:inline">
        @csrf
        <button class="btn btn-light" title="Entrar como este gimnasio"><i class="fas fa-user-secret"></i> Entrar como gym</button>
    </form>
    <a href="{{ route('superadmin.subscriptions.create',['gym'=>$gym->id]) }}" class="btn btn-primary"><i class="fas fa-file-invoice-dollar"></i> Registrar cobro</a>
    <a href="{{ route('superadmin.gyms.edit',$gym) }}" class="btn btn-light"><i class="fas fa-pen"></i> Editar</a>
@endsection

@section('content')

    <div class="grid" style="grid-template-columns:1fr 2fr;align-items:start">
        {{-- Tarjeta del gym --}}
        <div class="card">
            <div style="text-align:center;padding:10px 0 18px">
                <div class="gym-logo" style="width:70px;height:70px;font-size:26px;border-radius:18px;margin:0 auto 14px;background:{{ $gym->primary_color ?? '#7c3aed' }}">{{ strtoupper(substr($gym->name,0,2)) }}</div>
                <h2 style="font-size:20px;font-weight:700">{{ $gym->name }}</h2>
                <div style="color:var(--muted);font-size:13px;margin-top:3px">{{ $gym->slug }}.gymsaas.com</div>
                <div style="margin-top:12px"><span class="badge {{ $gym->status }}">{{ ucfirst($gym->status) }}</span></div>
            </div>
            <div style="border-top:1px solid var(--border);padding-top:16px;font-size:14px;line-height:2.1">
                <div><i class="fas fa-envelope" style="width:22px;color:var(--muted)"></i> {{ $gym->email ?? '—' }}</div>
                <div><i class="fas fa-phone" style="width:22px;color:var(--muted)"></i> {{ $gym->phone ?? '—' }}</div>
                <div><i class="fas fa-location-dot" style="width:22px;color:var(--muted)"></i> {{ $gym->city ?? '—' }}, {{ $gym->country }}</div>
                <div><i class="fas fa-user" style="width:22px;color:var(--muted)"></i> {{ $gym->owner->name ?? '—' }} (dueño)</div>
                <div><i class="fas fa-layer-group" style="width:22px;color:var(--muted)"></i> Plan <strong>{{ $gym->saasPlan->name ?? '—' }}</strong></div>
                @if($gym->status==='trial' && $gym->trial_ends_at)
                    <div><i class="fas fa-hourglass-half" style="width:22px;color:var(--warning)"></i> Prueba vence {{ \Carbon\Carbon::parse($gym->trial_ends_at)->format('d/m/Y') }}</div>
                @endif
            </div>

            {{-- Acciones rápidas de estado --}}
            <div style="border-top:1px solid var(--border);margin-top:16px;padding-top:16px;display:flex;gap:8px;flex-wrap:wrap">
                @if($gym->status!=='active')
                    <form method="POST" action="{{ route('superadmin.gyms.status',$gym) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="active"><button class="btn btn-light btn-sm" style="color:var(--success)"><i class="fas fa-circle-check"></i> Activar</button></form>
                @endif
                @if($gym->status!=='suspended')
                    <form method="POST" action="{{ route('superadmin.gyms.status',$gym) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="suspended"><button class="btn btn-light btn-sm" style="color:var(--warning)"><i class="fas fa-ban"></i> Suspender</button></form>
                @endif
                <form method="POST" action="{{ route('superadmin.gyms.destroy',$gym) }}" onsubmit="return confirm('¿Eliminar este gimnasio y todos sus datos? Esta acción no se puede deshacer.')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button></form>
            </div>
        </div>

        <div>
            {{-- Métricas del gym --}}
            <div class="grid" style="grid-template-columns:1fr 1fr;margin-bottom:18px">
                <div class="stat grad g-violet"><div class="ico"><i class="fas fa-users"></i></div><div class="val">{{ $gymStats['members'] }}</div><div class="lbl">Socios registrados</div></div>
                <div class="stat grad g-blue"><div class="ico"><i class="fas fa-user-shield"></i></div><div class="val">{{ $gymStats['users'] }}</div><div class="lbl">Usuarios del staff</div></div>
            </div>

            {{-- Historial de suscripciones --}}
            <div class="card" style="padding:0;overflow:hidden">
                <div class="card-h" style="padding:20px 22px 0"><h3>Historial de suscripciones</h3></div>
                <table>
                    <thead><tr><th>Fecha</th><th>Plan</th><th>Ciclo</th><th>Monto</th><th>Vigencia</th><th>Estado</th></tr></thead>
                    <tbody>
                    @forelse($subscriptions as $s)
                        <tr>
                            <td style="color:var(--muted)">{{ $s->created_at->format('d/m/Y') }}</td>
                            <td>{{ $s->saasPlan->name ?? '—' }}</td>
                            <td>{{ $s->billing_cycle==='yearly'?'Anual':'Mensual' }}</td>
                            <td style="font-weight:600">{{ money($s->amount,2) }}</td>
                            <td style="font-size:13px;color:var(--muted)">{{ \Carbon\Carbon::parse($s->starts_at)->format('d/m/y') }} → {{ \Carbon\Carbon::parse($s->ends_at)->format('d/m/y') }}</td>
                            <td><span class="badge {{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty"><i class="fas fa-receipt"></i><div>Sin suscripciones registradas</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
