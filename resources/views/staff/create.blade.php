@extends('layouts.app')
@section('title', 'Agregar miembro')
@section('page-title', 'Agregar miembro del equipo')
@section('breadcrumb')<a href="{{ route('staff.index') }}">Equipo</a> · <span class="current">Nuevo</span>@endsection

@section('content')
<form method="POST" action="{{ route('staff.store') }}" class="sform">
    @csrf
    @include('staff._form')
    <div style="display:flex;gap:10px;margin-top:8px">
        <button class="btn btn-primary"><i class="fas fa-check"></i> Agregar</button>
        <a href="{{ route('staff.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>
@endsection
