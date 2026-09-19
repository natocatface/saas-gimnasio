<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Socio · GymSaaS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{--primary:#7c3aed;--primary-dark:#6d28d9;--border:#e5e7eb;--muted:#6b7280}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;
             background:linear-gradient(135deg,#0b0716,#2a1361 60%,#7c3aed);padding:20px}
        .box{background:#fff;border-radius:22px;width:100%;max-width:400px;padding:40px 34px;box-shadow:0 30px 80px rgba(0,0,0,.4)}
        .logo{text-align:center;margin-bottom:24px}
        .logo .ic{width:60px;height:60px;border-radius:16px;background:linear-gradient(135deg,#ec4899,#7c3aed);display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:12px}
        .logo h1{font-size:20px;font-weight:800}.logo p{color:var(--muted);font-size:13px;margin-top:3px}
        .fg{margin-bottom:15px}
        .fg label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
        .fg input{width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit}
        .fg input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.12)}
        .btn{width:100%;padding:13px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:none;border-radius:11px;font-size:15px;font-weight:700;cursor:pointer}
        .btn:hover{transform:translateY(-1px);box-shadow:0 10px 24px rgba(124,58,237,.35)}
        .alert{background:#fee2e2;color:#b91c1c;padding:11px 14px;border-radius:10px;font-size:13px;margin-bottom:16px}
        .foot{text-align:center;font-size:12px;color:var(--muted);margin-top:18px}
        .foot a{color:var(--primary);text-decoration:none;font-weight:600}
    </style>
</head>
<body>
    <div class="box">
        <div class="logo">
            <div class="ic">🏋️</div>
            <h1>Portal del Socio</h1>
            <p>Ingresa para ver tu membresía y clases</p>
        </div>

        @if(session('error'))<div class="alert"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>@endif

        <form method="POST" action="{{ route('portal.login.post') }}">
            @csrf
            <div class="fg"><label>Correo electrónico</label><input type="email" name="email" value="{{ old('email') }}" required autofocus></div>
            <div class="fg"><label>Contraseña</label><input type="password" name="password" required></div>
            <label style="display:flex;align-items:center;gap:7px;font-size:13px;color:var(--muted);margin-bottom:16px"><input type="checkbox" name="remember"> Recordarme</label>
            <button class="btn"><i class="fas fa-right-to-bracket"></i> Ingresar</button>
        </form>

        <div class="foot">¿Eres parte del staff? <a href="{{ route('login') }}">Acceso del personal</a></div>
    </div>
</body>
</html>
