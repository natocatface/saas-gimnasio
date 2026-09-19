@extends('layouts.app')
@section('title','Editar Ítem')
@section('page-title','Editar Inventario')
@section('breadcrumb')<a href="{{ route('inventory.index') }}">Inventario</a><span class="sep">/</span><span class="current">Editar</span>@endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('inventory.update',$inventory) }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-boxes"></i> {{ $inventory->name }}</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Nombre *</label><input type="text" name="name" class="form-control" value="{{ old('name',$inventory->name) }}" required></div>
                <div class="form-group"><label class="form-label">Categoría</label><input type="text" name="category" class="form-control" value="{{ old('category',$inventory->category) }}"></div>
                <div class="form-group"><label class="form-label">Proveedor</label><input type="text" name="supplier" class="form-control" value="{{ old('supplier',$inventory->supplier) }}"></div>
                <div class="form-group"><label class="form-label">Cantidad *</label><input type="number" name="quantity" class="form-control" value="{{ old('quantity',$inventory->quantity) }}" min="0" required></div>
                <div class="form-group"><label class="form-label">Stock Mínimo *</label><input type="number" name="min_quantity" class="form-control" value="{{ old('min_quantity',$inventory->min_quantity) }}" min="0" required></div>
                <div class="form-group"><label class="form-label">Precio Unitario</label><input type="number" name="unit_price" class="form-control" step="0.01" value="{{ old('unit_price',$inventory->unit_price) }}"></div>
                <div class="form-group"><label class="form-label">Fecha Compra</label><input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', optional($inventory->purchase_date)->format('Y-m-d')) }}"></div>
                <div class="form-group"><label class="form-label">Estado</label>
                    <select name="status" class="form-control">
                        <option value="disponible" {{ $inventory->status=='disponible'?'selected':'' }}>Disponible</option>
                        <option value="agotado" {{ $inventory->status=='agotado'?'selected':'' }}>Agotado</option>
                        <option value="mantenimiento" {{ $inventory->status=='mantenimiento'?'selected':'' }}>Mantenimiento</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Notas</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$inventory->notes) }}</textarea></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            <a href="{{ route('inventory.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
