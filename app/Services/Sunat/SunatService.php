<?php

namespace App\Services\Sunat;

use App\Models\BillingSetting;
use App\Models\ElectronicDocument;
use Illuminate\Support\Facades\Storage;

/**
 * Integración DIRECTA con SUNAT usando Greenter.
 *
 * Requiere: composer require greenter/lite
 * y en PHP: extensiones openssl y soap habilitadas.
 *
 * Todas las referencias a clases de Greenter se resuelven de forma dinámica
 * y protegida (class_exists) para que la aplicación no falle si la librería
 * aún no está instalada; en ese caso emit() devuelve un error explicativo.
 */
class SunatService
{
    /** Comprueba que Greenter esté instalado. */
    public static function greenterInstalled(): bool
    {
        return class_exists(\Greenter\See::class);
    }

    /**
     * Prueba la configuración: valida que Greenter esté instalado y que el
     * certificado + credenciales carguen correctamente (construye el cliente
     * SUNAT). No envía ningún comprobante.
     *
     * @return array{ok:bool,message:string}
     */
    public function testConfig(BillingSetting $s): array
    {
        if (!self::greenterInstalled()) {
            return ['ok' => false, 'message' => 'Greenter no está instalado. Ejecuta: composer require greenter/lite'];
        }
        if (strlen((string) $s->ruc) !== 11) {
            return ['ok' => false, 'message' => 'El RUC debe tener 11 dígitos.'];
        }
        if (!$s->sol_user || !$s->sol_pass) {
            return ['ok' => false, 'message' => 'Faltan el usuario y/o la clave SOL.'];
        }
        try {
            $this->buildSee($s); // carga el certificado y configura el servicio
            return ['ok' => true, 'message' => 'Certificado y credenciales válidos. Listo para emitir en ambiente ' . $s->environment . '.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Emite un comprobante ya persistido: lo envía a SUNAT, guarda el XML
     * firmado y el CDR, y actualiza el estado del documento.
     *
     * @return array{ok:bool,message:string,code?:string}
     */
    public function emit(ElectronicDocument $doc): array
    {
        if (!self::greenterInstalled()) {
            return ['ok' => false, 'message' => 'Greenter no está instalado. Ejecuta: composer require greenter/lite'];
        }

        $setting = BillingSetting::query()->where('gymnasium_id', $doc->gymnasium_id)->first();
        if (!$setting || !$setting->enabled) {
            return ['ok' => false, 'message' => 'La facturación electrónica no está configurada o está deshabilitada.'];
        }

        // Driver "ninguno": no se envía, el comprobante queda pendiente.
        if (($setting->driver ?? 'greenter') === 'none') {
            $doc->estado = 'pendiente';
            $doc->save();
            return ['ok' => true, 'message' => 'Comprobante guardado como pendiente (driver de emisión: Ninguno).'];
        }

        try {
            $see = $this->buildSee($setting);
            $company = $this->buildCompany($setting);

            $ublObject = in_array($doc->tipo_doc, ['07', '08'], true)
                ? $this->buildNote($doc, $setting, $company)
                : $this->buildInvoice($doc, $setting, $company);

            $result = $see->send($ublObject);

            // Guardar el XML firmado
            $xml = $see->getFactory()->getLastXml();
            $xmlPath = $this->storePath($doc, 'xml');
            Storage::disk('local')->put($xmlPath, $xml);
            $doc->xml_path = $xmlPath;
            $doc->hash = $this->extractHash($xml);

            if ($result->isSuccess()) {
                // Guardar CDR (zip de respuesta de SUNAT)
                $cdrZip = $result->getCdrZip();
                if ($cdrZip) {
                    $cdrPath = $this->storePath($doc, 'zip');
                    Storage::disk('local')->put($cdrPath, $cdrZip);
                    $doc->cdr_path = $cdrPath;
                }
                $cdr = $result->getCdrResponse();
                $code = $cdr ? (string) $cdr->getCode() : '0';
                $doc->estado = ($code === '0') ? 'aceptado' : 'rechazado';
                $doc->sunat_code = $code;
                $doc->sunat_description = $cdr ? $cdr->getDescription() : 'Aceptado';
                $doc->save();

                return ['ok' => $doc->estado === 'aceptado', 'code' => $code,
                    'message' => $doc->sunat_description];
            }

            // Error de envío / rechazo
            $err = $result->getError();
            $doc->estado = 'rechazado';
            $doc->sunat_code = $err ? (string) $err->getCode() : 'ERR';
            $doc->sunat_description = $err ? $err->getMessage() : 'Error desconocido';
            $doc->save();

            return ['ok' => false, 'code' => $doc->sunat_code, 'message' => $doc->sunat_description];

        } catch (\Throwable $e) {
            $doc->estado = 'error';
            $doc->sunat_description = $e->getMessage();
            $doc->save();
            return ['ok' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Construcción de objetos Greenter                                  */
    /* ------------------------------------------------------------------ */

    private function buildSee(BillingSetting $s): \Greenter\See
    {
        $see = new \Greenter\See();
        $see->setCertificate($this->pemFromCertificate($s));
        $see->setService(
            $s->environment === 'produccion'
                ? \Greenter\Ws\Services\SunatEndpoints::FE_PRODUCCION
                : \Greenter\Ws\Services\SunatEndpoints::FE_BETA
        );
        $see->setClaveSOL($s->ruc, $s->sol_user, $s->sol_pass);
        return $see;
    }

    private function buildCompany(BillingSetting $s): \Greenter\Model\Company\Company
    {
        $address = (new \Greenter\Model\Company\Address())
            ->setUbigueo($s->ubigeo ?: '150101')
            ->setDepartamento($s->departamento ?: 'LIMA')
            ->setProvincia($s->provincia ?: 'LIMA')
            ->setDistrito($s->distrito ?: 'LIMA')
            ->setUrbanizacion($s->urbanizacion ?: '-')
            ->setDireccion($s->direccion ?: '-');

        return (new \Greenter\Model\Company\Company())
            ->setRuc($s->ruc)
            ->setRazonSocial($s->razon_social)
            ->setNombreComercial($s->nombre_comercial ?: $s->razon_social)
            ->setAddress($address);
    }

    private function buildClient(ElectronicDocument $doc): \Greenter\Model\Client\Client
    {
        return (new \Greenter\Model\Client\Client())
            ->setTipoDoc($doc->cliente_tipo_doc ?: '1')
            ->setNumDoc($doc->cliente_num_doc ?: '00000000')
            ->setRznSocial($doc->cliente_razon_social ?: 'CLIENTE VARIOS')
            ->setAddress(
                (new \Greenter\Model\Company\Address())->setDireccion($doc->cliente_direccion ?: '-')
            );
    }

    /** Convierte los items JSON del documento en SaleDetail[] de Greenter. */
    private function buildDetails(ElectronicDocument $doc, float $igvPct): array
    {
        $details = [];
        foreach (($doc->items ?? []) as $it) {
            $cantidad   = (float) ($it['cantidad'] ?? 1);
            $valorUnit  = (float) ($it['mto_valor_unitario'] ?? 0);      // sin IGV
            $valorVenta = round($valorUnit * $cantidad, 2);
            $igv        = round($valorVenta * ($igvPct / 100), 2);
            $precioUnit = round($valorUnit * (1 + $igvPct / 100), 2);

            $details[] = (new \Greenter\Model\Sale\SaleDetail())
                ->setCodProducto($it['codigo'] ?? 'P001')
                ->setUnidad($it['unidad'] ?? 'NIU')
                ->setDescripcion($it['descripcion'] ?? 'Servicio')
                ->setCantidad($cantidad)
                ->setMtoValorUnitario($valorUnit)
                ->setMtoValorVenta($valorVenta)
                ->setMtoBaseIgv($valorVenta)
                ->setPorcentajeIgv($igvPct)
                ->setIgv($igv)
                ->setTipAfeIgv('10')                    // Gravado - Operación Onerosa
                ->setTotalImpuestos($igv)
                ->setMtoPrecioUnitario($precioUnit);
        }
        return $details;
    }

    private function legend(float $total): \Greenter\Model\Sale\Legend
    {
        return (new \Greenter\Model\Sale\Legend())
            ->setCode('1000')
            ->setValue($this->numberToWords($total));
    }

    private function buildInvoice(ElectronicDocument $doc, BillingSetting $s, $company): \Greenter\Model\Sale\Invoice
    {
        $igvPct = (float) $s->igv_percent;
        $gravadas = (float) $doc->mto_oper_gravadas;
        $igv      = (float) $doc->mto_igv;
        $total    = (float) $doc->total;

        return (new \Greenter\Model\Sale\Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')                 // Venta interna
            ->setTipoDoc($doc->tipo_doc)               // 01 factura / 03 boleta
            ->setSerie($doc->serie)
            ->setCorrelativo($doc->correlativo)
            ->setFechaEmision(new \DateTime($doc->fecha_emision))
            ->setFormaPago(new \Greenter\Model\Sale\FormaPagos\FormaPagoContado())
            ->setTipoMoneda($doc->moneda ?: 'PEN')
            ->setCompany($company)
            ->setClient($this->buildClient($doc))
            ->setMtoOperGravadas($gravadas)
            ->setMtoIGV($igv)
            ->setTotalImpuestos($igv)
            ->setValorVenta($gravadas)
            ->setSubTotal($total)
            ->setMtoImpVenta($total)
            ->setDetails($this->buildDetails($doc, $igvPct))
            ->setLegends([$this->legend($total)]);
    }

    private function buildNote(ElectronicDocument $doc, BillingSetting $s, $company): \Greenter\Model\Sale\Note
    {
        $igvPct = (float) $s->igv_percent;
        $gravadas = (float) $doc->mto_oper_gravadas;
        $igv      = (float) $doc->mto_igv;
        $total    = (float) $doc->total;

        return (new \Greenter\Model\Sale\Note())
            ->setUblVersion('2.1')
            ->setTipoDoc($doc->tipo_doc)               // 07 NC / 08 ND
            ->setSerie($doc->serie)
            ->setCorrelativo($doc->correlativo)
            ->setFechaEmision(new \DateTime($doc->fecha_emision))
            ->setTipDocAfectado($doc->doc_afectado_tipo)
            ->setNumDocfectado($doc->doc_afectado_serie_num)
            ->setCodMotivo($doc->cod_motivo)
            ->setDesMotivo($doc->des_motivo)
            ->setTipoMoneda($doc->moneda ?: 'PEN')
            ->setCompany($company)
            ->setClient($this->buildClient($doc))
            ->setMtoOperGravadas($gravadas)
            ->setMtoIGV($igv)
            ->setTotalImpuestos($igv)
            ->setMtoImpVenta($total)
            ->setDetails($this->buildDetails($doc, $igvPct))
            ->setLegends([$this->legend($total)]);
    }

    /* ------------------------------------------------------------------ */
    /*  Utilidades                                                        */
    /* ------------------------------------------------------------------ */

    /** Devuelve el certificado en formato PEM (soporta .pem y .pfx/.p12). */
    private function pemFromCertificate(BillingSetting $s): string
    {
        if (!$s->cert_path || !Storage::disk('local')->exists($s->cert_path)) {
            throw new \RuntimeException('No se encontró el certificado digital configurado.');
        }
        $raw = Storage::disk('local')->get($s->cert_path);
        $ext = strtolower(pathinfo($s->cert_path, PATHINFO_EXTENSION));

        if (in_array($ext, ['pfx', 'p12'], true)) {
            $out = [];
            if (!openssl_pkcs12_read($raw, $out, (string) $s->cert_pass)) {
                throw new \RuntimeException('No se pudo leer el certificado .pfx (¿clave incorrecta?).');
            }
            return $out['cert'] . $out['pkey'];
        }
        return $raw; // ya es PEM
    }

    private function storePath(ElectronicDocument $doc, string $ext): string
    {
        $name = ($doc->tipo_doc) . '-' . $doc->serie . '-' . $doc->correlativo;
        return "comprobantes/gym_{$doc->gymnasium_id}/{$name}.{$ext}";
    }

    private function extractHash(string $xml): ?string
    {
        if (preg_match('/<ds:DigestValue>([^<]+)<\/ds:DigestValue>/', $xml, $m)) {
            return $m[1];
        }
        return null;
    }

    /** Convierte un monto a letras (para la leyenda 1000). */
    private function numberToWords(float $amount): string
    {
        $entero = (int) floor($amount);
        $centavos = str_pad((string) round(($amount - $entero) * 100), 2, '0', STR_PAD_LEFT);
        $words = $this->intToWords($entero);
        return 'SON ' . mb_strtoupper($words) . " CON {$centavos}/100 SOLES";
    }

    private function intToWords(int $n): string
    {
        if ($n === 0) return 'cero';
        $unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
            'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve',
            'veinte'];
        $decenas = ['', '', 'veinti', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos',
            'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

        $out = '';
        if ($n >= 1000000) { $out .= $this->intToWords((int) ($n / 1000000)) . ' millones '; $n %= 1000000; }
        if ($n >= 1000) {
            $miles = (int) ($n / 1000);
            $out .= ($miles === 1 ? 'mil ' : $this->intToWords($miles) . ' mil ');
            $n %= 1000;
        }
        if ($n === 100) { $out .= 'cien'; $n = 0; }
        if ($n >= 100) { $out .= $centenas[(int) ($n / 100)] . ' '; $n %= 100; }
        if ($n <= 20) { $out .= $unidades[$n]; }
        else {
            $d = (int) ($n / 10); $u = $n % 10;
            if ($d === 2) { $out .= ($u === 0 ? 'veinte' : 'veinti' . $unidades[$u]); }
            else { $out .= $decenas[$d] . ($u ? ' y ' . $unidades[$u] : ''); }
        }
        return trim($out);
    }
}
