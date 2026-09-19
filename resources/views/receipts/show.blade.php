<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #{{ str_pad($sub->id,5,'0',STR_PAD_LEFT) }} · GymSaaS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{--primary:#7c3aed;--muted:#6b7280;--border:#e5e7eb}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:#f4f4f8;color:#111827;padding:30px}
        .actions{max-width:720px;margin:0 auto 18px;display:flex;gap:10px;justify-content:flex-end}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;border:none;cursor:pointer;text-decoration:none}
        .btn-primary{background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff}
        .btn-light{background:#fff;color:#111827;border:1px solid var(--border)}
        .sheet{max-width:720px;margin:0 auto;background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.08)}
        .head{background:linear-gradient(135deg,#0b0716,#2a1361);color:#fff;padding:32px 40px;display:flex;justify-content:space-between;align-items:flex-start}
        .brand{display:flex;align-items:center;gap:12px}
        .brand .ic{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#ec4899,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:21px}
        .brand h1{font-size:18px;font-weight:800}.brand span{font-size:11px;color:rgba(255,255,255,.5);letter-spacing:1.5px}
        .rno{text-align:right}.rno .l{font-size:11px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px}.rno .v{font-size:20px;font-weight:800}
        .body{padding:32px 40px}
        .parties{display:flex;justify-content:space-between;gap:30px;margin-bottom:28px}
        .parties .lbl{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);font-weight:700;margin-bottom:6px}
        .parties .nm{font-weight:700;font-size:15px}.parties .sm{font-size:13px;color:var(--muted);line-height:1.7}
        table{width:100%;border-collapse:collapse;margin:14px 0}
        th{text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);padding:10px 0;border-bottom:2px solid var(--border)}
        td{padding:13px 0;border-bottom:1px solid #f1f1f4;font-size:14px}
        .totals{margin-top:18px;margin-left:auto;width:280px}
        .totals .row{display:flex;justify-content:space-between;padding:7px 0;font-size:14px}
        .totals .row.grand{border-top:2px solid var(--border);margin-top:6px;padding-top:14px;font-size:19px;font-weight:800;color:var(--primary)}
        .status{display:inline-block;padding:5px 13px;border-radius:20px;font-size:12px;font-weight:700}
        .status.active{background:#dcfce7;color:#15803d}.status.expired,.status.cancelled,.status.pending{background:#f3f4f6;color:#6b7280}
        .foot{padding:22px 40px;border-top:1px solid var(--border);font-size:12px;color:var(--muted);text-align:center}
        @media print{ body{background:#fff;padding:0}.actions{display:none}.sheet{box-shadow:none;border-radius:0;max-width:100%} }
    </style>
</head>
<body>
    <div class="actions">
        <a href="{{ url()->previous() }}" class="btn btn-light"><i class="fas fa-arrow-left"></i> Volver</a>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir / Guardar PDF</button>
    </div>

    <div class="sheet">
        <div class="head">
            <div class="brand">
                <div class="ic">🏋️</div>
                <div><h1>GymSaaS Pro</h1><span>RECIBO DE PAGO</span></div>
            </div>
            <div class="rno">
                <div class="l">Recibo N°</div>
                <div class="v">#{{ str_pad($sub->id,5,'0',STR_PAD_LEFT) }}</div>
                <div style="font-size:12px;color:rgba(255,255,255,.6);margin-top:6px">{{ $sub->created_at->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="body">
            <div class="parties">
                <div>
                    <div class="lbl">Facturado a</div>
                    <div class="nm">{{ $sub->gymnasium->name ?? '—' }}</div>
                    <div class="sm">
                        {{ $sub->gymnasium->email ?? '' }}<br>
                        {{ $sub->gymnasium->phone ?? '' }}<br>
                        {{ $sub->gymnasium->city ?? '' }} {{ $sub->gymnasium->country ?? '' }}
                    </div>
                </div>
                <div style="text-align:right">
                    <div class="lbl">Estado</div>
                    <span class="status {{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
                    <div class="sm" style="margin-top:10px">
                        Ref: {{ $sub->payment_ref ?? '—' }}<br>
                        Vigencia: {{ \Carbon\Carbon::parse($sub->starts_at)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($sub->ends_at)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <table>
                <thead><tr><th>Concepto</th><th style="text-align:center">Ciclo</th><th style="text-align:right">Importe</th></tr></thead>
                <tbody>
                    @php $base = $sub->amount + ($sub->discount ?? 0); @endphp
                    <tr>
                        <td>Suscripción GymSaaS — Plan <strong>{{ $sub->saasPlan->name ?? '—' }}</strong></td>
                        <td style="text-align:center">{{ $sub->billing_cycle==='yearly'?'Anual':'Mensual' }}</td>
                        <td style="text-align:right">{{ money($base,2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="totals">
                <div class="row"><span>Subtotal</span><span>{{ money($base,2) }}</span></div>
                @if(($sub->discount ?? 0) > 0)
                    <div class="row" style="color:#15803d"><span>Descuento {{ $sub->coupon_code ? '('.$sub->coupon_code.')' : '' }}</span><span>-{{ money($sub->discount,2) }}</span></div>
                @endif
                <div class="row grand"><span>Total</span><span>{{ money($sub->amount,2) }}</span></div>
            </div>
        </div>

        <div class="foot">
            Gracias por confiar en GymSaaS Pro · Este documento es un comprobante de pago simulado para fines de demostración.
        </div>
    </div>
</body>
</html>
