@extends('layouts.superadmin')
@section('title','Editar cupón')
@section('page-title','Editar cupón')
@section('breadcrumb')<a href="{{ route('superadmin.coupons.index') }}">Cupones</a> · Editar@endsection

@section('content')
<div class="card" style="max-width:740px">
    <form method="POST" action="{{ route('superadmin.coupons.update',$coupon) }}">
        @csrf @method('PUT')
        @include('superadmin.coupons._form')
        <div style="display:flex;gap:10px;margin-top:8px">
            <button class="btn btn-primary"><i class="fas fa-check"></i> Guardar cambios</button>
            <a href="{{ route('superadmin.coupons.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection
