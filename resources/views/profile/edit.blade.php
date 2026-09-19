@extends('layouts.app')
@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')
@section('breadcrumb')<span class="current">Perfil</span>@endsection

@push('styles')
<style>
    .pcard{background:#fff;border:1px solid var(--border);border-radius:16px;padding:26px;max-width:640px}
    .pcard h3{font-size:15px;font-weight:700;margin:0 0 16px;display:flex;align-items:center;gap:8px}
    .pcard h3 i{color:var(--primary)}
    .fg{margin-bottom:16px}
    .fg label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
    .fg input{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit}
    .fg input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.12)}
    .r2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .hr{border:none;border-top:1px solid var(--border);margin:22px 0}
</style>
@endpush

@section('content')
<div class="pcard">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
        <div style="width:60px;height:60px;border-radius:16px;background:linear-gradient(135deg,var(--primary),#6d28d9);color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800">{{ strtoupper(substr($user->name,0,2)) }}</div>
        <div><div style="font-size:18px;font-weight:700">{{ $user->name }}</div><div style="color:var(--text-secondary);font-size:13px">{{ ucfirst($user->role) }}</div></div>
    </div>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <h3><i class="fas fa-id-card"></i> Datos personales</h3>
        <div class="r2">
            <div class="fg"><label>Nombre</label><input name="name" value="{{ old('name',$user->name) }}" required></div>
            <div class="fg"><label>Teléfono</label><input name="phone" value="{{ old('phone',$user->phone) }}"></div>
        </div>
        <div class="fg"><label>Correo electrónico</label><input type="email" name="email" value="{{ old('email',$user->email) }}" required></div>

        <hr class="hr">

        <h3><i class="fas fa-lock"></i> Cambiar contraseña</h3>
        <div class="fg"><label>Contraseña actual</label><input type="password" name="current_password" autocomplete="current-password" placeholder="Solo si vas a cambiarla"></div>
        <div class="r2">
            <div class="fg"><label>Nueva contraseña</label><input type="password" name="password" autocomplete="new-password" placeholder="Mínimo 6 caracteres"></div>
            <div class="fg"><label>Confirmar nueva</label><input type="password" name="password_confirmation" autocomplete="new-password"></div>
        </div>

        <div style="display:flex;gap:10px;margin-top:6px">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
