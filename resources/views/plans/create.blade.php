@extends('layouts.app')
@section('title','Nuevo Plan')
@section('page-title','Nuevo Plan')
@section('breadcrumb')<a href="{{ route('plans.index') }}">Planes</a><span class="sep">/</span><span class="current">Nuevo</span>@endsection

@section('content')
<div style="max-width:600px;">
<form method="POST" action="{{ route('plans.store') }}">
    @csrf
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-clipboard-list"></i> Nuevo Plan de Membresía</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Nombre del Plan *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Ej: Plan Premium"></div>
                <div class="form-group"><label class="form-label">Precio *</label><input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required></div>
                <div class="form-group"><label class="form-label">Duración (días) *</label><input type="number" name="duration_days" class="form-control" value="{{ old('duration_days',30) }}" required min="1"></div>
                <div class="form-group"><label class="form-label">Color de acento</label><input type="color" name="color" value="{{ old('color','#7c3aed') }}" style="width:100%;height:44px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;"></div>
                <div class="form-group d-flex align-center gap-3">
                    <label class="form-label" style="margin-bottom:0;">Plan Destacado</label>
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured')?'checked':'' }} style="width:18px;height:18px;accent-color:var(--primary);">
                </div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Descripción</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Características (una por línea)</label>
                    <textarea name="features" class="form-control" rows="5" placeholder="Acceso al gym&#10;Vestuarios&#10;Clases grupales">{{ old('features') }}</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Plan</button>
            <a href="{{ route('plans.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
