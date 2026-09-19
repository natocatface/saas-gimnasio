@extends('layouts.app')
@section('title','Facturación Electrónica')
@section('page-title','Facturación Electrónica')
@section('breadcrumb')<span class="current">Facturación Electrónica</span>@endsection

@push('styles')
<style>
    /* ---- Banner ---- */
    .fe-hero{position:relative;overflow:hidden;border-radius:20px;padding:26px 28px;color:#fff;
        background:var(--primary,#7c3aed);
        background:linear-gradient(135deg,color-mix(in srgb,var(--primary,#7c3aed) 78%,#000),var(--primary,#7c3aed));
        box-shadow:0 18px 40px -18px color-mix(in srgb,var(--primary,#7c3aed) 60%,transparent);margin-bottom:22px}
    .fe-hero::after{content:"";position:absolute;right:-40px;top:-40px;width:220px;height:220px;border-radius:50%;
        background:rgba(255,255,255,.08)}
    .fe-hero .row1{display:flex;align-items:flex-start;gap:16px}
    .fe-hero .ic{width:58px;height:58px;border-radius:16px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0}
    .fe-hero h2{margin:0;font-size:22px;font-weight:800;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
    .fe-hero .flag{font-size:13px;font-weight:700;background:rgba(255,255,255,.2);padding:3px 10px;border-radius:20px}
    .fe-hero p{margin:6px 0 0;opacity:.92;font-size:13.5px;max-width:640px;line-height:1.5}
    .fe-hero .rightbadge{margin-left:auto;text-align:right;flex-shrink:0}
    .fe-hero .rightbadge .b{background:rgba(255,255,255,.22);font-weight:800;font-size:13px;padding:5px 14px;border-radius:10px;display:inline-block}
    .fe-hero .rightbadge small{display:block;opacity:.85;font-size:11px;margin-top:6px}
    .fe-chips{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:18px}
    .fe-chip{background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:5px 13px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:7px}
    .fe-chip .dot{width:8px;height:8px;border-radius:50%;background:#fde68a}
    .fe-chip.on .dot{background:#bbf7d0}
    .fe-chip.bad{background:rgba(0,0,0,.18)}
    .fe-testbtn{margin-left:auto;background:#fff;color:var(--primary,#7c3aed);border:none;border-radius:10px;padding:9px 16px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:8px}
    .fe-testbtn:hover{background:var(--primary-light,#ede9fe)}

    /* ---- Secciones ---- */
    .fe-sec{background:var(--card-bg,#fff);border:1px solid var(--border);border-radius:18px;padding:22px 24px;margin-bottom:18px}
    .fe-sec-head{display:flex;gap:12px;align-items:center;margin-bottom:18px}
    .fe-sec-head .ic{width:40px;height:40px;border-radius:11px;background:var(--primary-light,#ede9fe);color:var(--primary,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
    .fe-sec-head h3{margin:0;font-size:16px;font-weight:700}
    .fe-sec-head p{margin:2px 0 0;font-size:12.5px;color:var(--text-secondary)}
    .fe-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .fe-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}

    .chk-card{display:flex;gap:12px;align-items:flex-start;border:1px solid var(--border);border-radius:12px;padding:14px 16px;margin-bottom:12px;cursor:pointer;transition:.15s}
    .chk-card:hover{border-color:var(--primary,#7c3aed);background:var(--primary-light,#ede9fe)}
    .chk-card input{width:18px;height:18px;margin-top:2px;accent-color:var(--primary,#7c3aed)}
    .chk-card .t{font-weight:700;font-size:14px}
    .chk-card .d{font-size:12.5px;color:var(--text-secondary);margin-top:2px}

    .fe-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;border-radius:12px;padding:12px 15px;font-size:13px;display:flex;gap:9px;align-items:center;margin-bottom:16px}
    .cert-err{color:#dc2626;font-size:12.5px;margin-top:6px}
    .cert-ok{color:#059669;font-size:12.5px;margin-top:6px}

    /* ---- Barra fija ---- */
    .fe-bar{position:sticky;bottom:0;background:#fff;border:1px solid var(--border);border-radius:14px;
        padding:14px 20px;display:flex;align-items:center;justify-content:space-between;margin-top:8px;box-shadow:0 -6px 20px -12px rgba(0,0,0,.2)}
    .btn-save{background:var(--primary,#7c3aed);background:linear-gradient(135deg,color-mix(in srgb,var(--primary,#7c3aed) 78%,#000),var(--primary,#7c3aed));color:#fff;border:none;padding:11px 22px;border-radius:10px;font-weight:700;cursor:pointer;font-size:14px;display:inline-flex;gap:8px;align-items:center}
    @media(max-width:900px){.fe-grid,.fe-3{grid-template-columns:1fr}.fe-hero .row1{flex-wrap:wrap}.fe-hero .rightbadge{margin-left:0}}
</style>
@endpush

@section('content')

@if($errors->any())<div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> {{ $errors->first() }}</div>@endif

@php
    $modo = $setting->environment ?? 'beta';
    $drv  = $setting->driver ?? 'greenter';
@endphp

{{-- ============ BANNER ============ --}}
<div class="fe-hero">
    <div class="row1">
        <div class="ic"><i class="fas fa-file-invoice-dollar"></i></div>
        <div>
            <h2>Facturación Electrónica <span class="flag">🇵🇪 Perú</span></h2>
            <p>Emisión de comprobantes electrónicos ante <b>SUNAT</b> · UBL 2.1 · Boletas, facturas y notas de crédito/débito.</p>
        </div>
        <div class="rightbadge">
            <span class="b">SUNAT</span>
            <small>Comprobantes de Pago Electrónicos</small>
        </div>
    </div>

    <div class="fe-chips">
        <span class="fe-chip {{ $setting->enabled ? 'on' : '' }}"><span class="dot"></span> {{ $setting->enabled ? 'Habilitada' : 'Deshabilitada' }}</span>
        <span class="fe-chip"><i class="fas fa-plug"></i> Driver: {{ $drv }}</span>
        <span class="fe-chip"><i class="fas fa-flask"></i> Modo: {{ $modo }}</span>
        @if($certExists)
            <span class="fe-chip on"><i class="fas fa-certificate"></i> Certificado cargado</span>
        @else
            <span class="fe-chip bad"><i class="fas fa-xmark"></i> Certificado no encontrado</span>
        @endif
        @unless($greenter)
            <span class="fe-chip bad"><i class="fas fa-triangle-exclamation"></i> Greenter no instalado</span>
        @endunless

        <form method="POST" action="{{ route('sunat.settings.test') }}" style="margin-left:auto">
            @csrf
            <button class="fe-testbtn" type="submit"><i class="fas fa-bolt"></i> Probar conexión con SUNAT</button>
        </form>
    </div>
</div>

@unless($greenter)
    <div class="alert alert-warning">
        <i class="fas fa-triangle-exclamation"></i>
        La librería <b>Greenter</b> no está instalada. Ejecuta <code>composer require greenter/lite</code> (extensiones <b>openssl</b> y <b>soap</b>). Puedes guardar la configuración igual.
    </div>
@endunless

<form method="POST" action="{{ route('sunat.settings.update') }}" enctype="multipart/form-data">
    @csrf

    {{-- ============ ESTADO Y MODO ============ --}}
    <div class="fe-sec">
        <div class="fe-sec-head">
            <div class="ic"><i class="fas fa-bolt"></i></div>
            <div><h3>Estado y modo</h3><p>Activación, forma de emisión y entorno de SUNAT</p></div>
        </div>

        <label class="chk-card">
            <input type="checkbox" name="enabled" value="1" @checked(old('enabled',$setting->enabled))>
            <span><span class="t">Habilitar facturación electrónica</span>
            <span class="d">Si está desactivada, no se generan comprobantes ante SUNAT.</span></span>
        </label>
        <label class="chk-card">
            <input type="checkbox" name="auto_emit" value="1" @checked(old('auto_emit',$setting->auto_emit))>
            <span><span class="t">Emitir automáticamente al registrar el pago</span>
            <span class="d">Cada boleta o factura se envía apenas se registra el pago del socio.</span></span>
        </label>

        <div class="fe-grid" style="margin-top:6px">
            <div class="form-group">
                <label class="form-label">Driver de emisión</label>
                <select name="driver" class="form-control">
                    <option value="greenter" @selected($drv==='greenter')>Greenter (envío directo a SUNAT)</option>
                    <option value="none" @selected($drv==='none')>Ninguno (no emite, deja pendiente)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Entorno SUNAT</label>
                <select name="environment" class="form-control">
                    <option value="beta" @selected($modo==='beta')>Beta (homologación / pruebas)</option>
                    <option value="produccion" @selected($modo==='produccion')>Producción</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ============ DATOS DEL EMISOR ============ --}}
    <div class="fe-sec">
        <div class="fe-sec-head">
            <div class="ic"><i class="fas fa-building"></i></div>
            <div><h3>Datos del emisor</h3><p>Aparecen en el comprobante electrónico</p></div>
        </div>
        <div class="fe-grid">
            <div class="form-group"><label class="form-label">RUC *</label>
                <input name="ruc" maxlength="11" class="form-control" value="{{ old('ruc',$setting->ruc) }}" required></div>
            <div class="form-group"><label class="form-label">Razón social *</label>
                <input name="razon_social" class="form-control" value="{{ old('razon_social',$setting->razon_social) }}" required></div>
            <div class="form-group"><label class="form-label">Nombre comercial</label>
                <input name="nombre_comercial" class="form-control" value="{{ old('nombre_comercial',$setting->nombre_comercial) }}"></div>
            <div class="form-group"><label class="form-label">Dirección fiscal</label>
                <input name="direccion" class="form-control" value="{{ old('direccion',$setting->direccion) }}"></div>
        </div>
        <div class="fe-grid">
            <div class="form-group"><label class="form-label">Ubigeo</label>
                <input name="ubigeo" maxlength="6" class="form-control" placeholder="150101" value="{{ old('ubigeo',$setting->ubigeo) }}"></div>
            <div class="form-group"><label class="form-label">Departamento</label>
                <input name="departamento" class="form-control" value="{{ old('departamento',$setting->departamento) }}"></div>
            <div class="form-group"><label class="form-label">Provincia</label>
                <input name="provincia" class="form-control" value="{{ old('provincia',$setting->provincia) }}"></div>
            <div class="form-group"><label class="form-label">Distrito</label>
                <input name="distrito" class="form-control" value="{{ old('distrito',$setting->distrito) }}"></div>
        </div>
        <div class="form-group"><label class="form-label">Urbanización</label>
            <input name="urbanizacion" class="form-control" value="{{ old('urbanizacion',$setting->urbanizacion) }}"></div>
    </div>

    {{-- ============ CREDENCIALES SUNAT ============ --}}
    <div class="fe-sec">
        <div class="fe-sec-head">
            <div class="ic"><i class="fas fa-key"></i></div>
            <div><h3>Credenciales SUNAT</h3><p>Clave SOL y certificado digital</p></div>
        </div>

        <div class="fe-info"><i class="fas fa-circle-info"></i> En <b>beta</b> puedes usar el RUC <b>20000000001</b> con usuario y clave <b>MODDATOS</b>.</div>

        <div class="fe-grid">
            <div class="form-group"><label class="form-label">Usuario Clave SOL</label>
                <input name="sol_user" class="form-control" value="{{ old('sol_user',$setting->sol_user) }}" placeholder="MODDATOS"></div>
            <div class="form-group"><label class="form-label">Clave SOL</label>
                <input type="password" name="sol_pass" class="form-control" placeholder="{{ $setting->sol_pass ? '•••••• (sin cambios)' : '' }}"></div>
        </div>
        <div class="fe-grid">
            <div class="form-group"><label class="form-label">Certificado digital (.pem, .pfx o .p12)</label>
                <input type="file" name="cert" class="form-control" accept=".pem,.pfx,.p12">
                @if($certExists)
                    <div class="cert-ok"><i class="fas fa-check-circle"></i> Certificado cargado. Sube uno nuevo solo para reemplazarlo.</div>
                @else
                    <div class="cert-err"><i class="fas fa-triangle-exclamation"></i> No se ha cargado un certificado.</div>
                @endif
            </div>
            <div class="form-group"><label class="form-label">Clave del certificado</label>
                <input type="password" name="cert_pass" class="form-control" placeholder="{{ $setting->cert_pass ? '•••••• (sin cambios)' : '' }}"></div>
        </div>
    </div>

    {{-- ============ SERIES / IGV ============ --}}
    <div class="fe-sec">
        <div class="fe-sec-head">
            <div class="ic"><i class="fas fa-hashtag"></i></div>
            <div><h3>Series, correlativos e impuesto</h3><p>Numeración de cada tipo de comprobante</p></div>
        </div>
        <div class="fe-3">
            <div class="form-group"><label class="form-label">Serie Factura</label>
                <input name="serie_factura" class="form-control" value="{{ old('serie_factura',$setting->serie_factura ?? 'F001') }}"></div>
            <div class="form-group"><label class="form-label">Serie Boleta</label>
                <input name="serie_boleta" class="form-control" value="{{ old('serie_boleta',$setting->serie_boleta ?? 'B001') }}"></div>
            <div class="form-group"><label class="form-label">IGV (%)</label>
                <input type="number" step="0.01" name="igv_percent" class="form-control" value="{{ old('igv_percent',$setting->igv_percent ?? '18.00') }}"></div>
            <div class="form-group"><label class="form-label">Serie N. Crédito</label>
                <input name="serie_nc" class="form-control" value="{{ old('serie_nc',$setting->serie_nc ?? 'FC01') }}"></div>
            <div class="form-group"><label class="form-label">Serie N. Débito</label>
                <input name="serie_nd" class="form-control" value="{{ old('serie_nd',$setting->serie_nd ?? 'FD01') }}"></div>
        </div>
        <div class="fe-grid">
            <div class="form-group"><label class="form-label">Último correlativo Factura</label>
                <input type="number" name="correlativo_factura" class="form-control" value="{{ old('correlativo_factura',$setting->correlativo_factura ?? 0) }}"></div>
            <div class="form-group"><label class="form-label">Último correlativo Boleta</label>
                <input type="number" name="correlativo_boleta" class="form-control" value="{{ old('correlativo_boleta',$setting->correlativo_boleta ?? 0) }}"></div>
            <div class="form-group"><label class="form-label">Último correlativo N. Crédito</label>
                <input type="number" name="correlativo_nc" class="form-control" value="{{ old('correlativo_nc',$setting->correlativo_nc ?? 0) }}"></div>
            <div class="form-group"><label class="form-label">Último correlativo N. Débito</label>
                <input type="number" name="correlativo_nd" class="form-control" value="{{ old('correlativo_nd',$setting->correlativo_nd ?? 0) }}"></div>
        </div>
    </div>

    {{-- ============ BARRA FIJA ============ --}}
    <div class="fe-bar">
        <a href="{{ route('sunat.docs.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Volver</a>
        <button type="submit" class="btn-save"><i class="fas fa-circle-check"></i> Guardar configuración</button>
    </div>
</form>
@endsection
