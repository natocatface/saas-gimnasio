# Revisión del sistema — GymSaaS Pro

**Fecha:** 18/06/2026
**Alcance:** Caza de bugs módulo por módulo (rutas, controladores, modelos, vistas, esquema SQL multi-tenant).
**Resultado:** 6 problemas detectados. 5 corregidos en código; 1 requiere ejecutar un script SQL.

---

## Resumen de hallazgos

| # | Módulo | Problema | Severidad | Estado |
|---|--------|----------|-----------|--------|
| 1 | Socios (vista detalle) | `whereMonth()` sobre colección cargada → `BadMethodCallException` | Alta | ✅ Corregido |
| 2 | Ajustes | `settings.key` con UNIQUE global rompe el guardado para el 2.º gimnasio en adelante | Alta | ⚠️ Requiere SQL |
| 3 | Socios (alta) | Código de socio colisiona con el UNIQUE global en gimnasios nuevos | Alta | ✅ Corregido |
| 4 | Exportaciones | CSV de pagos: columna "Método" siempre vacía (`$p->method` inexistente) | Media | ✅ Corregido |
| 5 | Socios / Entrenadores | Email único global impide reusar un email en otro gimnasio | Media | ✅ Corregido |
| 6 | Repositorio | 42 archivos basura `.fuse_hidden*` y código muerto duplicado de tenant | Baja | ℹ️ Recomendación |

---

## Detalle por módulo

### 1. Socios — vista de detalle (Alta) ✅
`resources/views/members/show.blade.php` llamaba `$member->attendance->whereMonth(...)` y `->where('check_in','>=',...)`. Como `attendance` es una colección ya cargada (no un query builder), `whereMonth()` no existe y lanzaba `BadMethodCallException` al entrar a `/members/{id}`.

**Corrección:** se reemplazó por `filter()` con Carbon para "este mes" y "esta semana".

> Nota de rendimiento: filtrar en memoria está bien para volúmenes normales. Si un socio acumula miles de asistencias, conviene mover esos conteos al controlador con consultas (`whereMonth` sobre el query builder).

### 2. Ajustes — clave única global (Alta) ⚠️
La tabla `settings` tiene `key varchar(100) NOT NULL UNIQUE`. La migración multi-tenant añadió `gymnasium_id` pero **no** ajustó este índice. Cada gimnasio guarda sus propias claves (ej. `currency_symbol`), así que el **segundo gimnasio** que intente guardar Ajustes recibe error `1062 Duplicate entry`.

**Corrección:** script `database/fix_multitenant_constraints.sql` que cambia el UNIQUE de `key` a un compuesto `(gymnasium_id, key)`.

```bash
mysql -u root saas_gimnasio < database\fix_multitenant_constraints.sql
```

### 3. Socios — generación de código (Alta) ✅
`MemberController::store()` usaba `Member::max('id')`, que pasa por el scope de tenant y devuelve el máximo **del gimnasio actual**. Un gimnasio nuevo (0 socios) generaba `GYM-001`, que ya existe globalmente (el `code` es UNIQUE global) → error al registrar el primer socio.

**Corrección:** ahora usa el máximo global (`withoutGlobalScope('tenant')`) y verifica unicidad en bucle antes de asignar el código.

### 4. Exportaciones — CSV de pagos (Media) ✅
`ExportController::payments()` leía `$p->method`, pero la columna real es `payment_method`. La columna "Método" salía vacía en todo el CSV.

**Corrección:** `$p->payment_method`.

### 5. Socios / Entrenadores — email único global (Media) ✅
Las validaciones usaban `unique:members,email` y `unique:trainers,email` sin filtrar por gimnasio. Dos gimnasios distintos no podían tener un socio/entrenador con el mismo correo (escenario legítimo en multi-tenant).

**Corrección:** las reglas `unique` ahora se acotan con `->where('gymnasium_id', ...)` en alta y edición de `MemberController` y `TrainerController`.

### 6. Repositorio — limpieza (Baja) ℹ️
- 42 archivos `.fuse_hidden*` (residuos del editor sobre el montaje FUSE) en `app/Models`. No afectan la ejecución pero ensucian el repo. No se pudieron borrar desde aquí porque el sistema los tiene bloqueados; elimínalos tras cerrar el editor:
  ```bash
  find . -name ".fuse_hidden*" -delete
  ```
- Código muerto: `app/Models/Concerns/BelongsToTenant.php` + `app/Models/Scopes/TenantScope.php` duplican la lógica del trait activo `app/Traits/HasTenant.php` (que es el realmente usado). Se pueden eliminar para evitar confusión.

---

## Módulos revisados sin bugs

Autenticación y login, Portal del socio (valida ownership correctamente), Pagos, Clases e inscripciones (control de cupo y tenant OK), Asistencia, Inventario, Rutinas, Mediciones, Facturación y cupones, Staff (permisos de admin OK), Soporte, Notificaciones, Perfil, Marca del gimnasio, Onboarding, Registro de gimnasios, Dashboard y Reportes, y el panel Super Admin. El aislamiento multi-tenant (trait `HasTenant` + `TenantScope` + `TenantMiddleware`) está bien implementado.

> Observación menor (no es bug): en `ReportController`, las rutas `reports/revenue`, `reports/members` y `reports/attendance` devuelven todas la misma vista `index()`. Si la intención era tener páginas separadas, conviene implementarlas.

---

## Archivos modificados

- `resources/views/members/show.blade.php`
- `app/Http/Controllers/MemberController.php`
- `app/Http/Controllers/TrainerController.php`
- `app/Http/Controllers/ExportController.php`
- `database/fix_multitenant_constraints.sql` *(nuevo — pendiente de ejecutar)*

## Acción pendiente de tu parte

1. Ejecutar `database/fix_multitenant_constraints.sql` contra la base de datos.
2. (Opcional) Borrar los `.fuse_hidden*` y el código muerto de tenant.
