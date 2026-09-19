@extends('layouts.app')
@section('title','Planes')
@section('page-title','Planes de Membresía')
@section('breadcrumb')<span class="current">Planes</span>@endsection

@section('content')

@include('partials.module_hero', ['title' => 'Planes de Membresía', 'subtitle' => 'Tarifas y planes que ofreces a tus socios.', 'icon' => 'fa-clipboard-list'])
<div class="d-flex justify-between align-center mb-6">
    <div class="text-muted text-sm">{{ $plans->count() }} planes configurados</div>
    <a href="{{ route('plans.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Plan</a>
</div>

<div class="grid-4">
    @forelse($plans as $plan)
    <div class="card" style="border-top:4px solid {{ $plan->color ?? '#7c3aed' }};">
        <div class="card-body" style="padding:24px;">
            @if($plan->is_featured)
                <div style="background:linear-gradient(135deg,#7c3aed,#ec4899);color:white;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;padding:4px 12px;border-radius:20px;display:inline-block;margin-bottom:12px;">⭐ Destacado</div>
            @endif
            <h3 style="font-size:18px;font-weight:700;margin-bottom:4px;">{{ $plan->name }}</h3>
            <p class="text-sm text-muted" style="margin-bottom:16px;">{{ $plan->description }}</p>
            <div style="font-size:32px;font-weight:800;color:{{ $plan->color ?? 'var(--primary)' }};margin-bottom:4px;">{{ money($plan->price,2) }}</div>
            <div class="text-xs text-muted">por {{ $plan->duration_days }} días</div>

            @if($plan->features_array)
            <ul style="list-style:none;margin:16px 0;display:flex;flex-direction:column;gap:8px;">
                @foreach($plan->features_array as $feat)
                <li style="font-size:13px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-check" style="color:var(--success);font-size:11px;"></i> {{ $feat }}
                </li>
                @endforeach
            </ul>
            @endif

            <div class="d-flex align-center justify-between mt-4" style="padding-top:14px;border-top:1px solid var(--border);">
                <span class="badge badge-purple">{{ $plan->members_count }} socios</span>
                <span class="badge {{ $plan->status ? 'badge-success' : 'badge-gray' }}">{{ $plan->status ? 'Activo' : 'Inactivo' }}</span>
            </div>
        </div>
        <div style="border-top:1px solid var(--border);padding:12px 20px;display:flex;gap:8px;">
            <a href="{{ route('plans.edit', $plan) }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="fas fa-edit"></i> Editar</a>
            <form method="POST" action="{{ route('plans.destroy', $plan) }}" onsubmit="return confirm('¿Eliminar este plan?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-secondary);">
        <i class="fas fa-clipboard-list" style="font-size:48px;display:block;margin-bottom:16px;opacity:.3;"></i>
        <a href="{{ route('plans.create') }}" class="btn btn-primary mt-4">Crear Primer Plan</a>
    </div>
    @endforelse
</div>
@endsection
