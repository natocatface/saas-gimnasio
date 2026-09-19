@extends('layouts.superadmin')
@section('title','Dashboard SaaS')
@section('page-title','Dashboard SaaS')
@section('breadcrumb','Visión general de la plataforma')

@section('actions')
    <a href="{{ route('superadmin.gyms.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo gimnasio</a>
@endsection

@section('content')

    {{-- KPIs premium --}}
    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(205px,1fr));margin-bottom:20px">
        <div class="stat grad g-violet">
            <div class="trend"><i class="fas fa-arrow-up"></i> {{ $stats['new_this_month'] }} este mes</div>
            <div class="ico"><i class="fas fa-dumbbell"></i></div>
            <div class="val">{{ $stats['total_gyms'] }}</div><div class="lbl">Gimnasios totales</div>
        </div>
        <div class="stat grad g-green">
            <div class="trend"><i class="fas fa-bolt"></i> {{ $stats['conversion'] }}%</div>
            <div class="ico"><i class="fas fa-circle-check"></i></div>
            <div class="val">{{ $stats['active_gyms'] }}</div><div class="lbl">Activos (pagando)</div>
        </div>
        <div class="stat grad g-amber">
            <div class="ico"><i class="fas fa-hourglass-half"></i></div>
            <div class="val">{{ $stats['trial_gyms'] }}</div><div class="lbl">En prueba</div>
        </div>
        <div class="stat grad g-pink">
            <div class="trend">/mes</div>
            <div class="ico"><i class="fas fa-sack-dollar"></i></div>
            <div class="val">{{ money($mrr,0) }}</div><div class="lbl">MRR estimado</div>
        </div>
        <div class="stat grad g-blue">
            <div class="ico"><i class="fas fa-users"></i></div>
            <div class="val">{{ $stats['total_users'] }}</div><div class="lbl">Usuarios en la plataforma</div>
        </div>
    </div>

    <div class="grid" style="grid-template-columns:1.7fr 1fr;align-items:stretch">
        {{-- Crecimiento --}}
        <div class="card">
            <div class="card-h">
                <h3><span class="hi"><i class="fas fa-chart-line"></i></span> Crecimiento de gimnasios</h3>
                <span class="pill-soft">Últimos 6 meses</span>
            </div>
            <div style="height:250px"><canvas id="growthChart"></canvas></div>
        </div>
        {{-- Distribución por plan --}}
        <div class="card">
            <div class="card-h"><h3><span class="hi"><i class="fas fa-layer-group"></i></span> Por plan</h3></div>
            <div style="position:relative;height:200px">
                <canvas id="planChart"></canvas>
                <div id="planCenter" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none">
                    <div style="font-size:30px;font-weight:800;letter-spacing:-1px">{{ $stats['total_gyms'] }}</div>
                    <div style="font-size:12px;color:var(--muted);font-weight:600;margin-top:2px">gimnasios</div>
                </div>
            </div>
            <div class="legend">
                @foreach($planDistribution as $pd)
                    <div class="li">
                        <span class="dot" style="background:{{ $pd->color ?? '#7c3aed' }}"></span>
                        <span class="nm">{{ $pd->name }}</span>
                        <span class="vl">{{ $pd->total }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid" style="grid-template-columns:2fr 1fr;align-items:start;margin-top:18px">
        {{-- Gimnasios recientes --}}
        <div class="card" style="padding:0;overflow:hidden">
            <div class="card-h" style="padding:20px 22px 0"><h3>Gimnasios recientes</h3>
                <a href="{{ route('superadmin.gyms.index') }}" class="btn btn-light btn-sm">Ver todos</a></div>
            <table>
                <thead><tr><th>Gimnasio</th><th>Plan</th><th>Estado</th><th>Registro</th></tr></thead>
                <tbody>
                @forelse($recentGyms as $g)
                    <tr>
                        <td><div class="gym-mini">
                            <div class="gym-logo" style="background:{{ $g->primary_color ?? '#7c3aed' }}">{{ strtoupper(substr($g->name,0,2)) }}</div>
                            <div><div style="font-weight:600">{{ $g->name }}</div><div style="font-size:12px;color:var(--muted)">{{ $g->email }}</div></div>
                        </div></td>
                        <td>{{ $g->saasPlan->name ?? '—' }}</td>
                        <td><span class="badge {{ $g->status }}">{{ ucfirst($g->status) }}</span></td>
                        <td style="color:var(--muted)">{{ $g->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty"><i class="fas fa-dumbbell"></i><div>Aún no hay gimnasios registrados</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pruebas por vencer --}}
        <div class="card">
            <div class="card-h"><h3>Pruebas por vencer</h3></div>
            @forelse($expiringTrials as $t)
                <div style="display:flex;align-items:center;gap:11px;padding:11px 0;border-bottom:1px solid #f1f1f4">
                    <div class="gym-logo" style="background:{{ $t->primary_color ?? '#f59e0b' }}">{{ strtoupper(substr($t->name,0,2)) }}</div>
                    <div style="flex:1"><div style="font-weight:600;font-size:14px">{{ $t->name }}</div>
                        <div style="font-size:12px;color:var(--warning)">Vence {{ \Carbon\Carbon::parse($t->trial_ends_at)->diffForHumans() }}</div></div>
                    <a href="{{ route('superadmin.subscriptions.create',['gym'=>$t->id]) }}" class="btn btn-light btn-sm">Cobrar</a>
                </div>
            @empty
                <div class="empty" style="padding:30px 10px"><i class="fas fa-mug-hot"></i><div>Sin pruebas próximas a vencer</div></div>
            @endforelse
        </div>
    </div>

<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#9ca3af';

    // ── Línea de crecimiento con relleno degradado ──
    const growth = @json($growth);
    const gc = document.getElementById('growthChart');
    const gctx = gc.getContext('2d');
    const grad = gctx.createLinearGradient(0, 0, 0, 240);
    grad.addColorStop(0, 'rgba(124,58,237,.35)');
    grad.addColorStop(1, 'rgba(124,58,237,0)');

    new Chart(gc, {
        type:'line',
        data:{ labels:growth.map(g=>g.label),
            datasets:[{
                label:'Gimnasios', data:growth.map(g=>g.total),
                borderColor:'#7c3aed', backgroundColor:grad, fill:true, tension:.45,
                borderWidth:3, pointRadius:0, pointHoverRadius:6,
                pointBackgroundColor:'#fff', pointBorderColor:'#7c3aed', pointBorderWidth:3
            }]},
        options:{
            maintainAspectRatio:false,
            interaction:{intersect:false, mode:'index'},
            plugins:{
                legend:{display:false},
                tooltip:{
                    backgroundColor:'#1a1040', padding:12, cornerRadius:10, displayColors:false,
                    titleColor:'#fff', bodyColor:'#e9d5ff',
                    callbacks:{ label:c=>` ${c.parsed.y} gimnasios` }
                }
            },
            scales:{
                y:{beginAtZero:true, ticks:{precision:0, stepSize:1}, grid:{color:'#f1f1f4', drawBorder:false}, border:{display:false}},
                x:{grid:{display:false}, border:{display:false}}
            }
        }
    });

    // ── Dona por plan (leyenda propia abajo) ──
    const pd = @json($planDistribution);
    new Chart(document.getElementById('planChart'), {
        type:'doughnut',
        data:{ labels:pd.map(p=>p.name),
            datasets:[{
                data:pd.map(p=>p.total),
                backgroundColor:pd.map(p=>p.color||'#7c3aed'),
                borderWidth:4, borderColor:'#fff', hoverOffset:8, hoverBorderWidth:0
            }]},
        options:{
            maintainAspectRatio:false, cutout:'72%',
            plugins:{
                legend:{display:false},
                tooltip:{
                    backgroundColor:'#1a1040', padding:11, cornerRadius:10, displayColors:true, boxWidth:10, boxHeight:10,
                    callbacks:{ label:c=>` ${c.label}: ${c.parsed} gimnasios` }
                }
            }
        }
    });
</script>

@endsection
