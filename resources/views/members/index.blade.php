@extends('layouts.app')

@section('title', 'Socios')
@section('page-title', 'Gestión de Socios')
@section('breadcrumb')<span class="current">Socios</span>@endsection

@push('styles')
<style>
/* Cards de socios para móvil */
.member-card-mobile {
    display: none;
    background: white;
    border-radius: 14px;
    border: 1px solid var(--border);
    padding: 16px;
    margin-bottom: 12px;
}
@media (max-width: 700px) {
    .member-table-wrap { display: none; }
    .member-card-mobile { display: block; }
    .filter-row { flex-direction: column; gap: 10px; }
    .filter-row select, .filter-row input { width: 100% !important; }
    .stats-mini { flex-direction: column; gap: 8px; }
    .stats-mini .stat-card { min-width: unset; }
}
</style>
@endpush

@section('content')

@include('partials.module_hero', ['title' => 'Socios', 'subtitle' => 'Gestión de socios y membresías del gimnasio.', 'icon' => 'fa-users'])

<!-- Header Actions -->
<div class="d-flex justify-between align-center mb-6" style="flex-wrap:wrap;gap:12px;">
    <div class="d-flex gap-3 stats-mini" style="flex-wrap:wrap;">
        <div class="stat-card" style="padding:14px 20px; gap:10px; min-width:0;">
            <div class="stat-icon purple" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-users"></i></div>
            <div><div class="stat-value" style="font-size:22px;">{{ $counts['activo'] }}</div><div class="stat-label">Activos</div></div>
        </div>
        <div class="stat-card" style="padding:14px 20px; gap:10px; min-width:0;">
            <div class="stat-icon red" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-clock"></i></div>
            <div><div class="stat-value" style="font-size:22px;">{{ $counts['vencido'] }}</div><div class="stat-label">Vencidos</div></div>
        </div>
        <div class="stat-card" style="padding:14px 20px; gap:10px; min-width:0;">
            <div class="stat-icon yellow" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-user-slash"></i></div>
            <div><div class="stat-value" style="font-size:22px;">{{ $counts['inactivo'] }}</div><div class="stat-label">Inactivos</div></div>
        </div>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('export.members') }}" class="btn btn-outline"><i class="fas fa-file-csv"></i> Exportar</a>
        <a href="{{ route('members.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Socio
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body" style="padding:16px 24px;">
        <form method="GET" class="d-flex gap-3 align-center flex-wrap filter-row">
            <div class="topbar-search" style="flex:1; min-width:250px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Buscar por nombre, código, email..." value="{{ request('search') }}" style="width:100%; padding:10px 16px 10px 40px; border:1.5px solid var(--border); border-radius:10px; font-family:Inter,sans-serif; font-size:14px; outline:none;">
            </div>
            <select name="status" class="form-control" style="width:160px;">
                <option value="">Todos los estados</option>
                <option value="activo"    {{ request('status')=='activo'    ? 'selected':'' }}>Activo</option>
                <option value="vencido"   {{ request('status')=='vencido'   ? 'selected':'' }}>Vencido</option>
                <option value="inactivo"  {{ request('status')=='inactivo'  ? 'selected':'' }}>Inactivo</option>
                <option value="suspendido"{{ request('status')=='suspendido'? 'selected':'' }}>Suspendido</option>
            </select>
            <select name="plan_id" class="form-control" style="width:160px;">
                <option value="">Todos los planes</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan_id')==$plan->id ? 'selected':'' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filtrar</button>
            <a href="{{ route('members.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Limpiar</a>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-list"></i> Lista de Socios</div>
        <span class="text-sm text-muted">{{ $members->total() }} registros</span>
    </div>

    {{-- CARDS móvil --}}
    @foreach($members as $member)
    <div class="member-card-mobile">
        <div class="d-flex align-center gap-3 mb-2">
            <div class="member-avatar">{{ $member->initials }}</div>
            <div style="flex:1;">
                <div class="fw-600">{{ $member->full_name }}</div>
                <div class="text-xs text-muted">{{ $member->code }} · {{ $member->phone ?? '—' }}</div>
            </div>
            @php $sm = ['activo'=>'badge-success','vencido'=>'badge-danger','inactivo'=>'badge-gray','suspendido'=>'badge-warning']; @endphp
            <span class="badge {{ $sm[$member->status]??'badge-gray' }}">{{ ucfirst($member->status) }}</span>
        </div>
        <div class="d-flex justify-between text-sm mb-2">
            <span class="text-muted">Plan</span>
            <span class="badge badge-purple">{{ optional($member->plan)->name ?? 'Sin plan' }}</span>
        </div>
        <div class="d-flex justify-between text-sm mb-3">
            <span class="text-muted">Vence</span>
            <span class="fw-600">{{ $member->membership_end ? $member->membership_end->format('d/m/Y') : '—' }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('members.show',$member) }}" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;"><i class="fas fa-eye"></i> Ver</a>
            <a href="{{ route('members.edit',$member) }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="fas fa-edit"></i> Editar</a>
            <a href="{{ route('payments.create') }}?member_id={{ $member->id }}" class="btn btn-sm" style="background:var(--success);color:white;flex:1;justify-content:center;"><i class="fas fa-credit-card"></i></a>
        </div>
    </div>
    @endforeach

    {{-- TABLA desktop/tablet --}}
    <div class="table-container member-table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Socio</th>
                    <th>Contacto</th>
                    <th>Plan</th>
                    <th>Vigencia</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>
                        <div class="d-flex align-center gap-3">
                            <div class="member-avatar">{{ $member->initials }}</div>
                            <div>
                                <div class="fw-600">{{ $member->full_name }}</div>
                                <div class="text-xs text-muted">{{ $member->code }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm">{{ $member->email ?? '—' }}</div>
                        <div class="text-xs text-muted">{{ $member->phone ?? '—' }}</div>
                    </td>
                    <td>
                        @if($member->plan)
                            <span class="badge badge-purple">{{ $member->plan->name }}</span>
                        @else
                            <span class="badge badge-gray">Sin plan</span>
                        @endif
                    </td>
                    <td>
                        @if($member->membership_end)
                            <div class="text-sm fw-600">{{ $member->membership_end->format('d/m/Y') }}</div>
                            @php $daysLeft = now()->diffInDays($member->membership_end, false); @endphp
                            <div class="text-xs {{ $daysLeft < 0 ? 'text-muted' : ($daysLeft <= 7 ? 'fw-600' : 'text-muted') }}"
                                 style="{{ $daysLeft > 0 && $daysLeft <= 7 ? 'color:var(--warning)' : '' }}">
                                {{ $daysLeft < 0 ? 'Vencido hace '.abs($daysLeft).'d' : "Quedan {$daysLeft} días" }}
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php $statusMap = ['activo'=>'badge-success','vencido'=>'badge-danger','inactivo'=>'badge-gray','suspendido'=>'badge-warning']; @endphp
                        <span class="badge {{ $statusMap[$member->status] ?? 'badge-gray' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-center">
                            <a href="{{ route('members.show', $member) }}" class="btn btn-ghost btn-icon btn-sm" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-outline btn-icon btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('payments.create') }}?member_id={{ $member->id }}" class="btn btn-sm" style="background:var(--success);color:white;padding:7px 12px;" title="Registrar pago">
                                <i class="fas fa-credit-card"></i>
                            </a>
                            <form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('¿Eliminar este socio?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:60px; color:var(--text-secondary);">
                        <i class="fas fa-users" style="font-size:40px; display:block; margin-bottom:14px; opacity:0.3;"></i>
                        No se encontraron socios
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>{{-- fin .member-table-wrap --}}

    @if($members->hasPages())
    <div class="card-body" style="padding-top:8px;">
        {{ $members->links() }}
    </div>
    @endif
</div>
@endsection
