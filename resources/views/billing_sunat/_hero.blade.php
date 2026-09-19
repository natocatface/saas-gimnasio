{{-- Encabezado reutilizable del módulo de Facturación Electrónica.
     Parámetros: $subtitle (texto), $icon (clase FontAwesome opcional). --}}
@push('styles')
<style>
    .fe-hero{position:relative;overflow:hidden;border-radius:20px;padding:24px 28px;color:#fff;
        background:var(--primary,#7c3aed);
        background:linear-gradient(135deg,color-mix(in srgb,var(--primary,#7c3aed) 78%,#000),var(--primary,#7c3aed));
        box-shadow:0 18px 40px -18px color-mix(in srgb,var(--primary,#7c3aed) 60%,transparent);margin-bottom:22px}
    .fe-hero::after{content:"";position:absolute;right:-40px;top:-40px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.08)}
    .fe-hero .row1{display:flex;align-items:flex-start;gap:16px;position:relative;z-index:1}
    .fe-hero .ic{width:54px;height:54px;border-radius:15px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
    .fe-hero h2{margin:0;font-size:21px;font-weight:800;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
    .fe-hero .flag{font-size:12px;font-weight:700;background:rgba(255,255,255,.2);padding:3px 10px;border-radius:20px}
    .fe-hero p{margin:6px 0 0;opacity:.92;font-size:13.5px;max-width:640px;line-height:1.5}
    .fe-hero .rightbadge{margin-left:auto;text-align:right;flex-shrink:0}
    .fe-hero .rightbadge .b{background:rgba(255,255,255,.22);font-weight:800;font-size:13px;padding:5px 14px;border-radius:10px;display:inline-block}
    .fe-hero .rightbadge small{display:block;opacity:.85;font-size:11px;margin-top:6px}
    @media(max-width:900px){.fe-hero .row1{flex-wrap:wrap}.fe-hero .rightbadge{margin-left:0}}
</style>
@endpush

<div class="fe-hero">
    <div class="row1">
        <div class="ic"><i class="fas {{ $icon ?? 'fa-file-invoice-dollar' }}"></i></div>
        <div>
            <h2>Facturación Electrónica <span class="flag">🇵🇪 Perú</span></h2>
            <p>{{ $subtitle ?? 'Comprobantes electrónicos ante SUNAT · UBL 2.1 · Boletas, facturas y notas de crédito/débito.' }}</p>
        </div>
        <div class="rightbadge">
            <span class="b">SUNAT</span>
            <small>Comprobantes de Pago Electrónicos</small>
        </div>
    </div>
</div>
