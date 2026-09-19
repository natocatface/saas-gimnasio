@extends('layouts.app')

@section('title', 'Editar Socio')
@section('page-title', 'Editar Socio')
@section('breadcrumb')<a href="{{ route('members.index') }}">Socios</a><span class="sep">/</span><span class="current">Editar</span>@endsection

@section('content')
<div style="max-width:860px;">
<form method="POST" action="{{ route('members.update', $member) }}">
    @csrf @method('PUT')

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user"></i> Información Personal</div>
            <span class="badge badge-purple">{{ $member->code }}</span>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name',$member->first_name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Apellido *</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name',$member->last_name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email',$member->email) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone',$member->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nacimiento</label>
                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Género</label>
                    <select name="gender" class="form-control">
                        <option value="">Seleccionar...</option>
                        <option value="M" {{ old('gender',$member->gender)=='M'?'selected':'' }}>Masculino</option>
                        <option value="F" {{ old('gender',$member->gender)=='F'?'selected':'' }}>Femenino</option>
                        <option value="otro" {{ old('gender',$member->gender)=='otro'?'selected':'' }}>Otro</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address',$member->address) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Contacto de Emergencia</label>
                    <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact',$member->emergency_contact) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tel. Emergencia</label>
                    <input type="text" name="emergency_phone" class="form-control" value="{{ old('emergency_phone',$member->emergency_phone) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-id-card"></i> Membresía</div>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Plan</label>
                    <select name="plan_id" class="form-control">
                        <option value="">Sin plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('plan_id',$member->plan_id)==$plan->id?'selected':'' }}>
                                {{ $plan->name }} — {{ money($plan->price,2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="activo"     {{ old('status',$member->status)=='activo'     ?'selected':'' }}>Activo</option>
                        <option value="inactivo"   {{ old('status',$member->status)=='inactivo'   ?'selected':'' }}>Inactivo</option>
                        <option value="suspendido" {{ old('status',$member->status)=='suspendido' ?'selected':'' }}>Suspendido</option>
                        <option value="vencido"    {{ old('status',$member->status)=='vencido'    ?'selected':'' }}>Vencido</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Inicio Membresía</label>
                    <input type="date" name="membership_start" class="form-control" value="{{ old('membership_start', optional($member->membership_start)->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Vence</label>
                    <input type="date" name="membership_end" class="form-control" value="{{ old('membership_end', optional($member->membership_end)->format('Y-m-d')) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Notas</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes',$member->notes) }}</textarea>
                </div>

                <div class="form-group" style="grid-column:1/-1;background:#faf8ff;border:1px solid var(--border);border-radius:12px;padding:14px;">
                    <label class="form-label"><i class="fas fa-mobile-screen" style="color:var(--primary)"></i> Acceso al Portal del Socio</label>
                    <input type="password" name="password" class="form-control" placeholder="{{ $member->password ? 'Dejar vacío para no cambiar' : 'Definir contraseña para dar acceso' }}" autocomplete="new-password">
                    <small style="color:var(--text-secondary)">El socio ingresa en <strong>/socio/login</strong> con su correo ({{ $member->email ?? 'sin correo' }}) y esta contraseña.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
        <a href="{{ route('members.show', $member) }}" class="btn btn-ghost">Cancelar</a>
    </div>
</form>
</div>
@endsection
