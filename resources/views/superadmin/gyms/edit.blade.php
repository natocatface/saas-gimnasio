@extends('layouts.superadmin')
@section('title','Editar '.$gym->name)
@section('page-title','Editar gimnasio')
@section('breadcrumb')<a href="{{ route('superadmin.gyms.index') }}">Gimnasios</a> · <a href="{{ route('superadmin.gyms.show',$gym) }}">{{ $gym->name }}</a> · Editar@endsection

@section('content')
<div class="card" style="max-width:760px">
    <form method="POST" action="{{ route('superadmin.gyms.update',$gym) }}">
        @csrf @method('PUT')
        <div class="grid" style="grid-template-columns:1fr 1fr">
            <div class="form-group"><label>Nombre *</label><input name="name" value="{{ old('name',$gym->name) }}" class="form-control" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email',$gym->email) }}" class="form-control"></div>
            <div class="form-group"><label>Teléfono</label><input name="phone" value="{{ old('phone',$gym->phone) }}" class="form-control"></div>
            <div class="form-group"><label>Ciudad</label><input name="city" value="{{ old('city',$gym->city) }}" class="form-control"></div>
            <div class="form-group" style="grid-column:1/-1"><label>Dirección</label><input name="address" value="{{ old('address',$gym->address) }}" class="form-control"></div>
            <div class="form-group"><label>Plan SaaS *</label>
                <select name="saas_plan_id" class="form-control" required>
                    @foreach($plans as $p)<option value="{{ $p->id }}" @selected(old('saas_plan_id',$gym->saas_plan_id)==$p->id)>{{ $p->name }} — {{ money($p->price_monthly,0) }}/mes</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Estado *</label>
                <select name="status" class="form-control" required>
                    @foreach(['active'=>'Activo','trial'=>'Prueba','suspended'=>'Suspendido','cancelled'=>'Cancelado'] as $k=>$v)
                        <option value="{{ $k }}" @selected(old('status',$gym->status)===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>Máx. socios *</label><input type="number" name="max_members" value="{{ old('max_members',$gym->max_members) }}" class="form-control" required></div>
            <div class="form-group"><label>Fin de prueba</label><input type="date" name="trial_ends_at" value="{{ old('trial_ends_at', $gym->trial_ends_at ? \Carbon\Carbon::parse($gym->trial_ends_at)->format('Y-m-d') : '') }}" class="form-control"></div>
        </div>
        <div style="display:flex;gap:10px;margin-top:10px">
            <button class="btn btn-primary"><i class="fas fa-check"></i> Guardar cambios</button>
            <a href="{{ route('superadmin.gyms.show',$gym) }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection
