-- ============================================================
-- SaaS Gimnasio - Base de Datos Completa
-- ============================================================

CREATE DATABASE IF NOT EXISTS `saas_gimnasio` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `saas_gimnasio`;

-- ----------------------------
-- Usuarios del sistema
-- ----------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','trainer','member','receptionist') NOT NULL DEFAULT 'member',
  `avatar` varchar(255) NULL,
  `phone` varchar(20) NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Planes de membresía
-- ----------------------------
CREATE TABLE IF NOT EXISTS `plans` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duration_days` int NOT NULL DEFAULT 30,
  `features` text NULL COMMENT 'JSON array de características',
  `color` varchar(10) NULL DEFAULT '#7c3aed',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Entrenadores
-- ----------------------------
CREATE TABLE IF NOT EXISTS `trainers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NULL,
  `phone` varchar(20) NULL,
  `speciality` varchar(255) NULL,
  `bio` text NULL,
  `photo` varchar(255) NULL,
  `hire_date` date NULL,
  `salary` decimal(10,2) NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Socios/Miembros
-- ----------------------------
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL,
  `code` varchar(20) NOT NULL UNIQUE,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NULL,
  `phone` varchar(20) NULL,
  `birth_date` date NULL,
  `gender` enum('M','F','otro') NULL,
  `address` text NULL,
  `emergency_contact` varchar(255) NULL,
  `emergency_phone` varchar(20) NULL,
  `photo` varchar(255) NULL,
  `plan_id` bigint(20) UNSIGNED NULL,
  `membership_start` date NULL,
  `membership_end` date NULL,
  `status` enum('activo','inactivo','suspendido','vencido') NOT NULL DEFAULT 'activo',
  `notes` text NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`plan_id`) REFERENCES `plans`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Pagos
-- ----------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_date` date NOT NULL,
  `payment_method` enum('efectivo','tarjeta','transferencia','qr') NOT NULL DEFAULT 'efectivo',
  `reference` varchar(100) NULL,
  `period_start` date NULL,
  `period_end` date NULL,
  `status` enum('pagado','pendiente','cancelado') NOT NULL DEFAULT 'pagado',
  `notes` text NULL,
  `created_by` bigint(20) UNSIGNED NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`member_id`) REFERENCES `members`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`plan_id`) REFERENCES `plans`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Clases
-- ----------------------------
CREATE TABLE IF NOT EXISTS `gym_classes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NULL,
  `trainer_id` bigint(20) UNSIGNED NULL,
  `capacity` int NOT NULL DEFAULT 20,
  `duration_minutes` int NOT NULL DEFAULT 60,
  `schedule_day` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NULL,
  `schedule_time` time NULL,
  `room` varchar(100) NULL,
  `color` varchar(10) NULL DEFAULT '#7c3aed',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`trainer_id`) REFERENCES `trainers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Inscripción a clases
-- ----------------------------
CREATE TABLE IF NOT EXISTS `class_enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('activo','cancelado') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_member` (`class_id`,`member_id`),
  FOREIGN KEY (`class_id`) REFERENCES `gym_classes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`member_id`) REFERENCES `members`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Asistencia
-- ----------------------------
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime NULL,
  `notes` text NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`member_id`) REFERENCES `members`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`class_id`) REFERENCES `gym_classes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Inventario
-- ----------------------------
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NULL,
  `description` text NULL,
  `quantity` int NOT NULL DEFAULT 0,
  `min_quantity` int NOT NULL DEFAULT 5,
  `unit_price` decimal(10,2) NULL DEFAULT 0.00,
  `supplier` varchar(255) NULL,
  `purchase_date` date NULL,
  `status` enum('disponible','agotado','mantenimiento') NOT NULL DEFAULT 'disponible',
  `notes` text NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Configuración del gimnasio
-- ----------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL UNIQUE,
  `value` text NULL,
  `group` varchar(50) NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Sesiones de Laravel
-- ----------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NULL,
  `ip_address` varchar(45) NULL,
  `user_agent` text NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DATOS INICIALES (Seeders)
-- ============================================================

-- Usuario administrador (password: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
('Administrador', 'admin@gymsaas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW(), NOW()),
('Carlos Mendoza', 'trainer@gymsaas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', 1, NOW(), NOW()),
('María López', 'recep@gymsaas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'receptionist', 1, NOW(), NOW());

-- Planes de membresía
INSERT INTO `plans` (`name`, `description`, `price`, `duration_days`, `features`, `color`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
('Plan Básico', 'Acceso al gimnasio en horario regular', 29.99, 30, '["Acceso al gym","Vestuarios","Casillero básico"]', '#6b7280', 0, 1, NOW(), NOW()),
('Plan Estándar', 'Acceso completo + 2 clases grupales', 49.99, 30, '["Todo el Plan Básico","2 Clases grupales","Evaluación mensual","App móvil"]', '#7c3aed', 1, 1, NOW(), NOW()),
('Plan Premium', 'Todo incluido + entrenador personal', 89.99, 30, '["Todo el Plan Estándar","Entrenador personal 4x/mes","Nutricionista","Clases ilimitadas","Zona VIP"]', '#ec4899', 0, 1, NOW(), NOW()),
('Plan Anual', 'Plan Estándar por 12 meses con descuento', 399.99, 365, '["Todo el Plan Estándar","Descuento 33%","2 Meses gratis","Invitados 2x/mes"]', '#059669', 0, 1, NOW(), NOW());

-- Entrenadores
INSERT INTO `trainers` (`name`, `email`, `phone`, `speciality`, `bio`, `hire_date`, `salary`, `status`, `created_at`, `updated_at`) VALUES
('Carlos Mendoza', 'carlos@gym.com', '555-1001', 'Musculación y Fuerza', 'Especialista en fuerza con 8 años de experiencia', '2023-01-15', 1200.00, 1, NOW(), NOW()),
('Ana García', 'ana@gym.com', '555-1002', 'Yoga y Pilates', 'Instructora certificada en Hatha Yoga y Pilates reformer', '2023-03-01', 1100.00, 1, NOW(), NOW()),
('Luis Torres', 'luis@gym.com', '555-1003', 'CrossFit y Cardio', 'Coach CrossFit nivel 2 con certificación internacional', '2022-06-10', 1300.00, 1, NOW(), NOW()),
('Sofia Ríos', 'sofia@gym.com', '555-1004', 'Zumba y Baile', 'Instructora de Zumba con 5 años de experiencia', '2023-09-01', 1000.00, 1, NOW(), NOW());

-- Socios de ejemplo
INSERT INTO `members` (`code`, `first_name`, `last_name`, `email`, `phone`, `gender`, `plan_id`, `membership_start`, `membership_end`, `status`, `created_at`, `updated_at`) VALUES
('GYM-001', 'Juan', 'Pérez', 'juan@email.com', '555-2001', 'M', 2, '2026-06-01', '2026-06-30', 'activo', NOW(), NOW()),
('GYM-002', 'Laura', 'Martínez', 'laura@email.com', '555-2002', 'F', 3, '2026-05-15', '2026-06-14', 'activo', NOW(), NOW()),
('GYM-003', 'Pedro', 'Sánchez', 'pedro@email.com', '555-2003', 'M', 1, '2026-06-01', '2026-06-30', 'activo', NOW(), NOW()),
('GYM-004', 'María', 'González', 'maria@email.com', '555-2004', 'F', 2, '2026-04-01', '2026-04-30', 'vencido', NOW(), NOW()),
('GYM-005', 'Roberto', 'Flores', 'roberto@email.com', '555-2005', 'M', 4, '2026-01-01', '2026-12-31', 'activo', NOW(), NOW()),
('GYM-006', 'Carmen', 'Ruiz', 'carmen@email.com', '555-2006', 'F', 2, '2026-06-01', '2026-06-30', 'activo', NOW(), NOW()),
('GYM-007', 'Diego', 'López', 'diego@email.com', '555-2007', 'M', 1, '2026-05-01', '2026-05-31', 'vencido', NOW(), NOW()),
('GYM-008', 'Valeria', 'Castro', 'valeria@email.com', '555-2008', 'F', 3, '2026-06-01', '2026-06-30', 'activo', NOW(), NOW());

-- Clases
INSERT INTO `gym_classes` (`name`, `description`, `trainer_id`, `capacity`, `duration_minutes`, `schedule_day`, `schedule_time`, `room`, `color`, `status`, `created_at`, `updated_at`) VALUES
('Musculación Avanzada', 'Entrenamiento de fuerza y hipertrofia', 1, 15, 60, 'lunes', '07:00:00', 'Sala Pesas', '#7c3aed', 1, NOW(), NOW()),
('Yoga Matutino', 'Yoga para comenzar el día con energía', 2, 20, 60, 'martes', '08:00:00', 'Sala Yoga', '#059669', 1, NOW(), NOW()),
('CrossFit', 'Alta intensidad funcional', 3, 12, 45, 'miercoles', '18:00:00', 'Box CrossFit', '#dc2626', 1, NOW(), NOW()),
('Zumba Fitness', 'Baile y cardio al ritmo de la música', 4, 25, 60, 'jueves', '19:00:00', 'Sala Principal', '#ec4899', 1, NOW(), NOW()),
('Pilates', 'Fortalecimiento del core y flexibilidad', 2, 15, 55, 'viernes', '09:00:00', 'Sala Yoga', '#0891b2', 1, NOW(), NOW()),
('Spinning', 'Ciclismo indoor de alta intensidad', 3, 20, 45, 'sabado', '08:00:00', 'Sala Spinning', '#f59e0b', 1, NOW(), NOW());

-- Pagos de ejemplo
INSERT INTO `payments` (`member_id`, `plan_id`, `amount`, `payment_date`, `payment_method`, `period_start`, `period_end`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 49.99, '2026-06-01', 'efectivo', '2026-06-01', '2026-06-30', 'pagado', 1, NOW(), NOW()),
(2, 3, 89.99, '2026-05-15', 'tarjeta', '2026-05-15', '2026-06-14', 'pagado', 1, NOW(), NOW()),
(3, 1, 29.99, '2026-06-01', 'transferencia', '2026-06-01', '2026-06-30', 'pagado', 1, NOW(), NOW()),
(5, 4, 399.99, '2026-01-01', 'tarjeta', '2026-01-01', '2026-12-31', 'pagado', 1, NOW(), NOW()),
(6, 2, 49.99, '2026-06-01', 'efectivo', '2026-06-01', '2026-06-30', 'pagado', 1, NOW(), NOW()),
(8, 3, 89.99, '2026-06-01', 'qr', '2026-06-01', '2026-06-30', 'pagado', 1, NOW(), NOW());

-- Inventario
INSERT INTO `inventory` (`name`, `category`, `description`, `quantity`, `min_quantity`, `unit_price`, `status`, `created_at`, `updated_at`) VALUES
('Mancuernas 5kg', 'Pesas', 'Set de mancuernas ajustables', 20, 5, 25.00, 'disponible', NOW(), NOW()),
('Barras Olímpicas', 'Pesas', 'Barras de 20kg para halterofilia', 10, 3, 150.00, 'disponible', NOW(), NOW()),
('Colchonetas Yoga', 'Accesorios', 'Colchonetas antideslizantes', 25, 8, 18.00, 'disponible', NOW(), NOW()),
('Bicicletas Spinning', 'Cardio', 'Bicicletas de ciclismo indoor', 20, 5, 450.00, 'disponible', NOW(), NOW()),
('Elípticas', 'Cardio', 'Máquinas elípticas electrónicas', 8, 2, 800.00, 'disponible', NOW(), NOW()),
('Cintas de correr', 'Cardio', 'Cintas eléctricas con inclinación', 6, 2, 1200.00, 'mantenimiento', NOW(), NOW()),
('Guantes de box', 'Artes Marciales', 'Guantes para sparring', 3, 5, 35.00, 'agotado', NOW(), NOW()),
('Cuerdas de saltar', 'Cardio', 'Cuerdas de velocidad', 15, 5, 8.00, 'disponible', NOW(), NOW());

-- Asistencia de ejemplo
INSERT INTO `attendance` (`member_id`, `class_id`, `check_in`, `check_out`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-05 07:05:00', '2026-06-05 08:10:00', NOW(), NOW()),
(2, 2, '2026-06-04 08:00:00', '2026-06-04 09:05:00', NOW(), NOW()),
(3, NULL, '2026-06-05 09:30:00', '2026-06-05 10:45:00', NOW(), NOW()),
(5, 3, '2026-06-04 18:00:00', '2026-06-04 18:50:00', NOW(), NOW()),
(6, 4, '2026-06-03 19:00:00', '2026-06-03 20:05:00', NOW(), NOW()),
(8, 5, '2026-06-02 09:00:00', '2026-06-02 09:55:00', NOW(), NOW());

-- Configuración inicial del gimnasio
INSERT INTO `settings` (`key`, `value`, `group`, `created_at`, `updated_at`) VALUES
('gym_name', 'GymSaaS Pro', 'general', NOW(), NOW()),
('gym_address', 'Av. Fitness 123, Ciudad', 'general', NOW(), NOW()),
('gym_phone', '555-0100', 'general', NOW(), NOW()),
('gym_email', 'info@gymsaas.com', 'general', NOW(), NOW()),
('gym_opening', '06:00', 'schedule', NOW(), NOW()),
('gym_closing', '22:00', 'schedule', NOW(), NOW()),
('currency', 'USD', 'billing', NOW(), NOW()),
('currency_symbol', '$', 'billing', NOW(), NOW()),
('logo', '', 'branding', NOW(), NOW()),
('primary_color', '#7c3aed', 'branding', NOW(), NOW());
