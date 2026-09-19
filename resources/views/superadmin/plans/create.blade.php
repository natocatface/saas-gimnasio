@extends('layouts.superadmin')
@section('title','Nuevo plan SaaS')
@section('page-title','Nuevo plan SaaS')
@section('breadcrumb')<a href="{{ route('superadmin.plans.index') }}">Planes SaaS</a> · Crear@endsection

@section('content')
<div class="card" style="max-width:760px">
    <form method="POST" action="{{ route('superadmin.plans.store') }}">
        @csrf
        @include('superadmin.plans._form')
        <div style="display:flex;gap:10px;margin-top:10px">
            <button class="btn btn-primary"><i class="fas fa-check"></i> Crear plan</button>
            <a href="{{ route('superadmin.plans.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection
