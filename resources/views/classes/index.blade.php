@extends('layouts.app')
@section('title','Clases')
@section('page-title','Clases y Horarios')
@section('breadcrumb')<span class="current">Clases</span>@endsection

@section('content')

@include('partials.module_hero', ['title' => 'Clases', 'subtitle' => 'Clases grupales, horarios, cupos y entrenadores.', 'icon' => 'fa-dumbbell'])
<div class="d-flex justify-between align-center mb-6">
    <div class="text-muted text-sm">{{ count($classes) }} clases programadas</div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('classes.schedule') }}" class="btn btn-outline"><i class="fas fa-calendar-week"></i> Horario</a>
        <a href="{{ route('classes.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva Clase</a>
    </div>
</div>

<!-- Schedule View -->
<div class="card mb-6">
    <div class="card-header"><div class="card-title"><i class="fas fa-calendar-week"></i> Horario Semanal</div></div>
    <div class="card-body" style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:700px;">
            <thead>
                <tr>
                    @foreach($days as $day)
                    <th style="padding:12px;text-align:center;font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:capitalize;border-bottom:2px solid var(--border);background:var(--body-bg);">
                        {{ ucfirst($day) }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($days as $day)
                    <td style="padding:8px;vertical-align:top;border-right:1px solid #f3f4f6;min-height:120px;">
                        @foreach($classes->where('schedule_day',$day) as $c)
                        <div style="background:{{ $c->color ?? '#7c3aed' }}20;border-left:3px solid {{ $c->color ?? '#7c3aed' }};border-radius:8px;padding:10px;margin-bottom:8px;">
                            <div style="font-size:13px;font-weight:700;color:{{ $c->color ?? '#7c3aed' }};">{{ $c->name }}</div>
                            <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">
                                {{ $c->schedule_time ? substr($c->schedule_time,0,5) : '' }} · {{ $c->duration_minutes }}min
                            </div>
                            <div style="font-size:11px;color:var(--text-secondary);">{{ optional($c->trainer)->name ?? '—' }}</div>
                        </div>
                        @endforeach
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Classes List -->
<div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-list"></i> Lista de Clases</div></div>
    <div class="table-container">
        <table>
            <thead><tr><th>Clase</th><th>Entrenador</th><th>Horario</th><th>Sala</th><th>Inscritos</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse($classes as $c)
                <tr>
                    <td>
                        <div class="d-flex align-center gap-2">
                            <div style="width:10px;height:10px;border-radius:50%;background:{{ $c->color ?? '#7c3aed' }};flex-shrink:0;"></div>
                            <div><div class="fw-600">{{ $c->name }}</div><div class="text-xs text-muted">{{ $c->duration_minutes }} min</div></div>
                        </div>
                    </td>
                    <td class="text-sm">{{ optional($c->trainer)->name ?? '—' }}</td>
                    <td class="text-sm">{{ ucfirst($c->schedule_day ?? '—') }} {{ $c->schedule_time ? substr($c->schedule_time,0,5) : '' }}</td>
                    <td class="text-sm text-muted">{{ $c->room ?? '—' }}</td>
                    <td>
                        <span class="badge badge-purple">{{ $c->enrollments_count }}/{{ $c->capacity }}</span>
                    </td>
                    <td><span class="badge {{ $c->status?'badge-success':'badge-gray' }}">{{ $c->status?'Activa':'Inactiva' }}</span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('classes.edit',$c) }}" class="btn btn-outline btn-icon btn-sm"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('classes.destroy',$c) }}" onsubmit="return confirm('¿Eliminar clase?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:60px;color:var(--text-secondary);">
                    <i class="fas fa-dumbbell" style="font-size:40px;display:block;margin-bottom:14px;opacity:.3;"></i>
                    No hay clases registradas
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
