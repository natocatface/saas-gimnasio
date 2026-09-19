<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
use App\Models\ElectronicDocument;
use App\Models\Payment;
use App\Services\Sunat\SunatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Emisión y gestión de comprobantes electrónicos (Factura, Boleta, Notas).
 */
class ElectronicDocumentController extends Controller
{
    private function guardAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
    }

    private function setting(): ?BillingSetting
    {
        return BillingSetting::query()->first();
    }

    public function index(Request $request)
    {
        $this->guardAdmin();

        // Tabla con filtros (tipo, estado y rango de fechas)
        $q = ElectronicDocument::query();
        if ($request->filled('tipo'))   $q->where('tipo_doc', $request->tipo);
        if ($request->filled('estado')) $q->where('estado', $request->estado);
        if ($request->filled('desde'))  $q->whereDate('fecha_emision', '>=', $request->desde);
        if ($request->filled('hasta'))  $q->whereDate('fecha_emision', '<=', $request->hasta);
        $docs = $q->latest()->paginate(20)->withQueryString();

        $setting = $this->setting();

        $stats = [
            'total'      => ElectronicDocument::query()->count(),
            'aceptados'  => ElectronicDocument::query()->where('estado', 'aceptado')->count(),
            'pendientes' => ElectronicDocument::query()->whereIn('estado', ['pendiente', 'enviado', 'rechazado', 'error'])->count(),
            'mes'        => ElectronicDocument::query()
                                ->where('estado', 'aceptado')
                                ->whereIn('tipo_doc', ['01', '03'])
                                ->whereMonth('fecha_emision', now()->month)
                                ->whereYear('fecha_emision', now()->year)
                                ->sum('total'),
        ];

        return view('billing_sunat.index', compact('docs', 'setting', 'stats'));
    }

    public function create(Request $request)
    {
        $this->guardAdmin();
        $setting = $this->setting();
        if (!$setting || !$setting->enabled) {
            return redirect()->route('sunat.settings.edit')
                ->with('error', 'Primero configura y habilita la facturación electrónica.');
        }
        // Pago opcional para prellenar
        $payment = null;
        if ($request->filled('payment_id')) {
            $payment = Payment::with(['member', 'plan'])->find($request->payment_id);
        }
        return view('billing_sunat.create', compact('setting', 'payment'));
    }

    public function store(Request $request)
    {
        $this->guardAdmin();
        $setting = $this->setting();
        if (!$setting || !$setting->enabled) {
            return back()->with('error', 'La facturación electrónica no está habilitada.');
        }

        $data = $request->validate([
            'tipo_doc'             => 'required|in:01,03',
            'payment_id'           => 'nullable|exists:payments,id',
            'cliente_tipo_doc'     => 'required|in:0,1,6',
            'cliente_num_doc'      => 'nullable|string|max:15',
            'cliente_razon_social' => 'required|string|max:255',
            'cliente_direccion'    => 'nullable|string|max:255',
            'descripcion'          => 'required|string|max:255',
            'total'                => 'required|numeric|min:0.1',
        ]);

        // Factura exige RUC del cliente
        if ($data['tipo_doc'] === '01' && $data['cliente_tipo_doc'] !== '6') {
            return back()->withInput()->with('error', 'Una Factura requiere que el cliente tenga RUC (tipo de documento RUC).');
        }

        $igvPct = (float) $setting->igv_percent;
        $total  = round((float) $data['total'], 2);
        $base   = round($total / (1 + $igvPct / 100), 2);
        $igv    = round($total - $base, 2);

        $serie   = $setting->serieFor($data['tipo_doc']);
        $next    = $setting->nextCorrelativo($data['tipo_doc']);
        $corr    = (string) $next;

        $payment = $data['payment_id'] ? Payment::find($data['payment_id']) : null;

        $doc = new ElectronicDocument([
            'payment_id'           => $payment?->id,
            'member_id'            => $payment?->member_id,
            'tipo_doc'             => $data['tipo_doc'],
            'serie'                => $serie,
            'correlativo'          => $corr,
            'fecha_emision'        => now(),
            'moneda'               => $setting->moneda ?: 'PEN',
            'cliente_tipo_doc'     => $data['cliente_tipo_doc'],
            'cliente_num_doc'      => $data['cliente_num_doc'],
            'cliente_razon_social' => $data['cliente_razon_social'],
            'cliente_direccion'    => $data['cliente_direccion'],
            'items'                => [[
                'codigo'             => 'P001',
                'unidad'             => 'NIU',
                'descripcion'        => $data['descripcion'],
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
        $setting->bumpCorrelativo($data['tipo_doc'], $next);

        // Enviar a SUNAT
        $res = (new SunatService())->emit($doc);

        return redirect()->route('sunat.docs.show', $doc)
            ->with($res['ok'] ? 'success' : 'error', $res['message']);
    }

    public function show(ElectronicDocument $document)
    {
        $this->guardAdmin();
        return view('billing_sunat.show', ['doc' => $document]);
    }

    /** Reintentar el envío de un comprobante pendiente / con error. */
    public function emit(ElectronicDocument $document)
    {
        $this->guardAdmin();
        $res = (new SunatService())->emit($document);
        return back()->with($res['ok'] ? 'success' : 'error', $res['message']);
    }

    /* ---------------- Notas de crédito / débito ---------------- */

    public function createNote(ElectronicDocument $document)
    {
        $this->guardAdmin();
        abort_unless(in_array($document->tipo_doc, ['01', '03'], true), 404);
        return view('billing_sunat.note', ['ref' => $document]);
    }

    public function storeNote(Request $request, ElectronicDocument $document)
    {
        $this->guardAdmin();
        $setting = $this->setting();

        $data = $request->validate([
            'tipo_doc'   => 'required|in:07,08',   // 07 NC, 08 ND
            'cod_motivo' => 'required|string|max:2',
            'des_motivo' => 'required|string|max:255',
            'total'      => 'required|numeric|min:0.1',
        ]);

        $igvPct = (float) $setting->igv_percent;
        $total  = round((float) $data['total'], 2);
        $base   = round($total / (1 + $igvPct / 100), 2);
        $igv    = round($total - $base, 2);

        $serie = $setting->serieFor($data['tipo_doc']);
        $next  = $setting->nextCorrelativo($data['tipo_doc']);

        $note = new ElectronicDocument([
            'payment_id'           => $document->payment_id,
            'member_id'            => $document->member_id,
            'tipo_doc'             => $data['tipo_doc'],
            'serie'                => $serie,
            'correlativo'          => (string) $next,
            'fecha_emision'        => now(),
            'moneda'               => $document->moneda,
            'cliente_tipo_doc'     => $document->cliente_tipo_doc,
            'cliente_num_doc'      => $document->cliente_num_doc,
            'cliente_razon_social' => $document->cliente_razon_social,
            'cliente_direccion'    => $document->cliente_direccion,
            'items'                => [[
                'codigo'             => 'P001',
                'unidad'             => 'NIU',
                'descripcion'        => $data['des_motivo'],
                'cantidad'           => 1,
                'mto_valor_unitario' => $base,
            ]],
            'mto_oper_gravadas'    => $base,
            'mto_igv'              => $igv,
            'total'                => $total,
            'doc_afectado_tipo'    => $document->tipo_doc,
            'doc_afectado_serie_num' => $document->serie . '-' . $document->correlativo,
            'cod_motivo'           => $data['cod_motivo'],
            'des_motivo'           => $data['des_motivo'],
            'estado'               => 'pendiente',
            'created_by'           => auth()->id(),
        ]);
        $note->save();
        $setting->bumpCorrelativo($data['tipo_doc'], $next);

        $res = (new SunatService())->emit($note);

        return redirect()->route('sunat.docs.show', $note)
            ->with($res['ok'] ? 'success' : 'error', $res['message']);
    }

    /* ---------------- Descargas ---------------- */

    public function downloadXml(ElectronicDocument $document)
    {
        $this->guardAdmin();
        abort_unless($document->xml_path && Storage::disk('local')->exists($document->xml_path), 404);
        return Storage::disk('local')->download($document->xml_path, $document->numero . '.xml');
    }

    public function downloadCdr(ElectronicDocument $document)
    {
        $this->guardAdmin();
        abort_unless($document->cdr_path && Storage::disk('local')->exists($document->cdr_path), 404);
        return Storage::disk('local')->download($document->cdr_path, 'R-' . $document->numero . '.zip');
    }

    /**
     * Representación impresa del comprobante.
     * Si Barryvdh\DomPDF está instalado devuelve un PDF; si no, una página
     * A4 lista para imprimir o guardar como PDF desde el navegador.
     */
    public function representacion(ElectronicDocument $document)
    {
        $this->guardAdmin();
        $setting = BillingSetting::query()->where('gymnasium_id', $document->gymnasium_id)->first();

        // Cadena para el QR de SUNAT
        $qr = implode('|', [
            $setting->ruc ?? '',
            $document->tipo_doc,
            $document->serie,
            $document->correlativo,
            number_format($document->mto_igv, 2, '.', ''),
            number_format($document->total, 2, '.', ''),
            $document->fecha_emision?->format('Y-m-d'),
            $document->cliente_tipo_doc,
            $document->cliente_num_doc ?? '',
        ]);

        $data = [
            'doc'     => $document,
            'setting' => $setting,
            'qr'      => $qr,
            'qrImage' => $this->qrImage($qr),   // data-URI PNG (o null si no hay librería/GD)
        ];

        // PDF real si dompdf está disponible
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('billing_sunat.print', $data)->setPaper('a4');
            return $pdf->download($document->numero . '.pdf');
        }

        // Fallback: página imprimible
        return view('billing_sunat.print', $data);
    }

    /**
     * Genera el QR como imagen (data-URI PNG) usando endroid/qr-code si está
     * instalado. Devuelve null si no hay librería o falta la extensión GD, en
     * cuyo caso la vista dibuja el QR con JavaScript.
     */
    private function qrImage(string $text): ?string
    {
        if (!class_exists(\Endroid\QrCode\Writer\PngWriter::class) || !class_exists(\Endroid\QrCode\QrCode::class)) {
            return null;
        }
        // endroid/qr-code v6 (constructor con argumentos nombrados)
        try {
            $qr = new \Endroid\QrCode\QrCode(data: $text, size: 220, margin: 6);
            return (new \Endroid\QrCode\Writer\PngWriter())->write($qr)->getDataUri();
        } catch (\Throwable $e) {
            // continúa al intento para v4/v5
        }
        // endroid/qr-code v4/v5 (QrCode::create)
        try {
            if (method_exists(\Endroid\QrCode\QrCode::class, 'create')) {
                $qr = \Endroid\QrCode\QrCode::create($text)->setSize(220)->setMargin(6);
                return (new \Endroid\QrCode\Writer\PngWriter())->write($qr)->getDataUri();
            }
        } catch (\Throwable $e) {
            // sin QR de servidor
        }
        return null;
    }
}
