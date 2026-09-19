# Guía de uso — Facturación Electrónica (prueba en Beta)

Recorrido paso a paso para **probar la emisión** en el ambiente **Beta** de SUNAT:
configurar, emitir una **boleta**, una **factura** y una **nota de crédito**. Beta valida
formato y firma; los comprobantes **no tienen validez tributaria** (son de homologación).

> Requisitos ya cubiertos: `composer require greenter/lite`, `endroid/qr-code`,
> `barryvdh/laravel-dompdf`; extensiones PHP **openssl**, **soap**, **gd**; y las tablas
> creadas (`facturacion_electronica.sql` + `..._upgrade.sql`).

---

## Paso 1 · Configurar el emisor (menú → **Fact. Electrónica**)

Completa con los datos de prueba de SUNAT y **guarda**:

| Campo | Valor de ejemplo (beta) |
|-------|--------------------------|
| RUC | `20000000001` |
| Razón social | `EMPRESA DEMO S.A.C.` |
| Nombre comercial | `Mi Gimnasio` |
| Dirección fiscal | `AV. PRINCIPAL 123` |
| Ubigeo / Depto / Prov / Distrito | `150101` · LIMA · LIMA · LIMA |
| **Usuario Clave SOL** | `MODDATOS` |
| **Clave SOL** | `MODDATOS` |
| Certificado | tu `.pfx`/`.pem` (para pruebas sirve el **certificado demo de Greenter**) |
| Clave del certificado | la de tu certificado (demo Greenter: `password`) |
| Entorno SUNAT | **Beta (homologación / pruebas)** |
| Driver de emisión | **Greenter (envío directo a SUNAT)** |
| Habilitar facturación electrónica | ✅ |

Luego pulsa **⚡ Probar conexión con SUNAT**. Debe responder *"Certificado y credenciales
válidos. Listo para emitir en ambiente beta."* Si falla, revisa el certificado y su clave.

> El chip del banner debe pasar a **Habilitada** y **Certificado cargado**.

---

## Paso 2 · Emitir una BOLETA

Menú → **Comprobantes → Nuevo comprobante** (o desde un **Pago → Facturar**).

**Registro de ejemplo 1 — Boleta:**

| Campo | Valor |
|-------|-------|
| Tipo de comprobante | **Boleta de venta** |
| Tipo de doc. del cliente | DNI |
| N.° de documento | `12345678` |
| Nombre / Razón social | `JUAN PEREZ` |
| Dirección | `Calle Los Olivos 456` |
| Descripción / Concepto | `Membresía Mensual` |
| Total (incluye IGV) | `120.00` |

Pulsa **Emitir y enviar a SUNAT**. El sistema calcula el IGV (120 → base 101.69 + IGV 18.31),
genera el XML UBL 2.1, lo **firma** y lo envía. Verás el estado **Aceptado** con el mensaje
de SUNAT (CDR) si todo está bien.

---

## Paso 3 · Emitir una FACTURA

Menú → **Comprobantes → Nuevo comprobante**.

**Registro de ejemplo 2 — Factura:**

| Campo | Valor |
|-------|-------|
| Tipo de comprobante | **Factura** |
| Tipo de doc. del cliente | **RUC** (obligatorio en factura) |
| N.° de documento | `20512345678` |
| Nombre / Razón social | `CLIENTE CORPORATIVO S.A.C.` |
| Dirección | `Av. Empresarial 789` |
| Descripción / Concepto | `Membresía Corporativa (5 accesos)` |
| Total (incluye IGV) | `590.00` |

> Recuerda: la **Factura exige RUC**. Al elegir "Factura", el sistema fuerza el tipo de
> documento a RUC automáticamente.

Pulsa **Emitir y enviar a SUNAT**.

---

## Paso 4 · Ver el comprobante y sus archivos

Entra a cualquier comprobante (**Comprobantes → ojo**). Desde ahí puedes:

- **PDF / Imprimir** — representación impresa A4 con **QR SUNAT** (PDF real por dompdf).
- **XML** — el comprobante firmado (UBL 2.1).
- **CDR** — la Constancia de Recepción (zip) que devuelve SUNAT.
- **Reenviar a SUNAT** — si quedó pendiente o con error.

---

## Paso 5 · Emitir una NOTA DE CRÉDITO

Sobre un comprobante **aceptado** (ej. la boleta del Paso 2) → botón
**Nota de crédito/débito**.

**Registro de ejemplo — Nota de crédito:**

| Campo | Valor |
|-------|-------|
| Tipo de nota | **Nota de Crédito** |
| Código de motivo | `01 - Anulación de la operación` |
| Descripción del motivo | `Anulación por solicitud del cliente` |
| Monto (incluye IGV) | `120.00` |

Pulsa **Emitir nota y enviar a SUNAT**. La nota queda **referenciada** al comprobante
afectado (verás el enlace en el detalle).

---

## Paso 6 · Emisión automática (opcional)

En **Fact. Electrónica** activa **"Emitir automáticamente al registrar el pago"**. A partir
de ahí, cada **Pago** con estado *pagado* genera y envía una **boleta** al socio de forma
automática (el resultado aparece en el mensaje al guardar el pago).

> Si prefieres emitir a mano, en **Driver de emisión** elige **Ninguno**: los comprobantes se
> crean como *pendientes* y los envías cuando quieras con **Reenviar a SUNAT**.

---

## Pasar a PRODUCCIÓN

1. Completa tu registro como **emisor electrónico** en SUNAT y termina la **homologación**.
2. Sube tu **certificado digital real** y tu **Clave SOL** de producción.
3. Cambia **Entorno SUNAT** a **Producción**.
4. Ajusta las **series** reales autorizadas y verifica los **correlativos**.
5. Emite un comprobante de control y confirma el **CDR aceptado**.

> A partir de ese momento, los comprobantes emitidos **sí tienen validez tributaria**.
