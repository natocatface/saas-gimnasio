@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')<span class="current">Dashboard</span>@endsection

@push('styles')
<style>
.chart-card { height: 320px; }
.chart-wrap { height: 250px; position: relative; }
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
    margin-top: 2px;
}
.activity-text { flex: 1; }
.activity-text strong { font-size: 14px; color: var(--text-primary); display: block; }
.activity-text span { font-size: 12px; color: var(--text-secondary); }
.activity-time { font-size: 12px; color: var(--text-secondary); white-space: nowrap; }

.plan-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}
.plan-item:last-child { border-bottom: none; }
.plan-color { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
.plan-info { flex: 1; }
.plan-name { font-size: 14px; font-weight: 600; color: var(--text-primary); }
.plan-count { font-size: 12px; color: var(--text-secondary); }
.plan-pct { font-size: 14px; font-weight: 700; color: var(--text-primary); }

.quick-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 20px 16px;
    border-radius: 14px;
    border: 1.5px solid var(--border);
    background: white;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    font-family: 'Inter', sans-serif;
}
.quick-btn:hover { border-color: var(--primary); background: var(--primary-light); transform: translateY(-2px); }
.quick-btn .qicon { font-size: 28px; }
.quick-btn .qlabel { font-size: 13px; font-weight: 600; color: var(--text-primary); }

.expiring-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
.expiring-row:last-child { border-bottom: none; }
.days-left { font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 20px; white-space: nowrap; }
.days-1-3  { background: #fee2e2; color: #991b1b; }
.days-4-7  { background: #fef3c7; color: #92400e; }
.days-8-15 { background: #dbeafe; color: #1e40af; }

.greeting-banner {
    background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 50%, #ec4899 100%);
    border-radius: 20px;
    padding: 28px 32px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow: hidden;
    position: relative;
}
.greeting-banner::before {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%; top: -100px; right: 100px;
}
.greeting-banner::after {
    content: '';
    position: absolute;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%; bottom: -60px; right: -30px;
}
.greeting-text { position: relative; z-index: 1; flex: 1; }
.greeting-text h2 { font-size: 24px; font-weight: 700; color: white; margin-bottom: 6px; }
.greeting-text p  { font-size: 14px; color: rgba(255,255,255,0.75); }
.greeting-icon    { font-size: 60px; position: relative; z-index: 1; }

@media (max-width: 900px) {
    .greeting-banner  { padding: 22px 24px; border-radius: 16px; }
    .greeting-text h2 { font-size: 20px; }
    .greeting-icon    { font-size: 44px; }
}
@media (max-width: 600px) {
    .greeting-banner  { padding: 18px 20px; }
    .greeting-text h2 { font-size: 18px; }
    .greeting-text p  { font-size: 12px; }
    .greeting-icon    { display: none; }
    .chart-wrap       { height: 200px !important; }
    .quick-btn        { padding: 14px 10px; }
    .quick-btn .qicon { font-size: 22px; }
    .quick-btn .qlabel{ font-size: 12px; }
}
</style>
@endpush

@section('content')

<!-- Greeting Banner -->
<div class="greeting-banner">
    <div class="greeting-text">
        <h2>¡Buenos días, {{ explode(' ', auth()->user()->name ?? 'Administrador')[0] }}! 💪</h2>
        <p>{{ now()->format('l, d') }} de {{ now()->format('F') }} de {{ now()->format('Y') }} — Resumen general del gimnasio</p>
    </div>
    <div class="greeting-icon">🏋️‍♂️</div>
</div>

<!-- KPI Cards con gradientes -->
<div class="stats-grid">

    {{-- Socios Activos — Azul --}}
    <a href="{{ route('members.index') }}" class="kpi-card blue">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-users"></i></div>
            <div class="kpi-badge">
                <i class="fas fa-arrow-up"></i> +{{ $stats['newThisMonth'] }}
            </div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $stats['totalMembers'] }}</div>
            <div class="kpi-label">Socios Activos</div>
            <div class="kpi-sub"><i class="fas fa-user-plus"></i> {{ $stats['newThisMonth'] }} nuevos este mes</div>
        </div>
    </a>

    {{-- Ingresos del Mes — Verde/Teal --}}
    <a href="{{ route('payments.index') }}" class="kpi-card teal">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="kpi-badge">
                <i class="fas fa-chart-line"></i> Mes actual
            </div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ money($stats['monthlyRevenue'], 0) }}</div>
            <div class="kpi-label">Ingresos del Mes</div>
            <div class="kpi-sub"><i class="fas fa-coins"></i> {{ money($extraStats['totalRevenue'], 0) }} acumulado {{ now()->year }}</div>
        </div>
    </a>

    {{-- Clases Activas — Violeta --}}
    <a href="{{ route('classes.index') }}" class="kpi-card violet">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-dumbbell"></i></div>
            <div class="kpi-badge">
                <i class="fas fa-check"></i> Activas
            </div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $stats['totalClasses'] }}</div>
            <div class="kpi-label">Clases Programadas</div>
            <div class="kpi-sub"><i class="fas fa-user-tie"></i> {{ $stats['totalTrainers'] }} entrenadores activos</div>
        </div>
    </a>

    {{-- Asistencia Hoy — Rosa/Magenta --}}
    <a href="{{ route('attendance.index') }}" class="kpi-card rose">
        <div class="kpi-top">
            <div class="kpi-icon"><i class="fas fa-fingerprint"></i></div>
            <div class="kpi-badge">
                @if($stats['expiringCount'] > 0)
                    <i class="fas fa-exclamation"></i> {{ $stats['expiringCount'] }} alertas
                @else
                    <i class="fas fa-check"></i> Al día
                @endif
            </div>
        </div>
        <div class="kpi-body">
            <div class="kpi-value">{{ $extraStats['todayAttendance'] }}</div>
            <div class="kpi-label">Asistencia Hoy</div>
            <div class="kpi-sub">
                <i class="fas fa-clock"></i>
                {{ $stats['expiringCount'] > 0 ? $stats['expiringCount'].' membresías vencen en 7 días' : 'Sin vencimientos próximos' }}
            </div>
        </div>
    </a>

</div>

<!-- Row 2: Charts -->
<div class="grid-2 mb-6" style="">

    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-line"></i> Ingresos Mensuales</div>
            <span class="badge badge-success">2026</span>
        </div>
        <div class="card-body">
            <div class="chart-wrap">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Members Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-bar"></i> Socios por Mes</div>
            <span class="badge badge-purple">Registros</span>
        </div>
        <div class="card-body">
            <div class="chart-wrap">
                <canvas id="membersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 3 -->
<div class="grid-3 mb-6">

    <!-- Plans Distribution -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-pie"></i> Planes</div>
        </div>
        <div class="card-body">
            <div style="height:180px; position:relative; margin-bottom:16px;">
                <canvas id="plansChart"></canvas>
            </div>
            @foreach($planDistribution as $plan)
            <div class="plan-item">
                <div class="plan-color" style="background:{{ $plan['color'] }}"></div>
                <div class="plan-info">
                    <div class="plan-name">{{ $plan['name'] }}</div>
                    <div class="plan-count">{{ $plan['count'] }} socios</div>
                </div>
                <div class="plan-pct">{{ $plan['pct'] }}%</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Expiring Soon -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-clock"></i> Vencimientos Próximos</div>
            <a href="{{ route('members.index') }}?filter=expiring" class="btn btn-ghost btn-sm">Ver todos</a>
        </div>
        <div class="card-body" style="padding-top:12px;">
            @forelse($expiringSoon as $m)
            <div class="expiring-row">
                <div class="member-avatar">{{ strtoupper(substr($m->first_name,0,1).substr($m->last_name,0,1)) }}</div>
                <div style="flex:1; min-width:0;">
                    <div class="fw-600 text-sm">{{ $m->first_name }} {{ $m->last_name }}</div>
                    <div class="text-xs text-muted">{{ optional($m->plan)->name ?? 'Sin plan' }}</div>
                </div>
                @php $days = now()->diffInDays($m->membership_end, false); @endphp
                <span class="days-left {{ $days <= 3 ? 'days-1-3' : ($days <= 7 ? 'days-4-7' : 'days-8-15') }}">
                    {{ $days <= 0 ? 'Hoy' : "En {$days}d" }}
                </span>
            </div>
            @empty
            <div style="text-align:center; padding:30px; color:var(--text-secondary);">
                <i class="fas fa-check-circle" style="font-size:32px; color:var(--success); display:block; margin-bottom:10px;"></i>
                Sin vencimientos próximos
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-history"></i> Actividad Reciente</div>
        </div>
        <div class="card-body" style="padding-top:8px;">
            @foreach($recentActivity as $activity)
            <div class="activity-item">
                <div class="activity-dot" style="background:{{ $activity['bg'] }}; color:{{ $activity['color'] }}">
                    {{ $activity['icon'] }}
                </div>
                <div class="activity-text">
                    <strong>{{ $activity['title'] }}</strong>
                    <span>{{ $activity['desc'] }}</span>
                </div>
                <div class="activity-time">{{ $activity['time'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Row 4: Quick Actions + Recent Members -->
<div class="grid-2">

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-bolt"></i> Acciones Rápidas</div>
        </div>
        <div class="card-body">
            <div class="grid-2 gap-3">
                <a href="{{ route('members.create') }}" class="quick-btn">
                    <div class="qicon">👤</div>
                    <div class="qlabel">Nuevo Socio</div>
                </a>
                <a href="{{ route('payments.create') }}" class="quick-btn">
                    <div class="qicon">💳</div>
                    <div class="qlabel">Registrar Pago</div>
                </a>
                <a href="{{ route('attendance.create') }}" class="quick-btn">
                    <div class="qicon">📋</div>
                    <div class="qlabel">Check-in</div>
                </a>
                <a href="{{ route('classes.create') }}" class="quick-btn">
                    <div class="qicon">🏋️</div>
                    <div class="qlabel">Nueva Clase</div>
                </a>
                <a href="{{ route('trainers.create') }}" class="quick-btn">
                    <div class="qicon">👨‍💼</div>
                    <div class="qlabel">Entrenador</div>
                </a>
                <a href="{{ route('reports.index') }}" class="quick-btn">
                    <div class="qicon">📊</div>
                    <div class="qlabel">Reportes</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Members -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-user-plus"></i> Últimos Socios</div>
            <a href="{{ route('members.index') }}" class="btn btn-outline btn-sm">Ver todos</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Socio</th>
                        <th>Plan</th>
                        <th>Estado</th>
                        <th>Vence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentMembers as $m)
                    <tr>
                        <td>
                            <div class="d-flex align-center gap-2">
                                <div class="member-avatar" style="width:34px;height:34px;font-size:12px;">
                                    {{ strtoupper(substr($m->first_name,0,1).substr($m->last_name,0,1)) }}
                                </div>
                                <div>
                                    <div class="fw-600 text-sm">{{ $m->first_name }} {{ $m->last_name }}</div>
                                    <div class="text-xs text-muted">{{ $m->code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-purple text-xs">{{ optional($m->plan)->name ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $m->status === 'activo' ? 'badge-success' : ($m->status === 'vencido' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst($m->status) }}
                            </span>
                        </td>
                        <td class="text-sm text-muted">
                            {{ $m->membership_end ? \Carbon\Carbon::parse($m->membership_end)->format('d/m/Y') : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const chartDefaults = {
    plugins: { legend: { display: false } },
    responsive: true,
    maintainAspectRatio: false,
};

// Revenue Chart — datos reales de la DB
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        datasets: [{
            data: {!! json_encode($revenueByMonth) !!},
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,0.08)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#7c3aed',
            pointRadius: 5,
            pointHoverRadius: 7,
        }]
    },
    options: {
        ...chartDefaults,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: (c) => ' ' + @json(currency_symbol()) + Number(c.raw).toLocaleString('es', {minimumFractionDigits:2}) } }
        },
        scales: {
            y: { grid: { color: '#f3f4f6' }, ticks: { callback: v => '{{ $currency }}'+v } },
            x: { grid: { display: false } }
        }
    }
});

// Members Chart — datos reales de la DB
new Chart(document.getElementById('membersChart'), {
    type: 'bar',
    data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        datasets: [{
            data: {!! json_encode($membersByMonth) !!},
            backgroundColor: (ctx) => {
                const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                g.addColorStop(0, '#7c3aed');
                g.addColorStop(1, '#c4b5fd');
                return g;
            },
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        ...chartDefaults,
        scales: {
            y: { grid: { color: '#f3f4f6' }, beginAtZero: true },
            x: { grid: { display: false } }
        }
    }
});

// Plans Donut
new Chart(document.getElementById('plansChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($planDistribution->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($planDistribution->pluck('count')) !!},
            backgroundColor: {!! json_encode($planDistribution->pluck('color')) !!},
            borderWidth: 0,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
