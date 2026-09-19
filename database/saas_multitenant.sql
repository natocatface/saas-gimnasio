-- ============================================================
-- GymSaaS Pro — Migración Multi-Tenant
-- Ejecutar: mysql -u root saas_gimnasio < database\saas_multitenant.sql
-- ============================================================

USE `saas_gimnasio`;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- PLANES SAAS (los que tú vendes a los dueños de gymn)
-- Diferente a los planes de membresía de los socios
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
  `features`      text NULL COMMENT 'JSON array',
  `color`         varchar(20) NULL DEFAULT '#7c3aed',
  `is_popular`    tinyint(1) NOT NULL DEFAULT 0,
  `is_active`     tinyint(1) NOT NULL DEFAULT 1,
  `sort_order`    int NOT NULL DEFAULT 0,
  `created_at`    timestamp NULL DEFAULT NULL,
  `updated_at`    timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- GIMNASIOS (cada cliente que paga el SaaS)
-- ============================================================
CREATE TABLE IF NOT EXISTS `gymnasiums` (
  `id`              bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`            varchar(255) NOT NULL,
  `slug`            varchar(100) NOT NULL UNIQUE COMMENT 'subdominio: mi-gym.gymsaas.com',
  `email`           varchar(255) NULL,
  `phone`           varchar(30) NULL,
  `address`         text NULL,
  `city`            varchar(100) NULL,
  `country`         varchar(60) NULL DEFAULT 'PE',
  `logo`            varchar(255) NULL,
  `primary_color`   varchar(20) NULL DEFAULT '#7c3aed',
  `saas_plan_id`    bigint UNSIGNED NULL,
  `owner_id`        bigint UNSIGNED NULL COMMENT 'user que registró el gym',
  `status`          enum('active','trial','suspended','cancelled') NOT NULL DEFAULT 'trial',
  `trial_ends_at`   timestamp NULL DEFAULT NULL,
  `max_members`     int NOT NULL DEFAULT 100,
  `settings`        text NULL COMMENT 'JSON config por gym',
  `created_at`      timestamp NULL DEFAULT NULL,
  `updated_at`      timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`saas_plan_id`) REFERENCES `saas_plans`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SUSCRIPCIONES (historial de cobros SaaS)
-- ============================================================
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
  PRIMARY KEY (`id`),
  FOREIGN KEY (`gymnasium_id`) REFERENCES `gymnasiums`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`saas_plan_id`) REFERENCES `saas_plans`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- AGREGAR gymnasium_id A TODAS LAS TABLAS EXISTENTES
-- ============================================================

-- Users
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`,
  ADD COLUMN IF NOT EXISTS `role` varchar(20) NOT NULL DEFAULT 'admin' AFTER `name`;

-- Actualizar la columna role si ya existe (por si acaso)
-- Members
ALTER TABLE `members`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- Plans (planes de membresía del gym)
ALTER TABLE `plans`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- Payments
ALTER TABLE `payments`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- Trainers
ALTER TABLE `trainers`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- gym_classes
ALTER TABLE `gym_classes`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- attendance
ALTER TABLE `attendance`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- inventory
ALTER TABLE `inventory`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- settings
ALTER TABLE `settings`
  ADD COLUMN IF NOT EXISTS `gymnasium_id` bigint UNSIGNED NULL AFTER `id`;

-- ============================================================
-- ÍNDICES para búsquedas rápidas por gymnasium_id
-- ============================================================
CREATE INDEX IF NOT EXISTS idx_members_gym    ON `members`    (`gymnasium_id`);
CREATE INDEX IF NOT EXISTS idx_payments_gym   ON `payments`   (`gymnasium_id`);
CREATE INDEX IF NOT EXISTS idx_trainers_gym   ON `trainers`   (`gymnasium_id`);
CREATE INDEX IF NOT EXISTS idx_classes_gym    ON `gym_classes`(`gymnasium_id`);
CREATE INDEX IF NOT EXISTS idx_attendance_gym ON `attendance` (`gymnasium_id`);
CREATE INDEX IF NOT EXISTS idx_inventory_gym  ON `inventory`  (`gymnasium_id`);
CREATE INDEX IF NOT EXISTS idx_plans_gym      ON `plans`      (`gymnasium_id`);
CREATE INDEX IF NOT EXISTS idx_users_gym      ON `users`      (`gymnasium_id`);

-- ============================================================
-- DATOS INICIALES — Planes SaaS
-- ============================================================
INSERT INTO `saas_plans` (`name`,`slug`,`description`,`price_monthly`,`price_yearly`,`max_members`,`max_trainers`,`max_classes`,`features`,`color`,`is_popular`,`is_active`,`sort_order`,`created_at`,`updated_at`) VALUES
('Starter','starter','Ideal para gimnasios pequeños que están comenzando',29.00,290.00,50,3,5,'["Hasta 50 socios","3 entrenadores","5 clases","Soporte por email","Reportes básicos"]','#6b7280',0,1,1,NOW(),NOW()),
('Pro','pro','Para gimnasios en crecimiento con todas las funciones',59.00,590.00,200,10,20,'["Hasta 200 socios","10 entrenadores","20 clases","Soporte prioritario","Reportes avanzados","Inventario","App móvil"]','#7c3aed',1,1,2,NOW(),NOW()),
('Enterprise','enterprise','Sin límites para cadenas y gimnasios grandes',99.00,990.00,999999,999999,999999,'["Socios ilimitados","Entrenadores ilimitados","Clases ilimitadas","Soporte 24/7","API access","White-label","Multi-sucursal","Gerente de cuenta"]','#ec4899',0,1,3,NOW(),NOW());

-- ============================================================
-- GIMNASIO DEMO (el gym actual se convierte en gym #1)
-- ============================================================
INSERT INTO `gymnasiums` (`name`,`slug`,`email`,`phone`,`address`,`city`,`country`,`primary_color`,`saas_plan_id`,`status`,`trial_ends_at`,`max_members`,`created_at`,`updated_at`) VALUES
('GymSaaS Demo','demo','admin@gymsaas.com','555-0100','Av. Fitness 123','Lima','PE','#7c3aed',2,'active',DATE_ADD(NOW(), INTERVAL 30 DAY),200,NOW(),NOW());

-- ============================================================
-- ASIGNAR gymnasium_id = 1 A TODOS LOS DATOS EXISTENTES
-- (El gym actual pasa a ser el gimnasio #1)
-- ============================================================
UPDATE `users`       SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `members`     SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `plans`       SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `payments`    SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `trainers`    SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `gym_classes` SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `attendance`  SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `inventory`   SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;
UPDATE `settings`    SET `gymnasium_id` = 1 WHERE `gymnasium_id` IS NULL;

-- Asignar owner_id del gym al admin
UPDATE `gymnasiums` SET `owner_id` = (SELECT id FROM users WHERE role='admin' AND gymnasium_id=1 LIMIT 1) WHERE id = 1;

-- ============================================================
-- SUPER ADMIN (dueño del sistema SaaS)
-- password: superadmin123
-- ============================================================
INSERT INTO `users` (`name`,`email`,`password`,`role`,`gymnasium_id`,`status`,`created_at`,`updated_at`) VALUES
('Super Admin','superadmin@gymsaas.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','superadmin',NULL,1,NOW(),NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- VERIFICACIÓN FINAL
-- ============================================================
SELECT '✅ Migración multi-tenant completada' AS resultado;
SELECT COUNT(*) AS total_gymnasiums FROM gymnasiums;
SELECT COUNT(*) AS total_saas_plans FROM saas_plans;
SELECT name, slug, status FROM gymnasiums;
SELECT name, price_monthly, max_members FROM saas_plans;
