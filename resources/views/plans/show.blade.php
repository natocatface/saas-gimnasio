@extends('layouts.app')
@section('title', $plan->name)
@section('page-title', 'Detalle del Plan')
@section('breadcrumb')<a href="{{ route('plans.index') }}">Planes</a> <span class="sep">/</span> <span class="current">{{ $plan->name }}</span>@endsection

@section('content')
<div style="max-width:820px">
    <div class="card" style="border-top:4px solid {{ $plan->color ?? '#7c3aed' }}">
        <div class="card-body" style="padding:28px">

            <div class="d-flex justify-between align-center" style="flex-wrap:wrap;gap:14px;margin-bottom:8px">
                <div>
                    @if($plan->is_featured)
                        <span style="background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;padding:4px 12px;border-radius:20px;display:inline-block;margin-bottom:10px">⭐ Destacado</span>
                    @endif
                    <h2 style="font-size:24px;font-weight:800;margin:0">{{ $plan->name }}</h2>
                    <p class="text-muted" style="margin-top:4px">{{ $plan->description ?: 'Sin descripción' }}</p>
                </div>
                <span class="badge {{ $plan->status ? 'badge-success' : 'badge-gray' }}">{{ $plan->status ? 'Activo' : 'Inactivo' }}</span>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:22px 0">
                <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:12px;padding:16px">
                    <div class="text-xs text-muted" style="text-transform:uppercase;letter-spacing:.5px;font-weight:700">Precio</div>
                    <div style="font-size:26px;font-weight:800;color:{{ $plan->color ?? 'var(--primary)' }}">{{ money($plan->price,2) }}</div>
                </div>
                <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:12px;padding:16px">
                    <div class="text-xs text-muted" style="text-transform:uppercase;letter-spacing:.5px;font-weight:700">Duración</div>
                    <div style="font-size:26px;font-weight:800">{{ $plan->duration_days }} <span style="font-size:14px;font-weight:500;color:var(--text-secondary)">días</span></div>
                </div>
                <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:12px;padding:16px">
                    <div class="text-xs text-muted" style="text-transform:uppercase;letter-spacing:.5px;font-weight:700">Socios</div>
                    <div style="font-size:26px;font-weight:800">{{ $plan->members_count ?? 0 }}</div>
                </div>
            </div>

            @if($plan->features_array)
            <div style="margin:20px 0">
                <div class="text-xs text-muted" style="text-transform:uppercase;letter-spacing:.5px;font-weight:700;margin-bottom:10px">Características</div>
                <ul style="list-style:none;margin:0;display:flex;flex-direction:column;gap:9px">
                    @foreach($plan->features_array as $feat)
                    <li style="font-size:14px;display:flex;align-items:center;gap:10px">
                        <i class="fas fa-check" style="color:var(--success);font-size:12px"></i> {{ $feat }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="d-flex" style="gap:10px;padding-top:18px;border-top:1px solid var(--border)">
                <a href="{{ route('plans.edit', $plan) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar plan</a>
                <a href="{{ route('plans.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>

        </div>
    </div>
</div>
@endsection
