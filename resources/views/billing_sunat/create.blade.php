@extends('layouts.app')
@section('title','Nuevo comprobante')
@section('page-title','Emitir Comprobante')
@section('breadcrumb')<a href="{{ route('sunat.docs.index') }}">Comprobantes</a> <span class="sep">/</span> <span class="current">Nuevo</span>@endsection

@section('content')

@include('billing_sunat._hero', ['subtitle' => 'Emite una boleta o factura y envíala a SUNAT.', 'icon' => 'fa-file-circle-plus'])

@if($errors->any())<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}</div>@endif

<form method="POST" action="{{ route('sunat.docs.store') }}" style="max-width:820px">
    @csrf
    @if($payment)<input type="hidden" name="payment_id" value="{{ $payment->id }}">@endif

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-file-invoice"></i> Datos del comprobante</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Tipo de comprobante *</label>
                    <select name="tipo_doc" id="tipoDoc" class="form-control" required>
                        <option value="03" @selected(old('tipo_doc')==='03')>Boleta de venta</option>
                        <option value="01" @selected(old('tipo_doc')==='01')>Factura</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipo de documento del cliente *</label>
                    <select name="cliente_tipo_doc" id="cliTipo" class="form-control" required>
                        <option value="1" @selected(old('cliente_tipo_doc','1')==='1')>DNI</option>
                        <option value="6" @selected(old('cliente_tipo_doc')==='6')>RUC</option>
                        <option value="0" @selected(old('cliente_tipo_doc')==='0')>Sin documento</option>
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">N.° de documento</label>
                    <input name="cliente_num_doc" class="form-control" value="{{ old('cliente_num_doc', $payment?->member?->code ? '' : '') }}" placeholder="DNI (8) o RUC (11)">
                </div>
                <div class="form-group">
                    <label class="form-label">Nombre / Razón social *</label>
                    <input name="cliente_razon_social" class="form-control" required
                           value="{{ old('cliente_razon_social', $payment && $payment->member ? ($payment->member->first_name.' '.$payment->member->last_name) : '') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Dirección del cliente</label>
                <input name="cliente_direccion" class="form-control" value="{{ old('cliente_direccion') }}">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Descripción / Concepto *</label>
                    <input name="descripcion" class="form-control" required
                           value="{{ old('descripcion', $payment && $payment->plan ? ('Membresía '.$payment->plan->name) : 'Servicio de gimnasio') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Total (incluye IGV) *</label>
                    <input type="number" step="0.01" name="total" class="form-control" required
                           value="{{ old('total', $payment?->amount) }}">
                </div>
            </div>

            <div class="alert alert-info" style="margin-top:6px">
                <i class="fas fa-circle-info"></i> El IGV se calcula automáticamente a partir del total. La <b>Factura</b> exige cliente con <b>RUC</b>.
            </div>
        </div>
    </div>

    <div style="margin-top:16px">
        <button class="btn btn-primary"><i class="fas fa-paper-plane"></i> Emitir y enviar a SUNAT</button>
        <a href="{{ route('sunat.docs.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>

<script>
    // Al elegir Factura, forzar RUC
    const tipoDoc=document.getElementById('tipoDoc'), cliTipo=document.getElementById('cliTipo');
    function sync(){ if(tipoDoc.value==='01'){ cliTipo.value='6'; } }
    tipoDoc.addEventListener('change',sync);
</script>
@endsection
