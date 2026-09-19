@extends('layouts.app')
@section('title','Nueva Clase')
@section('page-title','Nueva Clase')
@section('breadcrumb')<a href="{{ route('classes.index') }}">Clases</a><span class="sep">/</span><span class="current">Nueva</span>@endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('classes.store') }}">
    @csrf
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-dumbbell"></i> Nueva Clase</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Nombre de la Clase *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Ej: CrossFit Avanzado"></div>
                <div class="form-group"><label class="form-label">Entrenador</label>
                    <select name="trainer_id" class="form-control"><option value="">Sin asignar</option>
                        @foreach($trainers as $t)<option value="{{ $t->id }}" {{ old('trainer_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Capacidad Máx.</label><input type="number" name="capacity" class="form-control" value="{{ old('capacity',20) }}" min="1"></div>
                <div class="form-group"><label class="form-label">Duración (min)</label><input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes',60) }}" min="15"></div>
                <div class="form-group"><label class="form-label">Día</label>
                    <select name="schedule_day" class="form-control"><option value="">Seleccionar...</option>
                        @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $d)
                        <option value="{{ $d }}" {{ old('schedule_day')==$d?'selected':'' }}>{{ ucfirst($d) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Hora</label><input type="time" name="schedule_time" class="form-control" value="{{ old('schedule_time') }}"></div>
                <div class="form-group"><label class="form-label">Sala / Espacio</label><input type="text" name="room" class="form-control" value="{{ old('room') }}" placeholder="Ej: Sala Principal"></div>
                <div class="form-group"><label class="form-label">Color</label><input type="color" name="color" value="{{ old('color','#7c3aed') }}" style="width:100%;height:44px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;"></div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Descripción</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Crear Clase</button>
            <a href="{{ route('classes.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
