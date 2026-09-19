@extends('layouts.app')
@section('title', 'Facturación')
@section('page-title', 'Facturación y Plan')
@section('breadcrumb')<span class="current">Facturación</span>@endsection

@push('styles')
<style>
    .bill-grid{display:grid;grid-template-columns:1.3fr 1fr;gap:20px;align-items:start;margin-bottom:22px}
    .panel{background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px}
    .panel h3{font-size:16px;font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:9px}
    .plan-now{display:flex;align-items:center;gap:16px;padding:18px;border-radius:13px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff}
    .plan-now .pc{width:54px;height:54px;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:22px}
    .plan-now .pn{font-size:20px;font-weight:800}
    .plan-now .ps{font-size:13px;opacity:.85}
    .usage{margin-top:8px}
    .usage-row{margin:16px 0}
    .usage-row .top{display:flex;justify-content:space-between;font-size:14px;margin-bottom:7px}
    .usage-row .top b{font-weight:700}
    .bar{height:9px;background:#eee;border-radius:20px;overflow:hidden}
    .bar span{display:block;height:100%;border-radius:20px;background:linear-gradient(90deg,#7c3aed,#ec4899)}
    .bar.warn span{background:linear-gradient(90deg,#f59e0b,#ef4444)}
    .pricing{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:6px}
    .pcard{border:2px solid var(--border);border-radius:15px;padding:20px;text-align:center;position:relative;transition:.18s}
    .pcard.current{border-color:var(--primary);background:#faf8ff}
    .pcard.pop{border-color:#ec4899}
    .pcard .tag{position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:#ec4899;color:#fff;font-size:10px;font-weight:700;padding:4px 12px;border-radius:20px;letter-spacing:.5px}
    .pcard .nm{font-weight:700;font-size:16px}
    .pcard .pr{font-size:30px;font-weight:800;margin:8px 0}
    .pcard .pr span{font-size:13px;font-weight:500;color:var(--muted)}
    .pcard ul{list-style:none;text-align:left;margin:14px 0;font-size:13px;color:var(--text-secondary)}
    .pcard li{padding:4px 0}.pcard li i{color:#10b981;margin-right:6px}
    .cycle-toggle{display:inline-flex;background:#f3f4f6;border-radius:10px;padding:4px;margin-bottom:18px}
    .cycle-toggle button{border:none;background:none;padding:8px 18px;border-radius:8px;font-weight:600;font-size:13px;cursor:pointer;color:var(--muted)}
    .cycle-toggle button.on{background:#fff;color:var(--primary);box-shadow:0 1px 4px rgba(0,0,0,.08)}
    .sub-table{width:100%;border-collapse:collapse}
    .sub-table th{text-align:left;font-size:11px;text-transform:uppercase;color:var(--muted);padding:10px;border-bottom:2px solid var(--border)}
    .sub-table td{padding:11px 10px;border-bottom:1px solid #f1f1f4;font-size:14px}
    .b{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .b.active{background:#dcfce7;color:#15803d}.b.expired,.b.cancelled,.b.pending{background:#f3f4f6;color:#6b7280}
</style>
@endpush

@section('content')

<div class="bill-grid">
    {{-- Plan actual + uso --}}
    <div class="panel">
        <h3><i class="fas fa-crown" style="color:var(--primary)"></i> Tu plan actual</h3>
        <div class="plan-now">
            <div class="pc"><i class="fas fa-layer-group"></i></div>
            <div style="flex:1">
                <div class="pn">{{ $gym->saasPlan->name ?? 'Sin plan' }}</div>
                <div class="ps">
                    @if($gym->isOnTrial())
                        Prueba gratis · {{ $gym->trialDaysLeft() }} días restantes
                    @elseif($gym->status==='active')
                        Suscripción activa
                    @else
                        {{ ucfirst($gym->status) }}
                    @endif
                </div>
            </div>
            <div style="text-align:right">
                <div style="font-size:24px;font-weight:800">{{ money($gym->saasPlan->price_monthly ?? 0,0) }}</div>
                <div style="font-size:12px;opacity:.85">/mes</div>
            </div>
        </div>

        <div class="usage">
            @foreach(['members'=>'Socios','trainers'=>'Entrenadores','classes'=>'Clases'] as $k=>$label)
                <div class="usage-row">
                    <div class="top"><span>{{ $label }}</span><b>{{ $usage[$k]['used'] }} / {{ $usage[$k]['limit'] }}</b></div>
                    <div class="bar {{ $usage[$k]['pct']>=80 ? 'warn':'' }}"><span style="width:{{ $usage[$k]['pct'] }}%"></span></div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Resumen de cuenta --}}
    <div class="panel">
        <h3><i class="fas fa-circle-info" style="color:var(--primary)"></i> Resumen</h3>
        <div style="font-size:14px;line-height:2.4">
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Gimnasio</span><b>{{ $gym->name }}</b></div>
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Estado</span><b style="text-transform:capitalize">{{ $gym->status }}</b></div>
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Plan</span><b>{{ $gym->saasPlan->name ?? '—' }}</b></div>
            @if($gym->isOnTrial())
            <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Prueba termina</span><b style="color:var(--warning)">{{ $gym->trial_ends_at->format('d/m/Y') }}</b></div>
            @endif
        </div>
        @if($gym->isOnTrial())
            <div style="margin-top:16px;padding:13px;background:#fffbeb;border:1px solid #fde68a;border-radius:11px;font-size:13px;color:#92400e">
                <i class="fas fa-hourglass-half"></i> Estás en prueba gratuita. Elige un plan abajo para no perder acceso cuando termine.
            </div>
        @endif
    </div>
</div>

{{-- Planes disponibles --}}
<div class="panel">
    <h3><i class="fas fa-arrow-up-right-dots" style="color:var(--primary)"></i> Cambia o mejora tu plan</h3>

    <div style="display:flex;gap:14px;flex-wrap:wrap;align-items:center;justify-content:space-between">
        <div class="cycle-toggle" id="cycleToggle">
            <button type="button" class="on" data-cycle="monthly">Mensual</button>
            <button type="button" data-cycle="yearly">Anual <span style="color:#10b981">-2 meses</span></button>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <i class="fas fa-tag" style="color:var(--primary)"></i>
            <input type="text" id="couponBox" placeholder="Código de cupón" value="{{ old('coupon') }}"
                   style="padding:9px 13px;border:1px solid var(--border);border-radius:9px;font-size:13px;text-transform:uppercase;width:170px">
        </div>
    </div>

    <div class="pricing">
        @foreach($plans as $p)
            @php $isCurrent = $gym->saas_plan_id == $p->id && $gym->status==='active'; @endphp
            <div class="pcard {{ $p->is_popular?'pop':'' }} {{ $isCurrent?'current':'' }}">
                @if($p->is_popular)<div class="tag">MÁS POPULAR</div>@endif
                <div class="nm">{{ $p->name }}</div>
                <div class="pr" data-m="{{ money($p->price_monthly,0,false) }}" data-y="{{ money($p->price_yearly,0,false) }}">
                    {{ money($p->price_monthly,0) }}<span class="per">/mes</span>
                </div>
                <ul>
                    @foreach(array_slice($p->features_array,0,5) as $f)<li><i class="fas fa-check"></i>{{ $f }}</li>@endforeach
                </ul>
                @if($isCurrent)
                    <button class="btn btn-outline" style="width:100%" disabled><i class="fas fa-check"></i> Plan actual</button>
                @else
                    <form method="POST" action="{{ route('billing.change') }}">
                        @csrf
                        <input type="hidden" name="saas_plan_id" value="{{ $p->id }}">
                        <input type="hidden" name="billing_cycle" value="monthly" class="cycleInput">
                        <input type="hidden" name="coupon" class="couponInput">
                        <button class="btn btn-primary" style="width:100%" onclick="return confirm('¿Cambiar al plan {{ $p->name }}?')">
                            {{ $gym->saasPlan && $p->price_monthly > $gym->saasPlan->price_monthly ? 'Mejorar a este plan' : 'Elegir este plan' }}
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
    <p style="font-size:12px;color:var(--muted);margin-top:14px"><i class="fas fa-shield-halved"></i> Cobro simulado para demostración. El cambio de plan es inmediato.</p>
</div>

{{-- Historial --}}
<div class="panel" style="margin-top:22px">
    <h3><i class="fas fa-receipt" style="color:var(--primary)"></i> Historial de pagos</h3>
    <table class="sub-table">
        <thead><tr><th>Fecha</th><th>Plan</th><th>Ciclo</th><th>Monto</th><th>Vigencia</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        @forelse($subscriptions as $s)
            <tr>
                <td style="color:var(--muted)">{{ $s->created_at->format('d/m/Y') }}</td>
                <td>{{ $s->saasPlan->name ?? '—' }}</td>
                <td>{{ $s->billing_cycle==='yearly'?'Anual':'Mensual' }}</td>
                <td style="font-weight:700">{{ money($s->amount,2) }}</td>
                <td style="font-size:13px;color:var(--muted)">{{ \Carbon\Carbon::parse($s->starts_at)->format('d/m/y') }} → {{ \Carbon\Carbon::parse($s->ends_at)->format('d/m/y') }}</td>
                <td><span class="b {{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                <td style="text-align:right"><a href="{{ route('billing.receipt',$s) }}" target="_blank" class="btn btn-outline" style="padding:6px 11px;font-size:12px"><i class="fas fa-receipt"></i> Recibo</a></td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--muted)">Aún no hay pagos registrados.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<script>
    const toggle=document.getElementById('cycleToggle');
    toggle.addEventListener('click',e=>{
        const btn=e.target.closest('button[data-cycle]'); if(!btn)return;
        toggle.querySelectorAll('button').forEach(b=>b.classList.remove('on')); btn.classList.add('on');
        const cycle=btn.dataset.cycle;
        document.querySelectorAll('.cycleInput').forEach(i=>i.value=cycle);
        document.querySelectorAll('.pr').forEach(pr=>{
            const raw=cycle==='yearly'?pr.dataset.y:pr.dataset.m;
            pr.childNodes[0].nodeValue = @json(currency_position()) === 'after' ? raw+' '+@json(currency_symbol()) : @json(currency_symbol())+raw;
            pr.querySelector('.per').textContent=cycle==='yearly'?'/año':'/mes';
        });
    });

    // Inyectar el cupón en cada formulario al enviar
    const couponBox=document.getElementById('couponBox');
    document.querySelectorAll('form[action="{{ route('billing.change') }}"]').forEach(f=>{
        f.addEventListener('submit',()=>{
            const ci=f.querySelector('.couponInput');
            if(ci) ci.value=couponBox ? couponBox.value.trim() : '';
        });
    });
</script>
@endsection
