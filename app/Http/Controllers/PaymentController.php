<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Plan;
use App\Models\BillingSetting;
use App\Models\ElectronicDocument;
use App\Services\Sunat\SunatService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['member','plan'])->latest();

        if ($request->search) {
            $q = $request->search;
            $query->whereHas('member', fn($mq) =>
                $mq->where('first_name','like',"%$q%")->orWhere('last_name','like',"%$q%")->orWhere('code','like',"%$q%")
            );
        }

        if ($request->status)  $query->where('status', $request->status);
        if ($request->method)  $query->where('payment_method', $request->method);
        if ($request->month)   $query->whereMonth('payment_date', $request->month);

        $payments = $query->paginate(15)->withQueryString();

        $totals = [
            'month'   => Payment::whereMonth('payment_date', now()->month)->where('status','pagado')->sum('amount'),
            'today'   => Payment::whereDate('payment_date', today())->where('status','pagado')->sum('amount'),
            'pending' => Payment::where('status','pendiente')->count(),
        ];

        return view('payments.index', compact('payments','totals'));
    }

    public function create(Request $request)
    {
        $members = Member::where('status','activo')->orderBy('first_name')->get();
        $plans   = Plan::where('status', 1)->get();
        $selectedMember = $request->member_id ? Member::find($request->member_id) : null;
        return view('payments.create', compact('members','plans','selectedMember'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'      => ['required', Rule::exists('members','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'plan_id'        => ['nullable', Rule::exists('plans','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'amount'         => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:efectivo,tarjeta,transferencia,qr',
            'reference'      => 'nullable|string|max:100',
            'period_start'   => 'nullable|date',
            'period_end'     => 'nullable|date',
            'status'         => 'required|in:pagado,pendiente,cancelado',
            'notes'          => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();

        $payment = Payment::create($data);

        // Update member's membership dates if payment is confirmed
        if ($data['status'] === 'pagado' && !empty($data['period_end'])) {
            $member = Member::find($data['member_id']);
            if ($member) {
                $member->update([
                    'membership_start' => $data['period_start'] ?? $member->membership_start,
                    'membership_end'   => $data['period_end'],
                    'plan_id'          => $data['plan_id'] ?? $member->plan_id,
                    'status'           => 'activo',
                ]);
            }
        }

        // Emisión electrónica automática (si está habilitada)
        $msg = 'Pago registrado exitosamente.';
        if ($data['status'] === 'pagado') {
            $auto = $this->autoEmit($payment);
            if ($auto) {
                $msg .= ' ' . $auto;
            }
        }

        return redirect()->route('payments.index')->with('success', $msg);
    }

    /**
     * Emite automáticamente una Boleta electrónica a partir del pago cuando la
     * facturación electrónica está habilitada con "Emitir automáticamente".
     * Nunca interrumpe el registro del pago (todo va protegido).
     */
    private function autoEmit(Payment $payment): ?string
    {
        try {
            $setting = BillingSetting::query()->first();
            if (!$setting || !$setting->enabled || !$setting->auto_emit) {
                return null;
            }

            $igvPct = (float) $setting->igv_percent;
            $total  = round((float) $payment->amount, 2);
            if ($total <= 0) {
                return null;
            }
            $base = round($total / (1 + $igvPct / 100), 2);
            $igv  = round($total - $base, 2);

            $next = $setting->nextCorrelativo('03');
            $member = $payment->member ?: Member::find($payment->member_id);
            $plan   = $payment->plan;

            $doc = new ElectronicDocument([
                'payment_id'           => $payment->id,
                'member_id'            => $payment->member_id,
                'tipo_doc'             => '03',
                'serie'                => $setting->serieFor('03'),
                'correlativo'          => (string) $next,
                'fecha_emision'        => now(),
                'moneda'               => $setting->moneda ?: 'PEN',
                'cliente_tipo_doc'     => '0',
                'cliente_num_doc'      => '-',
                'cliente_razon_social' => $member ? trim($member->first_name . ' ' . $member->last_name) : 'CLIENTE VARIOS',
                'items'                => [[
                    'codigo'             => 'P001',
                    'unidad'             => 'NIU',
                    'descripcion'        => $plan ? ('Membresía ' . $plan->name) : 'Servicio de gimnasio',
                    'cantidad'           => 1,
                    'mto_valor_unitario' => $base,
                ]],
                'mto_oper_gravadas'    => $base,
                'mto_igv'              => $igv,
                'total'                => $total,
                'estado'               => 'pendiente',
                'created_by'           => auth()->id(),
            ]);
            $doc->save();
            $setting->bumpCorrelativo('03', $next);

            $res = (new SunatService())->emit($doc);
            return 'Comprobante ' . $doc->numero . ': ' . $res['message'];
        } catch (\Throwable $e) {
            return 'No se pudo emitir el comprobante automático: ' . $e->getMessage();
        }
    }

    public function show(Payment $payment)
    {
        $payment->load(['member', 'plan', 'createdBy']);
        return view('payments.show', compact('payment'));
    }

    /** Recibo de pago en PDF (dompdf) o página imprimible como respaldo. */
    public function receiptPdf(Payment $payment)
    {
        $payment->load(['member', 'plan']);
        $gym  = auth()->user()->gymnasium;
        $data = ['payment' => $payment, 'gym' => $gym];

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.receipt_pdf', $data)->setPaper('a5');
            return $pdf->download('recibo-' . str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) . '.pdf');
        }
        return view('payments.receipt_pdf', $data);
    }

    public function edit(Payment $payment)
    {
        $members = Member::orderBy('first_name')->get();
        $plans   = Plan::where('status', 1)->get();
        return view('payments.edit', compact('payment','members','plans'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'member_id'      => ['required', Rule::exists('members','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'plan_id'        => ['nullable', Rule::exists('plans','id')->where('gymnasium_id', auth()->user()->gymnasium_id)],
            'amount'         => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:efectivo,tarjeta,transferencia,qr',
            'reference'      => 'nullable|string|max:100',
            'period_start'   => 'nullable|date',
            'period_end'     => 'nullable|date',
            'status'         => 'required|in:pagado,pendiente,cancelado',
            'notes'          => 'nullable|string',
        ]);

        $payment->update($data);
        return redirect()->route('payments.index')->with('success', 'Pago actualizado.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Pago eliminado.');
    }
}
