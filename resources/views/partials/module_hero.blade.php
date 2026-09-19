{{-- Encabezado (banner) reutilizable con el color del sistema.
     Parámetros: $title, $subtitle (opcional), $icon (clase FontAwesome, opcional). --}}
@push('styles')
<style>
    .mh-hero{position:relative;overflow:hidden;border-radius:18px;padding:18px 24px;color:#fff;margin-bottom:20px;
        display:flex;align-items:center;gap:15px;
        background:var(--primary,#7c3aed);
        background:linear-gradient(135deg,color-mix(in srgb,var(--primary,#7c3aed) 78%,#000),var(--primary,#7c3aed));
        box-shadow:0 16px 34px -18px color-mix(in srgb,var(--primary,#7c3aed) 60%,transparent)}
    .mh-hero::after{content:"";position:absolute;right:-36px;top:-36px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.08)}
    .mh-hero .mh-ic{width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;position:relative;z-index:1}
    .mh-hero h2{margin:0;font-size:19px;font-weight:800;position:relative;z-index:1}
    .mh-hero p{margin:3px 0 0;opacity:.9;font-size:12.5px;position:relative;z-index:1;line-height:1.45}
</style>
@endpush

<div class="mh-hero">
    <div class="mh-ic"><i class="fas {{ $icon ?? 'fa-layer-group' }}"></i></div>
    <div>
        <h2>{{ $title }}</h2>
        @isset($subtitle)<p>{{ $subtitle }}</p>@endisset
    </div>
</div>
