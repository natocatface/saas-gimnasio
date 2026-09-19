@extends('layouts.app')
@section('title','Nuevo Ítem')
@section('page-title','Nuevo Ítem de Inventario')
@section('breadcrumb')<a href="{{ route('inventory.index') }}">Inventario</a><span class="sep">/</span><span class="current">Nuevo</span>@endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('inventory.store') }}">
    @csrf
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-boxes"></i> Nuevo Ítem</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Nombre *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="form-group"><label class="form-label">Categoría</label><input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="Ej: Cardio, Pesas..."></div>
                <div class="form-group"><label class="form-label">Proveedor</label><input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}"></div>
                <div class="form-group"><label class="form-label">Cantidad *</label><input type="number" name="quantity" class="form-control" value="{{ old('quantity',0) }}" min="0" required></div>
                <div class="form-group"><label class="form-label">Stock Mínimo *</label><input type="number" name="min_quantity" class="form-control" value="{{ old('min_quantity',5) }}" min="0" required></div>
                <div class="form-group"><label class="form-label">Precio Unitario</label><input type="number" name="unit_price" class="form-control" step="0.01" value="{{ old('unit_price') }}"></div>
                <div class="form-group"><label class="form-label">Fecha de Compra</label><input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}"></div>
                <div class="form-group"><label class="form-label">Estado</label>
                    <select name="status" class="form-control">
                        <option value="disponible">Disponible</option>
                        <option value="agotado">Agotado</option>
                        <option value="mantenimiento">Mantenimiento</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Descripción / Notas</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
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
