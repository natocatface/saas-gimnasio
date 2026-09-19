<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') · GymSaaS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <style>
        :root{
            --primary:#7c3aed; --primary-dark:#6d28d9; --accent:#ec4899;
            --sidebar-bg:#0b0716; --sidebar-hover:#19102e; --sidebar-width:265px;
            --text:#111827; --muted:#6b7280; --border:#e5e7eb; --body-bg:#f4f4f8;
            --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --info:#3b82f6;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:var(--body-bg);color:var(--text);display:flex;min-height:100vh}
        a{text-decoration:none}
        /* SIDEBAR */
        .sidebar{width:var(--sidebar-width);background:var(--sidebar-bg);height:100vh;position:fixed;top:0;left:0;display:flex;flex-direction:column;z-index:1000;overflow-y:auto}
        .sidebar::-webkit-scrollbar{width:4px}.sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:4px}
        .brand{padding:22px 20px;display:flex;align-items:center;gap:13px;border-bottom:1px solid rgba(255,255,255,.06)}
        .brand-icon{width:44px;height:44px;background:linear-gradient(135deg,#ec4899,#7c3aed);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;box-shadow:0 8px 20px rgba(236,72,153,.3)}
        .brand h3{font-size:15px;font-weight:700;color:#fff;line-height:1.2}
        .brand span{font-size:10px;color:rgba(255,255,255,.45);letter-spacing:1.5px;text-transform:uppercase}
        .nav-badge-sa{margin-left:auto;font-size:9px;background:var(--accent);color:#fff;padding:3px 8px;border-radius:20px;font-weight:700;letter-spacing:.5px}
        .nav-section{padding:18px 0 4px}
        .nav-title{padding:0 20px 8px;font-size:10px;font-weight:700;color:rgba(255,255,255,.25);text-transform:uppercase;letter-spacing:1.5px}
        .nav-item{margin:2px 12px}
        .nav-link{display:flex;align-items:center;gap:12px;padding:11px 14px;border-radius:10px;color:rgba(255,255,255,.55);font-size:14px;font-weight:500;transition:.2s}
        .nav-link:hover{background:var(--sidebar-hover);color:#fff}
        .nav-link.active{background:linear-gradient(135deg,var(--primary),#6d28d9);color:#fff;box-shadow:0 4px 15px rgba(124,58,237,.4)}
        .nav-link i{width:34px;height:34px;display:flex;align-items:center;justify-content:center;border-radius:8px;background:rgba(255,255,255,.06);font-size:14px}
        .nav-link.active i{background:rgba(255,255,255,.16)}
        .sidebar-footer{margin-top:auto;padding:16px 12px;border-top:1px solid rgba(255,255,255,.06)}
        .su-user{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}
        .su-avatar{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#ec4899,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px}
        .su-user .n{color:#fff;font-size:13px;font-weight:600}.su-user .r{color:rgba(255,255,255,.4);font-size:11px}
        .su-logout{margin-left:auto;color:rgba(255,255,255,.4);font-size:15px}.su-logout:hover{color:var(--danger)}
        /* MAIN */
        .main{margin-left:var(--sidebar-width);flex:1;min-width:0}
        .topbar{height:68px;background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 28px;position:sticky;top:0;z-index:900}
        .topbar h1{font-size:19px;font-weight:700}
        .topbar .crumb{font-size:12px;color:var(--muted);margin-top:2px}
        .topbar .crumb a{color:var(--primary)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:10px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;border:none;cursor:pointer;transition:.2s}
        .btn-primary{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff}
        .btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(124,58,237,.35)}
        .btn-light{background:#f3f4f6;color:var(--text)}.btn-light:hover{background:#e5e7eb}
        .btn-danger{background:#fee2e2;color:var(--danger)}.btn-danger:hover{background:#fecaca}
        .btn-sm{padding:7px 12px;font-size:12px}
        .content{padding:26px 28px 60px}
        /* CARDS / GRID */
        .grid{display:grid;gap:18px}
        .card{background:#fff;border:1px solid var(--border);border-radius:18px;padding:22px;box-shadow:0 1px 3px rgba(16,12,30,.04)}
        .card-h{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
        .card-h h3{font-size:16px;font-weight:700;display:flex;align-items:center;gap:9px}
        .card-h h3 .hi{width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:13px;background:#f3effe;color:var(--primary)}

        /* ===== Tarjetas de estadística ===== */
        /* Base: tarjeta clara (no rompe gyms/plans/subscriptions) */
        .stat{position:relative;background:#fff;border:1px solid var(--border);border-radius:18px;padding:20px;overflow:hidden;
              box-shadow:0 1px 3px rgba(16,12,30,.04);transition:transform .22s ease,box-shadow .22s ease}
        .stat .ico{width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;margin-bottom:14px}
        .stat .val{font-size:30px;font-weight:800;line-height:1;letter-spacing:-.5px}
        .stat .lbl{font-size:13px;color:var(--muted);margin-top:7px;font-weight:500}

        /* Variante premium con gradiente (para el dashboard) */
        .stat.grad{color:#fff;border:none;padding:22px 22px 20px;box-shadow:0 14px 30px -12px rgba(16,12,30,.5)}
        .stat.grad:hover{transform:translateY(-5px);box-shadow:0 26px 44px -14px rgba(16,12,30,.55)}
        .stat.grad::after{content:"";position:absolute;right:-30px;top:-30px;width:130px;height:130px;border-radius:50%;background:rgba(255,255,255,.10)}
        .stat.grad::before{content:"";position:absolute;right:-55px;bottom:-55px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,.07)}
        .stat.grad .ico{background:rgba(255,255,255,.22);position:relative;z-index:1}
        .stat.grad .val{position:relative;z-index:1}
        .stat.grad .lbl{color:#fff;opacity:.88;position:relative;z-index:1}
        .stat.grad .trend{position:absolute;top:20px;right:20px;z-index:2;display:inline-flex;align-items:center;gap:5px;
                          background:rgba(255,255,255,.22);padding:5px 11px;border-radius:30px;font-size:12px;font-weight:700}
        .stat.g-violet{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
        .stat.g-green {background:linear-gradient(135deg,#34d399,#059669)}
        .stat.g-amber {background:linear-gradient(135deg,#fbbf24,#d97706)}
        .stat.g-pink  {background:linear-gradient(135deg,#f472b6,#be185d)}
        .stat.g-blue  {background:linear-gradient(135deg,#60a5fa,#2563eb)}

        .card.lift{transition:transform .2s ease,box-shadow .2s ease}
        .card.lift:hover{transform:translateY(-4px);box-shadow:0 18px 36px -16px rgba(16,12,30,.4)}
        .pill-soft{font-size:12px;color:var(--primary);background:#f3effe;padding:5px 12px;border-radius:30px;font-weight:600}
        .legend{display:flex;flex-direction:column;gap:9px;margin-top:16px}
        .legend .li{display:flex;align-items:center;gap:9px;font-size:13px}
        .legend .dot{width:11px;height:11px;border-radius:4px;flex-shrink:0}
        .legend .nm{font-weight:600}
        .legend .vl{margin-left:auto;color:var(--muted);font-weight:700}
        /* TABLE */
        table{width:100%;border-collapse:collapse}
        thead th{text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);padding:12px 14px;border-bottom:2px solid var(--border);font-weight:700}
        tbody td{padding:13px 14px;border-bottom:1px solid #f1f1f4;font-size:14px}
        tbody tr:hover{background:#faf9fd}
        .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700}
        .badge.active{background:#dcfce7;color:#15803d}
        .badge.trial{background:#fef9c3;color:#a16207}
        .badge.suspended,.badge.cancelled{background:#fee2e2;color:#b91c1c}
        .badge.expired,.badge.pending{background:#f3f4f6;color:#6b7280}
        .gym-mini{display:flex;align-items:center;gap:10px}
        .gym-logo{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px}
        .alert{padding:13px 18px;border-radius:11px;margin-bottom:18px;font-size:14px;font-weight:500;display:flex;align-items:center;gap:10px}
        .alert.success{background:#dcfce7;color:#15803d}
        .alert.error{background:#fee2e2;color:#b91c1c}
        .form-group{margin-bottom:16px}
        .form-group label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
        .form-control{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit;transition:.2s}
        .form-control:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.12)}
        .pagination{display:flex;gap:6px;margin-top:20px;list-style:none;flex-wrap:wrap}
        .pagination a,.pagination span{padding:8px 13px;border-radius:9px;border:1px solid var(--border);font-size:13px;color:var(--text);background:#fff}
        .pagination .active span{background:var(--primary);color:#fff;border-color:var(--primary)}
        .empty{text-align:center;padding:50px 20px;color:var(--muted)}
        .empty i{font-size:42px;margin-bottom:14px;opacity:.4}
        @media(max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fas fa-crown"></i></div>
            <div><h3>GymSaaS Pro</h3><span>Super Admin</span></div>
        </div>

        <div class="nav-section">
            <div class="nav-title">Panel SaaS</div>
            <div class="nav-item"><a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active':'' }}"><i class="fas fa-gauge-high"></i> Dashboard</a></div>
            <div class="nav-item"><a href="{{ route('superadmin.gyms.index') }}" class="nav-link {{ request()->routeIs('superadmin.gyms.*') ? 'active':'' }}"><i class="fas fa-dumbbell"></i> Gimnasios</a></div>
            <div class="nav-item"><a href="{{ route('superadmin.plans.index') }}" class="nav-link {{ request()->routeIs('superadmin.plans.*') ? 'active':'' }}"><i class="fas fa-layer-group"></i> Planes SaaS</a></div>
            <div class="nav-item"><a href="{{ route('superadmin.subscriptions.index') }}" class="nav-link {{ request()->routeIs('superadmin.subscriptions.*') ? 'active':'' }}"><i class="fas fa-file-invoice-dollar"></i> Suscripciones</a></div>
            <div class="nav-item"><a href="{{ route('superadmin.reports.index') }}" class="nav-link {{ request()->routeIs('superadmin.reports.*') ? 'active':'' }}"><i class="fas fa-chart-pie"></i> Reportes</a></div>
            <div class="nav-item"><a href="{{ route('superadmin.coupons.index') }}" class="nav-link {{ request()->routeIs('superadmin.coupons.*') ? 'active':'' }}"><i class="fas fa-ticket"></i> Cupones</a></div>
            <div class="nav-item"><a href="{{ route('superadmin.announcements.create') }}" class="nav-link {{ request()->routeIs('superadmin.announcements.*') ? 'active':'' }}"><i class="fas fa-bullhorn"></i> Comunicados</a></div>
            @php
                $openTickets = 0;
                try { if(\Illuminate\Support\Facades\Schema::hasTable('support_tickets')) $openTickets = \App\Models\SupportTicket::where('status','open')->count(); } catch(\Throwable $e){}
            @endphp
            <div class="nav-item"><a href="{{ route('superadmin.support.index') }}" class="nav-link {{ request()->routeIs('superadmin.support.*') ? 'active':'' }}"><i class="fas fa-headset"></i> Soporte @if($openTickets>0)<span class="nav-badge-sa">{{ $openTickets }}</span>@endif</a></div>
        </div>

        <div class="nav-section">
            <div class="nav-title">Sistema</div>
            <div class="nav-item"><a href="{{ route('superadmin.settings.index') }}" class="nav-link {{ request()->routeIs('superadmin.settings.*') ? 'active':'' }}"><i class="fas fa-gear"></i> Configuración</a></div>
            <div class="nav-item"><a href="{{ route('landing') }}" target="_blank" class="nav-link"><i class="fas fa-globe"></i> Ver landing</a></div>
        </div>

        <div class="sidebar-footer">
            <div class="su-user">
                <div class="su-avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                <div><div class="n">{{ auth()->user()->name }}</div><div class="r">Super Admin</div></div>
                <a href="{{ route('logout') }}" class="su-logout" title="Cerrar sesión"
                   onclick="event.preventDefault();document.getElementById('lf').submit();"><i class="fas fa-right-from-bracket"></i></a>
                <form id="lf" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <h1>@yield('page-title','Dashboard')</h1>
                <div class="crumb">@yield('breadcrumb', 'Panel Super Admin')</div>
            </div>
            <div class="topbar-right">@yield('actions')</div>
        </header>

        <main class="content">
            @if(session('success'))<div class="alert success"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert error"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>@endif
            @if($errors->any())
                <div class="alert error"><i class="fas fa-circle-exclamation"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
