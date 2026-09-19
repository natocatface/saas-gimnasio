@extends('layouts.app')
@section('title','Nota de crédito/débito')
@section('page-title','Nota de Crédito / Débito')
@section('breadcrumb')<a href="{{ route('sunat.docs.show',$ref) }}">{{ $ref->numero }}</a> <span class="sep">/</span> <span class="current">Nota</span>@endsection

@section('content')

@include('billing_sunat._hero', ['subtitle' => 'Emite una nota de crédito o débito sobre '.$ref->numero.'.', 'icon' => 'fa-file-circle-minus'])

@if($errors->any())<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}</div>@endif

<form method="POST" action="{{ route('sunat.docs.note.store',$ref) }}" style="max-width:720px">
    @csrf
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-file-circle-minus"></i> Nota sobre {{ $ref->tipo_nombre }} {{ $ref->numero }}</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Tipo de nota *</label>
                    <select name="tipo_doc" class="form-control" required>
                        <option value="07">Nota de Crédito</option>
                        <option value="08">Nota de Débito</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Código de motivo *</label>
                    <select name="cod_motivo" class="form-control" required>
                        <option value="01">01 - Anulación de la operación</option>
                        <option value="02">02 - Anulación por error en el RUC</option>
                        <option value="03">03 - Corrección por error en la descripción</option>
                        <option value="06">06 - Devolución total</option>
                        <option value="07">07 - Devolución por ítem</option>
                        <option value="09">09 - Disminución en el valor</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Descripción del motivo *</label>
                <input name="des_motivo" class="form-control" required placeholder="Ej: Anulación de la operación">
            </div>
            <div class="form-group">
                <label class="form-label">Monto de la nota (incluye IGV) *</label>
                <input type="number" step="0.01" name="total" class="form-control" required value="{{ $ref->total }}">
            </div>
            <div class="alert alert-info"><i class="fas fa-circle-info"></i> La nota queda referenciada al comprobante <b>{{ $ref->numero }}</b> y se envía a SUNAT al guardar.</div>
        </div>
    </div>
    <div style="margin-top:16px">
        <button class="btn btn-primary"><i class="fas fa-paper-plane"></i> Emitir nota y enviar a SUNAT</button>
        <a href="{{ route('sunat.docs.show',$ref) }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>
@endsection
