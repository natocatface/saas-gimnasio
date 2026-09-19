@extends('layouts.superadmin')
@section('title','Nuevo gimnasio')
@section('page-title','Nuevo gimnasio')
@section('breadcrumb')<a href="{{ route('superadmin.gyms.index') }}">Gimnasios</a> · Crear@endsection

@section('content')
<div class="card" style="max-width:760px">
    <form method="POST" action="{{ route('superadmin.gyms.store') }}">
        @csrf
        <h3 style="margin-bottom:18px">Datos del gimnasio</h3>
        <div class="grid" style="grid-template-columns:1fr 1fr">
            <div class="form-group"><label>Nombre del gimnasio *</label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
            <div class="form-group"><label>Teléfono</label><input name="phone" value="{{ old('phone') }}" class="form-control"></div>
        </div>

        <h3 style="margin:20px 0 18px">Cuenta del dueño (admin)</h3>
        <div class="grid" style="grid-template-columns:1fr 1fr">
            <div class="form-group"><label>Nombre del dueño *</label><input name="owner_name" value="{{ old('owner_name') }}" class="form-control" required></div>
            <div class="form-group"><label>Email *</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
            <div class="form-group"><label>Contraseña *</label><input type="password" name="password" class="form-control" required></div>
        </div>

        <h3 style="margin:20px 0 18px">Plan y estado</h3>
        <div class="grid" style="grid-template-columns:1fr 1fr">
            <div class="form-group"><label>Plan SaaS *</label>
                <select name="saas_plan_id" class="form-control" required>
                    @foreach($plans as $p)<option value="{{ $p->id }}" @selected(old('saas_plan_id')==$p->id)>{{ $p->name }} — {{ money($p->price_monthly,0) }}/mes</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Estado inicial *</label>
                <select name="status" class="form-control" required>
                    <option value="trial">Prueba (14 días)</option>
                    <option value="active">Activo</option>
                    <option value="suspended">Suspendido</option>
                </select>
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:10px">
            <button class="btn btn-primary"><i class="fas fa-check"></i> Crear gimnasio</button>
            <a href="{{ route('superadmin.gyms.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection
