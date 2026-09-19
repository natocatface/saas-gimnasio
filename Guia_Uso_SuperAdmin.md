# Guía de uso — Panel Super Admin (GymSaaS Pro)

Esta guía te lleva paso a paso por el panel del **dueño del SaaS** (Super Admin), en el
orden lógico en que conviene usar el sistema. Para cada módulo encontrarás: para qué
sirve, cómo llegar, los pasos, y **2 registros de ejemplo** listos para cargar.

> Ingresa primero con tu cuenta **Super Admin**. Al entrar caes en `127.0.0.1:8080/superadmin`
> (Dashboard). El menú lateral izquierdo tiene todos los módulos.

**Orden recomendado:** Configuración (moneda) → Planes SaaS → Cupones → Gimnasios →
Suscripciones → Comunicados → Reportes → Soporte.

---

## 1) Configuración — Moneda del sistema

**Para qué sirve:** define la moneda única de toda la plataforma. Se aplica a los precios
de los planes, suscripciones, reportes, facturación y todos los gimnasios. Conviene
hacerlo **primero** para que todos los importes se muestren bien.

**Cómo llegar:** menú lateral → **Sistema → Configuración** (`/superadmin/configuracion`).

**Pasos:**
1. Elige la **Moneda** en el desplegable (el símbolo se autocompleta).
2. Ajusta **Símbolo**, **Posición** (antes/después), **Decimales** y los **separadores**.
3. Mira la **Vista previa** a la derecha: verás cómo quedará un precio real.
4. Pulsa **Guardar moneda**. El formato se refleja al instante en todo el sistema.

**Registros de ejemplo (prueba uno, guarda, y observa cómo cambian los precios):**

| # | Moneda | Símbolo | Posición | Decimales | Miles | Decimal |
|---|--------|---------|----------|-----------|-------|---------|
| 1 | USD – Dólar | `$` | Antes ($100) | 2 | Coma (1,000) | Punto (100.50) |
| 2 | PEN – Sol | `S/` | Antes (S/100) | 2 | Coma (1,000) | Punto (100.50) |

---

## 2) Planes SaaS — Lo que le vendes a los gimnasios

**Para qué sirve:** son los planes/tarifas que ofreces a los gimnasios (con sus límites de
socios, entrenadores y clases). Defínelos antes de registrar gimnasios, porque al crear un
gimnasio deberás asignarle un plan.

**Cómo llegar:** menú → **Planes SaaS** (`/superadmin/plans`) → botón **+ Nuevo plan**.

**Pasos:**
1. Completa **Nombre**, **Color**, **Descripción**.
2. Fija **Precio mensual** y **Precio anual**.
3. Define los límites: **Máx. socios**, **Máx. entrenadores**, **Máx. clases**.
4. Escribe las **Características** (una por línea) y el **Orden** de aparición.
5. Marca **Popular** y/o **Activo** según corresponda y **Guarda**.

**Registros de ejemplo:**

**Plan 1 — Básico**
- Nombre: `Básico` · Color: morado · Descripción: `Ideal para gimnasios que comienzan`
- Precio mensual: `29` · Precio anual: `290`
- Máx. socios: `50` · Máx. entrenadores: `3` · Máx. clases: `5` · Orden: `1`
- Características:
  ```
  Hasta 50 socios
  3 entrenadores
  Reportes básicos
  Soporte por email
  ```
- Popular: No · Activo: Sí

**Plan 2 — Pro**
- Nombre: `Pro` · Color: azul · Descripción: `Para gimnasios en crecimiento`
- Precio mensual: `59` · Precio anual: `590`
- Máx. socios: `200` · Máx. entrenadores: `10` · Máx. clases: `20` · Orden: `2`
- Características:
  ```
  Hasta 200 socios
  10 entrenadores
  Reportes avanzados
  Soporte prioritario
  Portal del socio
  ```
- Popular: Sí · Activo: Sí

---

## 3) Cupones — Descuentos para captar gimnasios

**Para qué sirve:** códigos de descuento que un gimnasio puede aplicar al contratar o
cambiar de plan (porcentaje o monto fijo).

**Cómo llegar:** menú → **Cupones** (`/superadmin/coupons`) → **+ Nuevo cupón**.

**Pasos:**
1. Escribe el **Código** (se guarda en mayúsculas) y una **Descripción**.
2. Elige el **Tipo**: Porcentaje (%) o Monto fijo.
3. Pon el **Valor** del descuento.
4. En **Aplica al plan**, deja "Todos los planes" o elige uno.
5. Opcional: **Máximo de usos** (vacío = ilimitado) y fecha de **Vence**.
6. Marca **Cupón activo** y **Guarda**.

**Registros de ejemplo:**

| Campo | Cupón 1 | Cupón 2 |
|-------|---------|---------|
| Código | `BIENVENIDA20` | `VERANO10` |
| Descripción | 20% en el primer mes | Descuento fijo de temporada |
| Tipo | Porcentaje (%) | Monto fijo |
| Valor | `20` | `10` |
| Aplica al plan | Todos los planes | Pro |
| Máximo de usos | `100` | (vacío = ilimitado) |
| Vence | 31/12/2026 | 31/03/2026 |
| Activo | Sí | Sí |

---

## 4) Gimnasios — Alta de clientes

**Para qué sirve:** registrar cada gimnasio (cliente) y su usuario administrador. Al crear
el gimnasio se genera su cuenta de acceso y se le asigna un plan.

> Ya tienes un gimnasio registrado; aquí ves cómo dar de alta otros nuevos.

**Cómo llegar:** menú → **Gimnasios** (`/superadmin/gyms`) → **+ Nuevo**.

**Pasos:**
1. **Nombre** del gimnasio.
2. Datos del dueño/admin: **Nombre del propietario**, **Correo** (será su usuario) y **Contraseña**.
3. **Teléfono** (opcional).
4. Asigna el **Plan** y el **Estado** (Activo / Prueba / Suspendido / Cancelado).
5. **Guarda**. Ese admin ya podrá entrar con su correo y contraseña a su propio panel.

**Registros de ejemplo:**

**Gimnasio 1**
- Nombre: `Iron Fitness Center`
- Propietario: `María López` · Correo: `maria@ironfitness.com` · Teléfono: `555-3001`
- Contraseña: `IronFit2026` · Plan: `Pro` · Estado: `Activo`

**Gimnasio 2**
- Nombre: `PowerGym Norte`
- Propietario: `Carlos Ruiz` · Correo: `carlos@powergym.com` · Teléfono: `555-3002`
- Contraseña: `PowerGym2026` · Plan: `Básico` · Estado: `Prueba`

---

## 5) Suscripciones — Registrar cobros

**Para qué sirve:** dejar constancia de cada cobro/suscripción de un gimnasio (qué plan
pagó, por cuánto y desde cuándo). Alimenta los ingresos y el MRR de los reportes.

**Cómo llegar:** menú → **Suscripciones** (`/superadmin/subscriptions`) → **+ Nueva**
(también desde la ficha de un gimnasio con **Registrar cobro**).

**Pasos:**
1. Elige el **Gimnasio** y el **Plan**.
2. Selecciona el **Ciclo** (Mensual/Anual). El **Monto** se autocompleta con el precio del
   plan; puedes ajustarlo.
3. Pon la **Fecha de inicio** (la vigencia se calcula sola) y una **Referencia de pago**.
4. **Guarda**.

**Registros de ejemplo:**

| Campo | Suscripción 1 | Suscripción 2 |
|-------|---------------|---------------|
| Gimnasio | Iron Fitness Center | PowerGym Norte |
| Plan | Pro | Básico |
| Ciclo | Mensual | Anual |
| Monto | `59` | `290` |
| Fecha de inicio | hoy | hoy |
| Referencia | `TRANSF-001` | `YAPE-8842` |

---

## 6) Comunicados — Avisos masivos a los gimnasios

**Para qué sirve:** enviar una notificación a todos los gimnasios o solo a los de un plan
(aparece en las campanitas de sus paneles).

**Cómo llegar:** menú → **Comunicados** (`/superadmin/announcements`).

**Pasos:**
1. Escribe **Título** y **Mensaje**.
2. Elige **Destinatarios**: "Todos los gimnasios" o "Solo un plan" (y el plan).
3. Elige un **Color** de acento y pulsa enviar.

**Registros de ejemplo:**

**Comunicado 1**
- Título: `Nueva función: Copias de seguridad`
- Mensaje: `Ya puedes generar y restaurar respaldos de los datos de tu gimnasio desde Administración → Respaldos.`
- Destinatarios: Todos los gimnasios · Color: rosa

**Comunicado 2**
- Título: `Mantenimiento programado`
- Mensaje: `El domingo de 2:00 a 4:00 a.m. el sistema estará en mantenimiento. Disculpa las molestias.`
- Destinatarios: Solo un plan → `Pro` · Color: azul

---

## 7) Reportes — Analítica del negocio (solo lectura)

**Para qué sirve:** ver la salud del SaaS. No se cargan registros aquí; es un tablero que
se llena solo con los datos de gimnasios y suscripciones.

**Cómo llegar:** menú → **Reportes** (`/superadmin/reports`).

**Qué observar:**
- **Ingreso total** y **Ingreso este mes**.
- **MRR** (ingreso recurrente mensual) e **Ingreso medio por gimnasio (ARPA)**.
- Tabla de ingresos por periodo. Con **Exportar** descargas un CSV.

> Sugerencia: después de cargar los Planes, Gimnasios y Suscripciones de arriba, vuelve
> aquí y verás cómo el MRR y los ingresos reflejan esos movimientos.

---

## 8) Soporte — Atender tickets de los gimnasios

**Para qué sirve:** responder las consultas que abren los gimnasios. Los tickets **los crea
el gimnasio** desde su propio panel (Soporte); el Super Admin los responde y los cierra.

**Cómo llegar:** menú → **Soporte** (`/superadmin/support`). Entra a un ticket para
responder o cambiar su estado (Abierto / En espera / Cerrado).

**Ejemplos de respuesta (para 2 tickets típicos):**

**Ticket 1 — "No puedo registrar un socio nuevo"**
> Respuesta: `Hola, revisa que el código y el correo del socio no estén repetidos. Si el
> problema sigue, dime el mensaje exacto que ves y lo revisamos. ¡Gracias!` → Estado: En espera.

**Ticket 2 — "¿Cómo cambio de plan?"**
> Respuesta: `Desde tu panel entra a Facturación → elige el plan y confirma. El cambio es
> inmediato. Cualquier duda quedo atento.` → Estado: Cerrado.

---

## Extra) Impersonar un gimnasio (modo soporte)

Desde la ficha de un gimnasio (**Gimnasios → ver**) puedes pulsar **Impersonar** para entrar
a su panel tal como lo ve ese cliente, útil para dar soporte. Arriba aparecerá una barra
para **volver al Super Admin** cuando termines.

---

### Resumen del flujo
1. Configuras la **moneda**.
2. Creas los **planes** que vendes.
3. (Opcional) Creas **cupones** de descuento.
4. Das de alta **gimnasios** (clientes) con su admin y plan.
5. Registras las **suscripciones/cobros**.
6. Envías **comunicados** cuando haga falta.
7. Revisas **reportes** para medir el negocio.
8. Atiendes **soporte** e **impersonas** cuando un cliente necesita ayuda.
