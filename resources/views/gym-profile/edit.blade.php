@extends('layouts.app')
@section('title', 'Mi Gimnasio')
@section('page-title', 'Mi Gimnasio')
@section('breadcrumb')<span class="current">Marca</span>@endsection

@push('styles')
<style>
    .brand-grid{display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start}
    .panel{background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px}
    .panel h3{font-size:15px;font-weight:700;margin-bottom:18px}
    .fg{margin-bottom:16px}
    .fg label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
    .fg input{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit}
    .fg input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.12)}
    .r2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .logo-prev{width:120px;height:120px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:46px;color:#fff;margin:0 auto 16px;overflow:hidden;box-shadow:0 12px 28px -10px rgba(0,0,0,.3)}
    .swatches{display:flex;gap:10px;flex-wrap:wrap;margin-top:8px}
    .sw{width:34px;height:34px;border-radius:9px;cursor:pointer;border:3px solid transparent;transition:.15s}
    .sw:hover{transform:scale(1.1)}
    .file-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border:1px dashed var(--border);border-radius:10px;cursor:pointer;font-size:13px;font-weight:600;color:var(--text-secondary)}
    .file-btn:hover{border-color:var(--primary);color:var(--primary)}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('gym.profile.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="brand-grid">
        {{-- Logo + color --}}
        <div class="panel" style="text-align:center">
            <h3 style="text-align:left">Identidad visual</h3>
            <div class="logo-prev" id="logoPrev" style="background:linear-gradient(135deg,{{ $gym->primary_color ?? '#7c3aed' }},#7c3aed)">
                @if($gym->logo)
                    <img id="logoImg" src="{{ asset('storage/'.$gym->logo) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    <span id="logoEmoji">🏋️</span><img id="logoImg" style="display:none;width:100%;height:100%;object-fit:cover">
                @endif
            </div>

            <label class="file-btn"><i class="fas fa-upload"></i> Subir logo
                <input type="file" name="logo" accept="image/*" hidden id="logoInput">
            </label>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:8px">PNG, JPG o SVG · máx 1 MB</div>

            @if($gym->logo)
                <label style="display:flex;align-items:center;gap:7px;justify-content:center;margin-top:12px;font-size:13px;color:var(--danger);cursor:pointer">
                    <input type="checkbox" name="remove_logo" value="1"> Quitar logo actual
                </label>
            @endif

            <div style="margin-top:22px;text-align:left">
                <label style="font-size:13px;font-weight:600;display:block;margin-bottom:8px">Color principal</label>
                <input type="color" name="primary_color" id="colorInput" value="{{ $gym->primary_color ?? '#7c3aed' }}" style="width:100%;height:46px;border:1px solid var(--border);border-radius:10px;padding:4px;cursor:pointer">
                <div class="swatches">
                    @foreach(['#7c3aed','#ec4899','#3b82f6','#10b981','#f59e0b','#ef4444','#0ea5e9','#111827'] as $c)
                        <span class="sw" style="background:{{ $c }}" data-color="{{ $c }}"></span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Datos del gimnasio --}}
        <div class="panel">
            <h3>Información del gimnasio</h3>
            <div class="fg"><label>Nombre del gimnasio *</label><input name="name" value="{{ old('name',$gym->name) }}" required></div>
            <div class="r2">
                <div class="fg"><label>Correo</label><input type="email" name="email" value="{{ old('email',$gym->email) }}"></div>
                <div class="fg"><label>Teléfono</label><input name="phone" value="{{ old('phone',$gym->phone) }}"></div>
            </div>
            <div class="fg"><label>Dirección</label><input name="address" value="{{ old('address',$gym->address) }}"></div>
            <div class="fg"><label>Ciudad</label><input name="city" value="{{ old('city',$gym->city) }}"></div>

            <div style="background:#f9fafb;border:1px solid var(--border);border-radius:12px;padding:14px;font-size:13px;color:var(--text-secondary);margin-top:6px">
                <i class="fas fa-circle-info" style="color:var(--primary)"></i> Tu logo y color se reflejan en el menú lateral y en el sistema de tu gimnasio.
            </div>

            <div style="display:flex;gap:10px;margin-top:18px">
                <button class="btn btn-primary"><i class="fas fa-check"></i> Guardar cambios</button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </div>
    </div>
</form>

<script>
    const colorInput = document.getElementById('colorInput');
    const logoPrev   = document.getElementById('logoPrev');
    const logoInput  = document.getElementById('logoInput');

    function applyColor(c){ logoPrev.style.background = 'linear-gradient(135deg,'+c+',#7c3aed)'; }
    colorInput.addEventListener('input', e => applyColor(e.target.value));
    document.querySelectorAll('.sw').forEach(s => s.addEventListener('click', () => {
        colorInput.value = s.dataset.color; applyColor(s.dataset.color);
    }));

    logoInput.addEventListener('change', e => {
        const f = e.target.files[0]; if(!f) return;
        const url = URL.createObjectURL(f);
        const img = document.getElementById('logoImg');
        const emoji = document.getElementById('logoEmoji');
        img.src = url; img.style.display = 'block';
        if(emoji) emoji.style.display = 'none';
    });
</script>
@endsection
