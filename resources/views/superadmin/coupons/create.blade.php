@extends('layouts.superadmin')
@section('title','Nuevo cupón')
@section('page-title','Nuevo cupón')
@section('breadcrumb')<a href="{{ route('superadmin.coupons.index') }}">Cupones</a> · Crear@endsection

@section('content')
<div class="card" style="max-width:740px">
    <form method="POST" action="{{ route('superadmin.coupons.store') }}">
        @csrf
        @include('superadmin.coupons._form')
        <div style="display:flex;gap:10px;margin-top:8px">
            <button class="btn btn-primary"><i class="fas fa-check"></i> Crear cupón</button>
            <a href="{{ route('superadmin.coupons.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection
