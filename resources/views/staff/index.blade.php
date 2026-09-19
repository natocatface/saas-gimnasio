@extends('layouts.app')
@section('title', 'Equipo')
@section('page-title', 'Equipo del Gimnasio')
@section('breadcrumb')<span class="current">Equipo</span>@endsection

@push('styles')
<style>
    .staff-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}
    .scard{background:#fff;border:1px solid var(--border);border-radius:15px;padding:20px;display:flex;flex-direction:column;gap:12px}
    .scard .top{display:flex;align-items:center;gap:13px}
    .savatar{width:48px;height:48px;border-radius:13px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px}
    .rol{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .rol.admin{background:#ede9fe;color:#6d28d9}.rol.trainer{background:#dbeafe;color:#1d4ed8}.rol.receptionist{background:#fce7f3;color:#be185d}
    .scard .acts{display:flex;gap:8px;margin-top:auto;border-top:1px solid var(--border);padding-top:12px}
</style>
@endpush

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <p style="color:var(--text-secondary);font-size:14px;margin:0">Administra quién tiene acceso al sistema de tu gimnasio.</p>
    <a href="{{ route('staff.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Agregar miembro</a>
</div>

<div class="staff-grid">
    @foreach($staff as $u)
        @php $colors=['admin'=>'#7c3aed','trainer'=>'#3b82f6','receptionist'=>'#ec4899']; $c=$colors[$u->role]??'#6b7280'; @endphp
        <div class="scard">
            <div class="top">
                <div class="savatar" style="background:{{ $c }}">{{ strtoupper(substr($u->name,0,2)) }}</div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:700">{{ $u->name }}</div>
                    <div style="font-size:13px;color:var(--muted);overflow:hidden;text-overflow:ellipsis">{{ $u->email }}</div>
                </div>
            </div>
            <div>
                <span class="rol {{ $u->role }}">{{ $roles[$u->role] ?? ucfirst($u->role) }}</span>
                @if($u->id === optional($u->gymnasium)->owner_id)<span class="rol" style="background:#fef9c3;color:#a16207">Dueño</span>@endif
                @if($u->phone)<span style="font-size:12px;color:var(--muted);margin-left:6px"><i class="fas fa-phone"></i> {{ $u->phone }}</span>@endif
            </div>
            <div class="acts">
                <a href="{{ route('staff.edit',$u) }}" class="btn btn-outline" style="flex:1;justify-content:center"><i class="fas fa-pen"></i> Editar</a>
                @if($u->id !== auth()->id() && $u->id !== optional($u->gymnasium)->owner_id)
                <form method="POST" action="{{ route('staff.destroy',$u) }}" onsubmit="return confirm('¿Eliminar a {{ $u->name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline" style="color:var(--danger);border-color:#fecaca"><i class="fas fa-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
