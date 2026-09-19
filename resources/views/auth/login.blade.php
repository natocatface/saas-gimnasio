<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymSaaS Pro - Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f0a1e;
            display: flex;
            overflow: hidden;
        }

        /* Left Panel */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #1a0533 0%, #2d1065 40%, #4c1d95 70%, #7c3aed 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(236,72,153,0.3) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(139,92,246,0.4) 0%, transparent 70%);
            bottom: -50px;
            left: -50px;
            border-radius: 50%;
        }

        .gym-logo {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-bottom: 50px;
        }

        .gym-logo .logo-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 20px 60px rgba(236,72,153,0.4);
            font-size: 40px;
            color: white;
        }

        .gym-logo h1 {
            font-size: 36px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
        }

        .gym-logo span {
            color: #f0abfc;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 3px;
            text-transform: uppercase;
            display: block;
            margin-top: 6px;
        }

        .features-list {
            position: relative;
            z-index: 1;
            list-style: none;
            width: 100%;
            max-width: 380px;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.85);
            font-size: 15px;
        }

        .features-list li:last-child { border-bottom: none; }

        .feat-icon {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f0abfc;
            font-size: 16px;
            flex-shrink: 0;
            backdrop-filter: blur(10px);
        }

        .feat-text strong { display: block; color: white; font-weight: 600; }
        .feat-text small { color: rgba(255,255,255,0.5); font-size: 12px; }

        .stats-row {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 30px;
            margin-top: 50px;
        }

        .stat-item {
            text-align: center;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 20px 28px;
        }

        .stat-item .num {
            font-size: 28px;
            font-weight: 800;
            color: white;
            background: linear-gradient(135deg, #f0abfc, #c4b5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-item .lbl {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            margin-top: 4px;
        }

        /* Right Panel */
        .right-panel {
            width: 480px;
            background: #fafafa;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            position: relative;
        }

        .login-header { margin-bottom: 40px; }

        .login-header h2 {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #6b7280;
            font-size: 15px;
        }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background: white;
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124,58,237,0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrap input { accent-color: #7c3aed; width: 16px; height: 16px; }

        .checkbox-wrap span { font-size: 14px; color: #6b7280; }

        .forgot-link {
            font-size: 14px;
            color: #7c3aed;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { color: #6d28d9; }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.3px;
            box-shadow: 0 8px 24px rgba(124,58,237,0.35);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(124,58,237,0.5);
        }

        .btn-login:active { transform: translateY(0); }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span { font-size: 13px; color: #9ca3af; }

        .demo-accounts {
            background: linear-gradient(135deg, #f5f3ff, #fdf4ff);
            border: 1px solid #e9d5ff;
            border-radius: 12px;
            padding: 20px;
        }

        .demo-accounts h4 {
            font-size: 12px;
            font-weight: 700;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }

        .demo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(139,92,246,0.1);
            cursor: pointer;
            transition: all 0.2s;
        }

        .demo-item:last-child { border-bottom: none; padding-bottom: 0; }

        .demo-item:hover .demo-email { color: #7c3aed; }

        .demo-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-admin { background: #ede9fe; color: #7c3aed; }
        .badge-trainer { background: #dcfce7; color: #166534; }
        .badge-recep { background: #fce7f3; color: #9d174d; }

        .demo-email { font-size: 13px; color: #6b7280; }

        .error-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #9ca3af;
        }

        /* Tablet */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 48px 40px; }
        }

        /* Mobile grande */
        @media (max-width: 600px) {
            body { overflow-y: auto; align-items: flex-start; }
            .right-panel {
                width: 100%;
                padding: 36px 24px 48px;
                min-height: 100vh;
                border-radius: 0;
            }
            .login-header h2 { font-size: 24px; }
            .btn-login { padding: 13px; font-size: 15px; }
            .demo-accounts { padding: 16px; }
            .stats-row { gap: 12px; }
            .stat-item { padding: 14px 18px; }
        }

        /* Mobile pequeño */
        @media (max-width: 380px) {
            .right-panel { padding: 28px 16px 40px; }
            .login-header h2 { font-size: 22px; }
            .form-control { padding: 12px 14px 12px 40px; font-size: 14px; }
        }
    </style>
</head>
<body>

<!-- Left Panel -->
<div class="left-panel">
    <div class="gym-logo">
        <div class="logo-icon">🏋️</div>
        <h1>GymSaaS Pro</h1>
        <span>Sistema de Gestión Premium</span>
    </div>

    <ul class="features-list">
        <li>
            <div class="feat-icon"><i class="fas fa-users"></i></div>
            <div class="feat-text">
                <strong>Gestión de Socios</strong>
                <small>Control total de membresías y pagos</small>
            </div>
        </li>
        <li>
            <div class="feat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="feat-text">
                <strong>Horarios y Clases</strong>
                <small>Programa tus clases y entrenadores</small>
            </div>
        </li>
        <li>
            <div class="feat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="feat-text">
                <strong>Reportes Avanzados</strong>
                <small>Métricas y estadísticas en tiempo real</small>
            </div>
        </li>
        <li>
            <div class="feat-icon"><i class="fas fa-mobile-alt"></i></div>
            <div class="feat-text">
                <strong>Acceso Multiplataforma</strong>
                <small>Disponible en cualquier dispositivo</small>
            </div>
        </li>
    </ul>

    <div class="stats-row">
        <div class="stat-item">
            <div class="num">500+</div>
            <div class="lbl">Socios</div>
        </div>
        <div class="stat-item">
            <div class="num">98%</div>
            <div class="lbl">Satisfacción</div>
        </div>
        <div class="stat-item">
            <div class="num">24/7</div>
            <div class="lbl">Soporte</div>
        </div>
    </div>
</div>

<!-- Right Panel -->
<div class="right-panel">
    <div class="login-header">
        <h2>Bienvenido de vuelta 👋</h2>
        <p>Ingresa tus credenciales para acceder al sistema</p>
    </div>

    @if(session('error'))
        <div class="error-alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="form-group">
            <label>Correo Electrónico</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control"
                    placeholder="usuario@gymsaas.com"
                    value="{{ old('email') }}" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control"
                    placeholder="••••••••" required id="passwordInput">
            </div>
        </div>

        <div class="form-options">
            <label class="checkbox-wrap">
                <input type="checkbox" name="remember">
                <span>Recordarme</span>
            </label>
            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i>
            Iniciar Sesión
        </button>
    </form>

    <div class="divider"><span>Cuentas de demostración</span></div>

    <div class="demo-accounts">
        <h4>🔑 Acceso Rápido</h4>
        <div class="demo-item" onclick="fillLogin('admin@gymsaas.com','password')">
            <span class="demo-email">admin@gymsaas.com</span>
            <span class="demo-badge badge-admin">Admin</span>
        </div>
        <div class="demo-item" onclick="fillLogin('trainer@gymsaas.com','password')">
            <span class="demo-email">trainer@gymsaas.com</span>
            <span class="demo-badge badge-trainer">Entrenador</span>
        </div>
        <div class="demo-item" onclick="fillLogin('recep@gymsaas.com','password')">
            <span class="demo-email">recep@gymsaas.com</span>
            <span class="demo-badge badge-recep">Recepción</span>
        </div>
    </div>

    <p class="footer-text" style="margin-bottom:6px">¿Eres socio del gimnasio? <a href="{{ route('portal.login') }}" style="color:var(--primary,#7c3aed);font-weight:600;text-decoration:none">Entra a tu portal</a></p>
    <p class="footer-text">© 2026 GymSaaS Pro · Todos los derechos reservados</p>
</div>

<script>
function fillLogin(email, pass) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = pass;
}
</script>
</body>
</html>
