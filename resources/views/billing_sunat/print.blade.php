<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>{{ $doc->tipo_nombre }} {{ $doc->numero }}</title>
<style>
    /* Layout basado en tablas para compatibilidad con dompdf (no soporta flexbox). */
    * { box-sizing:border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color:#111; font-size:12px; margin:0; padding:24px; background:#fff; }
    .sheet { width:100%; max-width:760px; margin:0 auto; }
    table { border-collapse:collapse; }
    .full { width:100%; }
    .box { border:1px solid #333; border-radius:6px; }
    .emisor { padding:14px 16px; vertical-align:top; }
    .emisor .logo { font-size:19px; font-weight:800; color:#7c3aed; margin-bottom:4px; }
    .emisor h1 { font-size:14px; margin:0 0 4px; }
    .emisor p { margin:2px 0; color:#333; }
    .docbox { width:230px; border-left:1px solid #333; padding:14px; text-align:center; vertical-align:middle; }
    .docbox .ruc { font-weight:700; }
    .docbox .tit { font-weight:800; font-size:13px; margin:8px 0; text-transform:uppercase; }
    .docbox .num { font-size:16px; font-weight:800; letter-spacing:1px; }
    .meta { margin-top:14px; }
    .meta td { border:1px solid #ccc; border-radius:6px; padding:10px 12px; vertical-align:top; width:50%; }
    .meta .k { color:#666; font-size:10px; text-transform:uppercase; letter-spacing:.4px; }
    table.items { width:100%; margin-top:14px; }
    table.items th { background:#f3f0fb; border:1px solid #ccc; padding:7px 8px; font-size:10px; text-transform:uppercase; text-align:left; }
    table.items td { border:1px solid #ddd; padding:7px 8px; }
    .r { text-align:right; }
    table.tot { width:280px; margin-top:12px; float:right; }
    table.tot td { padding:5px 0; border-bottom:1px solid #eee; }
    table.tot .grand td { font-weight:800; font-size:14px; border-top:2px solid #333; border-bottom:none; padding-top:8px; }
    .clear { clear:both; }
    .foot { margin-top:18px; }
    .foot td { vertical-align:top; }
    .foot .hash { font-size:10px; color:#555; word-break:break-all; }
    .estado { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .e-aceptado { background:#dcfce7; color:#15803d; } .e-otro { background:#fef3c7; color:#92400e; }
    .noprint { margin:0 auto 16px; max-width:760px; }
    .btn { display:inline-block; background:#7c3aed; color:#fff; padding:9px 16px; border-radius:8px; text-decoration:none; font-weight:600; font-size:13px; border:none; cursor:pointer; }
    @media print { .noprint { display:none; } body { padding:0; } }
</style>
</head>
<body>

<div class="noprint">
    <button class="btn" onclick="window.print()">Imprimir / Guardar PDF</button>
    <a class="btn" style="background:#6b7280" href="{{ route('sunat.docs.show',$doc) }}">Volver</a>
</div>

<div class="sheet">

    {{-- Emisor + caja del documento --}}
    <table class="full box"><tr>
        <td class="emisor">
            <div class="logo">{{ $setting->nombre_comercial ?: ($setting->razon_social ?: 'GIMNASIO') }}</div>
            <h1>{{ $setting->razon_social }}</h1>
            <p>{{ $setting->direccion }}</p>
            <p>{{ trim(($setting->distrito ?? '').' '.($setting->provincia ?? '').' '.($setting->departamento ?? '')) }}</p>
        </td>
        <td class="docbox">
            <div class="ruc">RUC {{ $setting->ruc }}</div>
            <div class="tit">{{ $doc->tipo_nombre }} ELECTRÓNICA</div>
            <div class="num">{{ $doc->numero }}</div>
        </td>
    </tr></table>

    {{-- Cliente + emisión --}}
    <table class="full meta"><tr>
        <td>
            <div class="k">Cliente</div>
            <div><b>{{ $doc->cliente_razon_social }}</b></div>
            <div>{{ ['0'=>'','1'=>'DNI','6'=>'RUC'][$doc->cliente_tipo_doc] ?? '' }} {{ $doc->cliente_num_doc }}</div>
            <div>{{ $doc->cliente_direccion }}</div>
        </td>
        <td>
            <div class="k">Fecha de emisión</div>
            <div>{{ $doc->fecha_emision?->format('d/m/Y') }}</div>
            <div class="k" style="margin-top:6px">Moneda</div>
            <div>{{ $doc->moneda }}</div>
        </td>
    </tr></table>

    @if($doc->doc_afectado_serie_num)
        <p style="margin-top:10px"><b>Documento que modifica:</b> {{ $doc->doc_afectado_serie_num }} — {{ $doc->des_motivo }}</p>
    @endif

    {{-- Detalle --}}
    <table class="items">
        <thead><tr><th style="width:50px">Cant.</th><th>Descripción</th><th class="r" style="width:100px">V. Unitario</th><th class="r" style="width:100px">Importe</th></tr></thead>
        <tbody>
        @foreach(($doc->items ?? []) as $it)
            @php $cant=$it['cantidad'] ?? 1; $vu=$it['mto_valor_unitario'] ?? 0; @endphp
            <tr>
                <td>{{ $cant }}</td>
                <td>{{ $it['descripcion'] ?? '' }}</td>
                <td class="r">{{ number_format($vu,2) }}</td>
                <td class="r">{{ number_format($vu*$cant,2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Totales --}}
    <table class="tot">
        <tr><td>Op. Gravada</td><td class="r">{{ $doc->moneda }} {{ number_format($doc->mto_oper_gravadas,2) }}</td></tr>
        <tr><td>IGV ({{ rtrim(rtrim(number_format($setting->igv_percent ?? 18,2),'0'),'.') }}%)</td><td class="r">{{ $doc->moneda }} {{ number_format($doc->mto_igv,2) }}</td></tr>
        <tr class="grand"><td>Importe Total</td><td class="r">{{ $doc->moneda }} {{ number_format($doc->total,2) }}</td></tr>
    </table>
    <div class="clear"></div>

    {{-- QR + estado --}}
    <table class="full foot"><tr>
        <td style="width:140px; text-align:center">
            @if(!empty($qrImage))
                <img src="{{ $qrImage }}" alt="QR SUNAT" width="120" height="120">
            @else
                <div id="qrcode"></div>
            @endif
            <div class="hash" style="max-width:130px; margin:6px auto 0">{{ $qr }}</div>
        </td>
        <td>
            <p><b>Estado SUNAT:</b>
                <span class="estado {{ $doc->estado==='aceptado' ? 'e-aceptado':'e-otro' }}">{{ ucfirst($doc->estado) }}</span>
                @if($doc->sunat_code) <span style="color:#666">[{{ $doc->sunat_code }}] {{ $doc->sunat_description }}</span>@endif
            </p>
            @if($doc->hash)<p class="hash"><b>Hash:</b> {{ $doc->hash }}</p>@endif
            <p style="color:#666; font-size:10px; margin-top:14px">Representación impresa del comprobante electrónico. Consulte su validez en el portal de SUNAT.</p>
        </td>
    </tr></table>
</div>

@empty($qrImage)
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    try { new QRCode(document.getElementById("qrcode"), { text: @json($qr), width: 120, height: 120 }); } catch (e) {}
</script>
@endempty
</body>
</html>
