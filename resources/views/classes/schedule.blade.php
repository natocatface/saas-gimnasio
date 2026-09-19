@extends('layouts.app')
@section('title', 'Horario')
@section('page-title', 'Horario Semanal')
@section('breadcrumb')<a href="{{ route('classes.index') }}">Clases</a> · <span class="current">Horario</span>@endsection

@push('styles')
<style>
    .sched{display:grid;grid-template-columns:repeat(7,1fr);gap:12px}
    .col{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;min-height:120px}
    .col h4{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;text-align:center;padding:11px;color:#fff;background:linear-gradient(135deg,var(--primary),#6d28d9)}
    .col .body{padding:10px;display:flex;flex-direction:column;gap:8px}
    .cl{border-radius:10px;padding:10px;color:#fff;font-size:12px}
    .cl .t{font-weight:700;font-size:13px}
    .cl .meta{opacity:.9;margin-top:3px;display:flex;align-items:center;gap:6px}
    .cl a{color:#fff;text-decoration:none}
    .empty-day{color:var(--text-secondary);font-size:12px;text-align:center;padding:18px 4px}
    @media(max-width:1100px){.sched{grid-template-columns:repeat(3,1fr)}}
    @media(max-width:680px){.sched{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="sched">
    @foreach($days as $d)
        <div class="col">
            <h4>{{ $d }}</h4>
            <div class="body">
                @forelse($grid[$d] as $c)
                    <div class="cl" style="background:linear-gradient(135deg,{{ $c->color ?? '#7c3aed' }},#6d28d9)">
                        <a href="{{ route('classes.show',$c) }}">
                            <div class="t">{{ $c->name }}</div>
                            <div class="meta"><i class="fas fa-clock"></i> {{ $c->schedule_time ? \Carbon\Carbon::parse($c->schedule_time)->format('H:i') : '—' }} · {{ $c->duration_minutes }}m</div>
                            <div class="meta"><i class="fas fa-user-tie"></i> {{ $c->trainer->name ?? 'Sin entrenador' }}</div>
                            <div class="meta"><i class="fas fa-users"></i> {{ $c->enrollments_count }}/{{ $c->capacity }}</div>
                        </a>
                    </div>
                @empty
                    <div class="empty-day">—</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
