<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /** Exportar socios a CSV (respeta el aislamiento por gimnasio). */
    public function members(): StreamedResponse
    {
        return $this->stream('socios_' . now()->format('Y-m-d') . '.csv',
            ['Código', 'Nombre', 'Apellido', 'Email', 'Teléfono', 'Plan', 'Estado', 'Inicio', 'Vence'],
            function ($out) {
                Member::with('plan')->orderBy('first_name')->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $m) {
                        fputcsv($out, [
                            $m->code, $m->first_name, $m->last_name, $m->email, $m->phone,
                            $m->plan->name ?? '', $m->status,
                            optional($m->membership_start)->format('Y-m-d'),
                            optional($m->membership_end)->format('Y-m-d'),
                        ]);
                    }
                });
            });
    }

    /** Exportar pagos a CSV. */
    public function payments(): StreamedResponse
    {
        return $this->stream('pagos_' . now()->format('Y-m-d') . '.csv',
            ['Fecha', 'Socio', 'Plan', 'Monto', 'Método', 'Estado'],
            function ($out) {
                Payment::with('member', 'plan')->latest()->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $p) {
                        fputcsv($out, [
                            optional($p->payment_date ?? $p->created_at)->format('Y-m-d'),
                            $p->member ? ($p->member->first_name . ' ' . $p->member->last_name) : '',
                            $p->plan->name ?? '',
                            $p->amount,
                            $p->payment_method ?? '',
                            $p->status ?? '',
                        ]);
                    }
                });
            });
    }

    private function stream(string $filename, array $headers, callable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            // BOM para que Excel reconozca UTF-8
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers);
            $rows($out);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
