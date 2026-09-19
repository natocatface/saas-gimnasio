@extends('layouts.app')

@section('title', 'Registrar Pago')
@section('page-title', 'Registrar Pago')
@section('breadcrumb')<a href="{{ route('payments.index') }}">Pagos</a><span class="sep">/</span><span class="current">Nuevo Pago</span>@endsection

@section('content')
<div style="max-width:700px;width:100%;">
<form method="POST" action="{{ route('payments.store') }}">
    @csrf

    @if($errors->any())
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    </div>
    @endif

    @if($selectedMember)
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle"></i>
        Registrando pago para: <strong>{{ $selectedMember->full_name }}</strong> ({{ $selectedMember->code }})
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><div class="card-title"><i class="fas fa-receipt"></i> Información del Pago</div></div>
        <div class="card-body">
            <div class="grid-2">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Socio *</label>
                    <select name="member_id" class="form-control" required id="memberSelect" onchange="loadMemberPlan()">
                        <option value="">Seleccionar socio...</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}"
                                data-plan="{{ $m->plan_id }}"
                                data-price="{{ optional($m->plan)->price }}"
                                {{ (old('member_id', optional($selectedMember)->id)==$m->id)?'selected':'' }}>
                                {{ $m->full_name }} — {{ $m->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Plan</label>
                    <select name="plan_id" class="form-control" id="planSelect" onchange="updateAmount()">
                        <option value="">Sin plan</option>
                        @foreach($plans as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-days="{{ $p->duration_days }}"
                                {{ old('plan_id')==$p->id?'selected':'' }}>
                                {{ $p->name }} — {{ money($p->price,2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Monto *</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-weight:700;color:var(--text-secondary);">{{ $currency }}</span>
                        <input type="number" name="amount" id="amountInput" class="form-control" step="0.01" min="0"
                            value="{{ old('amount') }}" required style="padding-left:28px;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Fecha de Pago *</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Método de Pago *</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="efectivo" {{ old('payment_method')=='efectivo'?'selected':'' }}>💵 Efectivo</option>
                        <option value="tarjeta"  {{ old('payment_method')=='tarjeta' ?'selected':'' }}>💳 Tarjeta</option>
                        <option value="transferencia" {{ old('payment_method')=='transferencia'?'selected':'' }}>🏦 Transferencia</option>
                        <option value="qr"       {{ old('payment_method')=='qr'     ?'selected':'' }}>📱 QR / Digital</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Referencia / Comprobante</label>
                    <input type="text" name="reference" class="form-control" value="{{ old('reference') }}" placeholder="Número de operación...">
                </div>
                <div class="form-group">
                    <label class="form-label">Período Inicio</label>
                    <input type="date" name="period_start" class="form-control" id="periodStart"
                        value="{{ old('period_start', date('Y-m-d')) }}" onchange="updateEndPeriod()">
                </div>
                <div class="form-group">
                    <label class="form-label">Período Fin</label>
                    <input type="date" name="period_end" class="form-control" id="periodEnd" value="{{ old('period_end') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="pagado"   {{ old('status','pagado')=='pagado'  ?'selected':'' }}>✅ Pagado</option>
                        <option value="pendiente"{{ old('status')=='pendiente'?'selected':'' }}>⏳ Pendiente</option>
                        <option value="cancelado"{{ old('status')=='cancelado'?'selected':'' }}>❌ Cancelado</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Notas</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar Pago</button>
        <a href="{{ route('payments.index') }}" class="btn btn-ghost">Cancelar</a>
    </div>
</form>
</div>

@push('scripts')
<script>
function updateAmount() {
    const sel   = document.getElementById('planSelect');
    const price = sel.selectedOptions[0]?.dataset?.price;
    if (price) document.getElementById('amountInput').value = parseFloat(price).toFixed(2);
    updateEndPeriod();
}

function updateEndPeriod() {
    const sel  = document.getElementById('planSelect');
    const days = sel.selectedOptions[0]?.dataset?.days;
    const start = document.getElementById('periodStart').value;
    if (!days || !start) return;
    const d = new Date(start);
    d.setDate(d.getDate() + parseInt(days));
    document.getElementById('periodEnd').value = d.toISOString().split('T')[0];
}

function loadMemberPlan() {
    const sel    = document.getElementById('memberSelect');
    const planId = sel.selectedOptions[0]?.dataset?.plan;
    if (!planId) return;
    const planSel = document.getElementById('planSelect');
    for (const opt of planSel.options) {
        if (opt.value == planId) { opt.selected = true; break; }
    }
    updateAmount();
}
</script>
@endpush
@endsection
