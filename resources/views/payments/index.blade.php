@extends('layouts.app')

@section('title', 'Pagos')
@section('page-title', 'Gestión de Pagos')
@section('breadcrumb')<span class="current">Pagos</span>@endsection

@section('content')

@include('partials.module_hero', ['title' => 'Pagos', 'subtitle' => 'Registro de pagos y cobros de membresías.', 'icon' => 'fa-credit-card'])

<div class="stats-grid mb-6" style="grid-template-columns:repeat(3,1fr);">
    <div class="kpi-card teal">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="kpi-badge"><i class="fas fa-calendar-alt"></i> Este mes</div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ money($totals['month'],0) }}</div>
            <div class="kpi-label">Ingresos del Mes</div>
        </div>
    </div>
    <div class="kpi-card blue">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="kpi-badge"><i class="fas fa-clock"></i> Hoy</div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ money($totals['today'],2) }}</div>
            <div class="kpi-label">Ingresos Hoy</div>
        </div>
    </div>
    <div class="kpi-card" style="background:linear-gradient(135deg,#78350f,#d97706,#fbbf24);box-shadow:0 8px 24px rgba(217,119,6,0.35);">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-clock"></i></div>
            <div class="kpi-badge"><i class="fas fa-exclamation"></i> Pendientes</div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $totals['pending'] }}</div>
            <div class="kpi-label">Pagos Pendientes</div>
        </div>
    </div>
</div>

<div class="d-flex justify-between align-center mb-4">
    <div></div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('export.payments') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Exportar</a>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Pago
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body" style="padding:16px 24px;">
        <form method="GET" class="d-flex gap-3 align-center flex-wrap">
            <div style="flex:1; min-width:220px; position:relative;">
                <i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-secondary);"></i>
                <input type="text" name="search" placeholder="Buscar socio..." value="{{ request('search') }}"
                    style="width:100%;padding:10px 16px 10px 40px;border:1.5px solid var(--border);border-radius:10px;font-family:Inter,sans-serif;font-size:14px;outline:none;">
            </div>
            <select name="status" class="form-control" style="width:140px;">
                <option value="">Todo</option>
                <option value="pagado" {{ request('status')=='pagado'?'selected':'' }}>Pagado</option>
                <option value="pendiente" {{ request('status')=='pendiente'?'selected':'' }}>Pendiente</option>
                <option value="cancelado" {{ request('status')=='cancelado'?'selected':'' }}>Cancelado</option>
            </select>
            <select name="method" class="form-control" style="width:150px;">
                <option value="">Todos los métodos</option>
                <option value="efectivo" {{ request('method')=='efectivo'?'selected':'' }}>Efectivo</option>
                <option value="tarjeta" {{ request('method')=='tarjeta'?'selected':'' }}>Tarjeta</option>
                <option value="transferencia" {{ request('method')=='transferencia'?'selected':'' }}>Transferencia</option>
                <option value="qr" {{ request('method')=='qr'?'selected':'' }}>QR</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filtrar</button>
            <a href="{{ route('payments.index') }}" class="btn btn-ghost btn-sm">Limpiar</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-receipt"></i> Historial de Pagos</div>
        <span class="text-sm text-muted">{{ $payments->total() }} registros</span>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Socio</th>
                    <th>Plan</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Método</th>
                    <th>Período</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td class="text-muted text-sm">{{ $p->id }}</td>
                    <td>
                        <div class="d-flex align-center gap-2">
                            @if($p->member)
                            <div class="member-avatar" style="width:32px;height:32px;font-size:11px;">
                                {{ strtoupper(substr($p->member->first_name,0,1).substr($p->member->last_name,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-600 text-sm">{{ $p->member->full_name }}</div>
                                <div class="text-xs text-muted">{{ $p->member->code }}</div>
                            </div>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-sm">{{ optional($p->plan)->name ?? '—' }}</td>
                    <td><span class="fw-700" style="color:var(--success);">{{ money($p->amount,2) }}</span></td>
                    <td class="text-sm">{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                    <td>
                        @php $methodIcons=['efectivo'=>'💵','tarjeta'=>'💳','transferencia'=>'🏦','qr'=>'📱']; @endphp
                        <span class="badge badge-info">{{ $methodIcons[$p->payment_method]??'' }} {{ ucfirst($p->payment_method) }}</span>
                    </td>
                    <td class="text-xs text-muted">
                        @if($p->period_start && $p->period_end)
                            {{ \Carbon\Carbon::parse($p->period_start)->format('d/m') }} — {{ \Carbon\Carbon::parse($p->period_end)->format('d/m/Y') }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $p->status==='pagado'?'badge-success':($p->status==='pendiente'?'badge-warning':'badge-danger') }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('payments.show', $p) }}" class="btn btn-ghost btn-icon btn-sm" title="Ver recibo"><i class="fas fa-receipt"></i></a>
                            <a href="{{ route('payments.edit', $p) }}" class="btn btn-outline btn-icon btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('payments.destroy',$p) }}" onsubmit="return confirm('¿Eliminar este pago?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:60px;color:var(--text-secondary);">
                    <i class="fas fa-receipt" style="font-size:40px;display:block;margin-bottom:14px;opacity:.3;"></i>
                    No se encontraron pagos
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
