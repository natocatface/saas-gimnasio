@extends('layouts.app')
@section('title', 'Nuevo ticket')
@section('page-title', 'Nuevo ticket de soporte')
@section('breadcrumb')<a href="{{ route('support.index') }}">Soporte</a> · <span class="current">Nuevo</span>@endsection

@push('styles')
<style>
    .sform{background:#fff;border:1px solid var(--border);border-radius:16px;padding:26px;max-width:640px}
    .sform .fg{margin-bottom:16px}
    .sform label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
    .sform input,.sform select,.sform textarea{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit}
    .sform textarea{min-height:140px;resize:vertical}
    .sform :focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.12)}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('support.store') }}" class="sform">
    @csrf
    <div class="fg"><label>Asunto *</label><input name="subject" value="{{ old('subject') }}" placeholder="Resume tu problema" required></div>
    <div class="fg"><label>Prioridad *</label>
        <select name="priority" required>
            <option value="low">Baja</option>
            <option value="normal" selected>Normal</option>
            <option value="high">Alta</option>
        </select>
    </div>
    <div class="fg"><label>Describe tu problema *</label><textarea name="body" placeholder="Cuéntanos con detalle qué sucede..." required>{{ old('body') }}</textarea></div>
    <div style="display:flex;gap:10px">
        <button class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar ticket</button>
        <a href="{{ route('support.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>
@endsection
