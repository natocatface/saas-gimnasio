<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registra tu gimnasio · GymSaaS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{--primary:#7c3aed;--primary-dark:#6d28d9;--accent:#ec4899;--border:#e5e7eb;--muted:#6b7280;--danger:#ef4444}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex}
        .left{flex:1;background:linear-gradient(155deg,#0b0716,#2a1361 55%,#7c3aed);color:#fff;padding:60px 56px;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden}
        .left::after{content:"";position:absolute;width:420px;height:420px;background:radial-gradient(circle,rgba(236,72,153,.4),transparent 70%);bottom:-120px;right:-100px}
        .left .logo{display:flex;align-items:center;gap:13px;margin-bottom:40px}
        .left .logo .ic{width:48px;height:48px;background:linear-gradient(135deg,#ec4899,#7c3aed);border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:22px}
        .left h1{font-size:38px;font-weight:800;line-height:1.15;margin-bottom:18px;position:relative;z-index:1}
        .left p{font-size:16px;color:rgba(255,255,255,.7);max-width:420px;position:relative;z-index:1}
        .left ul{list-style:none;margin-top:34px;position:relative;z-index:1}
        .left li{display:flex;align-items:center;gap:12px;margin-bottom:16px;font-size:15px;color:rgba(255,255,255,.85)}
        .left li i{color:#34d399}
        .trial-pill{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);padding:8px 16px;border-radius:30px;font-size:13px;font-weight:600;margin-top:30px;position:relative;z-index:1;width:max-content}
        .right{width:560px;max-width:50%;padding:50px 52px;overflow-y:auto;display:flex;flex-direction:column;justify-content:center}
        .right h2{font-size:25px;font-weight:700}
        .right .sub{color:var(--muted);font-size:14px;margin:6px 0 26px}
        .alert{background:#fee2e2;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:18px}
        .alert div{margin:2px 0}
        .fg{margin-bottom:15px}
        .fg label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
        .fg input,.fg select{width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit;transition:.2s}
        .fg input:focus,.fg select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.12)}
        .row2{display:grid;grid-template-columns:1fr 1fr;gap:13px}
        .plans{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:16px}
        .plan{border:2px solid var(--border);border-radius:12px;padding:13px 11px;cursor:pointer;text-align:center;transition:.15s;position:relative}
        .plan:hover{border-color:#c4b5fd}
        .plan.sel{border-color:var(--primary);background:#f5f3ff}
        .plan .pn{font-weight:700;font-size:14px}
        .plan .pp{font-size:19px;font-weight:800;margin-top:4px}
        .plan .pp span{font-size:11px;font-weight:500;color:var(--muted)}
        .plan input{position:absolute;opacity:0}
        .terms{display:flex;gap:9px;align-items:flex-start;font-size:13px;color:var(--muted);margin:6px 0 20px}
        .terms a{color:var(--primary)}
        .btn{width:100%;padding:14px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:none;border-radius:11px;font-size:15px;font-weight:700;cursor:pointer;transition:.2s}
        .btn:hover{transform:translateY(-1px);box-shadow:0 10px 24px rgba(124,58,237,.35)}
        .foot{text-align:center;font-size:13px;color:var(--muted);margin-top:18px}
        .foot a{color:var(--primary);font-weight:600}
        @media(max-width:880px){.left{display:none}.right{width:100%;max-width:100%}}
    </style>
</head>
<body>
    <div class="left">
        <div class="logo"><div class="ic">🏋️</div><div><div style="font-weight:700;font-size:17px">GymSaaS Pro</div><div style="font-size:11px;letter-spacing:1.5px;color:rgba(255,255,255,.5)">GESTIÓN PREMIUM</div></div></div>
        <h1>Lleva tu gimnasio<br>al siguiente nivel.</h1>
        <p>Gestiona socios, pagos, clases, asistencia e inventario desde un solo lugar. Sin instalaciones, sin complicaciones.</p>
        <ul>
            <li><i class="fas fa-circle-check"></i> Control total de socios y membresías</li>
            <li><i class="fas fa-circle-check"></i> Cobros y reportes automáticos</li>
            <li><i class="fas fa-circle-check"></i> Asistencia y clases en tiempo real</li>
        </ul>
        <div class="trial-pill"><i class="fas fa-gift"></i> 14 días de prueba gratis · sin tarjeta</div>
    </div>

    <div class="right">
        <h2>Crea tu cuenta</h2>
        <div class="sub">Empieza tu prueba gratuita en menos de 1 minuto.</div>

        @if($errors->any())
            <div class="alert">@foreach($errors->all() as $e)<div><i class="fas fa-circle-exclamation"></i> {{ $e }}</div>@endforeach</div>
        @endif

        <form method="POST" action="{{ route('register.gym.store') }}">
            @csrf
            <div class="fg"><label>Nombre del gimnasio</label><input name="gym_name" value="{{ old('gym_name') }}" placeholder="Ej: PowerFit Center" required></div>
            <div class="row2">
                <div class="fg"><label>Tu nombre</label><input name="owner_name" value="{{ old('owner_name') }}" placeholder="Nombre completo" required></div>
                <div class="fg"><label>Teléfono</label><input name="phone" value="{{ old('phone') }}" placeholder="Opcional"></div>
            </div>
            <div class="fg"><label>Correo electrónico</label><input type="email" name="email" value="{{ old('email') }}" placeholder="tucorreo@ejemplo.com" required></div>
            <div class="row2">
                <div class="fg"><label>Contraseña</label><input type="password" name="password" placeholder="Mín. 6 caracteres" required></div>
                <div class="fg"><label>Confirmar</label><input type="password" name="password_confirmation" placeholder="Repite la contraseña" required></div>
            </div>

            <label style="font-size:13px;font-weight:600;margin-bottom:8px;display:block">Elige tu plan</label>
            <div class="plans">
                @foreach($plans as $p)
                    <label class="plan {{ ($selectedPlan ?? old('plan'))===$p->slug ? 'sel':'' }}" data-plan>
                        <input type="radio" name="saas_plan_id" value="{{ $p->id }}" @checked(($selectedPlan ?? old('saas_plan_id'))==$p->slug || old('saas_plan_id')==$p->id || $loop->first && !($selectedPlan))>
                        <div class="pn">{{ $p->name }}</div>
                        <div class="pp">{{ money($p->price_monthly,0) }}<span>/mes</span></div>
                    </label>
                @endforeach
            </div>

            <div class="terms">
                <input type="checkbox" name="terms" value="1" id="terms" style="margin-top:3px" required>
                <label for="terms">Acepto los <a href="#">términos y condiciones</a> y la <a href="#">política de privacidad</a>.</label>
            </div>

            <button class="btn"><i class="fas fa-rocket"></i> Empezar prueba gratis</button>
        </form>

        <div class="foot">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></div>
    </div>

    <script>
        document.querySelectorAll('[data-plan]').forEach(el=>{
            el.addEventListener('click',()=>{document.querySelectorAll('[data-plan]').forEach(p=>p.classList.remove('sel'));el.classList.add('sel');el.querySelector('input').checked=true;});
        });
    </script>
</body>
</html>
