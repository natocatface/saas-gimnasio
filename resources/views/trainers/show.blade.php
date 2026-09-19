@extends('layouts.app')
@section('title', $trainer->name)
@section('page-title','Perfil Entrenador')
@section('breadcrumb')
<a href="{{ route('trainers.index') }}">Entrenadores</a>
<span class="sep">/</span>
<span class="current">{{ $trainer->name }}</span>
@endsection

@section('content')
<div class="grid-2" style="align-items:start;">
    <div class="card">
        <div class="card-body" style="text-align:center;padding:36px 24px;">
            <div class="member-avatar" style="width:80px;height:80px;font-size:28px;margin:0 auto 16px;border-radius:16px;background:linear-gradient(135deg,#059669,#10b981);">
                {{ strtoupper(substr($trainer->name,0,2)) }}
            </div>
            <h2 style="font-size:22px;font-weight:700;margin-bottom:4px;">{{ $trainer->name }}</h2>
            <p class="text-muted text-sm">{{ $trainer->speciality ?? 'Entrenador General' }}</p>
            <div style="margin:14px 0;">
                <span class="badge {{ $trainer->status?'badge-success':'badge-gray' }}">
                    {{ $trainer->status?'Activo':'Inactivo' }}
                </span>
            </div>
            <div class="d-flex gap-2 justify-center mt-4">
                <a href="{{ route('trainers.edit',$trainer) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
            </div>
        </div>
        <div style="border-top:1px solid var(--border);padding:20px 24px;">
            <h4 style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;">Información</h4>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div class="d-flex align-center gap-3"><i class="fas fa-envelope" style="width:16px;color:var(--primary);"></i><span class="text-sm">{{ $trainer->email ?? '—' }}</span></div>
                <div class="d-flex align-center gap-3"><i class="fas fa-phone" style="width:16px;color:var(--primary);"></i><span class="text-sm">{{ $trainer->phone ?? '—' }}</span></div>
                <div class="d-flex align-center gap-3"><i class="fas fa-calendar" style="width:16px;color:var(--primary);"></i><span class="text-sm">Desde: {{ $trainer->hire_date?$trainer->hire_date->format('d/m/Y'):'—' }}</span></div>
                <div class="d-flex align-center gap-3"><i class="fas fa-dollar-sign" style="width:16px;color:var(--primary);"></i><span class="text-sm">{{ money($trainer->salary,2) }}/mes</span></div>
            </div>
        </div>
        @if($trainer->bio)
        <div style="border-top:1px solid var(--border);padding:20px 24px;">
            <h4 style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Biografía</h4>
            <p class="text-sm">{{ $trainer->bio }}</p>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-dumbbell"></i> Clases Asignadas</div></div>
        <div class="table-container">
            <table>
                <thead><tr><th>Clase</th><th>Día</th><th>Hora</th><th>Sala</th></tr></thead>
                <tbody>
                    @forelse($trainer->classes as $c)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div style="width:10px;height:10px;border-radius:50%;background:{{ $c->color??'#7c3aed' }};"></div>
                                <span class="fw-600 text-sm">{{ $c->name }}</span>
                            </div>
                        </td>
                        <td class="text-sm">{{ ucfirst($c->schedule_day??'—') }}</td>
                        <td class="text-sm">{{ $c->schedule_time?substr($c->schedule_time,0,5):'—' }}</td>
                        <td class="text-sm text-muted">{{ $c->room??'—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-secondary);">Sin clases asignadas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
