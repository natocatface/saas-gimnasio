-- ============================================================
-- GymSaaS Pro — Corrección de constraints multi-tenant
-- Ejecutar: mysql -u root saas_gimnasio < database\fix_multitenant_constraints.sql
--
-- PROBLEMA:
--   La tabla `settings` tiene UNIQUE global en `key`. Como ahora cada
--   gimnasio guarda sus propias settings con la misma clave (ej.
--   'currency_symbol'), el SEGUNDO gimnasio en adelante recibe un error
--   1062 (Duplicate entry) al guardar Ajustes. La clave debe ser única
--   POR gimnasio, no global.
-- ============================================================

-- Compatible con MySQL 8.x (ya aplicado el 18/06/2026 en saas_gimnasio).
USE `saas_gimnasio`;

-- 1) Quitar el índice UNIQUE global sobre `key`
--    (MySQL nombra ese índice como `key` al declarar la columna UNIQUE)
ALTER TABLE `settings` DROP INDEX `key`;

-- 2) Crear índice UNIQUE compuesto: una clave por gimnasio
--    (el prefijo gymnasium_id de este índice ya sirve para el filtro por tenant,
--     por eso no se crea un índice adicional)
ALTER TABLE `settings`
  ADD UNIQUE KEY `settings_gym_key` (`gymnasium_id`, `key`);

SHOW INDEX FROM `settings`;
