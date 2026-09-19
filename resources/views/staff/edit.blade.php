@extends('layouts.app')
@section('title', 'Editar miembro')
@section('page-title', 'Editar miembro del equipo')
@section('breadcrumb')<a href="{{ route('staff.index') }}">Equipo</a> · <span class="current">{{ $staff->name }}</span>@endsection

@section('content')
<form method="POST" action="{{ route('staff.update',$staff) }}" class="sform">
    @csrf @method('PUT')
    @include('staff._form')
    <div style="display:flex;gap:10px;margin-top:8px">
        <button class="btn btn-primary"><i class="fas fa-check"></i> Guardar cambios</button>
        <a href="{{ route('staff.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>
@endsection
