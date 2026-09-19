@extends('layouts.app')
@section('title',$doc->numero)
@section('page-title','Comprobante '.$doc->numero)
@section('breadcrumb')<a href="{{ route('sunat.docs.index') }}">Comprobantes</a> <span class="sep">/</span> <span class="current">{{ $doc->numero }}</span>@endsection

@push('styles')
<style>
    .chip{display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700}
    .c-success{background:#dcfce7;color:#15803d}.c-info{background:#dbeafe;color:#1e40af}
    .c-warning{background:#fef3c7;color:#92400e}.c-danger{background:#fee2e2;color:#b91c1c}.c-gray{background:#f3f4f6;color:#6b7280}
    .kv{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);font-size:14px}
    .kv span:first-child{color:var(--text-secondary)}
</style>
@endpush

@section('content')

@include('billing_sunat._hero', ['subtitle' => 'Detalle del comprobante '.$doc->numero.' · '.$doc->tipo_nombre.'.', 'icon' => 'fa-file-invoice'])

<div style="max-width:820px">
    <div class="card">
        <div class="card-body" style="padding:26px">
            <div class="d-flex justify-between align-center" style="flex-wrap:wrap;gap:12px;margin-bottom:16px">
                <div>
                    <h2 style="font-size:22px;font-weight:800;margin:0">{{ $doc->tipo_nombre }} {{ $doc->numero }}</h2>
                    <div class="text-muted">{{ $doc->fecha_emision?->format('d/m/Y H:i') }}</div>
                </div>
                <span class="chip c-{{ $doc->estadoBadge() }}">{{ ucfirst($doc->estado) }}</span>
            </div>

            @if($doc->sunat_description)
                <div class="alert {{ $doc->estado==='aceptado' ? 'alert-success' : 'alert-warning' }}" style="margin-bottom:16px">
                    <i class="fas fa-info-circle"></i> SUNAT [{{ $doc->sunat_code }}]: {{ $doc->sunat_description }}
                </div>
            @endif

            <div class="grid-2" style="gap:24px">
                <div>
                    <div class="text-xs text-muted" style="text-transform:uppercase;font-weight:700;letter-spacing:.5px;margin-bottom:6px">Cliente</div>
                    <div style="font-weight:600">{{ $doc->cliente_razon_social }}</div>
                    <div class="text-muted text-sm">{{ ['0'=>'Sin doc','1'=>'DNI','6'=>'RUC'][$doc->cliente_tipo_doc] ?? '' }} {{ $doc->cliente_num_doc }}</div>
                    <div class="text-muted text-sm">{{ $doc->cliente_direccion }}</div>
                </div>
                <div>
                    <div class="kv"><span>Op. gravada</span><b>{{ number_format($doc->mto_oper_gravadas,2) }}</b></div>
                    <div class="kv"><span>IGV</span><b>{{ number_format($doc->mto_igv,2) }}</b></div>
                    <div class="kv" style="border:none"><span style="font-weight:700;color:var(--text-primary)">Total</span><b style="font-size:18px;color:var(--primary)">{{ number_format($doc->total,2) }} {{ $doc->moneda }}</b></div>
                </div>
            </div>

            @if($doc->doc_afectado_serie_num)
                <div style="margin-top:14px;font-size:13px;color:var(--text-secondary)">
                    <i class="fas fa-link"></i> Documento afectado: <b>{{ $doc->doc_afectado_serie_num }}</b> · Motivo: {{ $doc->des_motivo }}
                </div>
            @endif

            <div style="margin-top:18px;font-size:13px">
                <div class="text-xs text-muted" style="text-transform:uppercase;font-weight:700;letter-spacing:.5px;margin-bottom:8px">Detalle</div>
                @foreach(($doc->items ?? []) as $it)
                    <div class="kv"><span>{{ $it['descripcion'] ?? '' }} (x{{ $it['cantidad'] ?? 1 }})</span><b>{{ number_format(($it['mto_valor_unitario'] ?? 0)*($it['cantidad'] ?? 1),2) }}</b></div>
                @endforeach
            </div>

            {{-- Acciones --}}
            <div class="d-flex" style="gap:8px;flex-wrap:wrap;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
                <a href="{{ route('sunat.docs.pdf',$doc) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-file-pdf"></i> PDF / Imprimir</a>
                @if($doc->xml_path)
                    <a href="{{ route('sunat.docs.xml',$doc) }}" class="btn btn-outline btn-sm"><i class="fas fa-code"></i> XML</a>
                @endif
                @if($doc->cdr_path)
                    <a href="{{ route('sunat.docs.cdr',$doc) }}" class="btn btn-outline btn-sm"><i class="fas fa-file-zipper"></i> CDR</a>
                @endif
                @if(in_array($doc->estado,['pendiente','error','rechazado']))
                    <form method="POST" action="{{ route('sunat.docs.emit',$doc) }}" style="display:inline">
                        @csrf
                        <button class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Reenviar a SUNAT</button>
                    </form>
                @endif
                @if(in_array($doc->tipo_doc,['01','03']) && $doc->estado==='aceptado')
                    <a href="{{ route('sunat.docs.note.create',$doc) }}" class="btn btn-outline btn-sm"><i class="fas fa-file-circle-minus"></i> Nota de crédito/débito</a>
                @endif
                <a href="{{ route('sunat.docs.index') }}" class="btn btn-ghost btn-sm">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection
