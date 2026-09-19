@extends('layouts.app')
@section('title', $class->name)
@section('page-title', 'Detalle de Clase')
@section('breadcrumb')
<a href="{{ route('classes.index') }}">Clases</a>
<span class="sep">/</span>
<span class="current">{{ $class->name }}</span>
@endsection

@section('content')
<div class="grid-2" style="align-items:start;">
    <div class="card">
        <div style="background:{{ $class->color??'#7c3aed' }}20;border-left:5px solid {{ $class->color??'#7c3aed' }};padding:28px 24px;">
            <h2 style="font-size:22px;font-weight:700;color:var(--text-primary);margin-bottom:6px;">{{ $class->name }}</h2>
            <p class="text-sm text-muted">{{ $class->description ?? 'Sin descripción' }}</p>
            <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap;">
                <span class="badge {{ $class->status?'badge-success':'badge-gray' }}">{{ $class->status?'Activa':'Inactiva' }}</span>
                <span class="badge badge-purple">{{ $class->duration_minutes }} min</span>
                <span class="badge badge-info">Cap. {{ $class->capacity }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="grid-2" style="gap:16px;">
                <div style="background:var(--body-bg);border-radius:12px;padding:16px;">
                    <div class="text-xs text-muted fw-600 mb-2">ENTRENADOR</div>
                    <div class="fw-700">{{ optional($class->trainer)->name ?? 'No asignado' }}</div>
                    <div class="text-xs text-muted">{{ optional($class->trainer)->speciality ?? '' }}</div>
                </div>
                <div style="background:var(--body-bg);border-radius:12px;padding:16px;">
                    <div class="text-xs text-muted fw-600 mb-2">SALA</div>
                    <div class="fw-700">{{ $class->room ?? '—' }}</div>
                </div>
                <div style="background:var(--body-bg);border-radius:12px;padding:16px;">
                    <div class="text-xs text-muted fw-600 mb-2">HORARIO</div>
                    <div class="fw-700">{{ ucfirst($class->schedule_day ?? '—') }}</div>
                    <div class="text-xs">{{ $class->schedule_time ? substr($class->schedule_time,0,5) : '' }}</div>
                </div>
                <div style="background:var(--body-bg);border-radius:12px;padding:16px;">
                    <div class="text-xs text-muted fw-600 mb-2">INSCRITOS</div>
                    <div class="fw-700">{{ $class->enrollments->count() }} / {{ $class->capacity }}</div>
                    <div class="progress mt-2"><div class="progress-bar" style="width:{{ $class->capacity>0?round($class->enrollments->count()/$class->capacity*100):0 }}%"></div></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="{{ route('classes.edit',$class) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>
            <a href="{{ route('classes.index') }}" class="btn btn-ghost btn-sm">Volver</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div class="card-title"><i class="fas fa-users"></i> Socios Inscritos</div>
            @php $pct = $class->capacity>0 ? min(100, round($enrolledCount/$class->capacity*100)) : 0; @endphp
            <div style="min-width:200px">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px"><span>Cupo</span><b>{{ $enrolledCount }} / {{ $class->capacity }}</b></div>
                <div style="height:8px;background:#eee;border-radius:20px;overflow:hidden"><span style="display:block;height:100%;width:{{ $pct }}%;background:{{ $pct>=100?'#ef4444':'var(--primary)' }}"></span></div>
            </div>
        </div>

        @if($enrolledCount < $class->capacity)
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
            <form method="POST" action="{{ route('classes.enroll',$class) }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                @csrf
                <select name="member_id" class="form-control" style="max-width:300px" required>
                    <option value="">Selecciona un socio para inscribir…</option>
                    @foreach($availableMembers as $m)<option value="{{ $m->id }}">{{ $m->full_name }} ({{ $m->code }})</option>@endforeach
                </select>
                <button class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Inscribir</button>
            </form>
        </div>
        @else
        <div style="padding:12px 20px;border-bottom:1px solid var(--border);color:var(--danger);font-size:13px"><i class="fas fa-circle-exclamation"></i> Clase llena.</div>
        @endif

        <div class="table-container">
            <table>
                <thead><tr><th>Socio</th><th>Inscrito</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @forelse($class->enrollments->where('status','activo') as $e)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div class="member-avatar" style="width:32px;height:32px;font-size:11px;">{{ $e->member?strtoupper(substr($e->member->first_name,0,1).substr($e->member->last_name,0,1)):'' }}</div>
                                <span class="fw-600 text-sm">{{ optional($e->member)->full_name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="text-xs text-muted">{{ \Carbon\Carbon::parse($e->enrolled_at)->format('d/m/Y') }}</td>
                        <td><span class="badge {{ $e->status==='activo'?'badge-success':'badge-gray' }}">{{ ucfirst($e->status) }}</span></td>
                        <td style="text-align:right">
                            <form method="POST" action="{{ route('classes.unenroll',[$class,$e]) }}" onsubmit="return confirm('¿Quitar a este socio?')">@csrf @method('DELETE')
                                <button class="btn btn-ghost btn-sm" style="color:var(--danger)"><i class="fas fa-user-minus"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-secondary);">Sin inscritos</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
