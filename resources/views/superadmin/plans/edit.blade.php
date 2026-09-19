@extends('layouts.superadmin')
@section('title','Editar plan')
@section('page-title','Editar plan SaaS')
@section('breadcrumb')<a href="{{ route('superadmin.plans.index') }}">Planes SaaS</a> · Editar@endsection

@section('content')
<div class="card" style="max-width:760px">
    <form method="POST" action="{{ route('superadmin.plans.update',$plan) }}">
        @csrf @method('PUT')
        @include('superadmin.plans._form')
        <div style="display:flex;gap:10px;margin-top:10px">
            <button class="btn btn-primary"><i class="fas fa-check"></i> Guardar cambios</button>
            <a href="{{ route('superadmin.plans.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection
