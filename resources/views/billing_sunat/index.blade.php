@extends('layouts.app')
@section('title','Comprobantes')
@section('page-title','Comprobantes Electrónicos')
@section('breadcrumb')<span class="current">Comprobantes</span>@endsection

@push('styles')
<style>
    .fe-table{width:100%;border-collapse:collapse;font-size:13.5px}
    .fe-table th{text-align:left;padding:11px 12px;color:var(--text-secondary);font-size:11px;text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid var(--border)}
    .fe-table td{padding:12px;border-bottom:1px solid var(--border)}
    .chip{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .c-success{background:#dcfce7;color:#15803d}.c-info{background:#dbeafe;color:#1e40af}
    .c-warning{background:#fef3c7;color:#92400e}.c-danger{background:#fee2e2;color:#b91c1c}.c-gray{background:#f3f4f6;color:#6b7280}
    .fe-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px}
    .kpi{background:var(--card-bg,#fff);border:1px solid var(--border);border-radius:16px;padding:16px 18px;display:flex;align-items:center;gap:13px;box-shadow:0 1px 2px rgba(0,0,0,.04)}
    .kpi .kic{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:17px;flex-shrink:0}
    .kpi .kval{font-size:20px;font-weight:800;line-height:1.1;color:var(--text-primary)}
    .kpi .klbl{font-size:11.5px;color:var(--text-secondary);margin-top:3px;font-weight:600}
    @media(max-width:900px){.fe-kpis{grid-template-columns:repeat(2,1fr)}}
    .fe-filters{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px}
    .fe-filters .form-control{width:auto;min-width:150px}
    .fe-filters input[type=date]{min-width:150px}
</style>
@endpush

@section('content')

@include('billing_sunat._hero', ['subtitle' => 'Historial de comprobantes emitidos y su estado ante SUNAT.', 'icon' => 'fa-file-invoice'])

{{-- KPIs --}}
<div class="fe-kpis">
    <div class="kpi">
        <div class="kic" style="background:linear-gradient(135deg,#7c3aed,#a855f7)"><i class="fas fa-file-invoice"></i></div>
        <div><div class="kval">{{ number_format($stats['total']) }}</div><div class="klbl">Emitidos</div></div>
    </div>
    <div class="kpi">
        <div class="kic" style="background:linear-gradient(135deg,#10b981,#34d399)"><i class="fas fa-circle-check"></i></div>
        <div><div class="kval">{{ number_format($stats['aceptados']) }}</div><div class="klbl">Aceptados</div></div>
    </div>
    <div class="kpi">
        <div class="kic" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)"><i class="fas fa-clock"></i></div>
        <div><div class="kval">{{ number_format($stats['pendientes']) }}</div><div class="klbl">Pendientes / rechazados</div></div>
    </div>
    <div class="kpi">
        <div class="kic" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8)"><i class="fas fa-sack-dollar"></i></div>
        <div><div class="kval">{{ money($stats['mes'],2) }}</div><div class="klbl">Facturado este mes</div></div>
    </div>
</div>

<div class="d-flex justify-between align-center" style="margin-bottom:16px;flex-wrap:wrap;gap:10px">
    <div class="text-muted text-sm">{{ $docs->total() }} comprobantes emitidos</div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('sunat.settings.edit') }}" class="btn btn-outline"><i class="fas fa-gear"></i> Configuración</a>
        <a href="{{ route('sunat.docs.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo comprobante</a>
    </div>
</div>

@if(!$setting || !$setting->enabled)
    <div class="alert alert-warning">
        <i class="fas fa-triangle-exclamation"></i>
        La facturación electrónica no está habilitada. Ve a <a href="{{ route('sunat.settings.edit') }}">Configuración</a> para activarla.
    </div>
@endif

<form method="GET" action="{{ route('sunat.docs.index') }}" class="fe-filters">
    <select name="tipo" class="form-control">
        <option value="">Todos los tipos</option>
        <option value="01" @selected(request('tipo')==='01')>Factura</option>
        <option value="03" @selected(request('tipo')==='03')>Boleta</option>
        <option value="07" @selected(request('tipo')==='07')>Nota de Crédito</option>
        <option value="08" @selected(request('tipo')==='08')>Nota de Débito</option>
    </select>
    <select name="estado" class="form-control">
        <option value="">Todos los estados</option>
        @foreach(['aceptado','pendiente','enviado','rechazado','error','anulado'] as $e)
            <option value="{{ $e }}" @selected(request('estado')===$e)>{{ ucfirst($e) }}</option>
        @endforeach
    </select>
    <input type="date" name="desde" class="form-control" value="{{ request('desde') }}" title="Desde">
    <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}" title="Hasta">
    <button class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
    @if(request()->hasAny(['tipo','estado','desde','hasta']))
        <a href="{{ route('sunat.docs.index') }}" class="btn btn-ghost"><i class="fas fa-xmark"></i> Limpiar</a>
    @endif
</form>

<div class="card">
    <div class="card-body" style="padding:8px">
        @if($docs->count())
        <table class="fe-table">
            <thead><tr><th>Comprobante</th><th>Tipo</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            @foreach($docs as $d)
                <tr>
                    <td style="font-weight:700">{{ $d->numero }}</td>
                    <td>{{ $d->tipo_nombre }}</td>
                    <td>{{ $d->cliente_razon_social }}<div class="text-xs text-muted">{{ $d->cliente_num_doc }}</div></td>
                    <td class="text-muted">{{ $d->fecha_emision?->format('d/m/Y H:i') }}</td>
                    <td style="font-weight:700">{{ number_format($d->total,2) }} {{ $d->moneda }}</td>
                    <td><span class="chip c-{{ $d->estadoBadge() }}">{{ ucfirst($d->estado) }}</span></td>
                    <td style="text-align:right"><a href="{{ route('sunat.docs.show',$d) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="padding:12px">{{ $docs->links() }}</div>
        @else
            <div style="text-align:center;padding:40px;color:var(--text-secondary)">
                <i class="fas fa-file-invoice" style="font-size:34px;opacity:.35;display:block;margin-bottom:12px"></i>
                @if(request()->hasAny(['tipo','estado','desde','hasta']))
                    No hay comprobantes con esos filtros.
                @else
                    Aún no has emitido comprobantes.
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
