<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Recibo de pago</title>
<style>
    * { box-sizing:border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color:#111; font-size:12px; margin:0; padding:22px; background:#fff; }
    .sheet { max-width:520px; margin:0 auto; }
    table { border-collapse:collapse; }
    .full { width:100%; }
    .head { border-bottom:3px solid {{ $gym->primary_color ?? '#7c3aed' }}; padding-bottom:12px; margin-bottom:16px; }
    .gym { font-size:19px; font-weight:800; color:{{ $gym->primary_color ?? '#7c3aed' }}; }
    .gym-sub { color:#555; font-size:11px; margin-top:2px; }
    .title { text-align:right; }
    .title .t { font-size:15px; font-weight:800; letter-spacing:1px; }
    .title .n { color:#555; font-size:12px; margin-top:2px; }
    .kv td { padding:7px 0; border-bottom:1px solid #eee; font-size:12.5px; }
    .kv td.k { color:#666; width:42%; }
    .kv td.v { font-weight:600; text-align:right; }
    .amount { margin-top:16px; background:#f7f5ff; border:1px solid #e5e0fb; border-radius:10px; padding:14px 16px; }
    .amount .l { color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.5px; }
    .amount .b { font-size:26px; font-weight:800; color:{{ $gym->primary_color ?? '#7c3aed' }}; }
    .badge { display:inline-block; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .b-ok { background:#dcfce7; color:#15803d; } .b-p { background:#fef3c7; color:#92400e; } .b-c { background:#fee2e2; color:#b91c1c; }
    .foot { margin-top:18px; color:#888; font-size:10.5px; text-align:center; }
    .noprint { max-width:520px; margin:0 auto 14px; }
    .btn { display:inline-block; background:#7c3aed; color:#fff; padding:8px 15px; border-radius:8px; text-decoration:none; font-weight:600; font-size:13px; border:none; cursor:pointer; }
    @media print { .noprint { display:none; } body { padding:0; } }
</style>
</head>
<body>

<div class="noprint">
    <button class="btn" onclick="window.print()">Imprimir / Guardar PDF</button>
    <a class="btn" style="background:#6b7280" href="{{ route('payments.show',$payment) }}">Volver</a>
</div>

<div class="sheet">
    <table class="full head"><tr>
        <td>
            <div class="gym">{{ $gym->name ?? 'Gimnasio' }}</div>
            <div class="gym-sub">{{ $gym->address ?? '' }}</div>
            <div class="gym-sub">{{ $gym->phone ?? '' }} @if(!empty($gym->email)) · {{ $gym->email }}@endif</div>
        </td>
        <td class="title">
            <div class="t">RECIBO DE PAGO</div>
            <div class="n">N.° {{ str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="n">{{ $payment->payment_date?->format('d/m/Y') }}</div>
        </td>
    </tr></table>

    <table class="full kv">
        <tr><td class="k">Socio</td><td class="v">{{ $payment->member ? ($payment->member->first_name.' '.$payment->member->last_name) : '—' }}</td></tr>
        <tr><td class="k">Código de socio</td><td class="v">{{ $payment->member->code ?? '—' }}</td></tr>
        <tr><td class="k">Concepto</td><td class="v">{{ $payment->plan->name ?? 'Pago de membresía' }}</td></tr>
        <tr><td class="k">Método de pago</td><td class="v">{{ ucfirst($payment->payment_method) }}</td></tr>
        @if($payment->reference)<tr><td class="k">Referencia</td><td class="v">{{ $payment->reference }}</td></tr>@endif
        @if($payment->period_start || $payment->period_end)
        <tr><td class="k">Periodo</td><td class="v">{{ optional($payment->period_start)->format('d/m/Y') }} — {{ optional($payment->period_end)->format('d/m/Y') }}</td></tr>
        @endif
        <tr><td class="k">Estado</td><td class="v">
            <span class="badge {{ $payment->status==='pagado'?'b-ok':($payment->status==='pendiente'?'b-p':'b-c') }}">{{ ucfirst($payment->status) }}</span>
        </td></tr>
    </table>

    <table class="full amount"><tr>
        <td><div class="l">Monto pagado</div></td>
        <td style="text-align:right"><div class="b">{{ money($payment->amount, 2) }}</div></td>
    </tr></table>

    @if($payment->notes)
        <p style="margin-top:14px;font-size:11.5px;color:#555"><b>Notas:</b> {{ $payment->notes }}</p>
    @endif

    <div class="foot">Gracias por tu pago. Este recibo es un comprobante interno del gimnasio.</div>
</div>
</body>
</html>
