@extends('layouts.app')
@section('title','Editar Entrenador')
@section('page-title','Editar Entrenador')
@section('breadcrumb')<a href="{{ route('trainers.index') }}">Entrenadores</a><span class="sep">/</span><span class="current">Editar</span>@endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('trainers.update', $trainer) }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-user-tie"></i> {{ $trainer->name }}</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Nombre *</label><input type="text" name="name" class="form-control" value="{{ old('name',$trainer->name) }}" required></div>
                <div class="form-group"><label class="form-label">Correo</label><input type="email" name="email" class="form-control" value="{{ old('email',$trainer->email) }}"></div>
                <div class="form-group"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$trainer->phone) }}"></div>
                <div class="form-group"><label class="form-label">Especialidad</label><input type="text" name="speciality" class="form-control" value="{{ old('speciality',$trainer->speciality) }}"></div>
                <div class="form-group"><label class="form-label">Fecha Contratación</label><input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', optional($trainer->hire_date)->format('Y-m-d')) }}"></div>
                <div class="form-group"><label class="form-label">Salario</label><input type="number" name="salary" class="form-control" step="0.01" value="{{ old('salary',$trainer->salary) }}"></div>
                <div class="form-group d-flex align-center gap-3">
                    <label class="form-label" style="margin-bottom:0;">Activo</label>
                    <input type="checkbox" name="status" value="1" {{ $trainer->status?'checked':'' }} style="width:18px;height:18px;accent-color:var(--primary);">
                </div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Biografía</label><textarea name="bio" class="form-control" rows="3">{{ old('bio',$trainer->bio) }}</textarea></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            <a href="{{ route('trainers.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
