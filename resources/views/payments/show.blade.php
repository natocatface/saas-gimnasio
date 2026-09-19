@extends('layouts.app')
@section('title','Recibo de Pago')
@section('page-title','Detalle de Pago')
@section('breadcrumb')
<a href="{{ route('payments.index') }}">Pagos</a>
<span class="sep">/</span>
<span class="current">Pago #{{ $payment->id }}</span>
@endsection

@section('content')
<div style="max-width:700px;">

    <div class="card">
        <!-- Receipt Header -->
        <div style="background:linear-gradient(135deg,#4c1d95,#7c3aed,#ec4899);padding:32px;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;width:200px;height:200px;background:rgba(255,255,255,0.05);border-radius:50%;top:-60px;right:-40px;"></div>
            <div style="font-size:36px;margin-bottom:8px;">🧾</div>
            <h2 style="color:white;font-size:22px;font-weight:700;margin-bottom:4px;">Recibo de Pago</h2>
            <p style="color:rgba(255,255,255,0.7);font-size:14px;">GymSaaS Pro</p>
            <div style="margin-top:16px;">
                <span class="badge {{ $payment->status==='pagado'?'badge-success':($payment->status==='pendiente'?'badge-warning':'badge-danger') }}"
                    style="font-size:14px;padding:8px 20px;">
                    {{ strtoupper($payment->status) }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <!-- Amount -->
            <div style="text-align:center;padding:24px 0;border-bottom:1px solid var(--border);">
                <div style="font-size:48px;font-weight:800;color:var(--success);">{{ money($payment->amount,2) }}</div>
                <div class="text-sm text-muted">Monto pagado</div>
            </div>

            <!-- Details -->
            <div style="display:flex;flex-direction:column;gap:0;margin-top:20px;">
                @php
                    $rows = [
                        ['Nº de Recibo', '#'.$payment->id],
                        ['Socio', optional($payment->member)->full_name ?? '—'],
                        ['Código', optional($payment->member)->code ?? '—'],
                        ['Plan', optional($payment->plan)->name ?? '—'],
                        ['Fecha de Pago', \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y')],
                        ['Método', ucfirst($payment->payment_method)],
                        ['Referencia', $payment->reference ?? '—'],
                        ['Período', ($payment->period_start && $payment->period_end) ? \Carbon\Carbon::parse($payment->period_start)->format('d/m/Y').' → '.\Carbon\Carbon::parse($payment->period_end)->format('d/m/Y') : '—'],
                    ];
                @endphp
                @foreach($rows as $row)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:13px 0;border-bottom:1px solid #f3f4f6;">
                    <span class="text-sm text-muted">{{ $row[0] }}</span>
                    <span class="text-sm fw-600">{{ $row[1] }}</span>
                </div>
                @endforeach
            </div>

            @if($payment->notes)
            <div style="background:var(--body-bg);border-radius:10px;padding:14px;margin-top:16px;">
                <div class="text-xs text-muted fw-600 mb-2">NOTAS</div>
                <div class="text-sm">{{ $payment->notes }}</div>
            </div>
            @endif
        </div>

        <div class="modal-footer">
            <a href="{{ route('payments.edit',$payment) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> Editar</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('sunat.docs.create', ['payment_id' => $payment->id]) }}" class="btn btn-outline btn-sm"><i class="fas fa-file-invoice"></i> Facturar</a>
            @endif
            <a href="{{ route('payments.receipt.pdf',$payment) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-file-pdf"></i> Recibo PDF</a>
            <a href="{{ route('payments.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
            <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Imprimir</button>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .modal-footer, .alert { display: none !important; }
    .main-content { margin-left: 0 !important; }
    .page-content { padding: 0 !important; }
}
</style>
@endpush
@endsection
