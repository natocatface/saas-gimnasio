<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suscripción · {{ $gym->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{--primary:#7c3aed;--primary-dark:#6d28d9;--accent:#ec4899;--border:#e5e7eb;--muted:#6b7280}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0b0716,#2a1361);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px}
        .box{background:#fff;border-radius:22px;max-width:840px;width:100%;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.4)}
        .head{padding:38px 40px 30px;text-align:center;border-bottom:1px solid var(--border)}
        .head .ic{width:74px;height:74px;border-radius:20px;background:linear-gradient(135deg,#f59e0b,#ef4444);display:flex;align-items:center;justify-content:center;font-size:32px;color:#fff;margin:0 auto 18px}
        .head h1{font-size:24px;font-weight:800}
        .head p{color:var(--muted);font-size:15px;margin-top:8px;max-width:520px;margin-left:auto;margin-right:auto}
        .body{padding:30px 40px 36px}
        @if(auth()->user()->isAdmin())
        .plans{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:8px}
        @endif
        .pcard{border:2px solid var(--border);border-radius:15px;padding:22px 18px;text-align:center;position:relative}
        .pcard.pop{border-color:var(--accent)}
        .pcard .tag{position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:var(--accent);color:#fff;font-size:10px;font-weight:700;padding:4px 12px;border-radius:20px}
        .pcard .nm{font-weight:700;font-size:16px}
        .pcard .pr{font-size:30px;font-weight:800;margin:8px 0}.pcard .pr span{font-size:13px;font-weight:500;color:var(--muted)}
        .pcard ul{list-style:none;text-align:left;margin:12px 0;font-size:13px;color:var(--muted)}
        .pcard li{padding:3px 0}.pcard li i{color:#10b981;margin-right:6px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;border:none;border-radius:11px;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;text-decoration:none}
        .btn:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(124,58,237,.35)}
        .foot{text-align:center;margin-top:26px;color:var(--muted);font-size:13px}
        .foot a{color:var(--primary);font-weight:600;text-decoration:none}
        .note{background:#f9fafb;border:1px solid var(--border);border-radius:13px;padding:20px;text-align:center;color:var(--muted);font-size:14px}
    </style>
</head>
<body>
    <div class="box">
        <div class="head">
            <div class="ic"><i class="fas fa-lock"></i></div>
            <h1>{{ $reason }}</h1>
            <p>
                @if(auth()->user()->isAdmin())
                    Elige un plan para reactivar <b>{{ $gym->name }}</b> y seguir gestionando tu gimnasio.
                @else
                    Contacta al administrador de <b>{{ $gym->name }}</b> para reactivar la suscripción.
                @endif
            </p>
        </div>
        <div class="body">
            @if(session('error'))<div class="note" style="color:#b91c1c;background:#fee2e2;border-color:#fecaca;margin-bottom:16px">{{ session('error') }}</div>@endif

            @if(auth()->user()->isAdmin())
                <div class="plans">
                    @foreach($plans as $p)
                        <div class="pcard {{ $p->is_popular?'pop':'' }}">
                            @if($p->is_popular)<div class="tag">POPULAR</div>@endif
                            <div class="nm">{{ $p->name }}</div>
                            <div class="pr">{{ money($p->price_monthly,0) }}<span>/mes</span></div>
                            <ul>@foreach(array_slice($p->features_array,0,4) as $f)<li><i class="fas fa-check"></i>{{ $f }}</li>@endforeach</ul>
                            <form method="POST" action="{{ route('billing.change') }}">
                                @csrf
                                <input type="hidden" name="saas_plan_id" value="{{ $p->id }}">
                                <input type="hidden" name="billing_cycle" value="monthly">
                                <button class="btn">Activar plan</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="note"><i class="fas fa-circle-info"></i> Tu cuenta sigue activa, pero el gimnasio está en pausa hasta que el administrador renueve el plan.</div>
            @endif

            @if(session('impersonator_id'))
                <form method="POST" action="{{ route('impersonation.leave') }}" style="text-align:center;margin-top:8px">
                    @csrf
                    <button class="btn" style="width:auto;background:#111827"><i class="fas fa-arrow-left"></i> Volver al Super Admin</button>
                </form>
            @endif

            <div class="foot">
                ¿Necesitas ayuda? Escríbenos a <a href="mailto:soporte@gymsaas.com">soporte@gymsaas.com</a>
                · <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit();">Cerrar sesión</a>
                <form id="lf" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </div>
        </div>
    </div>
</body>
</html>
