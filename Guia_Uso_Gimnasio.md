# Guía de uso — Panel del Gimnasio (GymSaaS Pro)

Recorrido paso a paso del **uso diario** del gimnasio, en el orden lógico en que conviene
cargar la información. Para cada módulo: para qué sirve, cómo llegar, los pasos y **2
registros de ejemplo**. Los datos están enlazados entre sí (los entrenadores y socios de
ejemplo se reutilizan en Clases, Pagos, Asistencia, Rutinas y Mediciones).

> Ingresa con la cuenta **admin del gimnasio**. Caes en `127.0.0.1:8080/dashboard`.
> El menú lateral tiene los módulos agrupados (Socios & Membresías, Operaciones, Administración).

**Orden recomendado:** Configuración → Planes → Entrenadores → Clases → Socios → Pagos →
Asistencia → Inventario → Rutinas → Mediciones → Reportes → Respaldos.

---

## 0) Configuración de la empresa (una sola vez)

**Para qué sirve:** dejar listos los datos del negocio antes de operar.

**Cómo llegar:** menú → **Administración → Configuración** (`/settings`).

**Qué completar (ejemplo):**
- **Datos:** Nombre comercial `GymSaaS Pro`, Razón social `Fitness SAC`, RUC `20123456789`,
  Dirección `Av. Fitness 123`, Ciudad `Lima`, Teléfono `555-0100`, Correo `info@gym.com`.
- **Logo e identidad:** sube tu logo (PNG/JPG/SVG, máx 1 MB) y elige el **Color principal**.
- **Impuestos:** Nombre `IGV`, Tasa `18`, marca si los precios ya lo incluyen.
- **Recibos:** Prefijo `REC-`, pie de página con tu mensaje.

> Nota: la **moneda** ahora se define de forma global desde el panel Super Admin
> (Configuración del sistema) y se aplica automáticamente a todo el gimnasio.

---

## 1) Planes — Tarifas de membresía

**Para qué sirve:** las membresías que vendes a tus socios (precio y duración).
Créalas primero porque al registrar socios y pagos deberás elegir un plan.

**Cómo llegar:** menú → **Socios & Membresías → Planes** (`/plans`) → **+ Nuevo**.

**Pasos:** completa **Nombre**, **Descripción**, **Precio**, **Duración (días)**, **Color**;
marca **Destacado**/**Activo** y guarda.

**Registros de ejemplo:**

| Campo | Plan 1 | Plan 2 |
|-------|--------|--------|
| Nombre | `Mensual` | `Trimestral` |
| Descripción | Acceso ilimitado por 30 días | 3 meses con descuento |
| Precio | `120` | `320` |
| Duración (días) | `30` | `90` |
| Destacado | No | Sí |
| Activo | Sí | Sí |

---

## 2) Entrenadores

**Para qué sirve:** el personal que dictará las clases y armará rutinas. Créalos antes que
las Clases (para poder asignarlos).

**Cómo llegar:** menú → **Operaciones → Entrenadores** (`/trainers`) → **+ Nuevo**.

**Pasos:** carga **Nombre**, **Correo**, **Teléfono**, **Especialidad**, **Biografía**,
**Fecha de contratación**, **Salario** y estado **Activo**.

**Registros de ejemplo:**

**Entrenador 1**
- Nombre: `Carlos Mendoza` · Correo: `carlos.m@tugimnasio.com` · Teléfono: `555-1001`
- Especialidad: `Musculación y Fuerza` · Salario: `1200` · Activo: Sí

**Entrenador 2**
- Nombre: `Ana García` · Correo: `ana.g@tugimnasio.com` · Teléfono: `555-1002`
- Especialidad: `Yoga y Pilates` · Salario: `1100` · Activo: Sí

---

## 3) Clases

**Para qué sirve:** las clases grupales con su horario, cupo y entrenador.

**Cómo llegar:** menú → **Operaciones → Clases** (`/classes`) → **+ Nueva**.

**Pasos:** **Nombre**, **Descripción**, **Entrenador**, **Capacidad**, **Duración (min)**,
**Día**, **Hora** (HH:MM), **Sala** y **Color**.

**Registros de ejemplo:**

| Campo | Clase 1 | Clase 2 |
|-------|---------|---------|
| Nombre | `Spinning` | `Yoga` |
| Entrenador | Carlos Mendoza | Ana García |
| Capacidad | `20` | `15` |
| Duración (min) | `45` | `60` |
| Día | Lunes | Miércoles |
| Hora | `07:00` | `18:00` |
| Sala | `Sala 1` | `Sala 2` |

---

## 4) Socios

**Para qué sirve:** el corazón del negocio: tus clientes y su membresía.

**Cómo llegar:** menú → **Socios & Membresías → Socios** (`/members`) → **+ Nuevo**.
El **código** del socio (GYM-###) se genera solo.

**Pasos:** datos personales, **Género**, contacto de **Emergencia**, asigna el **Plan** y las
fechas de **inicio/fin** de membresía, y el **Estado**.

**Registros de ejemplo:**

**Socio 1**
- Nombre: `Juan` · Apellido: `Pérez` · Correo: `juan.perez@email.com` · Teléfono: `555-2001`
- Género: `M` · Contacto emergencia: `Rosa Pérez` / `555-2101`
- Plan: `Mensual` · Inicio: hoy · Fin: +30 días · Estado: `Activo`

**Socio 2**
- Nombre: `Laura` · Apellido: `Torres` · Correo: `laura.torres@email.com` · Teléfono: `555-2002`
- Género: `F` · Contacto emergencia: `Mario Torres` / `555-2102`
- Plan: `Trimestral` · Inicio: hoy · Fin: +90 días · Estado: `Activo`

---

## 5) Pagos

**Para qué sirve:** registrar los cobros de membresía de cada socio.

**Cómo llegar:** menú → **Socios & Membresías → Pagos** (`/payments`) → **+ Nuevo**.

**Pasos:** elige **Socio** y **Plan**, pon el **Monto**, la **Fecha de pago**, el **Método**
(Efectivo / Tarjeta / Transferencia / QR), una **Referencia**, el **periodo** cubierto y el
**Estado**.

**Registros de ejemplo:**

| Campo | Pago 1 | Pago 2 |
|-------|--------|--------|
| Socio | Juan Pérez | Laura Torres |
| Plan | Mensual | Trimestral |
| Monto | `120` | `320` |
| Fecha de pago | hoy | hoy |
| Método | Efectivo | Transferencia |
| Referencia | `EFEC-0001` | `TRF-1002` |
| Estado | Pagado | Pagado |

---

## 6) Asistencia

**Para qué sirve:** registrar la entrada (check-in) de los socios, con o sin clase.

**Cómo llegar:** menú → **Operaciones → Asistencia** (`/attendance`) → **+ Nuevo**
(o el botón de huella del encabezado para marcar rápido).

**Pasos:** elige **Socio**, opcionalmente la **Clase**, la fecha/hora de **check-in** y notas.

**Registros de ejemplo:**

| Campo | Asistencia 1 | Asistencia 2 |
|-------|--------------|--------------|
| Socio | Juan Pérez | Laura Torres |
| Clase | Spinning | (Acceso libre — sin clase) |
| Check-in | hoy 07:00 | hoy 18:00 |

---

## 7) Inventario

**Para qué sirve:** controlar productos y equipos, con alerta de stock bajo cuando la
cantidad llega al mínimo.

**Cómo llegar:** menú → **Administración → Inventario** (`/inventory`) → **+ Nuevo**.

**Pasos:** **Nombre**, **Categoría**, **Cantidad**, **Cantidad mínima**, **Precio unitario**,
**Proveedor**, **Fecha de compra** y **Estado**.

**Registros de ejemplo:**

**Ítem 1**
- Nombre: `Toallas` · Categoría: `Textil` · Cantidad: `50` · Mínimo: `10`
- Precio unitario: `15` · Proveedor: `Textiles Lima` · Estado: `Disponible`

**Ítem 2**
- Nombre: `Proteína Whey 1kg` · Categoría: `Suplementos` · Cantidad: `8` · Mínimo: `10`
- Precio unitario: `120` · Proveedor: `NutriMax` · Estado: `Disponible`
- *(La cantidad 8 < mínimo 10 disparará la alerta de stock bajo en el menú.)*

---

## 8) Rutinas (dentro de la ficha del socio)

**Para qué sirve:** asignar planes de entrenamiento a un socio.

**Cómo llegar:** **Socios** → abre un socio → sección **Rutinas** → **Agregar rutina**.

**Pasos:** **Título**, **Entrenador**, **Descripción** y los **ejercicios** (uno por línea).

**Registros de ejemplo:**

**Rutina 1 — para Juan Pérez**
- Título: `Hipertrofia · Día A` · Entrenador: Carlos Mendoza
- Ejercicios:
  ```
  Sentadilla 4x10
  Press de banca 4x8
  Remo con barra 4x10
  Curl de bíceps 3x12
  ```

**Rutina 2 — para Laura Torres**
- Título: `Full body principiante` · Entrenador: Ana García
- Ejercicios:
  ```
  Prensa de piernas 3x12
  Jalón al pecho 3x12
  Plancha 3x30s
  Elíptica 15 min
  ```

---

## 9) Mediciones (dentro de la ficha del socio)

**Para qué sirve:** llevar el progreso físico del socio. Con **2 o más** mediciones se dibuja
la gráfica de evolución.

**Cómo llegar:** **Socios** → abre un socio → sección **Mediciones** → **Agregar medición**.

**Pasos:** **Fecha**, **Peso** (kg), **Altura** (cm), **% grasa**, y medidas de **pecho,
cintura, cadera, brazo, muslo** (cm).

**Registros de ejemplo (mismo socio, Juan Pérez, para ver el progreso):**

| Campo | Medición 1 | Medición 2 |
|-------|------------|------------|
| Fecha | hace 1 mes | hoy |
| Peso (kg) | `80` | `78` |
| Altura (cm) | `175` | `175` |
| % grasa | `20` | `18` |
| Cintura (cm) | `88` | `85` |

---

## 10) Reportes (solo lectura)

**Para qué sirve:** ver ingresos, socios activos y asistencia. No se cargan registros; el
tablero se llena con lo que registraste.

**Cómo llegar:** menú → **Administración → Reportes** (`/reports`). Con **Exportar** obtienes
CSV de socios o pagos.

---

## 11) Respaldos (copia / restauración / reseteo de TUS datos)

**Para qué sirve:** respaldar, restaurar o resetear **únicamente los datos de tu gimnasio**.

**Cómo llegar:** menú → **Administración → Respaldos** (`/respaldos`).

- **Copia de seguridad:** botón para descargar un `.sql` con tus datos.
- **Restaurar:** sube un respaldo **de tu propio gimnasio** (escribe `RESTAURAR`).
- **Resetear mis datos:** deja tu gimnasio en cero para empezar de nuevo (escribe `RESETEAR`).
  Tu cuenta y tu acceso siempre se conservan, y ningún otro gimnasio se ve afectado.

---

### Resumen del flujo diario
1. Configuras la **empresa** (una vez).
2. Creas **planes** y **entrenadores**.
3. Armas las **clases**.
4. Registras **socios** (con su plan) y sus **pagos**.
5. Marcas **asistencia** cada día.
6. Gestionas **inventario**.
7. Asignas **rutinas** y registras **mediciones** por socio.
8. Revisas **reportes** y haces **respaldos** periódicos.
