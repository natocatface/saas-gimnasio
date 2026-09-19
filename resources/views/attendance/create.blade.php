@extends('layouts.app')

@section('title', 'Registrar Asistencia')
@section('page-title', 'Registrar Check-in')
@section('breadcrumb')<a href="{{ route('attendance.index') }}">Asistencia</a><span class="sep">/</span><span class="current">Check-in</span>@endsection

@section('content')
<div style="max-width:580px;">
<form method="POST" action="{{ route('attendance.store') }}">
    @csrf

    @if($errors->any())
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-fingerprint"></i> Registro de Entrada</div>
            <span class="badge badge-success">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Socio *</label>
                <select name="member_id" class="form-control" required>
                    <option value="">Seleccionar socio activo...</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('member_id')==$m->id?'selected':'' }}>
                            {{ $m->full_name }} ({{ $m->code }}) — {{ optional($m->plan)->name ?? 'Sin plan' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Clase (opcional)</label>
                <select name="class_id" class="form-control">
                    <option value="">Acceso libre / Sin clase</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('class_id')==$c->id?'selected':'' }}>
                            {{ $c->name }} — {{ ucfirst($c->schedule_day ?? '') }} {{ $c->schedule_time ? substr($c->schedule_time,0,5) : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Fecha y Hora de Entrada *</label>
                <input type="datetime-local" name="check_in" class="form-control"
                    value="{{ old('check_in', now()->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Notas</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Observaciones...">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Registrar Entrada</button>
            <a href="{{ route('attendance.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
