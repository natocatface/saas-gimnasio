<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymSaaS Pro — Gestiona tu Gimnasio en la Nube</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:#fff;color:#111827;}

        /* NAV */
        nav{position:fixed;top:0;left:0;right:0;z-index:100;background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);border-bottom:1px solid #e5e7eb;padding:0 40px;height:68px;display:flex;align-items:center;justify-content:space-between;}
        .nav-logo{display:flex;align-items:center;gap:12px;text-decoration:none;}
        .nav-logo .icon{width:40px;height:40px;background:linear-gradient(135deg,#ec4899,#7c3aed);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;}
        .nav-logo h1{font-size:20px;font-weight:800;color:#111827;}
        .nav-links{display:flex;align-items:center;gap:8px;}
        .nav-links a{padding:8px 18px;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none;color:#6b7280;transition:all .2s;}
        .nav-links a:hover{color:#7c3aed;background:#f5f3ff;}
        .btn-nav-cta{background:linear-gradient(135deg,#7c3aed,#6d28d9)!important;color:white!important;box-shadow:0 4px 12px rgba(124,58,237,.3);}
        .btn-nav-cta:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(124,58,237,.4)!important;}

        /* HERO */
        .hero{min-height:100vh;background:linear-gradient(135deg,#0f0a1e 0%,#1e1045 40%,#2d1065 70%,#4c1d95 100%);display:flex;align-items:center;justify-content:center;text-align:center;padding:100px 24px 60px;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;width:600px;height:600px;background:radial-gradient(circle,rgba(236,72,153,.25) 0%,transparent 70%);top:-100px;right:-100px;border-radius:50%;}
        .hero::after{content:'';position:absolute;width:500px;height:500px;background:radial-gradient(circle,rgba(124,58,237,.2) 0%,transparent 70%);bottom:-100px;left:-100px;border-radius:50%;}
        .hero-content{position:relative;z-index:1;max-width:800px;}
        .hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.9);font-size:13px;font-weight:600;padding:8px 20px;border-radius:99px;margin-bottom:28px;letter-spacing:.3px;}
        .hero h1{font-size:clamp(40px,6vw,72px);font-weight:900;color:white;line-height:1.1;margin-bottom:24px;letter-spacing:-2px;}
        .hero h1 span{background:linear-gradient(135deg,#f0abfc,#c4b5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .hero p{font-size:18px;color:rgba(255,255,255,.7);max-width:560px;margin:0 auto 40px;line-height:1.7;}
        .hero-btns{display:flex;gap:16px;justify-content:center;flex-wrap:wrap;}
        .btn-hero-primary{background:linear-gradient(135deg,#ec4899,#db2777);color:white;padding:16px 36px;border-radius:12px;font-size:16px;font-weight:700;text-decoration:none;box-shadow:0 8px 24px rgba(236,72,153,.4);transition:all .3s;display:inline-flex;align-items:center;gap:10px;}
        .btn-hero-primary:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(236,72,153,.55);}
        .btn-hero-secondary{background:rgba(255,255,255,.1);color:white;padding:16px 36px;border-radius:12px;font-size:16px;font-weight:600;text-decoration:none;border:1.5px solid rgba(255,255,255,.2);backdrop-filter:blur(10px);transition:all .3s;display:inline-flex;align-items:center;gap:10px;}
        .btn-hero-secondary:hover{background:rgba(255,255,255,.18);}
        .hero-stats{display:flex;gap:40px;justify-content:center;margin-top:60px;flex-wrap:wrap;}
        .hero-stat{text-align:center;}
        .hero-stat .num{font-size:32px;font-weight:800;color:white;}
        .hero-stat .lbl{font-size:13px;color:rgba(255,255,255,.5);margin-top:4px;}

        /* FEATURES */
        section{padding:80px 40px;}
        .section-tag{display:inline-block;background:#f5f3ff;color:#7c3aed;font-size:12px;font-weight:700;padding:6px 16px;border-radius:99px;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:16px;}
        .section-title{font-size:clamp(28px,4vw,42px);font-weight:800;color:#111827;margin-bottom:14px;letter-spacing:-1px;}
        .section-sub{font-size:17px;color:#6b7280;max-width:560px;margin:0 auto 60px;line-height:1.7;}
        .text-center{text-align:center;}

        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1100px;margin:0 auto;}
        .feature-card{background:white;border:1.5px solid #e5e7eb;border-radius:18px;padding:28px;transition:all .2s;}
        .feature-card:hover{border-color:#c4b5fd;transform:translateY(-3px);box-shadow:0 12px 36px rgba(124,58,237,.1);}
        .feat-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:18px;}
        .feat-icon.purple{background:#f5f3ff;color:#7c3aed;}
        .feat-icon.pink{background:#fdf2f8;color:#db2777;}
        .feat-icon.green{background:#f0fdf4;color:#16a34a;}
        .feat-icon.blue{background:#eff6ff;color:#2563eb;}
        .feat-icon.orange{background:#fff7ed;color:#ea580c;}
        .feat-icon.teal{background:#f0fdfa;color:#0d9488;}
        .feature-card h3{font-size:17px;font-weight:700;color:#111827;margin-bottom:8px;}
        .feature-card p{font-size:14px;color:#6b7280;line-height:1.6;}

        /* PRICING */
        .pricing-section{background:#fafafa;}
        .pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1000px;margin:0 auto;}
        .pricing-card{background:white;border:2px solid #e5e7eb;border-radius:20px;padding:32px;text-align:center;transition:all .25s;position:relative;}
        .pricing-card.popular{border-color:#7c3aed;box-shadow:0 0 0 4px rgba(124,58,237,.1);}
        .popular-badge{position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#7c3aed,#ec4899);color:white;font-size:12px;font-weight:700;padding:6px 20px;border-radius:99px;white-space:nowrap;}
        .pricing-card:hover{transform:translateY(-4px);box-shadow:0 16px 40px rgba(0,0,0,.1);}
        .plan-name{font-size:18px;font-weight:700;color:#111827;margin-bottom:6px;}
        .plan-desc{font-size:13px;color:#6b7280;margin-bottom:24px;}
        .plan-price{font-size:48px;font-weight:900;color:#111827;line-height:1;}
        .plan-price span{font-size:18px;font-weight:500;color:#6b7280;}
        .plan-period{font-size:13px;color:#9ca3af;margin:6px 0 24px;}
        .plan-features{list-style:none;text-align:left;margin-bottom:28px;}
        .plan-features li{font-size:14px;color:#374151;padding:6px 0;display:flex;align-items:center;gap:10px;border-bottom:1px solid #f3f4f6;}
        .plan-features li:last-child{border-bottom:none;}
        .plan-features li i{color:#10b981;font-size:12px;flex-shrink:0;}
        .btn-plan{display:block;padding:14px;border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;text-align:center;transition:all .2s;}
        .btn-plan.outline{border:2px solid #7c3aed;color:#7c3aed;}
        .btn-plan.outline:hover{background:#7c3aed;color:white;}
        .btn-plan.filled{background:linear-gradient(135deg,#7c3aed,#6d28d9);color:white;box-shadow:0 6px 16px rgba(124,58,237,.3);}
        .btn-plan.filled:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(124,58,237,.45);}

        /* HOW IT WORKS */
        .steps{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;max-width:1000px;margin:0 auto;}
        .step{text-align:center;padding:20px;}
        .step-num{width:52px;height:52px;background:linear-gradient(135deg,#7c3aed,#ec4899);border-radius:14px;color:white;font-size:20px;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
        .step h3{font-size:16px;font-weight:700;color:#111827;margin-bottom:8px;}
        .step p{font-size:14px;color:#6b7280;line-height:1.6;}

        /* CTA */
        .cta-section{background:linear-gradient(135deg,#1e1045,#4c1d95,#7c3aed);padding:80px 40px;text-align:center;}
        .cta-section h2{font-size:clamp(28px,4vw,48px);font-weight:900;color:white;margin-bottom:16px;letter-spacing:-1px;}
        .cta-section p{font-size:17px;color:rgba(255,255,255,.7);margin-bottom:36px;}

        /* FOOTER */
        footer{background:#0f0a1e;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:14px;}
        footer a{color:rgba(255,255,255,.6);text-decoration:none;}

        @media(max-width:768px){
            nav{padding:0 20px;}
            .nav-links .hidden-mobile{display:none;}
            .features-grid,.pricing-grid{grid-template-columns:1fr;}
            .steps{grid-template-columns:repeat(2,1fr);}
            section{padding:60px 20px;}
        }
    </style>
</head>
<body>

<!-- ───── NAV ───── -->
<nav>
    <a href="/" class="nav-logo">
        <div class="icon">🏋️</div>
        <h1>GymSaaS Pro</h1>
    </a>
    <div class="nav-links">
        <a href="#features" class="hidden-mobile">Funciones</a>
        <a href="#pricing" class="hidden-mobile">Precios</a>
        <a href="{{ route('login') }}">Iniciar sesión</a>
        <a href="{{ route('register.gym') }}" class="btn-nav-cta">
            <i class="fas fa-rocket"></i> Prueba gratis
        </a>
    </div>
</nav>

<!-- ───── HERO ───── -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            🚀 Plataforma SaaS #1 para Gimnasios en LATAM
        </div>
        <h1>Gestiona tu gimnasio<br><span>de forma inteligente</span></h1>
        <p>Todo lo que necesitas para administrar socios, pagos, clases y entrenadores. Sin complicaciones, desde cualquier dispositivo.</p>
        <div class="hero-btns">
            <a href="{{ route('register.gym') }}" class="btn-hero-primary">
                <i class="fas fa-rocket"></i> Comenzar gratis — 30 días
            </a>
            <a href="#pricing" class="btn-hero-secondary">
                <i class="fas fa-tag"></i> Ver precios
            </a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat"><div class="num">500+</div><div class="lbl">Gimnasios activos</div></div>
            <div class="hero-stat"><div class="num">50k+</div><div class="lbl">Socios gestionados</div></div>
            <div class="hero-stat"><div class="num">99.9%</div><div class="lbl">Uptime garantizado</div></div>
            <div class="hero-stat"><div class="num">30 días</div><div class="lbl">Prueba gratuita</div></div>
        </div>
    </div>
</section>

<!-- ───── FEATURES ───── -->
<section id="features">
    <div class="text-center">
        <div class="section-tag">Funcionalidades</div>
        <h2 class="section-title">Todo lo que necesita tu gimnasio</h2>
        <p class="section-sub">Una plataforma completa diseñada específicamente para la gestión diaria de gimnasios profesionales.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feat-icon purple"><i class="fas fa-users"></i></div>
            <h3>Gestión de Socios</h3>
            <p>Registra socios, asigna planes, controla vencimientos y envía alertas automáticas de renovación.</p>
        </div>
        <div class="feature-card">
            <div class="feat-icon green"><i class="fas fa-credit-card"></i></div>
            <h3>Pagos y Facturación</h3>
            <p>Registra cobros, genera recibos, acepta múltiples métodos de pago y lleva contabilidad automática.</p>
        </div>
        <div class="feature-card">
            <div class="feat-icon blue"><i class="fas fa-calendar-alt"></i></div>
            <h3>Horario de Clases</h3>
            <p>Programa clases grupales, gestiona inscripciones y asigna entrenadores por especialidad.</p>
        </div>
        <div class="feature-card">
            <div class="feat-icon pink"><i class="fas fa-fingerprint"></i></div>
            <h3>Control de Asistencia</h3>
            <p>Check-in / Check-out en tiempo real. Estadísticas de visitas diarias, semanales y mensuales.</p>
        </div>
        <div class="feature-card">
            <div class="feat-icon orange"><i class="fas fa-chart-bar"></i></div>
            <h3>Reportes y Analítica</h3>
            <p>Dashboards en tiempo real con gráficos de ingresos, crecimiento de socios y asistencia.</p>
        </div>
        <div class="feature-card">
            <div class="feat-icon teal"><i class="fas fa-boxes"></i></div>
            <h3>Inventario</h3>
            <p>Controla equipos, alertas de stock mínimo y valor total del inventario actualizado.</p>
        </div>
    </div>
</section>

<!-- ───── HOW IT WORKS ───── -->
<section style="background:#f9fafb;">
    <div class="text-center">
        <div class="section-tag">¿Cómo funciona?</div>
        <h2 class="section-title">En 4 pasos simples</h2>
        <p class="section-sub" style="margin-bottom:50px;">Configura tu gimnasio y empieza a gestionar en minutos.</p>
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <h3>Regístrate</h3>
            <p>Crea tu cuenta con el nombre de tu gimnasio. 30 días de prueba gratuita.</p>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <h3>Configura</h3>
            <p>Agrega tus planes de membresía, entrenadores y horario de clases.</p>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <h3>Registra socios</h3>
            <p>Importa o añade socios manualmente. Asigna planes y controla pagos.</p>
        </div>
        <div class="step">
            <div class="step-num">4</div>
            <h3>Gestiona y crece</h3>
            <p>Usa los reportes para tomar decisiones y hacer crecer tu negocio.</p>
        </div>
    </div>
</section>

<!-- ───── PRICING ───── -->
<section id="pricing" class="pricing-section">
    <div class="text-center">
        <div class="section-tag">Precios</div>
        <h2 class="section-title">Planes para cada gimnasio</h2>
        <p class="section-sub">Sin contratos. Cancela cuando quieras. 30 días de prueba en todos los planes.</p>
    </div>
    <div class="pricing-grid">
        @foreach($plans as $plan)
        <div class="pricing-card {{ $plan->is_popular ? 'popular' : '' }}">
            @if($plan->is_popular)
                <div class="popular-badge">⭐ Más Popular</div>
            @endif
            <div class="plan-name">{{ $plan->name }}</div>
            <div class="plan-desc">{{ $plan->description }}</div>
            <div class="plan-price">{{ money($plan->price_monthly, 0) }}<span>/mes</span></div>
            <div class="plan-period">o {{ money($plan->price_yearly, 0) }}/año (ahorra {{ money($plan->price_monthly*12 - $plan->price_yearly, 0) }})</div>
            <ul class="plan-features">
                @foreach($plan->features_array as $feat)
                <li><i class="fas fa-check-circle"></i> {{ $feat }}</li>
                @endforeach
            </ul>
            <a href="{{ route('register.gym') }}?plan={{ $plan->slug }}"
               class="btn-plan {{ $plan->is_popular ? 'filled' : 'outline' }}">
                Comenzar prueba gratis
            </a>
        </div>
        @endforeach
    </div>
</section>

<!-- ───── CTA ───── -->
<section class="cta-section">
    <h2>¿Listo para transformar tu gimnasio?</h2>
    <p>Únete a más de 500 gimnasios que ya confían en GymSaaS Pro</p>
    <a href="{{ route('register.gym') }}" class="btn-hero-primary" style="display:inline-flex;">
        <i class="fas fa-rocket"></i> Comenzar 30 días gratis
    </a>
</section>

<!-- ───── FOOTER ───── -->
<footer>
    <p>© {{ date('Y') }} GymSaaS Pro · Todos los derechos reservados ·
    <a href="{{ route('login') }}">Iniciar Sesión</a> ·
    <a href="{{ route('register.gym') }}">Registrar Gimnasio</a></p>
</footer>

{{-- ───── CHATBOT FAQ ───── --}}
@include('landing._chatbot')

</body>
</html>
