<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GymSaaS Pro') - GymSaaS Pro</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:       #7c3aed;
            --primary-dark:  #6d28d9;
            --primary-light: #ede9fe;
            --accent:        #ec4899;
            --accent-light:  #fce7f3;
            --sidebar-bg:    #0f0a1e;
            --sidebar-hover: #1a1040;
            --sidebar-active:#7c3aed;
            --text-primary:  #111827;
            --text-secondary:#6b7280;
            --border:        #e5e7eb;
            --card-bg:       #ffffff;
            --body-bg:       #f3f4f6;
            --success:       #10b981;
            --warning:       #f59e0b;
            --danger:        #ef4444;
            --info:          #3b82f6;
            --sidebar-width: 260px;
            --topbar-h:      70px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        /* Sidebar Logo */
        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-decoration: none;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #ec4899, #7c3aed);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(236,72,153,0.3);
        }

        .brand-text {
            flex: 1;
        }

        .brand-text h3 {
            font-size: 16px;
            font-weight: 700;
            color: white;
            line-height: 1.2;
        }

        .brand-text span {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Nav Sections */
        .nav-section {
            padding: 20px 0;
        }

        .nav-section-title {
            padding: 0 20px 10px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.25);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Nav Item */
        .nav-item {
            margin: 2px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255,255,255,0.55);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: rgba(255,255,255,0.9);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary), #6d28d9);
            color: white;
            box-shadow: 0 4px 15px rgba(124,58,237,0.4);
        }

        .nav-link .nav-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            font-size: 15px;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .nav-link.active .nav-icon {
            background: rgba(255,255,255,0.15);
        }

        .nav-link:hover .nav-icon {
            background: rgba(255,255,255,0.1);
        }

        .nav-label { flex: 1; }

        .nav-badge {
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: var(--accent);
            color: white;
        }

        .nav-badge.success { background: var(--success); }
        .nav-badge.warning { background: var(--warning); }

        /* Sidebar Footer */
        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-user:hover { background: rgba(255,255,255,0.08); }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #ec4899, #7c3aed);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }

        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 13px; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: rgba(255,255,255,0.4); text-transform: capitalize; }

        .user-menu-btn {
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            padding: 4px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-h);
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 500;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        .topbar-left { flex: 1; }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 2px;
        }

        .breadcrumb a, .breadcrumb span {
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
        }

        .breadcrumb .sep { color: var(--border); }
        .breadcrumb .current { color: var(--primary); font-weight: 500; }

        /* Search */
        .topbar-search {
            position: relative;
        }

        .topbar-search input {
            width: 280px;
            padding: 10px 16px 10px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text-primary);
            background: var(--body-bg);
            outline: none;
            transition: all 0.2s;
        }

        .topbar-search input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
        }

        .topbar-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Topbar Actions */
        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .icon-btn {
            width: 42px;
            height: 42px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 16px;
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
        }

        .icon-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            border: 2px solid white;
        }

        .topbar-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #ec4899, #7c3aed);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            position: relative;
        }

        /* Dropdown */
        .dropdown { position: relative; }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            min-width: 200px;
            display: none;
            overflow: hidden;
            z-index: 999;
        }

        .dropdown:hover .dropdown-menu,
        .dropdown.open .dropdown-menu { display: block; }

        /* Puente invisible que cubre el hueco entre el avatar y el menú,
           para que el cursor pueda llegar sin que se cierre. */
        .dropdown-menu::before {
            content: "";
            position: absolute;
            top: -14px;
            left: 0;
            right: 0;
            height: 16px;
            background: transparent;
        }

        .dropdown-header {
            padding: 16px;
            background: linear-gradient(135deg, #f5f3ff, #fdf4ff);
            border-bottom: 1px solid var(--border);
        }

        .dropdown-header .dname { font-weight: 600; font-size: 14px; color: var(--text-primary); }
        .dropdown-header .demail { font-size: 12px; color: var(--text-secondary); }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            font-size: 14px;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.15s;
        }

        .dropdown-item:hover { background: var(--body-bg); }

        .dropdown-item i {
            width: 18px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .dropdown-item.danger { color: var(--danger); }
        .dropdown-item.danger i { color: var(--danger); }
        .dropdown-divider { height: 1px; background: var(--border); margin: 4px 0; }

        /* Page Content */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i { color: var(--primary); }
        .card-body { padding: 24px; }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-icon.purple { background: var(--primary-light); color: var(--primary); }
        .stat-icon.pink   { background: var(--accent-light);  color: var(--accent); }
        .stat-icon.green  { background: #d1fae5; color: var(--success); }
        .stat-icon.blue   { background: #dbeafe; color: var(--info); }
        .stat-icon.yellow { background: #fef3c7; color: var(--warning); }
        .stat-icon.red    { background: #fee2e2; color: var(--danger); }

        /* ── Gradient KPI Cards ─────────────────────────────────── */
        .kpi-card {
            border-radius: 18px;
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 130px;
            position: relative;
            overflow: hidden;
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 36px rgba(0,0,0,0.25);
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            width: 140px; height: 140px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            top: -50px; right: -30px;
        }
        .kpi-card::after {
            content: '';
            position: absolute;
            width: 90px; height: 90px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
            bottom: -20px; left: -20px;
        }
        .kpi-card.blue    { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #3b82f6 100%); }
        .kpi-card.teal    { background: linear-gradient(135deg, #065f46 0%, #059669 60%, #10b981 100%); }
        .kpi-card.violet  { background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 60%, #a78bfa 100%); }
        .kpi-card.rose    { background: linear-gradient(135deg, #9d174d 0%, #db2777 60%, #f472b6 100%); }
        .kpi-card.orange  { background: linear-gradient(135deg, #92400e 0%, #d97706 60%, #fbbf24 100%); }
        .kpi-card.indigo  { background: linear-gradient(135deg, #312e81 0%, #4f46e5 60%, #818cf8 100%); }

        .kpi-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative; z-index: 1;
        }
        .kpi-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            backdrop-filter: blur(8px);
            color: white;
        }
        .kpi-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: flex; align-items: center; gap: 4px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .kpi-body { position: relative; z-index: 1; margin-top: 14px; }
        .kpi-value {
            font-size: 32px;
            font-weight: 800;
            color: white;
            line-height: 1;
            letter-spacing: -1px;
        }
        .kpi-label {
            font-size: 13px;
            color: rgba(255,255,255,0.75);
            margin-top: 5px;
            font-weight: 500;
        }
        .kpi-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.55);
            margin-top: 4px;
            display: flex; align-items: center; gap: 5px;
        }
        .kpi-sub i { font-size: 10px; }

        .stat-info { flex: 1; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1; }
        .stat-label { font-size: 13px; color: var(--text-secondary); margin-top: 4px; font-weight: 500; }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.up   { color: var(--success); }
        .stat-change.down { color: var(--danger); }

        /* Tables */
        .table-container { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }

        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            background: var(--body-bg);
            border-bottom: 1px solid var(--border);
        }

        tbody td {
            padding: 14px 16px;
            font-size: 14px;
            color: var(--text-primary);
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }

        /* Badges */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info    { background: #dbeafe; color: #1e40af; }
        .badge-purple  { background: var(--primary-light); color: var(--primary-dark); }
        .badge-gray    { background: #f3f4f6; color: #6b7280; }
        .badge-pink    { background: var(--accent-light); color: #9d174d; }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(124,58,237,0.3);
        }

        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(124,58,237,0.4); }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #db2777);
            color: white;
            box-shadow: 0 4px 12px rgba(236,72,153,0.3);
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1.5px solid var(--primary);
        }

        .btn-outline:hover { background: var(--primary-light); }

        .btn-ghost {
            background: var(--body-bg);
            color: var(--text-secondary);
            border: 1.5px solid var(--border);
        }

        .btn-ghost:hover { background: var(--border); color: var(--text-primary); }

        .btn-danger { background: var(--danger); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-sm { padding: 7px 14px; font-size: 13px; }
        .btn-icon { padding: 8px; width: 36px; height: 36px; justify-content: center; }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .alert-info    { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

        /* Grid helpers */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; }

        /* Form controls */
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            background: white;
            outline: none;
            transition: all 0.2s;
        }

        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
        .form-group { margin-bottom: 18px; }

        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* Flex utilities */
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .text-sm { font-size: 13px; }
        .text-xs { font-size: 11px; }
        .text-muted { color: var(--text-secondary); }
        .fw-600 { font-weight: 600; }
        .fw-700 { font-weight: 700; }

        /* Avatar inline */
        .member-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            color: white;
            font-weight: 700;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Progress bar */
        .progress {
            height: 8px;
            background: var(--body-bg);
            border-radius: 99px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transition: width 0.5s ease;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .page-link {
            padding: 8px 13px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            font-size: 14px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
        }

        .page-link:hover { border-color: var(--primary); color: var(--primary); }
        .page-link.active { background: var(--primary); border-color: var(--primary); color: white; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.open { display: flex; }

        .modal {
            background: white;
            border-radius: 20px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalIn 0.3s ease;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title { font-size: 18px; font-weight: 700; color: var(--text-primary); }

        .modal-close {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            background: var(--body-bg);
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover { background: #fee2e2; color: var(--danger); }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; gap: 10px; justify-content: flex-end; }

        /* ============================================================
           RESPONSIVE — Desktop / Tablet / Mobile
        ============================================================ */

        /* ── Tablet (≤ 1100px) ─────────────────────────────────────── */
        @media (max-width: 1100px) {
            :root { --sidebar-width: 220px; }

            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .grid-3     { grid-template-columns: repeat(2, 1fr); }
            .grid-4     { grid-template-columns: repeat(2, 1fr); }

            .topbar-search input { width: 200px; }

            .page-content { padding: 20px; }
        }

        /* ── Sidebar colapsable (≤ 900px) ──────────────────────────── */
        @media (max-width: 900px) {
            :root { --sidebar-width: 260px; }

            /* Sidebar se oculta fuera de pantalla */
            .sidebar {
                transform: translateX(-100%);
                z-index: 1100;
                box-shadow: none;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            /* Clase .open la agrega JS al hacer click en hamburguesa */
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(0,0,0,0.35);
            }

            /* Overlay oscuro detrás del sidebar */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1099;
                backdrop-filter: blur(2px);
            }
            .sidebar-overlay.active { display: block; }

            /* Contenido ocupa toda la pantalla */
            .main-content { margin-left: 0; }

            /* Botón hamburguesa visible */
            #menuToggle { display: flex !important; }

            .topbar-search { display: none; }
            .topbar { padding: 0 16px; gap: 12px; }
            .page-title { font-size: 17px; }

            .page-content { padding: 16px; }

            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .grid-2  { grid-template-columns: 1fr; }
            .grid-3  { grid-template-columns: 1fr; }
            .grid-4  { grid-template-columns: repeat(2, 1fr); }
        }

        /* ── Mobile (≤ 600px) ───────────────────────────────────────── */
        @media (max-width: 600px) {
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .grid-4 { grid-template-columns: 1fr 1fr; }

            .stat-card { padding: 14px; gap: 10px; }
            .stat-icon { width: 44px; height: 44px; font-size: 18px; flex-shrink: 0; }
            .stat-value { font-size: 22px; }

            .page-content { padding: 12px; }
            .card-header  { padding: 14px 16px; flex-wrap: wrap; gap: 8px; }
            .card-body    { padding: 16px; }

            .topbar { height: 58px; padding: 0 12px; gap: 8px; }
            .topbar-avatar { width: 36px; height: 36px; font-size: 13px; }
            .icon-btn { width: 36px; height: 36px; font-size: 14px; }
            .page-title { font-size: 16px; }
            .breadcrumb { display: none; }

            /* Tablas scroll horizontal */
            .table-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            table { min-width: 560px; }

            /* Formularios 1 columna */
            .grid-2 { grid-template-columns: 1fr !important; }

            /* Modales full screen en móvil */
            .modal { width: 100%; max-width: 100%; border-radius: 20px 20px 0 0; margin: auto 0 0 0; max-height: 92vh; }
            .modal-overlay { align-items: flex-end; }

            /* Toast más pequeño */
            .toast { min-width: 280px; max-width: calc(100vw - 32px); }
            .toast-container { left: 16px; right: 16px; bottom: 16px; }

            /* Botones en móvil */
            .btn { padding: 9px 14px; font-size: 13px; }
            .btn-sm { padding: 6px 10px; font-size: 12px; }

            /* Sidebar brand texto más pequeño */
            .brand-text h3 { font-size: 14px; }

            /* Pagination centrada */
            .pagination { justify-content: center; flex-wrap: wrap; gap: 4px; }
        }

        /* ── KPI Cards responsive ───────────────────────────────────── */
        @media (max-width: 900px) {
            .kpi-card   { min-height: 110px; padding: 16px 18px; }
            .kpi-value  { font-size: 26px; }
        }
        @media (max-width: 600px) {
            .kpi-card   { min-height: 100px; padding: 14px 16px; border-radius: 14px; }
            .kpi-value  { font-size: 22px; }
            .kpi-label  { font-size: 12px; }
            .kpi-sub    { display: none; }
            .kpi-icon   { width: 36px; height: 36px; font-size: 16px; border-radius: 10px; }
            .kpi-badge  { font-size: 10px; padding: 3px 8px; }
        }

        /* ── Pantalla muy pequeña (≤ 380px) ────────────────────────── */
        @media (max-width: 380px) {
            .stats-grid { grid-template-columns: 1fr; }
            .grid-4     { grid-template-columns: 1fr; }
            .stat-value { font-size: 20px; }
            .kpi-value  { font-size: 20px; }
            .topbar-actions .icon-btn:not(:last-child) { display: none; }
        }

        /* Toasts */
        .toast-container {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: white;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 320px;
            max-width: 400px;
            animation: toastIn 0.3s ease;
            border-left: 4px solid var(--primary);
        }

        .toast.success { border-left-color: var(--success); }
        .toast.error   { border-left-color: var(--danger); }
        .toast.warning { border-left-color: var(--warning); }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .toast-icon { font-size: 20px; }
        .toast-msg { flex: 1; font-size: 14px; color: var(--text-primary); }
        .toast-close { color: var(--text-secondary); cursor: pointer; background: none; border: none; font-size: 16px; }
    </style>

    @isset($currentGym)
    <style>
        :root {
            --primary:      {{ $currentGym->primary_color ?? '#7c3aed' }};
            --sidebar-active:{{ $currentGym->primary_color ?? '#7c3aed' }};
        }
    </style>
    @endisset

    @stack('styles')
</head>
<body>

<!-- Overlay para cerrar sidebar en móvil/tablet -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ===== SIDEBAR ===== -->
<nav class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon" @isset($currentGym) style="background:linear-gradient(135deg,{{ $currentGym->primary_color ?? '#ec4899' }},#7c3aed);overflow:hidden" @endisset>@if(isset($currentGym) && $currentGym->logo)<img src="{{ asset('storage/'.$currentGym->logo) }}" alt="logo" style="width:100%;height:100%;object-fit:cover">@else🏋️@endif</div>
        <div class="brand-text">
            <h3>{{ $currentGym->name ?? 'GymSaaS Pro' }}</h3>
            <span>{{ isset($gymPlan) ? 'Plan '.$gymPlan->name : 'Gestión Premium' }}</span>
        </div>
    </a>

    @isset($currentGym)
        @if($currentGym->isOnTrial())
            <div style="margin:12px;padding:12px 14px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:12px;color:#fff;font-size:12px;line-height:1.5">
                <div style="font-weight:700;display:flex;align-items:center;gap:6px"><i class="fas fa-hourglass-half"></i> Prueba gratis</div>
                <div style="opacity:.9;margin-top:3px">Te quedan <strong>{{ $currentGym->trialDaysLeft() }} días</strong>. Activa tu plan para no perder acceso.</div>
            </div>
        @endif
    @endisset

    <!-- Main Navigation -->
    <div class="nav-section">
        <div class="nav-section-title">Principal</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-th-large"></i></div>
                <span class="nav-label">Dashboard</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Socios & Membresías</div>

        <div class="nav-item">
            <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-users"></i></div>
                <span class="nav-label">Socios</span>
                <span class="nav-badge">{{ \App\Models\Member::where('status','activo')->count() }}</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('plans.index') }}" class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
                <span class="nav-label">Planes</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
                <span class="nav-label">Pagos</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Operaciones</div>

        <div class="nav-item">
            <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-dumbbell"></i></div>
                <span class="nav-label">Clases</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('trainers.index') }}" class="nav-link {{ request()->routeIs('trainers.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-user-tie"></i></div>
                <span class="nav-label">Entrenadores</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-fingerprint"></i></div>
                <span class="nav-label">Asistencia</span>
            </a>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Administración</div>

        <div class="nav-item">
            <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-boxes"></i></div>
                <span class="nav-label">Inventario</span>
                @php $lowStock = \App\Models\Inventory::whereColumn('quantity','<=','min_quantity')->count(); @endphp
                @if($lowStock > 0)
                    <span class="nav-badge warning">{{ $lowStock }}</span>
                @endif
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-chart-bar"></i></div>
                <span class="nav-label">Reportes</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-cog"></i></div>
                <span class="nav-label">Configuración</span>
            </a>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="nav-item">
            <a href="{{ route('sunat.docs.index') }}" class="nav-link {{ request()->routeIs('sunat.docs.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-file-invoice"></i></div>
                <span class="nav-label">Comprobantes</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('sunat.settings.edit') }}" class="nav-link {{ request()->routeIs('sunat.settings.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-file-signature"></i></div>
                <span class="nav-label">Fact. Electrónica</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('backup.index') }}" class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-database"></i></div>
                <span class="nav-label">Respaldos</span>
            </a>
        </div>
        @endif
    </div>

    <div class="nav-section">
        <div class="nav-section-title">Mi Cuenta</div>

        @if(auth()->user()->isAdmin())
        <div class="nav-item">
            <a href="{{ route('gym.profile.edit') }}" class="nav-link {{ request()->routeIs('gym.profile.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-store"></i></div>
                <span class="nav-label">Mi Gimnasio</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-user-shield"></i></div>
                <span class="nav-label">Equipo</span>
            </a>
        </div>
        @endif

        <div class="nav-item">
            <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <span class="nav-label">Facturación</span>
                @isset($currentGym)
                    @if($currentGym->isOnTrial())<span class="nav-badge warning">Prueba</span>@endif
                @endisset
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('support.index') }}" class="nav-link {{ request()->routeIs('support.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-headset"></i></div>
                <span class="nav-label">Soporte</span>
            </a>
        </div>
    </div>

    <!-- Sidebar Footer - User -->
    <div class="sidebar-footer">
        <div class="sidebar-user" onclick="toggleDropdownSidebar()">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
            </div>
            <button class="user-menu-btn"><i class="fas fa-ellipsis-v"></i></button>
        </div>
    </div>
</nav>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

    @if(session('impersonator_id'))
    <div style="background:linear-gradient(135deg,#111827,#374151);color:#fff;padding:10px 28px;display:flex;align-items:center;gap:12px;font-size:13px;font-weight:500">
        <i class="fas fa-user-secret" style="color:#fbbf24"></i>
        Estás viendo el sistema como <strong>{{ auth()->user()->gymnasium->name ?? 'gimnasio' }}</strong> (modo soporte).
        <form method="POST" action="{{ route('impersonation.leave') }}" style="margin-left:auto">
            @csrf
            <button style="background:#fbbf24;color:#111827;border:none;padding:6px 14px;border-radius:8px;font-weight:700;font-size:12px;cursor:pointer">
                <i class="fas fa-arrow-left"></i> Volver al Super Admin
            </button>
        </form>
    </div>
    @endif

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left" style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
            <button class="icon-btn" onclick="toggleSidebar()" style="display:none;flex-shrink:0;" id="menuToggle">
                <i class="fas fa-bars" id="menuIcon"></i>
            </button>
            <div style="min-width:0;">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                    <span class="sep">/</span>
                    @yield('breadcrumb', '<span class="current">Dashboard</span>')
                </nav>
            </div>
        </div>

        <!-- Search -->
        <div class="topbar-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar socios, pagos...">
        </div>

        <!-- Actions -->
        <div class="topbar-actions">
            <a href="{{ route('attendance.create') }}" class="icon-btn" title="Registrar Asistencia">
                <i class="fas fa-fingerprint"></i>
            </a>
            @php
                $notifs = collect(); $unreadCount = 0;
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('gym_notifications')) {
                        $notifs = \App\Models\GymNotification::forUser(auth()->id())->latest()->take(8)->get();
                        $unreadCount = $notifs->whereNull('read_at')->count();
                    }
                } catch (\Throwable $e) { /* tabla aún no creada */ }
            @endphp
            <div class="dropdown">
                <div class="icon-btn" title="Notificaciones" style="position:relative">
                    <i class="fas fa-bell"></i>
                    @if($unreadCount > 0)<span class="notif-dot"></span>@endif
                </div>
                <div class="dropdown-menu" style="min-width:330px;right:0">
                    <div class="dropdown-header" style="display:flex;align-items:center;justify-content:space-between">
                        <div class="dname">Notificaciones</div>
                        @if($unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">@csrf
                                <button style="background:none;border:none;color:var(--primary);font-size:12px;font-weight:600;cursor:pointer">Marcar leídas</button>
                            </form>
                        @endif
                    </div>
                    <div style="max-height:340px;overflow-y:auto">
                        @forelse($notifs as $n)
                            <a href="{{ $n->url ? route('notifications.read',$n) : '#' }}" class="dropdown-item" style="align-items:flex-start;gap:11px;padding:13px 16px;{{ $n->read_at ? '' : 'background:#faf8ff' }}">
                                <span style="width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;background:{{ $n->color ?? '#7c3aed' }}"><i class="fas {{ $n->icon ?? 'fa-bell' }}"></i></span>
                                <span style="flex:1">
                                    <span style="display:block;font-weight:600;font-size:13.5px;color:var(--text-primary)">{{ $n->title }}</span>
                                    <span style="display:block;font-size:12.5px;color:var(--text-secondary);margin-top:2px">{{ $n->body }}</span>
                                    <span style="display:block;font-size:11px;color:var(--text-secondary);margin-top:4px">{{ $n->created_at?->diffForHumans() }}</span>
                                </span>
                            </a>
                        @empty
                            <div style="padding:34px 16px;text-align:center;color:var(--text-secondary);font-size:13px">
                                <i class="fas fa-bell-slash" style="font-size:26px;opacity:.4;display:block;margin-bottom:8px"></i>
                                Sin notificaciones
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="dropdown">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="dropdown-menu">
                    <div class="dropdown-header">
                        <div class="dname">{{ auth()->user()->name ?? 'Administrador' }}</div>
                        <div class="demail">{{ auth()->user()->email ?? 'admin@gymsaas.com' }}</div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user-circle"></i> Mi Perfil
                    </a>
                    <a href="{{ route('settings.index') }}" class="dropdown-item">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Alerts -->
    <div style="padding: 0 28px; margin-top: 16px;">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="page-content">
        @yield('content')
    </main>

</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
// ── Dropdowns del topbar (clic para fijar, clic afuera para cerrar) ──
document.addEventListener('DOMContentLoaded', function () {
    const dds = document.querySelectorAll('.topbar .dropdown');
    dds.forEach(function (dd) {
        const trigger = dd.querySelector('.topbar-avatar, .icon-btn');
        if (trigger) {
            trigger.style.cursor = 'pointer';
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                // cerrar los demás
                dds.forEach(o => { if (o !== dd) o.classList.remove('open'); });
                dd.classList.toggle('open');
            });
        }
    });
    document.addEventListener('click', function (e) {
        dds.forEach(function (dd) { if (!dd.contains(e.target)) dd.classList.remove('open'); });
    });
});

// ── Sidebar ──────────────────────────────────────────────────
const sidebar  = document.getElementById('sidebar');
const overlay  = document.getElementById('sidebarOverlay');
const menuBtn  = document.getElementById('menuToggle');
const menuIcon = document.getElementById('menuIcon');

function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('active');
    menuIcon.className = 'fas fa-times';
    document.body.style.overflow = 'hidden'; // evita scroll del fondo
}

function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    menuIcon.className = 'fas fa-bars';
    document.body.style.overflow = '';
}

function toggleSidebar() {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
}

// Cerrar con tecla Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

// Mostrar/ocultar botón hamburguesa según breakpoint
function checkWidth() {
    const isMobile = window.innerWidth <= 900;
    menuBtn.style.display = isMobile ? 'flex' : 'none';
    if (!isMobile) closeSidebar(); // cierra al pasar a desktop
}
window.addEventListener('resize', checkWidth);
checkWidth();

// ── Toasts ────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const icons = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <span class="toast-icon">${icons[type] || 'ℹ️'}</span>
        <span class="toast-msg">${msg}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity='0'; setTimeout(()=>toast.remove(),300); }, 4000);
}

// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(a => {
    setTimeout(() => { a.style.opacity='0'; setTimeout(()=>a.remove(),300); }, 5000);
});
</script>

@stack('scripts')
</body>
</html>
