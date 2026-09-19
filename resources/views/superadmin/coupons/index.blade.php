@extends('layouts.superadmin')
@section('title','Cupones')
@section('page-title','Cupones de descuento')
@section('breadcrumb','Promociones para los gimnasios')

@section('actions')
    <a href="{{ route('superadmin.coupons.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo cupón</a>
@endsection

@section('content')
<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead><tr><th>Código</th><th>Descuento</th><th>Plan</th><th>Usos</th><th>Vence</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        @forelse($coupons as $c)
            <tr>
                <td><span style="font-family:monospace;font-weight:700;background:#f3effe;color:var(--primary);padding:4px 10px;border-radius:7px">{{ $c->code }}</span>
                    @if($c->description)<div style="font-size:12px;color:var(--muted);margin-top:4px">{{ $c->description }}</div>@endif</td>
                <td style="font-weight:700">{{ $c->labelValue() }}{{ $c->type==='percent'?'':'' }}</td>
                <td>{{ $c->saasPlan->name ?? 'Todos' }}</td>
                <td>{{ $c->used_count }}{{ $c->max_uses ? ' / '.$c->max_uses : '' }}</td>
                <td style="color:var(--muted)">{{ $c->expires_at ? $c->expires_at->format('d/m/Y') : 'Sin límite' }}</td>
                <td><span class="badge {{ $c->is_active ? 'active':'cancelled' }}">{{ $c->is_active ? 'Activo':'Inactivo' }}</span></td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="{{ route('superadmin.coupons.edit',$c) }}" class="btn btn-light btn-sm"><i class="fas fa-pen"></i></a>
                    <form method="POST" action="{{ route('superadmin.coupons.destroy',$c) }}" style="display:inline" onsubmit="return confirm('¿Eliminar cupón?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty"><i class="fas fa-ticket"></i><div>Aún no hay cupones</div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
