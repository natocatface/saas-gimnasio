@extends('layouts.app')
@section('title', 'Soporte')
@section('page-title', 'Soporte')
@section('breadcrumb')<span class="current">Soporte</span>@endsection

@push('styles')
<style>
    .tk{display:flex;align-items:center;gap:14px;background:#fff;border:1px solid var(--border);border-radius:14px;padding:16px 18px;margin-bottom:10px;text-decoration:none;color:inherit;transition:.15s}
    .tk:hover{box-shadow:0 6px 18px rgba(0,0,0,.06);transform:translateY(-1px)}
    .tk .ic{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:16px;color:#fff;flex-shrink:0}
    .tb{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .tb.open{background:#dcfce7;color:#15803d}.tb.pending{background:#fef9c3;color:#a16207}.tb.closed{background:#f3f4f6;color:#6b7280}
    .pr{font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px}
    .pr.high{background:#fee2e2;color:#b91c1c}.pr.normal{background:#eef2ff;color:#4f46e5}.pr.low{background:#f3f4f6;color:#6b7280}
</style>
@endpush

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px">
    <p style="color:var(--text-secondary);font-size:14px;margin:0">¿Necesitas ayuda? Abre un ticket y nuestro equipo te responderá.</p>
    <a href="{{ route('support.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo ticket</a>
</div>

@forelse($tickets as $t)
    <a href="{{ route('support.show',$t) }}" class="tk">
        <div class="ic" style="background:{{ $t->status==='closed' ? '#9ca3af' : '#7c3aed' }}"><i class="fas fa-headset"></i></div>
        <div style="flex:1;min-width:0">
            <div style="font-weight:700">{{ $t->subject }}</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:3px">#{{ $t->id }} · {{ $t->messages_count }} mensaje(s) · {{ $t->last_reply_at?->diffForHumans() }}</div>
        </div>
        <span class="pr {{ $t->priority }}">{{ $t->priorityLabel() }}</span>
        <span class="tb {{ $t->status }}">{{ $t->statusLabel() }}</span>
    </a>
@empty
    <div style="text-align:center;padding:60px 20px;color:var(--text-secondary)">
        <i class="fas fa-headset" style="font-size:46px;opacity:.3;display:block;margin-bottom:14px"></i>
        Aún no tienes tickets de soporte.
    </div>
@endforelse
@endsection
