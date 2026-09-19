@extends('layouts.superadmin')
@section('title','Planes SaaS')
@section('page-title','Planes SaaS')
@section('breadcrumb','Lo que vendes a los gimnasios')

@section('actions')
    <a href="{{ route('superadmin.plans.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo plan</a>
@endsection

@section('content')
<div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr))">
    @forelse($plans as $p)
        <div class="card lift" style="border-top:4px solid {{ $p->color }}">
            <div style="display:flex;justify-content:space-between;align-items:start">
                <div>
                    <div style="font-size:18px;font-weight:700">{{ $p->name }}
                        @if($p->is_popular)<span class="badge" style="background:{{ $p->color }}22;color:{{ $p->color }};margin-left:6px">Popular</span>@endif
                    </div>
                    <div style="font-size:13px;color:var(--muted);margin-top:3px">{{ $p->description }}</div>
                </div>
                <span class="badge {{ $p->is_active?'active':'cancelled' }}">{{ $p->is_active?'Activo':'Inactivo' }}</span>
            </div>
            <div style="margin:16px 0;font-size:30px;font-weight:800">{{ money($p->price_monthly,0) }}<span style="font-size:14px;font-weight:500;color:var(--muted)">/mes</span></div>
            <div style="font-size:13px;color:var(--muted);line-height:2">
                <div><i class="fas fa-users" style="width:22px;color:{{ $p->color }}"></i> {{ $p->max_members>=999999?'∞':$p->max_members }} socios</div>
                <div><i class="fas fa-user-tie" style="width:22px;color:{{ $p->color }}"></i> {{ $p->max_trainers>=999999?'∞':$p->max_trainers }} entrenadores</div>
                <div><i class="fas fa-dumbbell" style="width:22px;color:{{ $p->color }}"></i> {{ $p->max_classes>=999999?'∞':$p->max_classes }} clases</div>
                <div><i class="fas fa-building" style="width:22px;color:{{ $p->color }}"></i> {{ $p->gymnasiums_count }} gimnasios usando este plan</div>
            </div>
            <div style="display:flex;gap:8px;margin-top:18px;border-top:1px solid var(--border);padding-top:16px">
                <a href="{{ route('superadmin.plans.edit',$p) }}" class="btn btn-light btn-sm"><i class="fas fa-pen"></i> Editar</a>
                <form method="POST" action="{{ route('superadmin.plans.destroy',$p) }}" onsubmit="return confirm('¿Eliminar este plan?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
            </div>
        </div>
    @empty
        <div class="empty"><i class="fas fa-layer-group"></i><div>No hay planes SaaS aún</div></div>
    @endforelse
</div>
@endsection
