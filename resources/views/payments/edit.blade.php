@extends('layouts.app')
@section('title','Editar Pago')
@section('page-title','Editar Pago')
@section('breadcrumb')<a href="{{ route('payments.index') }}">Pagos</a><span class="sep">/</span><span class="current">Editar</span>@endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('payments.update',$payment) }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-receipt"></i> Pago #{{ $payment->id }}</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Socio *</label>
                    <select name="member_id" class="form-control" required>
                        <option value="">Seleccionar...</option>
                        @foreach($members as $m)<option value="{{ $m->id }}" {{ old('member_id',$payment->member_id)==$m->id?'selected':'' }}>{{ $m->full_name }} ({{ $m->code }})</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Plan</label>
                    <select name="plan_id" class="form-control">
                        <option value="">Sin plan</option>
                        @foreach($plans as $p)<option value="{{ $p->id }}" {{ old('plan_id',$payment->plan_id)==$p->id?'selected':'' }}>{{ $p->name }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Monto *</label><input type="number" name="amount" class="form-control" step="0.01" value="{{ old('amount',$payment->amount) }}" required></div>
                <div class="form-group"><label class="form-label">Fecha Pago *</label><input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required></div>
                <div class="form-group"><label class="form-label">Método *</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="efectivo" {{ old('payment_method',$payment->payment_method)=='efectivo'?'selected':'' }}>💵 Efectivo</option>
                        <option value="tarjeta" {{ old('payment_method',$payment->payment_method)=='tarjeta'?'selected':'' }}>💳 Tarjeta</option>
                        <option value="transferencia" {{ old('payment_method',$payment->payment_method)=='transferencia'?'selected':'' }}>🏦 Transferencia</option>
                        <option value="qr" {{ old('payment_method',$payment->payment_method)=='qr'?'selected':'' }}>📱 QR</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Referencia</label><input type="text" name="reference" class="form-control" value="{{ old('reference',$payment->reference) }}"></div>
                <div class="form-group"><label class="form-label">Período Inicio</label><input type="date" name="period_start" class="form-control" value="{{ old('period_start', optional($payment->period_start)->format('Y-m-d')) }}"></div>
                <div class="form-group"><label class="form-label">Período Fin</label><input type="date" name="period_end" class="form-control" value="{{ old('period_end', optional($payment->period_end)->format('Y-m-d')) }}"></div>
                <div class="form-group"><label class="form-label">Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="pagado" {{ old('status',$payment->status)=='pagado'?'selected':'' }}>✅ Pagado</option>
                        <option value="pendiente" {{ old('status',$payment->status)=='pendiente'?'selected':'' }}>⏳ Pendiente</option>
                        <option value="cancelado" {{ old('status',$payment->status)=='cancelado'?'selected':'' }}>❌ Cancelado</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Notas</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$payment->notes) }}</textarea></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            <a href="{{ route('payments.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </div>
</form>
</div>
@endsection
