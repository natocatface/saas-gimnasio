@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Reportes y Estadísticas')
@section('breadcrumb')<span class="current">Reportes</span>@endsection

@push('styles')
<style>
.chart-wrap { height: 280px; position: relative; }
</style>
@endpush

@section('content')

@include('partials.module_hero', ['title' => 'Reportes', 'subtitle' => 'Indicadores de ingresos, socios y asistencia.', 'icon' => 'fa-chart-bar'])

<div class="d-flex justify-between align-center mb-4 no-print">
    <div class="text-muted text-sm">Resumen del año {{ $year }}</div>
    <button onclick="window.print()" class="btn btn-outline"><i class="fas fa-print"></i> Imprimir / PDF</button>
</div>

<style>@media print{.sidebar,.topbar,.no-print{display:none!important}.main-content{margin:0!important}}</style>

<div class="stats-grid mb-6">
    <div class="kpi-card teal">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div><div class="kpi-badge"><i class="fas fa-calendar"></i> {{ $year }}</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ money($stats['total_revenue'],0) }}</div><div class="kpi-label">Ingresos {{ $year }}</div></div>
    </div>
    <div class="kpi-card blue">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-receipt"></i></div><div class="kpi-badge"><i class="fas fa-check"></i> Procesados</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ $stats['total_payments'] }}</div><div class="kpi-label">Pagos Procesados</div></div>
    </div>
    <div class="kpi-card violet">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-user-plus"></i></div><div class="kpi-badge"><i class="fas fa-arrow-up"></i> Nuevos</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ $stats['new_members'] }}</div><div class="kpi-label">Nuevos Socios</div></div>
    </div>
    <div class="kpi-card rose">
        <div class="kpi-top"><div class="kpi-icon"><i class="fas fa-chart-line"></i></div><div class="kpi-badge"><i class="fas fa-coins"></i> Promedio</div></div>
        <div class="kpi-body"><div class="kpi-value">{{ money($stats['avg_payment'],2) }}</div><div class="kpi-label">Ticket Promedio</div></div>
    </div>
</div>

<div class="grid-2 mb-6">
    <div class="card" id="section-revenue">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-line"></i> Ingresos por Mes {{ $year }}</div>
        </div>
        <div class="card-body">
            <div class="chart-wrap"><canvas id="revenueChart"></canvas></div>
        </div>
    </div>
    <div class="card" id="section-members">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-users"></i> Nuevos Socios por Mes</div>
        </div>
        <div class="card-body">
            <div class="chart-wrap"><canvas id="membersChart"></canvas></div>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card" id="section-attendance">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-bar"></i> Asistencia por Día de Semana</div>
        </div>
        <div class="card-body">
            <div class="chart-wrap"><canvas id="attendanceChart"></canvas></div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-credit-card"></i> Métodos de Pago</div>
        </div>
        <div class="card-body">
            <div class="chart-wrap" style="height:200px;"><canvas id="methodsChart"></canvas></div>
            <div style="margin-top:16px;">
                @foreach($paymentMethods as $pm)
                <div class="d-flex justify-between align-center" style="padding:8px 0;border-bottom:1px solid var(--border);">
                    <span class="text-sm fw-600">{{ ucfirst($pm->payment_method) }}</span>
                    <div class="d-flex gap-3 align-center">
                        <span class="text-sm text-muted">{{ $pm->cnt }} pagos</span>
                        <span class="fw-700" style="color:var(--success);">{{ money($pm->total,2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const months = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const opts = { responsive:true, maintainAspectRatio:false };

new Chart(document.getElementById('revenueChart'),{
    type:'line',
    data:{
        labels:months,
        datasets:[{
            data:{!! json_encode($revenueByMonth) !!},
            borderColor:'#7c3aed', backgroundColor:'rgba(124,58,237,0.1)',
            borderWidth:3, fill:true, tension:0.4,
            pointBackgroundColor:'#7c3aed', pointRadius:5
        }]
    },
    options:{...opts, plugins:{legend:{display:false}},
        scales:{y:{ticks:{callback:v=>'{{ $currency }}'+v.toLocaleString()},grid:{color:'#f3f4f6'}},x:{grid:{display:false}}}}
});

new Chart(document.getElementById('membersChart'),{
    type:'bar',
    data:{
        labels:months,
        datasets:[{
            data:{!! json_encode($membersByMonth) !!},
            backgroundColor:'rgba(236,72,153,0.7)', borderRadius:8, borderSkipped:false
        }]
    },
    options:{...opts, plugins:{legend:{display:false}},
        scales:{y:{grid:{color:'#f3f4f6'}},x:{grid:{display:false}}}}
});

new Chart(document.getElementById('attendanceChart'),{
    type:'bar',
    data:{
        labels:{!! json_encode($days) !!},
        datasets:[{
            data:{!! json_encode($attendanceData) !!},
            backgroundColor:'rgba(124,58,237,0.6)', borderRadius:8, borderSkipped:false
        }]
    },
    options:{...opts, plugins:{legend:{display:false}},
        scales:{y:{grid:{color:'#f3f4f6'}},x:{grid:{display:false}}}}
});

new Chart(document.getElementById('methodsChart'),{
    type:'doughnut',
    data:{
        labels:{!! json_encode($paymentMethods->pluck('payment_method')->map(fn($m)=>ucfirst($m))) !!},
        datasets:[{
            data:{!! json_encode($paymentMethods->pluck('total')) !!},
            backgroundColor:['#7c3aed','#ec4899','#10b981','#3b82f6'],
            borderWidth:0, hoverOffset:6
        }]
    },
    options:{...opts, cutout:'65%', plugins:{legend:{position:'bottom'}}}
});

// Deep-link a una sección concreta del reporte (reports/revenue|members|attendance)
const reportFocus = @json($focus ?? 'all');
if (reportFocus && reportFocus !== 'all') {
    const target = document.getElementById('section-' + reportFocus);
    if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        target.style.boxShadow = '0 0 0 2px var(--primary, #7c3aed)';
    }
}
</script>
@endpush
