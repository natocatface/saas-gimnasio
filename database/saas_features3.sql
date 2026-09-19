-- ============================================================
-- GymSaaS Pro - Funcionalidades 3
-- Rutinas, medidas corporales, portal del socio e inscripciones.
--
-- Ejecutar:  mysql -u root saas_gimnasio < database\saas_features3.sql
-- (o importar desde phpMyAdmin en la base saas_gimnasio)
-- Idempotente: se puede correr varias veces.
-- ============================================================

USE `saas_gimnasio`;
SET FOREIGN_KEY_CHECKS = 0;

DROP PROCEDURE IF EXISTS `add_col_if_missing3`;
DELIMITER //
CREATE PROCEDURE `add_col_if_missing3`(IN tbl VARCHAR(64), IN col VARCHAR(64), IN ddl TEXT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col) THEN
        SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
        PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
    END IF;
END //
DELIMITER ;

-- ── Portal del socio: contraseña en members ─────────────────
CALL add_col_if_missing3('members', 'password', "`password` varchar(255) NULL AFTER `email`");

-- ── Inscripciones: gymnasium_id para aislamiento ────────────
CALL add_col_if_missing3('class_enrollments', 'gymnasium_id', "`gymnasium_id` bigint UNSIGNED NULL AFTER `id`");
UPDATE `class_enrollments` ce
  JOIN `gym_classes` gc ON gc.id = ce.class_id
  SET ce.gymnasium_id = gc.gymnasium_id
  WHERE ce.gymnasium_id IS NULL;

-- ============================================================
-- RUTINAS DE ENTRENAMIENTO
-- ============================================================
CREATE TABLE IF NOT EXISTS `routines` (
  `id`           bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint UNSIGNED NOT NULL,
  `member_id`    bigint UNSIGNED NOT NULL,
  `trainer_id`   bigint UNSIGNED NULL,
  `title`        varchar(255) NOT NULL,
  `description`  text NULL,
  `exercises`    text NULL COMMENT 'JSON: [{name,sets,reps,rest,notes}]',
  `is_active`    tinyint(1) NOT NULL DEFAULT 1,
  `created_at`   timestamp NULL DEFAULT NULL,
  `updated_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_routine_gym` (`gymnasium_id`),
  KEY `idx_routine_member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- MEDIDAS CORPORALES (progreso)
-- ============================================================
CREATE TABLE IF NOT EXISTS `member_measurements` (
  `id`           bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint UNSIGNED NOT NULL,
  `member_id`    bigint UNSIGNED NOT NULL,
  `measured_on`  date NOT NULL,
  `weight`       decimal(6,2) NULL COMMENT 'kg',
  `height`       decimal(6,2) NULL COMMENT 'cm',
  `body_fat`     decimal(5,2) NULL COMMENT '%',
  `chest`        decimal(6,2) NULL,
  `waist`        decimal(6,2) NULL,
  `hips`         decimal(6,2) NULL,
  `arm`          decimal(6,2) NULL,
  `thigh`        decimal(6,2) NULL,
  `notes`        text NULL,
  `created_at`   timestamp NULL DEFAULT NULL,
  `updated_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_meas_gym` (`gymnasium_id`),
  KEY `idx_meas_member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP PROCEDURE IF EXISTS `add_col_if_missing3`;
SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Bloque 3 (rutinas, medidas, portal) listo' AS resultado;
