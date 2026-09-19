@extends('layouts.app')
@section('title','Inventario')
@section('page-title','Inventario')
@section('breadcrumb')<span class="current">Inventario</span>@endsection

@section('content')

@include('partials.module_hero', ['title' => 'Inventario', 'subtitle' => 'Productos, insumos y control de stock.', 'icon' => 'fa-boxes'])

<div class="stats-grid mb-6" style="grid-template-columns:repeat(4,1fr);">
    <div class="kpi-card blue">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-boxes"></i></div><div class="kpi-badge"><i class="fas fa-list"></i> Total</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ $stats['total'] }}</div><div class="kpi-label">Total Ítems</div></div>
    </div>
    <div class="kpi-card" style="background:linear-gradient(135deg,#78350f,#d97706,#fbbf24);box-shadow:0 8px 24px rgba(217,119,6,0.3);">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div><div class="kpi-badge"><i class="fas fa-arrow-down"></i> Alerta</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ $stats['low_stock'] }}</div><div class="kpi-label">Stock Bajo</div></div>
    </div>
    <div class="kpi-card rose">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-ban"></i></div><div class="kpi-badge"><i class="fas fa-times"></i> Agotado</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ $stats['out_of_stock'] }}</div><div class="kpi-label">Sin Stock</div></div>
    </div>
    <div class="kpi-card teal">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div><div class="kpi-badge"><i class="fas fa-chart-line"></i> Valor</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ money($stats['total_value'],0) }}</div><div class="kpi-label">Valor Total</div></div>
    </div>
</div>

<div class="d-flex justify-between align-center mb-4">
    <form method="GET" class="d-flex gap-3">
        <div style="position:relative;"><i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-secondary);"></i>
            <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" style="padding:10px 16px 10px 40px;border:1.5px solid var(--border);border-radius:10px;font-family:Inter,sans-serif;font-size:14px;outline:none;width:220px;">
        </div>
        <select name="status" class="form-control" style="width:160px;"><option value="">Todo</option><option value="disponible" {{ request('status')=='disponible'?'selected':'' }}>Disponible</option><option value="agotado" {{ request('status')=='agotado'?'selected':'' }}>Agotado</option><option value="mantenimiento" {{ request('status')=='mantenimiento'?'selected':'' }}>Mantenimiento</option></select>
        <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-filter"></i></button>
    </form>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Ítem</a>
</div>

<div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-list"></i> Lista de Inventario</div></div>
    <div class="table-container">
        <table>
            <thead><tr><th>Ítem</th><th>Categoría</th><th>Cantidad</th><th>Stock Mín.</th><th>Precio Unit.</th><th>Valor Total</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse($inventory as $item)
                <tr>
                    <td><div class="fw-600">{{ $item->name }}</div><div class="text-xs text-muted">{{ $item->supplier }}</div></td>
                    <td><span class="badge badge-gray">{{ $item->category ?? '—' }}</span></td>
                    <td>
                        <span class="fw-700 {{ $item->quantity <= $item->min_quantity ? 'text-danger' : '' }}">{{ $item->quantity }}</span>
                        @if($item->isLowStock())<i class="fas fa-exclamation-triangle text-warning ms-1" style="color:var(--warning);margin-left:6px;font-size:12px;"></i>@endif
                    </td>
                    <td class="text-muted text-sm">{{ $item->min_quantity }}</td>
                    <td class="text-sm">{{ money($item->unit_price,2) }}</td>
                    <td class="fw-600" style="color:var(--success);">{{ money($item->quantity * $item->unit_price,2) }}</td>
                    <td>
                        <span class="badge {{ $item->status=='disponible'?'badge-success':($item->status=='agotado'?'badge-danger':'badge-warning') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('inventory.show',$item) }}" class="btn btn-ghost btn-icon btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('inventory.edit',$item) }}" class="btn btn-outline btn-icon btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('inventory.destroy',$item) }}" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:60px;color:var(--text-secondary);">
                    <i class="fas fa-boxes" style="font-size:40px;display:block;margin-bottom:14px;opacity:.3;"></i>Sin ítems en inventario
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
