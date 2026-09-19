@extends('layouts.app')
@section('title', 'Bienvenida')
@section('page-title', 'Bienvenida')
@section('breadcrumb')<span class="current">Primeros pasos</span>@endsection

@push('styles')
<style>
    .ob-hero{background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border-radius:18px;padding:30px 32px;margin-bottom:24px;position:relative;overflow:hidden}
    .ob-hero h2{font-size:24px;font-weight:800;margin-bottom:6px}
    .ob-hero p{opacity:.9;font-size:15px}
    .ob-progress{margin-top:18px;background:rgba(255,255,255,.25);height:10px;border-radius:20px;overflow:hidden;max-width:420px}
    .ob-progress span{display:block;height:100%;background:#fff;border-radius:20px}
    .steps{display:grid;gap:14px}
    .step{display:flex;align-items:center;gap:16px;background:#fff;border:1px solid var(--border);border-radius:15px;padding:18px 20px;transition:.18s}
    .step:hover{box-shadow:0 6px 18px rgba(0,0,0,.06)}
    .step .ic{width:50px;height:50px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:19px;background:#ede9fe;color:#7c3aed;flex-shrink:0}
    .step.done .ic{background:#dcfce7;color:#15803d}
    .step .tt{font-weight:700;font-size:15px}
    .step .dd{font-size:13px;color:var(--muted);margin-top:2px}
    .step .chk{margin-left:auto;display:flex;align-items:center;gap:12px}
    .done-badge{color:#15803d;font-size:13px;font-weight:600}
</style>
@endpush

@section('content')

<div class="ob-hero">
    <h2>¡Tu gimnasio está listo, {{ explode(' ', auth()->user()->name)[0] }}! 🎉</h2>
    <p>Completa estos pasos para sacarle el máximo provecho a {{ $gym->name }}. Tienes {{ $gym->trialDaysLeft() }} días de prueba gratis.</p>
    <div class="ob-progress"><span style="width:{{ count($steps) ? round($completed/count($steps)*100) : 0 }}%"></span></div>
    <p style="margin-top:8px;font-size:13px">{{ $completed }} de {{ count($steps) }} pasos completados</p>
</div>

<div class="steps">
    @foreach($steps as $s)
        <a href="{{ $s['route'] }}" style="text-decoration:none;color:inherit">
            <div class="step {{ $s['done']?'done':'' }}">
                <div class="ic"><i class="fas {{ $s['icon'] }}"></i></div>
                <div>
                    <div class="tt">{{ $s['title'] }}</div>
                    <div class="dd">{{ $s['desc'] }}</div>
                </div>
                <div class="chk">
                    @if($s['done'])
                        <span class="done-badge"><i class="fas fa-circle-check"></i> Hecho</span>
                    @else
                        <span class="btn btn-outline" style="pointer-events:none">Empezar <i class="fas fa-arrow-right"></i></span>
                    @endif
                </div>
            </div>
        </a>
    @endforeach
</div>

<div style="margin-top:24px;text-align:center">
    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fas fa-gauge-high"></i> Ir al Dashboard</a>
</div>
@endsection
