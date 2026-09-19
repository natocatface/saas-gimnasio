@extends('layouts.app')

@section('title', 'Entrenadores')
@section('page-title', 'Gestión de Entrenadores')
@section('breadcrumb')<span class="current">Entrenadores</span>@endsection

@section('content')

@include('partials.module_hero', ['title' => 'Entrenadores', 'subtitle' => 'Equipo de entrenadores del gimnasio.', 'icon' => 'fa-user-tie'])

<div class="d-flex justify-between align-center mb-6">
    <div class="text-muted text-sm">{{ $trainers->total() }} entrenadores registrados</div>
    <a href="{{ route('trainers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Entrenador</a>
</div>

<div class="grid-3">
    @forelse($trainers as $trainer)
    <div class="card" style="overflow:visible;">
        <div class="card-body" style="text-align:center; padding:28px 24px 20px;">
            <div class="member-avatar" style="width:64px;height:64px;font-size:22px;margin:0 auto 14px;border-radius:16px;">
                {{ strtoupper(substr($trainer->name,0,2)) }}
            </div>
            <h3 style="font-size:16px;font-weight:700;margin-bottom:4px;">{{ $trainer->name }}</h3>
            <p class="text-sm text-muted">{{ $trainer->speciality ?? 'Entrenador General' }}</p>
            <div style="margin:14px 0;">
                <span class="badge {{ $trainer->status ? 'badge-success' : 'badge-gray' }}">
                    {{ $trainer->status ? 'Activo' : 'Inactivo' }}
                </span>
                <span class="badge badge-blue" style="background:#dbeafe;color:#1e40af;margin-left:6px;">
                    {{ $trainer->classes_count }} clases
                </span>
            </div>
            @if($trainer->email)
            <div class="text-xs text-muted mb-2"><i class="fas fa-envelope" style="margin-right:6px;"></i>{{ $trainer->email }}</div>
            @endif
            @if($trainer->phone)
            <div class="text-xs text-muted"><i class="fas fa-phone" style="margin-right:6px;"></i>{{ $trainer->phone }}</div>
            @endif
        </div>
        <div style="border-top:1px solid var(--border);padding:14px 20px;display:flex;gap:8px;justify-content:center;">
            <a href="{{ route('trainers.show', $trainer) }}" class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i> Ver</a>
            <a href="{{ route('trainers.edit', $trainer) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> Editar</a>
            <form method="POST" action="{{ route('trainers.destroy', $trainer) }}" onsubmit="return confirm('¿Eliminar entrenador?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1; text-align:center; padding:60px; color:var(--text-secondary);">
        <i class="fas fa-user-tie" style="font-size:48px;display:block;margin-bottom:16px;opacity:.3;"></i>
        <p>No hay entrenadores registrados</p>
        <a href="{{ route('trainers.create') }}" class="btn btn-primary mt-4">Agregar Entrenador</a>
    </div>
    @endforelse
</div>
@endsection
