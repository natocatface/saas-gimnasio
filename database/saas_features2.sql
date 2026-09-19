-- ============================================================
-- GymSaaS Pro - Funcionalidades 2
-- Cupones, soporte/tickets y columnas de descuento en suscripciones.
--
-- Ejecutar:  mysql -u root saas_gimnasio < database\saas_features2.sql
-- (o importar desde phpMyAdmin en la base saas_gimnasio)
-- Idempotente: se puede correr varias veces.
-- ============================================================

USE `saas_gimnasio`;
SET FOREIGN_KEY_CHECKS = 0;

-- Helper para agregar columnas si faltan (MySQL y MariaDB)
DROP PROCEDURE IF EXISTS `add_col_if_missing2`;
DELIMITER //
CREATE PROCEDURE `add_col_if_missing2`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN ddl TEXT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col) THEN
        SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
        PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
    END IF;
END //
DELIMITER ;

-- ============================================================
-- CUPONES (los crea el Super Admin; los aplican los gimnasios)
-- ============================================================
CREATE TABLE IF NOT EXISTS `coupons` (
  `id`           bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code`         varchar(40) NOT NULL UNIQUE,
  `description`  varchar(255) NULL,
  `type`         enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `value`        decimal(10,2) NOT NULL DEFAULT 0,
  `saas_plan_id` bigint UNSIGNED NULL COMMENT 'NULL = aplica a cualquier plan',
  `max_uses`     int NULL COMMENT 'NULL = ilimitado',
  `used_count`   int NOT NULL DEFAULT 0,
  `expires_at`   date NULL,
  `is_active`    tinyint(1) NOT NULL DEFAULT 1,
  `created_at`   timestamp NULL DEFAULT NULL,
  `updated_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SOPORTE / TICKETS
-- ============================================================
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `id`           bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint UNSIGNED NOT NULL,
  `user_id`      bigint UNSIGNED NULL,
  `subject`      varchar(255) NOT NULL,
  `priority`     enum('low','normal','high') NOT NULL DEFAULT 'normal',
  `status`       enum('open','pending','closed') NOT NULL DEFAULT 'open',
  `last_reply_at` timestamp NULL DEFAULT NULL,
  `created_at`   timestamp NULL DEFAULT NULL,
  `updated_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ticket_gym` (`gymnasium_id`),
  KEY `idx_ticket_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `support_ticket_messages` (
  `id`         bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id`  bigint UNSIGNED NOT NULL,
  `user_id`    bigint UNSIGNED NULL,
  `is_staff`   tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = respuesta del Super Admin',
  `body`       text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_msg_ticket` (`ticket_id`),
  FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DESCUENTOS EN SUSCRIPCIONES (para recibos)
-- ============================================================
CALL add_col_if_missing2('gym_subscriptions', 'coupon_code', "`coupon_code` varchar(40) NULL AFTER `payment_ref`");
CALL add_col_if_missing2('gym_subscriptions', 'discount',    "`discount` decimal(10,2) NOT NULL DEFAULT 0 AFTER `coupon_code`");

DROP PROCEDURE IF EXISTS `add_col_if_missing2`;
SET FOREIGN_KEY_CHECKS = 1;

-- Cupón de ejemplo
INSERT IGNORE INTO `coupons` (`code`,`description`,`type`,`value`,`max_uses`,`is_active`,`created_at`,`updated_at`)
VALUES ('BIENVENIDA20','20% de descuento de bienvenida','percent',20,100,1,NOW(),NOW());

SELECT 'Bloque 2 (cupones, soporte, descuentos) listo' AS resultado;
