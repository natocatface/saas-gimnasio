@extends('layouts.app')
@section('title', $inventory->name)
@section('page-title', 'Detalle Inventario')
@section('breadcrumb')
<a href="{{ route('inventory.index') }}">Inventario</a>
<span class="sep">/</span>
<span class="current">{{ $inventory->name }}</span>
@endsection

@section('content')
<div style="max-width:600px;">
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-box"></i> {{ $inventory->name }}</div>
        <span class="badge {{ $inventory->status=='disponible'?'badge-success':($inventory->status=='agotado'?'badge-danger':'badge-warning') }}">
            {{ ucfirst($inventory->status) }}
        </span>
    </div>
    <div class="card-body">
        <div class="grid-2" style="gap:16px;">
            <div style="background:var(--primary-light);border-radius:12px;padding:20px;text-align:center;">
                <div class="stat-value" style="font-size:36px;color:var(--primary);">{{ $inventory->quantity }}</div>
                <div class="stat-label">Unidades Disponibles</div>
                @if($inventory->isLowStock())
                    <div style="margin-top:8px;font-size:12px;color:var(--warning);font-weight:600;">⚠️ Stock Bajo</div>
                @endif
            </div>
            <div style="background:#d1fae5;border-radius:12px;padding:20px;text-align:center;">
                <div class="stat-value" style="font-size:36px;color:var(--success);">{{ money($inventory->quantity * $inventory->unit_price, 2) }}</div>
                <div class="stat-label">Valor Total</div>
            </div>
        </div>

        <div style="margin-top:20px;display:flex;flex-direction:column;gap:0;">
            @php
            $info = [
                ['Categoría', $inventory->category ?? '—'],
                ['Precio Unitario', money($inventory->unit_price,2)],
                ['Stock Mínimo', $inventory->min_quantity.' unidades'],
                ['Proveedor', $inventory->supplier ?? '—'],
                ['Fecha de Compra', $inventory->purchase_date?$inventory->purchase_date->format('d/m/Y'):'—'],
            ];
            @endphp
            @foreach($info as $row)
            <div style="display:flex;justify-content:space-between;padding:13px 0;border-bottom:1px solid #f3f4f6;">
                <span class="text-sm text-muted">{{ $row[0] }}</span>
                <span class="text-sm fw-600">{{ $row[1] }}</span>
            </div>
            @endforeach
        </div>

        @if($inventory->notes)
        <div style="background:var(--body-bg);border-radius:10px;padding:14px;margin-top:16px;">
            <div class="text-xs text-muted fw-600 mb-2">NOTAS</div>
            <p class="text-sm">{{ $inventory->notes }}</p>
        </div>
        @endif

        <!-- Stock Progress -->
        <div style="margin-top:20px;">
            <div class="d-flex justify-between text-sm mb-2">
                <span class="text-muted">Nivel de stock</span>
                <span class="fw-600">{{ $inventory->quantity }} / mín. {{ $inventory->min_quantity }}</span>
            </div>
            @php $pct = $inventory->min_quantity > 0 ? min(100, round($inventory->quantity / ($inventory->min_quantity * 2) * 100)) : 100; @endphp
            <div class="progress">
                <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $pct < 50 ? 'var(--danger)' : ($pct < 75 ? 'var(--warning)' : 'var(--success)') }};"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="{{ route('inventory.edit',$inventory) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
        <a href="{{ route('inventory.index') }}" class="btn btn-ghost btn-sm">Volver</a>
    </div>
</div>
</div>
@endsection
