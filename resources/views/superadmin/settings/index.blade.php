@extends('layouts.superadmin')
@section('title','Configuración')
@section('page-title','Configuración del Sistema')
@section('breadcrumb','Moneda global · se aplica a todos los módulos')

@section('content')

@if($errors->any())
    <div class="alert error"><i class="fas fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
@endif

<style>
    .sys-hero{position:relative;overflow:hidden;border-radius:20px;padding:22px 26px;color:#fff;margin-bottom:20px;
        background:var(--primary,#7c3aed);
        background:linear-gradient(135deg,color-mix(in srgb,var(--primary,#7c3aed) 78%,#000),var(--primary,#7c3aed));
        box-shadow:0 18px 40px -18px color-mix(in srgb,var(--primary,#7c3aed) 60%,transparent);display:flex;align-items:center;gap:16px}
    .sys-hero::after{content:"";position:absolute;right:-40px;top:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.08)}
    .sys-hero .ic{width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;position:relative;z-index:1}
    .sys-hero h2{margin:0;font-size:20px;font-weight:800;position:relative;z-index:1}
    .sys-hero p{margin:5px 0 0;opacity:.92;font-size:13px;position:relative;z-index:1;max-width:640px;line-height:1.5}
</style>
<div class="sys-hero">
    <div class="ic"><i class="fas fa-gear"></i></div>
    <div><h2>Configuración del Sistema</h2><p>Moneda global de la plataforma, aplicada de forma sincronizada a todos los módulos y gimnasios.</p></div>
</div>

<form method="POST" action="{{ route('superadmin.settings.update') }}">
    @csrf

    <div class="grid" style="grid-template-columns:1.4fr .9fr;gap:22px;align-items:start">

        {{-- ── Configuración de moneda ── --}}
        <div class="card">
            <div class="card-h">
                <h3><span class="hi"><i class="fas fa-coins"></i></span> Moneda del sistema</h3>
            </div>

            <p style="font-size:13px;color:var(--muted);line-height:1.6;margin:-4px 0 18px">
                Esta moneda es <b>única para toda la plataforma</b>: planes SaaS, suscripciones, reportes,
                facturación, recibos y todos los gimnasios usarán este formato de forma sincronizada.
            </p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label>Moneda</label>
                    <select name="currency" id="curCode" class="form-control">
                        @php
                            $curs = [
                                'USD'=>'Dólar estadounidense ($)',
                                'PEN'=>'Sol peruano (S/)',
                                'MXN'=>'Peso mexicano ($)',
                                'COP'=>'Peso colombiano ($)',
                                'ARS'=>'Peso argentino ($)',
                                'CLP'=>'Peso chileno ($)',
                                'BOB'=>'Boliviano (Bs)',
                                'EUR'=>'Euro (€)',
                                'BRL'=>'Real brasileño (R$)',
                                'GBP'=>'Libra esterlina (£)',
                            ];
                            $curSymbols = [
                                'USD'=>'$','PEN'=>'S/','MXN'=>'$','COP'=>'$','ARS'=>'$',
                                'CLP'=>'$','BOB'=>'Bs','EUR'=>'€','BRL'=>'R$','GBP'=>'£',
                            ];
                            $curVal = $settings['currency'] ?? 'USD';
                        @endphp
                        @foreach($curs as $code=>$label)
                            <option value="{{ $code }}" data-symbol="{{ $curSymbols[$code] }}" @selected($curVal===$code)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Símbolo</label>
                    <input type="text" name="currency_symbol" id="curSymbol" class="form-control"
                           value="{{ $settings['currency_symbol'] ?? '$' }}" maxlength="6">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label>Posición del símbolo</label>
                    <select name="currency_position" id="curPos" class="form-control">
                        <option value="before" @selected(($settings['currency_position'] ?? 'before')==='before')>Antes del monto ($100)</option>
                        <option value="after"  @selected(($settings['currency_position'] ?? '')==='after')>Después del monto (100 $)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Decimales</label>
                    <select name="decimals" id="curDec" class="form-control">
                        @for($d=0;$d<=4;$d++)
                            <option value="{{ $d }}" @selected((int)($settings['decimals'] ?? 2)===$d)>{{ $d }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label>Separador de miles</label>
                    <select name="thousands_sep" id="curTho" class="form-control">
                        <option value="coma"     @selected(($settings['thousands_sep'] ?? 'coma')==='coma')>Coma (1,000)</option>
                        <option value="punto"    @selected(($settings['thousands_sep'] ?? '')==='punto')>Punto (1.000)</option>
                        <option value="espacio"  @selected(($settings['thousands_sep'] ?? '')==='espacio')>Espacio (1 000)</option>
                        <option value="ninguno"  @selected(($settings['thousands_sep'] ?? '')==='ninguno')>Ninguno (1000)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Separador decimal</label>
                    <select name="decimal_sep" id="curDecs" class="form-control">
                        <option value="punto" @selected(($settings['decimal_sep'] ?? 'punto')==='punto')>Punto (100.50)</option>
                        <option value="coma"  @selected(($settings['decimal_sep'] ?? '')==='coma')>Coma (100,50)</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:8px">
                <button class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Guardar moneda</button>
            </div>
        </div>

        {{-- ── Vista previa ── --}}
        <div class="card" style="background:linear-gradient(160deg,#faf8ff,#fff)">
            <div class="card-h"><h3><span class="hi"><i class="fas fa-eye"></i></span> Vista previa</h3></div>

            <div style="text-align:center;padding:10px 0 4px">
                <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;font-weight:700">Ejemplo de precio</div>
                <div id="prevBig" style="font-size:40px;font-weight:800;color:var(--primary);margin:8px 0">$1,299.90</div>
            </div>

            <div style="border-top:1px solid var(--border);margin-top:8px;padding-top:14px;font-size:14px;line-height:2.3">
                <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Plan mensual</span><b id="prev1">$59.00</b></div>
                <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Suscripción anual</span><b id="prev2">$590.00</b></div>
                <div style="display:flex;justify-content:space-between"><span style="color:var(--muted)">Ingreso total</span><b id="prev3">$14,250.00</b></div>
            </div>

            <div style="margin-top:16px;padding:12px 14px;background:#eef2ff;border:1px solid #c7d2fe;border-radius:11px;font-size:12.5px;color:#3730a3;line-height:1.55">
                <i class="fas fa-circle-info"></i> Al guardar, el nuevo formato se refleja automáticamente en todo el sistema.
            </div>
        </div>

    </div>
</form>

<script>
(function(){
    const map = { coma:',', punto:'.', espacio:' ', ninguno:'' };
    const $ = id => document.getElementById(id);

    function fmt(value){
        const sym = $('curSymbol').value || '$';
        const pos = $('curPos').value;
        const dec = parseInt($('curDec').value,10) || 0;
        let tho = map[$('curTho').value] ?? ',';
        let decs = map[$('curDecs').value] ?? '.';
        if(decs==='') decs='.';

        let n = Number(value).toFixed(dec);
        let [int, frac] = n.split('.');
        int = int.replace(/\B(?=(\d{3})+(?!\d))/g, ' '); // marcador temporal
        int = int.split(' ').join(tho);
        let out = frac ? int + decs + frac : int;
        return pos==='after' ? out + ' ' + sym : sym + out;
    }

    function refresh(){
        $('prevBig').textContent = fmt(1299.90);
        $('prev1').textContent   = fmt(59);
        $('prev2').textContent   = fmt(590);
        $('prev3').textContent   = fmt(14250);
    }

    // Al cambiar la moneda, sugiere su símbolo
    $('curCode').addEventListener('change', function(){
        const opt = this.options[this.selectedIndex];
        if(opt && opt.dataset.symbol) $('curSymbol').value = opt.dataset.symbol;
        refresh();
    });

    ['curSymbol','curPos','curDec','curTho','curDecs'].forEach(id=>{
        $(id).addEventListener('input', refresh);
        $(id).addEventListener('change', refresh);
    });

    refresh();
})();
</script>
@endsection
