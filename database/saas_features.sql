-- ============================================================
-- GymSaaS Pro - Funcionalidades adicionales
-- Tabla de notificaciones in-app por gimnasio.
--
-- Ejecutar:  mysql -u root saas_gimnasio < database\saas_features.sql
-- (o importar desde phpMyAdmin en la base saas_gimnasio)
-- Idempotente: se puede correr varias veces.
-- ============================================================

USE `saas_gimnasio`;

CREATE TABLE IF NOT EXISTS `gym_notifications` (
  `id`           bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint UNSIGNED NOT NULL,
  `user_id`      bigint UNSIGNED NULL COMMENT 'NULL = para todo el gimnasio',
  `type`         varchar(50) NOT NULL DEFAULT 'info',
  `title`        varchar(255) NOT NULL,
  `body`         text NULL,
  `icon`         varchar(40) NULL DEFAULT 'fa-bell',
  `color`        varchar(20) NULL DEFAULT '#7c3aed',
  `url`          varchar(255) NULL,
  `read_at`      timestamp NULL DEFAULT NULL,
  `created_at`   timestamp NULL DEFAULT NULL,
  `updated_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notif_gym`  (`gymnasium_id`),
  KEY `idx_notif_user` (`user_id`),
  KEY `idx_notif_read` (`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SELECT 'Tabla gym_notifications lista' AS resultado;
