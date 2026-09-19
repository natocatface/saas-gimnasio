@extends('layouts.app')

@section('title', 'Configuración')
@section('page-title', 'Configuración de la Empresa')
@section('breadcrumb')<span class="current">Configuración</span>@endsection

@push('styles')
<style>
    .set-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;align-items:start}
    .set-full{grid-column:1 / -1}
    .logo-prev{width:96px;height:96px;border-radius:18px;display:flex;align-items:center;justify-content:center;
        font-size:38px;color:#fff;overflow:hidden;box-shadow:0 10px 24px -10px rgba(0,0,0,.35);flex-shrink:0}
    .file-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border:1px dashed var(--border);
        border-radius:10px;cursor:pointer;font-size:13px;font-weight:600;color:var(--text-secondary)}
    .file-btn:hover{border-color:var(--primary);color:var(--primary)}
    .fmt-preview{background:var(--body-bg);border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center}
    .fmt-preview .big{font-size:28px;font-weight:800;color:var(--primary)}
    .swatches{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}
    .sw{width:30px;height:30px;border-radius:8px;cursor:pointer;border:3px solid transparent;transition:.15s}
    .sw:hover{transform:scale(1.12)}
    @media(max-width:900px){.set-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
@if($errors->any())
    <div class="alert alert-danger" style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;margin-bottom:16px;">
        <i class="fas fa-exclamation-triangle"></i> Revisa los campos: {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="set-grid">

        {{-- ── Datos de la empresa ── --}}
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-building"></i> Datos de la Empresa</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nombre comercial</label>
                    <input type="text" name="gym_name" class="form-control" value="{{ $settings['gym_name'] ?? ($gym->name ?? '') }}">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Razón social</label>
                        <input type="text" name="gym_legal_name" class="form-control" value="{{ $settings['gym_legal_name'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">RUC / NIF / RFC</label>
                        <input type="text" name="gym_tax_id" class="form-control" value="{{ $settings['gym_tax_id'] ?? '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="gym_address" class="form-control" value="{{ $settings['gym_address'] ?? ($gym->address ?? '') }}">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="gym_city" class="form-control" value="{{ $settings['gym_city'] ?? ($gym->city ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="gym_phone" class="form-control" value="{{ $settings['gym_phone'] ?? ($gym->phone ?? '') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Correo de contacto</label>
                    <input type="email" name="gym_email" class="form-control" value="{{ $settings['gym_email'] ?? ($gym->email ?? '') }}">
                </div>
            </div>
        </div>

        {{-- ── Identidad visual (logo + color) ── --}}
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-image"></i> Logo e Identidad</div></div>
            <div class="card-body">
                <div class="d-flex gap-3 align-center" style="margin-bottom:16px;">
                    <div class="logo-prev" id="logoPrev" style="background:linear-gradient(135deg,{{ $gym->primary_color ?? '#7c3aed' }},#7c3aed)">
                        @if(!empty($gym->logo))
                            <img id="logoImg" src="{{ asset('storage/'.$gym->logo) }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <span id="logoEmoji">🏋️</span><img id="logoImg" style="display:none;width:100%;height:100%;object-fit:cover">
                        @endif
                    </div>
                    <div>
                        <label class="file-btn"><i class="fas fa-upload"></i> Subir logo
                            <input type="file" name="logo" accept="image/*" hidden id="logoInput">
                        </label>
                        <div class="text-xs text-muted" style="margin-top:6px;">PNG, JPG o SVG · máx 1 MB</div>
                        @if(!empty($gym->logo))
                            <label style="display:flex;align-items:center;gap:6px;margin-top:10px;font-size:13px;color:var(--danger);cursor:pointer">
                                <input type="checkbox" name="remove_logo" value="1"> Quitar logo actual
                            </label>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Color principal</label>
                    <input type="color" name="primary_color" id="colorInput" value="{{ $settings['primary_color'] ?? ($gym->primary_color ?? '#7c3aed') }}"
                        style="width:100%;height:44px;border:1px solid var(--border);border-radius:10px;padding:4px;cursor:pointer">
                    <div class="swatches">
                        @foreach(['#7c3aed','#ec4899','#3b82f6','#10b981','#f59e0b','#ef4444','#0ea5e9','#111827'] as $c)
                            <span class="sw" style="background:{{ $c }}" data-color="{{ $c }}"></span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Moneda y formato de número ── --}}
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-dollar-sign"></i> Moneda y Formato de Número</div></div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Moneda</label>
                        <select name="currency" id="curCode" class="form-control">
                            @php $cur = $settings['currency'] ?? 'USD'; @endphp
                            <option value="USD" {{ $cur=='USD'?'selected':'' }}>USD - Dólar</option>
                            <option value="PEN" {{ $cur=='PEN'?'selected':'' }}>PEN - Sol</option>
                            <option value="MXN" {{ $cur=='MXN'?'selected':'' }}>MXN - Peso Mexicano</option>
                            <option value="COP" {{ $cur=='COP'?'selected':'' }}>COP - Peso Colombiano</option>
                            <option value="ARS" {{ $cur=='ARS'?'selected':'' }}>ARS - Peso Argentino</option>
                            <option value="CLP" {{ $cur=='CLP'?'selected':'' }}>CLP - Peso Chileno</option>
                            <option value="EUR" {{ $cur=='EUR'?'selected':'' }}>EUR - Euro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Símbolo</label>
                        <input type="text" name="currency_symbol" id="curSym" class="form-control" value="{{ $settings['currency_symbol'] ?? '$' }}" maxlength="5">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Posición del símbolo</label>
                        <select name="currency_position" id="curPos" class="form-control">
                            @php $pos = $settings['currency_position'] ?? 'before'; @endphp
                            <option value="before" {{ $pos=='before'?'selected':'' }}>Antes ($100)</option>
                            <option value="after"  {{ $pos=='after'?'selected':'' }}>Después (100 $)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Decimales</label>
                        <select name="decimals" id="curDec" class="form-control">
                            @php $dec = (string)($settings['decimals'] ?? '2'); @endphp
                            @foreach(['0','1','2','3','4'] as $d)
                                <option value="{{ $d }}" {{ $dec===$d?'selected':'' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Separador de miles</label>
                        <select name="thousands_sep" id="curTh" class="form-control">
                            @php $th = $settings['thousands_sep'] ?? 'coma'; @endphp
                            <option value="coma"    {{ $th=='coma'?'selected':'' }}>Coma (1,000)</option>
                            <option value="punto"   {{ $th=='punto'?'selected':'' }}>Punto (1.000)</option>
                            <option value="espacio" {{ $th=='espacio'?'selected':'' }}>Espacio (1 000)</option>
                            <option value="ninguno" {{ $th=='ninguno'?'selected':'' }}>Ninguno (1000)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Separador decimal</label>
                        <select name="decimal_sep" id="curDecSep" class="form-control">
                            @php $ds = $settings['decimal_sep'] ?? 'punto'; @endphp
                            <option value="punto" {{ $ds=='punto'?'selected':'' }}>Punto (10.50)</option>
                            <option value="coma"  {{ $ds=='coma'?'selected':'' }}>Coma (10,50)</option>
                        </select>
                    </div>
                </div>
                <div class="fmt-preview">
                    <div class="text-xs text-muted">Vista previa</div>
                    <div class="big" id="fmtPreview">$1,234.50</div>
                </div>
            </div>
        </div>

        {{-- ── Impuestos + Recibos + Regional ── --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><div class="card-title"><i class="fas fa-percent"></i> Impuestos</div></div>
                <div class="card-body">
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Nombre del impuesto</label>
                            <input type="text" name="tax_name" class="form-control" value="{{ $settings['tax_name'] ?? 'IGV' }}" placeholder="IGV / IVA">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tasa (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="tax_rate" class="form-control" value="{{ $settings['tax_rate'] ?? '0' }}">
                        </div>
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer">
                        <input type="checkbox" name="tax_included" value="1" {{ ($settings['tax_included'] ?? '0')=='1'?'checked':'' }}>
                        Los precios ya incluyen el impuesto
                    </label>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><div class="card-title"><i class="fas fa-receipt"></i> Recibos</div></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Prefijo de recibo</label>
                        <input type="text" name="receipt_prefix" class="form-control" value="{{ $settings['receipt_prefix'] ?? 'REC-' }}" maxlength="12">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pie de recibo</label>
                        <textarea name="receipt_footer" class="form-control" rows="2" placeholder="Gracias por su preferencia">{{ $settings['receipt_footer'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><div class="card-title"><i class="fas fa-globe"></i> Regional</div></div>
                <div class="card-body">
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Zona horaria</label>
                            <select name="timezone" class="form-control">
                                @php $tz = $settings['timezone'] ?? 'America/Lima'; @endphp
                                @foreach(['America/Lima'=>'Lima (PE)','America/Mexico_City'=>'Ciudad de México (MX)','America/Bogota'=>'Bogotá (CO)','America/Argentina/Buenos_Aires'=>'Buenos Aires (AR)','America/Santiago'=>'Santiago (CL)','Europe/Madrid'=>'Madrid (ES)','UTC'=>'UTC'] as $v=>$lbl)
                                    <option value="{{ $v }}" {{ $tz==$v?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Formato de fecha</label>
                            <select name="date_format" class="form-control">
                                @php $df = $settings['date_format'] ?? 'd/m/Y'; @endphp
                                <option value="d/m/Y" {{ $df=='d/m/Y'?'selected':'' }}>31/12/2026</option>
                                <option value="m/d/Y" {{ $df=='m/d/Y'?'selected':'' }}>12/31/2026</option>
                                <option value="Y-m-d" {{ $df=='Y-m-d'?'selected':'' }}>2026-12-31</option>
                                <option value="d-m-Y" {{ $df=='d-m-Y'?'selected':'' }}>31-12-2026</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Hora apertura</label>
                            <input type="time" name="gym_opening" class="form-control" value="{{ $settings['gym_opening'] ?? '06:00' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hora cierre</label>
                            <input type="time" name="gym_closing" class="form-control" value="{{ $settings['gym_closing'] ?? '22:00' }}">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Idioma</label>
                        <select name="locale" class="form-control">
                            @php $lc = $settings['locale'] ?? 'es'; @endphp
                            <option value="es" {{ $lc=='es'?'selected':'' }}>Español</option>
                            <option value="en" {{ $lc=='en'?'selected':'' }}>English</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4" style="display:flex;gap:12px;align-items:center;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Configuración</button>
        <span class="text-sm text-muted">Estos ajustes aplican a todo tu gimnasio.</span>
    </div>
</form>

@push('scripts')
<script>
(function(){
    // Vista previa del logo
    const logoInput = document.getElementById('logoInput');
    if(logoInput){
        logoInput.addEventListener('change', e=>{
            const f = e.target.files[0]; if(!f) return;
            const img = document.getElementById('logoImg');
            const emoji = document.getElementById('logoEmoji');
            img.src = URL.createObjectURL(f); img.style.display='block'; if(emoji) emoji.style.display='none';
        });
    }
    // Color: swatches + preview del logo
    const colorInput = document.getElementById('colorInput');
    const logoPrev = document.getElementById('logoPrev');
    function applyColor(c){ if(colorInput) colorInput.value=c; if(logoPrev) logoPrev.style.background='linear-gradient(135deg,'+c+',#7c3aed)'; }
    document.querySelectorAll('.sw').forEach(s=> s.addEventListener('click', ()=>applyColor(s.dataset.color)));
    if(colorInput) colorInput.addEventListener('input', e=>applyColor(e.target.value));

    // Vista previa del formato de número
    const sym=document.getElementById('curSym'), pos=document.getElementById('curPos'),
          dec=document.getElementById('curDec'), th=document.getElementById('curTh'),
          dsep=document.getElementById('curDecSep'), out=document.getElementById('fmtPreview');
    const THMAP={coma:',',punto:'.',espacio:' ',ninguno:''};
    const DSMAP={punto:'.',coma:','};
    function fmt(){
        if(!out) return;
        const d=parseInt(dec.value||'2');
        const ds=DSMAP[dsep.value]||'.';
        const ts=(th.value in THMAP)?THMAP[th.value]:',';
        const parts=(1234.5).toFixed(d).split('.');
        const ent=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ts);
        const frac=parts[1];
        const num=(frac!==undefined)?ent+ds+frac:ent;
        out.textContent = pos.value==='after' ? num+' '+(sym.value||'$') : (sym.value||'$')+num;
    }
    [sym,pos,dec,th,dsep].forEach(el=> el && el.addEventListener('input', fmt));
    fmt();
})();
</script>
@endpush
@endsection
