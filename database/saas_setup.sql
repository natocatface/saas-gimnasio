-- ============================================================
-- GymSaaS Pro - SETUP / REPARACION MULTI-TENANT (idempotente)
-- Compatible con MySQL 5.7+/8.0 y MariaDB.
--
-- Ejecutar:
--   mysql -u root saas_gimnasio < database\saas_setup.sql
-- (o impórtalo desde phpMyAdmin en la base "saas_gimnasio")
--
-- Soluciona el error: "Tu cuenta no esta asociada a ningun gimnasio".
-- Se puede correr varias veces sin romper nada.
-- ============================================================

USE `saas_gimnasio`;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Helper: agrega una columna solo si no existe (MySQL y MariaDB)
-- ------------------------------------------------------------
DROP PROCEDURE IF EXISTS `add_col_if_missing`;
DELIMITER //
CREATE PROCEDURE `add_col_if_missing`(
    IN tbl VARCHAR(64),
    IN col VARCHAR(64),
    IN ddl TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = tbl
          AND COLUMN_NAME = col
    ) THEN
        SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN ', ddl);
        PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
    END IF;
END //
DELIMITER ;

-- ============================================================
-- 1) TABLAS SAAS
-- ============================================================
CREATE TABLE IF NOT EXISTS `saas_plans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL UNIQUE,
  `description` text NULL,
  `price_monthly` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_yearly`  decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_members`   int NOT NULL DEFAULT 100,
  `max_trainers`  int NOT NULL DEFAULT 5,
  `max_classes`   int NOT NULL DEFAULT 10,
  `features`      text NULL,
  `color`         varchar(20) NULL DEFAULT '#7c3aed',
  `is_popular`    tinyint(1) NOT NULL DEFAULT 0,
  `is_active`     tinyint(1) NOT NULL DEFAULT 1,
  `sort_order`    int NOT NULL DEFAULT 0,
  `created_at`    timestamp NULL DEFAULT NULL,
  `updated_at`    timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `gymnasiums` (
  `id`              bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`            varchar(255) NOT NULL,
  `slug`            varchar(100) NOT NULL UNIQUE,
  `email`           varchar(255) NULL,
  `phone`           varchar(30) NULL,
  `address`         text NULL,
  `city`            varchar(100) NULL,
  `country`         varchar(60) NULL DEFAULT 'PE',
  `logo`            varchar(255) NULL,
  `primary_color`   varchar(20) NULL DEFAULT '#7c3aed',
  `saas_plan_id`    bigint UNSIGNED NULL,
  `owner_id`        bigint UNSIGNED NULL,
  `status`          enum('active','trial','suspended','cancelled') NOT NULL DEFAULT 'trial',
  `trial_ends_at`   timestamp NULL DEFAULT NULL,
  `max_members`     int NOT NULL DEFAULT 100,
  `settings`        text NULL,
  `created_at`      timestamp NULL DEFAULT NULL,
  `updated_at`      timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `gym_subscriptions` (
  `id`           bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint UNSIGNED NOT NULL,
  `saas_plan_id` bigint UNSIGNED NULL,
  `amount`       decimal(10,2) NOT NULL,
  `billing_cycle` enum('monthly','yearly') NOT NULL DEFAULT 'monthly',
  `starts_at`    date NOT NULL,
  `ends_at`      date NOT NULL,
  `status`       enum('active','expired','cancelled','pending') NOT NULL DEFAULT 'active',
  `payment_ref`  varchar(100) NULL,
  `notes`        text NULL,
  `created_at`   timestamp NULL DEFAULT NULL,
  `updated_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 2) COLUMNA gymnasium_id EN TODAS LAS TABLAS DEL GYM
-- ============================================================
CALL add_col_if_missing('users',       'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('members',     'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('plans',       'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('payments',    'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('trainers',    'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('gym_classes', 'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('attendance',  'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('inventory',   'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');
CALL add_col_if_missing('settings',    'gymnasium_id', '`gymnasium_id` bigint UNSIGNED NULL AFTER `id`');

-- El rol debe permitir 'superadmin' -> lo pasamos a varchar
ALTER TABLE `users` MODIFY `role` VARCHAR(20) NOT NULL DEFAULT 'member';
-- Columna status de usuarios (por si el modelo la usa como texto)
-- (se deja como esta; no se toca)

-- ============================================================
-- 3) PLANES SAAS (no se duplican gracias al slug unico)
-- ============================================================
INSERT IGNORE INTO `saas_plans`
  (`name`,`slug`,`description`,`price_monthly`,`price_yearly`,`max_members`,`max_trainers`,`max_classes`,`features`,`color`,`is_popular`,`is_active`,`sort_order`,`created_at`,`updated_at`) VALUES
('Starter','starter','Ideal para gimnasios pequenos que estan comenzando',29.00,290.00,50,3,5,'["Hasta 50 socios","3 entrenadores","5 clases","Soporte por email","Reportes basicos"]','#6b7280',0,1,1,NOW(),NOW()),
('Pro','pro','Para gimnasios en crecimiento con todas las funciones',59.00,590.00,200,10,20,'["Hasta 200 socios","10 entrenadores","20 clases","Soporte prioritario","Reportes avanzados","Inventario","App movil"]','#7c3aed',1,1,2,NOW(),NOW()),
('Enterprise','enterprise','Sin limites para cadenas y gimnasios grandes',99.00,990.00,999999,999999,999999,'["Socios ilimitados","Entrenadores ilimitados","Clases ilimitadas","Soporte 24/7","API access","White-label","Multi-sucursal"]','#ec4899',0,1,3,NOW(),NOW());

-- ============================================================
-- 4) GIMNASIO DEMO (id = 1) - solo si aun no hay gimnasios
-- ============================================================
INSERT INTO `gymnasiums`
  (`id`,`name`,`slug`,`email`,`phone`,`address`,`city`,`country`,`primary_color`,`saas_plan_id`,`status`,`trial_ends_at`,`max_members`,`created_at`,`updated_at`)
SELECT 1,'GymSaaS Demo','demo','admin@gymsaas.com','555-0100','Av. Fitness 123','Lima','PE','#7c3aed',
       (SELECT id FROM saas_plans WHERE slug='pro' LIMIT 1),
       'active', DATE_ADD(NOW(), INTERVAL 30 DAY), 200, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `gymnasiums` WHERE `id` = 1);

-- ============================================================
-- 5) ASIGNAR EL GIMNASIO #1 A TODOS LOS DATOS EXISTENTES
-- ============================================================
UPDATE `users`       SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL AND `role` <> 'superadmin';
UPDATE `members`     SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `plans`       SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `payments`    SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `trainers`    SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `gym_classes` SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `attendance`  SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `inventory`   SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `settings`    SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;

-- Vincular al admin como dueno del gimnasio #1
UPDATE `gymnasiums`
SET `owner_id` = (SELECT id FROM users WHERE email = 'admin@gymsaas.com' LIMIT 1)
WHERE `id` = 1 AND (`owner_id` IS NULL OR `owner_id` = 0);

-- ============================================================
-- 6) SUPER ADMIN (dueno del SaaS) - no se duplica por email unico
--    Usuario: superadmin@gymsaas.com   Password: password
-- ============================================================
INSERT IGNORE INTO `users` (`name`,`email`,`password`,`role`,`gymnasium_id`,`status`,`created_at`,`updated_at`) VALUES
('Super Admin','superadmin@gymsaas.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','superadmin',NULL,1,NOW(),NOW());

-- Asegurar que el superadmin tenga el rol correcto aunque ya existiera
UPDATE `users` SET `role` = 'superadmin', `gymnasium_id` = NULL WHERE `email` = 'superadmin@gymsaas.com';

-- ------------------------------------------------------------
DROP PROCEDURE IF EXISTS `add_col_if_missing`;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- VERIFICACION
-- ============================================================
SELECT 'Setup multi-tenant completado' AS resultado;
SELECT id, name, email, role, gymnasium_id FROM users ORDER BY id;
SELECT id, name, slug, status, saas_plan_id, owner_id FROM gymnasiums;
SELECT name, slug, price_monthly FROM saas_plans;
