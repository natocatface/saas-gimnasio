@extends('layouts.superadmin')
@section('title','Registrar cobro')
@section('page-title','Registrar cobro / renovación')
@section('breadcrumb')<a href="{{ route('superadmin.subscriptions.index') }}">Suscripciones</a> · Nueva@endsection

@section('content')
<div class="card" style="max-width:680px">
    <p style="color:var(--muted);font-size:14px;margin-bottom:18px"><i class="fas fa-circle-info"></i> Al registrar el cobro, el gimnasio se marca como <strong>activo</strong> y se actualiza su plan.</p>
    <form method="POST" action="{{ route('superadmin.subscriptions.store') }}">
        @csrf
        <div class="grid" style="grid-template-columns:1fr 1fr">
            <div class="form-group" style="grid-column:1/-1"><label>Gimnasio *</label>
                <select name="gymnasium_id" class="form-control" required>
                    <option value="">Selecciona un gimnasio</option>
                    @foreach($gyms as $g)<option value="{{ $g->id }}" @selected($gymId==$g->id)>{{ $g->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Plan SaaS *</label>
                <select name="saas_plan_id" id="planSel" class="form-control" required>
                    @foreach($plans as $p)<option value="{{ $p->id }}" data-m="{{ $p->price_monthly }}" data-y="{{ $p->price_yearly }}">{{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Ciclo de facturación *</label>
                <select name="billing_cycle" id="cycleSel" class="form-control" required>
                    <option value="monthly">Mensual</option>
                    <option value="yearly">Anual</option>
                </select>
            </div>
            <div class="form-group"><label>Monto ({{ currency_symbol() }}) *</label><input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="form-control" required></div>
            <div class="form-group"><label>Fecha de inicio *</label><input type="date" name="starts_at" value="{{ old('starts_at', now()->format('Y-m-d')) }}" class="form-control" required></div>
            <div class="form-group" style="grid-column:1/-1"><label>Referencia de pago</label><input name="payment_ref" value="{{ old('payment_ref') }}" class="form-control" placeholder="Ej: transferencia #12345"></div>
            <div class="form-group" style="grid-column:1/-1"><label>Notas</label><textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea></div>
        </div>
        <div style="display:flex;gap:10px;margin-top:10px">
            <button class="btn btn-primary"><i class="fas fa-check"></i> Registrar y activar</button>
            <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>

<script>
    const planSel=document.getElementById('planSel'),cycleSel=document.getElementById('cycleSel'),amount=document.getElementById('amount');
    function sync(){const o=planSel.options[planSel.selectedIndex];amount.value=cycleSel.value==='yearly'?o.dataset.y:o.dataset.m;}
    planSel.addEventListener('change',sync);cycleSel.addEventListener('change',sync);
    if(!amount.value)sync();
</script>
@endsection
