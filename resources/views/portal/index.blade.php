<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Portal · {{ $member->gymnasium->name ?? 'GymSaaS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{--primary:{{ $member->gymnasium->primary_color ?? '#7c3aed' }};--border:#e5e7eb;--muted:#6b7280;--ok:#10b981;--warn:#f59e0b;--bad:#ef4444}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:#f4f4f8;color:#111827}
        .top{background:linear-gradient(135deg,#0b0716,var(--primary));color:#fff;padding:18px 22px;display:flex;align-items:center;gap:14px}
        .top .av{width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-weight:800}
        .top .n{font-weight:700}.top .g{font-size:12px;opacity:.8}
        .top form{margin-left:auto}
        .top .lo{background:rgba(255,255,255,.15);color:#fff;border:none;padding:9px 14px;border-radius:9px;font-weight:600;font-size:13px;cursor:pointer}
        .wrap{max-width:920px;margin:0 auto;padding:22px}
        .alert{padding:12px 16px;border-radius:11px;margin-bottom:16px;font-size:14px;font-weight:500}
        .alert.s{background:#dcfce7;color:#15803d}.alert.e{background:#fee2e2;color:#b91c1c}
        .grid{display:grid;gap:16px}
        .card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px}
        .card h3{font-size:15px;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:8px}
        .card h3 i{color:var(--primary)}
        .mstat{display:flex;align-items:center;gap:16px}
        .mstat .big{width:70px;height:70px;border-radius:18px;background:linear-gradient(135deg,var(--primary),#6d28d9);color:#fff;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800}
        .pill{display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700}
        .pill.ok{background:#dcfce7;color:#15803d}.pill.bad{background:#fee2e2;color:#b91c1c}
        table{width:100%;border-collapse:collapse;font-size:13.5px}
        th{text-align:left;font-size:11px;text-transform:uppercase;color:var(--muted);padding:8px 6px;border-bottom:2px solid var(--border)}
        td{padding:10px 6px;border-bottom:1px solid #f1f1f4}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 13px;border-radius:9px;font-size:13px;font-weight:600;border:none;cursor:pointer;text-decoration:none}
        .btn-p{background:var(--primary);color:#fff}.btn-o{background:#f3f4f6;color:#111827}
        .cl-row{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid #f1f1f4}
        .cl-row .dot{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0}
        @media(max-width:760px){.cols{grid-template-columns:1fr!important}}
    </style>
</head>
<body>
    <div class="top">
        <div class="av">{{ $member->initials }}</div>
        <div><div class="n">{{ $member->full_name }}</div><div class="g">{{ $member->gymnasium->name ?? '' }} · {{ $member->code }}</div></div>
        <form method="POST" action="{{ route('portal.logout') }}">@csrf<button class="lo"><i class="fas fa-right-from-bracket"></i> Salir</button></form>
    </div>

    <div class="wrap">
        @if(session('success'))<div class="alert s"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert e"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>@endif

        {{-- Membresía --}}
        <div class="card" style="margin-bottom:16px">
            <div class="mstat">
                <div class="big">{{ $member->initials }}</div>
                <div style="flex:1">
                    <div style="font-size:18px;font-weight:700">{{ $member->plan->name ?? 'Sin plan' }}</div>
                    <div style="color:var(--muted);font-size:14px;margin-top:2px">
                        @if($member->membership_end)
                            Vence el <strong>{{ $member->membership_end->format('d/m/Y') }}</strong>
                            @if($member->isMembershipActive())
                                ({{ now()->diffInDays($member->membership_end, false) }} días)
                            @endif
                        @else
                            Sin fecha de vencimiento
                        @endif
                    </div>
                    <div style="margin-top:8px">
                        <span class="pill {{ $member->isMembershipActive() ? 'ok':'bad' }}">{{ $member->isMembershipActive() ? 'Membresía activa' : 'Membresía '.$member->status }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid cols" style="grid-template-columns:1fr 1fr">
            {{-- Mi rutina --}}
            <div class="card">
                <h3><i class="fas fa-dumbbell"></i> Mi rutina</h3>
                @php $routine = $member->routines->where('is_active',true)->last() ?? $member->routines->last(); @endphp
                @if($routine)
                    <div style="font-weight:700;margin-bottom:8px">{{ $routine->title }} @if($routine->trainer)<span style="font-size:12px;color:var(--muted)">· {{ $routine->trainer->name }}</span>@endif</div>
                    @if(!empty($routine->exercises))
                    <table>
                        <thead><tr><th>Ejercicio</th><th>Series</th><th>Reps</th></tr></thead>
                        <tbody>
                            @foreach($routine->exercises as $ex)
                            <tr><td>{{ $ex['name'] ?? '' }}</td><td>{{ $ex['sets'] ?? '' }}</td><td>{{ $ex['reps'] ?? '' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                @else
                    <div style="color:var(--muted);font-size:14px">Aún no tienes una rutina asignada. Pídele a tu entrenador que te asigne una.</div>
                @endif
            </div>

            {{-- Pagos --}}
            <div class="card">
                <h3><i class="fas fa-receipt"></i> Mis pagos recientes</h3>
                <table>
                    <thead><tr><th>Fecha</th><th>Concepto</th><th style="text-align:right">Monto</th></tr></thead>
                    <tbody>
                        @forelse($member->payments as $p)
                        <tr><td>{{ \Carbon\Carbon::parse($p->payment_date ?? $p->created_at)->format('d/m/Y') }}</td><td>{{ $p->plan->name ?? 'Pago' }}</td><td style="text-align:right;font-weight:700">{{ number_format($p->amount,2) }}</td></tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:16px">Sin pagos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mis clases --}}
        <div class="card" style="margin-top:16px">
            <h3><i class="fas fa-calendar-check"></i> Mis clases inscritas</h3>
            @forelse($member->enrollments->where('status','activo') as $e)
                @if($e->gymClass)
                <div class="cl-row">
                    <div class="dot" style="background:{{ $e->gymClass->color ?? '#7c3aed' }}"><i class="fas fa-dumbbell"></i></div>
                    <div style="flex:1">
                        <div style="font-weight:700">{{ $e->gymClass->name }}</div>
                        <div style="font-size:12px;color:var(--muted)">{{ ucfirst($e->gymClass->schedule_day ?? '') }} {{ $e->gymClass->schedule_time ? \Carbon\Carbon::parse($e->gymClass->schedule_time)->format('H:i') : '' }} · {{ $e->gymClass->trainer->name ?? '' }}</div>
                    </div>
                    <form method="POST" action="{{ route('portal.unenroll',$e) }}" onsubmit="return confirm('¿Cancelar tu inscripción?')">@csrf @method('DELETE')<button class="btn btn-o" style="color:var(--bad)"><i class="fas fa-xmark"></i> Cancelar</button></form>
                </div>
                @endif
            @empty
                <div style="color:var(--muted);font-size:14px">No estás inscrito en ninguna clase todavía.</div>
            @endforelse
        </div>

        {{-- Clases disponibles --}}
        <div class="card" style="margin-top:16px">
            <h3><i class="fas fa-plus-circle"></i> Inscríbete a una clase</h3>
            @forelse($availableClasses as $c)
                <div class="cl-row">
                    <div class="dot" style="background:{{ $c->color ?? '#7c3aed' }}"><i class="fas fa-dumbbell"></i></div>
                    <div style="flex:1">
                        <div style="font-weight:700">{{ $c->name }}</div>
                        <div style="font-size:12px;color:var(--muted)">{{ ucfirst($c->schedule_day ?? '') }} {{ $c->schedule_time ? \Carbon\Carbon::parse($c->schedule_time)->format('H:i') : '' }} · {{ $c->enrollments_count }}/{{ $c->capacity }} cupos</div>
                    </div>
                    @if($c->enrollments_count < $c->capacity)
                        <form method="POST" action="{{ route('portal.enroll',$c) }}">@csrf<button class="btn btn-p"><i class="fas fa-plus"></i> Inscribirme</button></form>
                    @else
                        <span class="pill bad">Llena</span>
                    @endif
                </div>
            @empty
                <div style="color:var(--muted);font-size:14px">No hay más clases disponibles para inscribirte.</div>
            @endforelse
        </div>
    </div>
</body>
</html>
