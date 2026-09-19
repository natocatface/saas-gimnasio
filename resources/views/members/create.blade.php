@extends('layouts.app')

@section('title', 'Nuevo Socio')
@section('page-title', 'Nuevo Socio')
@section('breadcrumb')<a href="{{ route('members.index') }}">Socios</a><span class="sep">/</span><span class="current">Nuevo</span>@endsection

@section('content')
<div style="max-width:860px;width:100%;">
<form method="POST" action="{{ route('members.store') }}">
    @csrf

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            @foreach($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Personal Info -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user"></i> Información Personal</div>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nombre <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Apellido <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Género</label>
                    <select name="gender" class="form-control">
                        <option value="">Seleccionar...</option>
                        <option value="M" {{ old('gender')=='M'?'selected':'' }}>Masculino</option>
                        <option value="F" {{ old('gender')=='F'?'selected':'' }}>Femenino</option>
                        <option value="otro" {{ old('gender')=='otro'?'selected':'' }}>Otro</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-phone-alt"></i> Contacto de Emergencia</div>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nombre del Contacto</label>
                    <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono de Emergencia</label>
                    <input type="text" name="emergency_phone" class="form-control" value="{{ old('emergency_phone') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Membership -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-id-card"></i> Membresía</div>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Plan de Membresía</label>
                    <select name="plan_id" class="form-control" id="planSelect" onchange="updateEndDate()">
                        <option value="">Sin plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" data-days="{{ $plan->duration_days }}" {{ old('plan_id')==$plan->id?'selected':'' }}>
                                {{ $plan->name }} — {{ money($plan->price,2) }} / {{ $plan->duration_days }}d
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="activo" {{ old('status','activo')=='activo'?'selected':'' }}>Activo</option>
                        <option value="inactivo" {{ old('status')=='inactivo'?'selected':'' }}>Inactivo</option>
                        <option value="suspendido" {{ old('status')=='suspendido'?'selected':'' }}>Suspendido</option>
                        <option value="vencido" {{ old('status')=='vencido'?'selected':'' }}>Vencido</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Fecha de Inicio</label>
                    <input type="date" name="membership_start" class="form-control" id="startDate"
                        value="{{ old('membership_start', date('Y-m-d')) }}" onchange="updateEndDate()">
                </div>
                <div class="form-group">
                    <label class="form-label">Fecha de Vencimiento</label>
                    <input type="date" name="membership_end" class="form-control" id="endDate"
                        value="{{ old('membership_end') }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Notas</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Socio</button>
        <a href="{{ route('members.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Cancelar</a>
    </div>
</form>
</div>

@push('scripts')
<script>
function updateEndDate() {
    const plan   = document.getElementById('planSelect');
    const start  = document.getElementById('startDate');
    const end    = document.getElementById('endDate');
    const days   = plan.selectedOptions[0]?.dataset?.days;
    if (!days || !start.value) return;
    const d = new Date(start.value);
    d.setDate(d.getDate() + parseInt(days));
    end.value = d.toISOString().split('T')[0];
}
updateEndDate();
</script>
@endpush

@endsection
