@extends('layouts.app')
@section('title','Nuevo Entrenador')
@section('page-title','Nuevo Entrenador')
@section('breadcrumb')<a href="{{ route('trainers.index') }}">Entrenadores</a><span class="sep">/</span><span class="current">Nuevo</span>@endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('trainers.store') }}">
    @csrf
    @if($errors->any())<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-user-tie"></i> Datos del Entrenador</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Nombre Completo *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="form-group"><label class="form-label">Correo</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
                <div class="form-group"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
                <div class="form-group"><label class="form-label">Especialidad</label><input type="text" name="speciality" class="form-control" value="{{ old('speciality') }}" placeholder="Ej: CrossFit, Yoga, Musculación"></div>
                <div class="form-group"><label class="form-label">Fecha de Contratación</label><input type="date" name="hire_date" class="form-control" value="{{ old('hire_date') }}"></div>
                <div class="form-group"><label class="form-label">Salario Mensual</label><input type="number" name="salary" class="form-control" step="0.01" value="{{ old('salary') }}"></div>
                <div class="form-group d-flex align-center gap-3">
                    <label class="form-label" style="margin-bottom:0;">Activo</label>
                    <input type="checkbox" name="status" value="1" {{ old('status',1)?'checked':'' }} style="width:18px;height:18px;accent-color:var(--primary);">
                </div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Biografía</label><textarea name="bio" class="form-control" rows="3">{{ old('bio') }}</textarea></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            <a href="{{ route('trainers.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
