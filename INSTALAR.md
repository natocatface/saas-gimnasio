# 🏋️ GymSaaS Pro - Guía de Instalación

## Requisitos Previos
- PHP 8.1 o superior
- Composer
- MySQL 5.7+ o MariaDB
- Git (opcional)

---

## ⚡ Instalación Rápida

### Paso 1: Instalar dependencias de Laravel
```bash
cd C:\SAAS\saas_gimnasio
composer install
```

### Paso 2: Crear la base de datos en MySQL
Importar el archivo SQL con todos los datos de ejemplo:
```bash
mysql -u root -p < database/saas_gimnasio.sql
```
O desde phpMyAdmin: importar el archivo `database/saas_gimnasio.sql`

### Paso 3: Configurar el .env
El archivo `.env` ya está configurado con:
- DB_HOST=127.0.0.1
- DB_DATABASE=saas_gimnasio
- DB_USERNAME=root
- DB_PASSWORD= (vacío)

Si tu MySQL tiene contraseña, edita `.env` y agrega tu contraseña en `DB_PASSWORD=`

### Paso 4: Generar la clave de la aplicación
```bash
php artisan key:generate
```

### Paso 5: Crear los directorios de almacenamiento
```bash
php artisan storage:link
```

### Paso 6: Iniciar el servidor
```bash
php artisan serve
```

Luego abre tu navegador en: **http://localhost:8000**

---

## 🔐 Credenciales de Acceso

| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| Admin | admin@gymsaas.com | password | Administrador |
| Entrenador | trainer@gymsaas.com | password | Entrenador |
| Recepción | recep@gymsaas.com | password | Recepcionista |

---

## 📋 Módulos del Sistema

| Módulo | URL | Descripción |
|--------|-----|-------------|
| Dashboard | /dashboard | Panel principal con KPIs y gráficos |
| Socios | /members | Gestión completa de socios |
| Planes | /plans | Planes de membresía |
| Pagos | /payments | Registro de pagos y facturación |
| Clases | /classes | Horarios y clases grupales |
| Entrenadores | /trainers | Gestión de entrenadores |
| Asistencia | /attendance | Control de entradas/salidas |
| Inventario | /inventory | Equipamiento y stock |
| Reportes | /reports | Estadísticas y gráficos |
| Configuración | /settings | Configuración del gimnasio |

---

## 🛠️ Solución de Problemas

**Error de permisos en storage:**
```bash
chmod -R 777 storage bootstrap/cache
```

**Error de conexión a MySQL:**
Verifica que MySQL esté corriendo y las credenciales en `.env` sean correctas.

**Error 500:**
Revisa `storage/logs/laravel.log` para ver el detalle del error.
