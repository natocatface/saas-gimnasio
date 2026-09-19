@extends('layouts.app')
@section('title','Editar Clase')
@section('page-title','Editar Clase')
@section('breadcrumb')<a href="{{ route('classes.index') }}">Clases</a><span class="sep">/</span><span class="current">Editar</span>@endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('classes.update',$class) }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-dumbbell"></i> {{ $class->name }}</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Nombre *</label><input type="text" name="name" class="form-control" value="{{ old('name',$class->name) }}" required></div>
                <div class="form-group"><label class="form-label">Entrenador</label>
                    <select name="trainer_id" class="form-control"><option value="">Sin asignar</option>
                        @foreach($trainers as $t)<option value="{{ $t->id }}" {{ old('trainer_id',$class->trainer_id)==$t->id?'selected':'' }}>{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Capacidad</label><input type="number" name="capacity" class="form-control" value="{{ old('capacity',$class->capacity) }}"></div>
                <div class="form-group"><label class="form-label">Duración (min)</label><input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes',$class->duration_minutes) }}"></div>
                <div class="form-group"><label class="form-label">Día</label>
                    <select name="schedule_day" class="form-control"><option value="">Seleccionar...</option>
                        @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $d)
                        <option value="{{ $d }}" {{ old('schedule_day',$class->schedule_day)==$d?'selected':'' }}>{{ ucfirst($d) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Hora</label><input type="time" name="schedule_time" class="form-control" value="{{ old('schedule_time',$class->schedule_time ? substr($class->schedule_time,0,5) : '') }}"></div>
                <div class="form-group"><label class="form-label">Sala</label><input type="text" name="room" class="form-control" value="{{ old('room',$class->room) }}"></div>
                <div class="form-group"><label class="form-label">Color</label><input type="color" name="color" value="{{ old('color',$class->color) }}" style="width:100%;height:44px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;"></div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Descripción</label><textarea name="description" class="form-control" rows="2">{{ old('description',$class->description) }}</textarea></div>
                <div class="form-group d-flex align-center gap-3"><label class="form-label" style="margin-bottom:0;">Activa</label><input type="checkbox" name="status" value="1" {{ $class->status?'checked':'' }} style="width:18px;height:18px;accent-color:var(--primary);"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            <a href="{{ route('classes.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
