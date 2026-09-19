@extends('layouts.superadmin')
@section('title','Reportes')
@section('page-title','Reportes y Analítica')
@section('breadcrumb','Salud financiera del SaaS')

@section('actions')
    <a href="{{ route('superadmin.reports.export') }}" class="btn btn-primary"><i class="fas fa-file-csv"></i> Exportar CSV</a>
@endsection

@section('content')

    {{-- KPIs --}}
    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(190px,1fr));margin-bottom:20px">
        <div class="stat grad g-violet"><div class="ico"><i class="fas fa-sack-dollar"></i></div><div class="val">{{ money($kpis['total_revenue'],0) }}</div><div class="lbl">Ingreso total</div></div>
        <div class="stat grad g-green"><div class="ico"><i class="fas fa-calendar-day"></i></div><div class="val">{{ money($kpis['revenue_month'],0) }}</div><div class="lbl">Ingreso este mes</div></div>
        <div class="stat grad g-blue"><div class="ico"><i class="fas fa-arrows-rotate"></i></div><div class="val">{{ money($kpis['mrr'],0) }}</div><div class="lbl">MRR (recurrente/mes)</div></div>
        <div class="stat grad g-amber"><div class="ico"><i class="fas fa-user-check"></i></div><div class="val">{{ money($kpis['arpa'],0) }}</div><div class="lbl">Ingreso medio por gym</div></div>
        <div class="stat grad g-pink"><div class="ico"><i class="fas fa-user-slash"></i></div><div class="val">{{ $kpis['churned'] }}</div><div class="lbl">Cancelados / suspendidos</div></div>
    </div>

    {{-- Ingresos por mes --}}
    <div class="card" style="margin-bottom:18px">
        <div class="card-h">
            <h3><span class="hi"><i class="fas fa-chart-column"></i></span> Ingresos por mes</h3>
            <span class="pill-soft">Últimos 12 meses</span>
        </div>
        <div style="height:260px"><canvas id="revChart"></canvas></div>
    </div>

    <div class="grid" style="grid-template-columns:1.5fr 1fr;align-items:start">
        {{-- Altas de gimnasios --}}
        <div class="card">
            <div class="card-h"><h3><span class="hi"><i class="fas fa-dumbbell"></i></span> Altas de gimnasios</h3><span class="pill-soft">Últimos 12 meses</span></div>
            <div style="height:230px"><canvas id="newChart"></canvas></div>
        </div>

        {{-- Ingresos por plan --}}
        <div class="card">
            <div class="card-h"><h3><span class="hi"><i class="fas fa-layer-group"></i></span> Ingresos por plan</h3></div>
            <table>
                <thead><tr><th>Plan</th><th>Suscripciones</th><th style="text-align:right">Ingreso</th></tr></thead>
                <tbody>
                @forelse($revByPlan as $r)
                    <tr>
                        <td><span class="badge" style="background:{{ $r->color }}22;color:{{ $r->color }}">{{ $r->name }}</span></td>
                        <td>{{ $r->cnt }}</td>
                        <td style="text-align:right;font-weight:700">{{ money($r->total,2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty"><i class="fas fa-chart-pie"></i><div>Sin ingresos registrados</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#9ca3af';

    const rev = @json($revenueSeries);
    const rc = document.getElementById('revChart').getContext('2d');
    const rg = rc.createLinearGradient(0,0,0,260);
    rg.addColorStop(0,'rgba(124,58,237,.9)'); rg.addColorStop(1,'rgba(236,72,153,.6)');
    new Chart(rc, {
        type:'bar',
        data:{ labels:rev.map(r=>r.label),
            datasets:[{ data:rev.map(r=>r.total), backgroundColor:rg, borderRadius:8, maxBarThickness:34 }]},
        options:{ maintainAspectRatio:false,
            plugins:{ legend:{display:false},
                tooltip:{ backgroundColor:'#1a1040', padding:11, cornerRadius:10, displayColors:false,
                    callbacks:{ label:c=>' $'+Number(c.parsed.y).toLocaleString() } } },
            scales:{ y:{beginAtZero:true, grid:{color:'#f1f1f4'}, border:{display:false},
                        ticks:{callback:v=>'$'+v}},
                     x:{grid:{display:false}, border:{display:false}} } }
    });

    const ng = @json($newGymsSeries);
    const ngctx = document.getElementById('newChart').getContext('2d');
    const ngrad = ngctx.createLinearGradient(0,0,0,230);
    ngrad.addColorStop(0,'rgba(16,185,129,.35)'); ngrad.addColorStop(1,'rgba(16,185,129,0)');
    new Chart(ngctx, {
        type:'line',
        data:{ labels:ng.map(r=>r.label),
            datasets:[{ data:ng.map(r=>r.total), borderColor:'#10b981', backgroundColor:ngrad, fill:true,
                tension:.4, borderWidth:3, pointRadius:0, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#10b981', pointBorderWidth:3 }]},
        options:{ maintainAspectRatio:false, interaction:{intersect:false,mode:'index'},
            plugins:{ legend:{display:false},
                tooltip:{ backgroundColor:'#1a1040', padding:11, cornerRadius:10, displayColors:false,
                    callbacks:{ label:c=>' '+c.parsed.y+' nuevos' } } },
            scales:{ y:{beginAtZero:true, ticks:{precision:0,stepSize:1}, grid:{color:'#f1f1f4'}, border:{display:false}},
                     x:{grid:{display:false}, border:{display:false}} } }
    });
</script>

@endsection
