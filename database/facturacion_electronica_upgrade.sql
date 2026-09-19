-- ============================================================
-- Actualización de facturación electrónica: campos driver y auto_emit
-- Ejecutar una vez si ya tenías la tabla billing_settings creada:
--   mysql -u root saas_gimnasio < database\facturacion_electronica_upgrade.sql
-- ============================================================
USE `saas_gimnasio`;

ALTER TABLE `billing_settings`
  ADD COLUMN `driver` varchar(20) NOT NULL DEFAULT 'greenter' AFTER `moneda`,
  ADD COLUMN `auto_emit` tinyint(1) NOT NULL DEFAULT '0' AFTER `driver`;
