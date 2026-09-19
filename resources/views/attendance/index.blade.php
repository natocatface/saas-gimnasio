@extends('layouts.app')

@section('title', 'Asistencia')
@section('page-title', 'Control de Asistencia')
@section('breadcrumb')<span class="current">Asistencia</span>@endsection

@section('content')

@include('partials.module_hero', ['title' => 'Asistencia', 'subtitle' => 'Control de ingresos y asistencia de los socios.', 'icon' => 'fa-fingerprint'])

<div class="stats-grid mb-6" style="grid-template-columns:repeat(3,1fr);">
    <div class="kpi-card blue">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="kpi-badge"><i class="fas fa-clock"></i> Hoy</div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $todayCount }}</div>
            <div class="kpi-label">Visitas Hoy</div>
        </div>
    </div>
    <div class="kpi-card violet">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-calendar-week"></i></div>
            <div class="kpi-badge"><i class="fas fa-chart-bar"></i> Semana</div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $weekCount }}</div>
            <div class="kpi-label">Esta Semana</div>
        </div>
    </div>
    <div class="kpi-card teal">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="kpi-badge"><i class="fas fa-chart-line"></i> Mes</div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $monthCount }}</div>
            <div class="kpi-label">Este Mes</div>
        </div>
    </div>
</div>

<div class="d-flex justify-between align-center mb-4">
    <form method="GET" class="d-flex gap-3 align-center">
        <input type="date" name="date" class="form-control" style="width:180px;" value="{{ request('date', date('Y-m-d')) }}" onchange="this.form.submit()">
        <div style="position:relative;">
            <i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-secondary);"></i>
            <input type="text" name="search" class="form-control" placeholder="Buscar socio..." value="{{ request('search') }}" style="padding-left:40px;width:220px;">
        </div>
        <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-filter"></i> Filtrar</button>
    </form>
    <a href="{{ route('attendance.create') }}" class="btn btn-primary">
        <i class="fas fa-fingerprint"></i> Registrar Check-in
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-list-check"></i> Registros de Asistencia</div>
        <span class="badge badge-purple">{{ $attendance->total() }} registros</span>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Socio</th>
                    <th>Clase</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $a)
                <tr>
                    <td>
                        <div class="d-flex align-center gap-2">
                            @if($a->member)
                            <div class="member-avatar" style="width:34px;height:34px;font-size:12px;">
                                {{ strtoupper(substr($a->member->first_name,0,1).substr($a->member->last_name,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-600 text-sm">{{ $a->member->full_name }}</div>
                                <div class="text-xs text-muted">{{ $a->member->code }}</div>
                            </div>
                            @else <span class="text-muted">—</span> @endif
                        </div>
                    </td>
                    <td class="text-sm">{{ optional($a->gymClass)->name ?? 'Acceso libre' }}</td>
                    <td class="text-sm fw-600">{{ \Carbon\Carbon::parse($a->check_in)->format('H:i') }}</td>
                    <td class="text-sm">
                        {{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : '—' }}
                    </td>
                    <td class="text-sm text-muted">
                        @if($a->check_out)
                            {{ \Carbon\Carbon::parse($a->check_in)->diffInMinutes($a->check_out) }} min
                        @else
                            <span style="color:var(--success);">En curso</span>
                        @endif
                    </td>
                    <td>
                        @if($a->check_out)
                            <span class="badge badge-gray">Completado</span>
                        @else
                            <span class="badge badge-success">Activo</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            @if(!$a->check_out)
                            <form method="POST" action="{{ route('attendance.checkout', $a->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:var(--warning);color:white;">
                                    <i class="fas fa-sign-out-alt"></i> Check-out
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('attendance.destroy', $a) }}" onsubmit="return confirm('¿Eliminar registro?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:60px;color:var(--text-secondary);">
                    <i class="fas fa-fingerprint" style="font-size:40px;display:block;margin-bottom:14px;opacity:.3;"></i>
                    Sin registros para esta fecha
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
