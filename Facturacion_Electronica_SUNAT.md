# Facturación Electrónica SUNAT — Guía de instalación y uso

Módulo de comprobantes electrónicos (**Factura, Boleta, Nota de Crédito y Nota de Débito**)
con envío **directo a SUNAT** usando la librería **Greenter**. Es multi-tenant: cada
gimnasio tiene su propia configuración y sus propios comprobantes.

---

## 1. Requisitos e instalación (una sola vez)

**a) Extensiones de PHP** (normalmente ya activas en XAMPP): habilita en `php.ini`:
```
extension=openssl
extension=soap
```

**b) Instalar Greenter** (en la carpeta del proyecto):
```bash
composer require greenter/lite
```

**c) Crear las tablas** en la base de datos:
```bash
mysql -u root saas_gimnasio < database\facturacion_electronica.sql
```
> Si haces una importación limpia de `bk_basededatos.sql`, las tablas ya vienen incluidas.
>
> Si YA tenías creada `billing_settings` de una versión anterior, corre además la
> actualización que agrega los campos `driver` y `auto_emit`:
> ```bash
> mysql -u root saas_gimnasio < database\facturacion_electronica_upgrade.sql
> ```

**d)** Asegúrate de que la carpeta `storage/app` sea escribible (ahí se guardan el
certificado, los XML firmados y los CDR).

---

## 2. Configuración (menú → Administración → **Fact. Electrónica**)

Completa y **habilita**:

- **Datos del emisor:** RUC, razón social, dirección, ubigeo, distrito/provincia/departamento.
- **Credenciales SOL:** usuario y clave SOL. En **beta** usa `MODDATOS` como usuario.
- **Certificado digital:** sube tu `.pfx`/`.p12` (con su clave) o `.pem`.
  - Para pruebas puedes usar el **certificado de demo de Greenter**.
- **Ambiente:** `Beta` para pruebas, `Producción` cuando ya estés homologado.
- **Series y correlativos:** Factura `F001`, Boleta `B001`, N. Crédito `FC01`, N. Débito `FD01`.
  El correlativo avanza solo con cada emisión.
- **IGV:** 18 % por defecto.

---

## 3. Emitir un comprobante

**Opción A — desde un pago:** ve a **Pagos → (abrir un pago) → Facturar**. Se prellenan
el cliente (socio), el concepto (su plan) y el total.

**Opción B — manual:** menú → **Comprobantes → Nuevo comprobante**.

Pasos:
1. Elige **Boleta** o **Factura** (la Factura exige cliente con **RUC**).
2. Completa el documento del cliente, nombre/razón social, concepto y **total** (el IGV se
   calcula automáticamente desde el total).
3. **Emitir y enviar a SUNAT.** El sistema genera el XML UBL 2.1, lo **firma** con tu
   certificado y lo envía. Verás el estado (**aceptado / rechazado / error**) y el mensaje
   de SUNAT (CDR).

Desde el detalle del comprobante puedes **descargar el XML** firmado y el **CDR**, **reenviar**
si quedó pendiente, y emitir una **Nota de Crédito/Débito** referenciada.

---

## 4. Notas de crédito y débito

En un comprobante **aceptado** → botón **Nota de crédito/débito** → elige el tipo, el código
de motivo (01 anulación, 06 devolución, 09 disminución, etc.), la descripción y el monto.
La nota queda enlazada al comprobante afectado y se envía a SUNAT.

---

## 5. Datos de prueba (ambiente Beta)

- **Usuario SOL:** `MODDATOS`  ·  **Clave SOL:** `moddatos`
- **RUC de pruebas:** `20000000001` (o el que uses en tu certificado de demo).
- Endpoint beta de SUNAT ya está configurado automáticamente según el ambiente elegido.

> En beta SUNAT valida el formato y la firma, pero los comprobantes **no tienen validez
> tributaria**. Para producción necesitas tu certificado real y haber completado la
> homologación/registro como emisor electrónico en SUNAT.

---

## 6. Notas técnicas

- **Arquitectura:** `App\Services\Sunat\SunatService` construye los objetos Greenter
  (`Company`, `Client`, `Invoice`, `Note`), firma y envía. Los comprobantes se guardan en la
  tabla `electronic_documents` y la configuración en `billing_settings` (ambas por gimnasio).
- Si Greenter aún no está instalado, la app **no se rompe**: la pantalla te avisa y el envío
  devuelve un mensaje pidiendo `composer require greenter/lite`.
- Los XML y CDR se guardan en `storage/app/comprobantes/gym_<id>/`. El certificado en
  `storage/app/certs/gym_<id>/` (fuera del acceso público).
- **PDF / representación impresa:** el botón **PDF / Imprimir** del comprobante genera un
  **PDF real** (dompdf ya está instalado) con el formato A4 del comprobante: emisor, cliente,
  ítems, totales, **QR SUNAT** y hash. Si dompdf no estuviera, cae a una página imprimible
  desde el navegador.
- **QR en el PDF:** para que el QR aparezca dentro del PDF (dompdf no ejecuta JavaScript) se
  genera como imagen en el servidor. Instala una vez:
  ```bash
  composer require endroid/qr-code
  ```
  (requiere la extensión PHP **gd**). Sin esta librería, el QR se dibuja igual en la vista de
  navegador, pero no dentro del PDF.
- La plantilla del PDF usa **layout de tablas** (no flexbox) para renderizar correctamente en
  dompdf.
