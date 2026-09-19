-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para saas_gimnasio
CREATE DATABASE IF NOT EXISTS `saas_gimnasio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `saas_gimnasio`;

-- Volcando estructura para tabla saas_gimnasio.attendance
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `member_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `gym_classes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.attendance: ~95 rows (aproximadamente)
DELETE FROM `attendance`;
INSERT INTO `attendance` (`id`, `gymnasium_id`, `member_id`, `class_id`, `check_in`, `check_out`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, '2026-01-05 07:00:00', '2026-01-05 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(2, 1, 2, 2, '2026-01-07 08:00:00', '2026-01-07 09:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(3, 1, 3, NULL, '2026-01-08 09:00:00', '2026-01-08 10:15:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(4, 1, 5, 3, '2026-01-08 18:00:00', '2026-01-08 18:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(5, 1, 6, 4, '2026-01-09 19:00:00', '2026-01-09 20:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(6, 1, 8, 5, '2026-01-10 09:00:00', '2026-01-10 09:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(7, 1, 4, NULL, '2026-01-12 08:00:00', '2026-01-12 09:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(8, 1, 7, 1, '2026-01-13 07:05:00', '2026-01-13 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(9, 1, 9, 2, '2026-01-14 08:00:00', '2026-01-14 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(10, 1, 10, NULL, '2026-01-15 10:00:00', '2026-01-15 11:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(11, 1, 1, 6, '2026-01-17 08:00:00', '2026-01-17 08:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(12, 1, 2, NULL, '2026-01-19 17:00:00', '2026-01-19 18:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(13, 1, 3, 3, '2026-01-21 18:05:00', '2026-01-21 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(14, 1, 1, 1, '2026-02-02 07:00:00', '2026-02-02 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(15, 1, 2, 2, '2026-02-03 08:00:00', '2026-02-03 09:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(16, 1, 4, NULL, '2026-02-04 09:00:00', '2026-02-04 10:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(17, 1, 5, 3, '2026-02-05 18:00:00', '2026-02-05 18:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(18, 1, 6, 4, '2026-02-06 19:00:00', '2026-02-06 20:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(19, 1, 7, NULL, '2026-02-07 10:00:00', '2026-02-07 11:15:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(20, 1, 8, 5, '2026-02-07 09:00:00', '2026-02-07 09:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(21, 1, 12, NULL, '2026-02-10 08:30:00', '2026-02-10 10:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(22, 1, 13, 2, '2026-02-11 08:00:00', '2026-02-11 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(23, 1, 3, 6, '2026-02-14 08:00:00', '2026-02-14 08:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(24, 1, 14, 3, '2026-02-17 18:00:00', '2026-02-17 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(25, 1, 15, NULL, '2026-02-19 09:00:00', '2026-02-19 10:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(26, 1, 1, 4, '2026-02-20 19:00:00', '2026-02-20 20:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(27, 1, 2, NULL, '2026-02-24 07:30:00', '2026-02-24 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(28, 1, 1, 1, '2026-03-02 07:00:00', '2026-03-02 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(29, 1, 3, 2, '2026-03-03 08:00:00', '2026-03-03 09:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(30, 1, 5, 3, '2026-03-04 18:00:00', '2026-03-04 18:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(31, 1, 6, 4, '2026-03-05 19:00:00', '2026-03-05 20:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(32, 1, 8, 5, '2026-03-07 09:00:00', '2026-03-07 09:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(33, 1, 16, NULL, '2026-03-09 07:00:00', '2026-03-09 08:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(34, 1, 17, 2, '2026-03-10 08:00:00', '2026-03-10 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(35, 1, 18, NULL, '2026-03-12 10:00:00', '2026-03-12 11:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(36, 1, 19, 3, '2026-03-12 18:00:00', '2026-03-12 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(37, 1, 20, 4, '2026-03-13 19:00:00', '2026-03-13 20:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(38, 1, 2, 6, '2026-03-14 08:00:00', '2026-03-14 08:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(39, 1, 4, NULL, '2026-03-16 09:00:00', '2026-03-16 10:15:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(40, 1, 7, 1, '2026-03-17 07:05:00', '2026-03-17 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(41, 1, 13, NULL, '2026-03-19 07:30:00', '2026-03-19 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(42, 1, 1, 5, '2026-03-21 09:00:00', '2026-03-21 09:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(43, 1, 1, 1, '2026-04-01 07:00:00', '2026-04-01 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(44, 1, 2, 2, '2026-04-01 08:00:00', '2026-04-01 09:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(45, 1, 3, NULL, '2026-04-02 09:30:00', '2026-04-02 11:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(46, 1, 5, 3, '2026-04-03 18:00:00', '2026-04-03 18:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(47, 1, 6, 4, '2026-04-03 19:00:00', '2026-04-03 20:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(48, 1, 21, NULL, '2026-04-07 07:00:00', '2026-04-07 08:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(49, 1, 22, 2, '2026-04-08 08:00:00', '2026-04-08 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(50, 1, 23, NULL, '2026-04-09 10:00:00', '2026-04-09 11:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(51, 1, 24, 3, '2026-04-10 18:00:00', '2026-04-10 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(52, 1, 25, 4, '2026-04-10 19:00:00', '2026-04-10 20:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(53, 1, 7, 6, '2026-04-12 08:00:00', '2026-04-12 08:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(54, 1, 8, 1, '2026-04-14 07:05:00', '2026-04-14 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(55, 1, 4, NULL, '2026-04-15 09:00:00', '2026-04-15 10:15:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(56, 1, 16, 5, '2026-04-18 09:00:00', '2026-04-18 09:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(57, 1, 17, NULL, '2026-04-21 08:00:00', '2026-04-21 09:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(58, 1, 1, 3, '2026-04-24 18:00:00', '2026-04-24 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(59, 1, 1, 1, '2026-05-05 07:00:00', '2026-05-05 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(60, 1, 2, 2, '2026-05-06 08:00:00', '2026-05-06 09:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(61, 1, 3, NULL, '2026-05-07 09:30:00', '2026-05-07 10:45:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(62, 1, 5, 3, '2026-05-07 18:00:00', '2026-05-07 18:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(63, 1, 6, 4, '2026-05-08 19:00:00', '2026-05-08 20:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(64, 1, 26, NULL, '2026-05-11 07:00:00', '2026-05-11 08:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(65, 1, 27, 2, '2026-05-12 08:00:00', '2026-05-12 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(66, 1, 28, NULL, '2026-05-13 10:00:00', '2026-05-13 11:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(67, 1, 29, 3, '2026-05-14 18:00:00', '2026-05-14 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(68, 1, 30, 4, '2026-05-15 19:00:00', '2026-05-15 20:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(69, 1, 31, 6, '2026-05-17 08:00:00', '2026-05-17 08:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(70, 1, 7, 1, '2026-05-19 07:05:00', '2026-05-19 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(71, 1, 8, 5, '2026-05-23 09:00:00', '2026-05-23 09:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(72, 1, 4, NULL, '2026-05-26 09:00:00', '2026-05-26 10:15:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(73, 1, 22, 3, '2026-05-28 18:00:00', '2026-05-28 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(74, 1, 24, NULL, '2026-05-29 07:30:00', '2026-05-29 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(75, 1, 1, 4, '2026-05-29 19:00:00', '2026-05-29 20:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(76, 1, 1, 1, '2026-06-02 07:05:00', '2026-06-02 08:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(77, 1, 3, NULL, '2026-06-02 09:30:00', '2026-06-02 10:45:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(78, 1, 5, NULL, '2026-06-02 18:00:00', '2026-06-02 19:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(79, 1, 32, 1, '2026-06-02 07:00:00', '2026-06-02 08:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(80, 1, 33, NULL, '2026-06-02 10:00:00', '2026-06-02 11:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(81, 1, 2, 2, '2026-06-03 08:00:00', '2026-06-03 09:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(82, 1, 6, 2, '2026-06-03 08:05:00', '2026-06-03 09:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(83, 1, 34, NULL, '2026-06-03 17:00:00', '2026-06-03 18:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(84, 1, 35, 2, '2026-06-03 08:00:00', '2026-06-03 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(85, 1, 36, NULL, '2026-06-03 09:00:00', '2026-06-03 10:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(86, 1, 5, 3, '2026-06-04 18:00:00', '2026-06-04 18:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(87, 1, 8, 3, '2026-06-04 18:00:00', '2026-06-04 18:55:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(88, 1, 37, NULL, '2026-06-04 10:00:00', '2026-06-04 11:30:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(89, 1, 38, 3, '2026-06-04 18:05:00', '2026-06-04 18:50:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(90, 1, 4, NULL, '2026-06-04 07:30:00', '2026-06-04 09:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(91, 1, 6, 4, '2026-06-05 19:00:00', '2026-06-05 20:05:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(92, 1, 32, 4, '2026-06-05 19:05:00', '2026-06-05 20:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(93, 1, 33, NULL, '2026-06-05 08:00:00', '2026-06-05 09:15:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(94, 1, 3, NULL, '2026-06-05 09:30:00', '2026-06-05 11:00:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57'),
	(95, 1, 34, 4, '2026-06-05 19:00:00', '2026-06-05 20:10:00', NULL, '2026-06-06 01:32:57', '2026-06-06 01:32:57');

-- Volcando estructura para tabla saas_gimnasio.billing_settings
CREATE TABLE IF NOT EXISTS `billing_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `ruc` varchar(11) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `nombre_comercial` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ubigeo` varchar(6) DEFAULT NULL,
  `urbanizacion` varchar(120) DEFAULT NULL,
  `distrito` varchar(120) DEFAULT NULL,
  `provincia` varchar(120) DEFAULT NULL,
  `departamento` varchar(120) DEFAULT NULL,
  `sol_user` varchar(60) DEFAULT NULL,
  `sol_pass` varchar(255) DEFAULT NULL,
  `cert_path` varchar(255) DEFAULT NULL,
  `cert_pass` varchar(255) DEFAULT NULL,
  `environment` enum('beta','produccion') NOT NULL DEFAULT 'beta',
  `igv_percent` decimal(5,2) NOT NULL DEFAULT '18.00',
  `moneda` varchar(3) NOT NULL DEFAULT 'PEN',
  `driver` varchar(20) NOT NULL DEFAULT 'greenter',
  `auto_emit` tinyint(1) NOT NULL DEFAULT '0',
  `serie_factura` varchar(4) NOT NULL DEFAULT 'F001',
  `serie_boleta` varchar(4) NOT NULL DEFAULT 'B001',
  `serie_nc` varchar(4) NOT NULL DEFAULT 'FC01',
  `serie_nd` varchar(4) NOT NULL DEFAULT 'FD01',
  `correlativo_factura` int NOT NULL DEFAULT '0',
  `correlativo_boleta` int NOT NULL DEFAULT '0',
  `correlativo_nc` int NOT NULL DEFAULT '0',
  `correlativo_nd` int NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_settings_gym` (`gymnasium_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.billing_settings: ~0 rows (aproximadamente)
DELETE FROM `billing_settings`;

-- Volcando estructura para tabla saas_gimnasio.class_enrollments
CREATE TABLE IF NOT EXISTS `class_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `class_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('activo','cancelado') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_member` (`class_id`,`member_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `class_enrollments_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `gym_classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_enrollments_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.class_enrollments: ~25 rows (aproximadamente)
DELETE FROM `class_enrollments`;
INSERT INTO `class_enrollments` (`id`, `gymnasium_id`, `class_id`, `member_id`, `enrolled_at`, `status`) VALUES
	(1, 1, 1, 1, '2026-01-03 14:00:00', 'activo'),
	(2, 1, 1, 5, '2026-01-03 14:00:00', 'activo'),
	(3, 1, 1, 7, '2026-01-05 14:30:00', 'activo'),
	(4, 1, 1, 8, '2026-02-01 14:00:00', 'activo'),
	(5, 1, 1, 32, '2026-06-01 14:00:00', 'activo'),
	(6, 1, 1, 33, '2026-06-01 14:30:00', 'activo'),
	(7, 1, 2, 2, '2026-01-05 15:00:00', 'activo'),
	(8, 1, 2, 6, '2026-01-05 15:00:00', 'activo'),
	(9, 1, 2, 13, '2026-02-10 15:00:00', 'activo'),
	(10, 1, 2, 35, '2026-06-03 14:00:00', 'activo'),
	(11, 1, 2, 36, '2026-06-03 14:30:00', 'activo'),
	(12, 1, 3, 5, '2026-01-08 23:00:00', 'activo'),
	(13, 1, 3, 8, '2026-02-05 23:00:00', 'activo'),
	(14, 1, 3, 19, '2026-03-22 23:00:00', 'activo'),
	(15, 1, 3, 38, '2026-06-04 23:00:00', 'activo'),
	(16, 1, 4, 6, '2026-01-10 00:00:00', 'activo'),
	(17, 1, 4, 20, '2026-03-29 00:00:00', 'activo'),
	(18, 1, 4, 32, '2026-06-05 15:00:00', 'activo'),
	(19, 1, 4, 34, '2026-06-05 15:30:00', 'activo'),
	(20, 1, 5, 2, '2026-02-05 14:00:00', 'activo'),
	(21, 1, 5, 8, '2026-02-05 14:00:00', 'activo'),
	(22, 1, 5, 16, '2026-04-18 14:00:00', 'activo'),
	(23, 1, 6, 3, '2026-02-14 13:00:00', 'activo'),
	(24, 1, 6, 7, '2026-04-12 13:00:00', 'activo'),
	(25, 1, 6, 31, '2026-05-17 13:00:00', 'activo');

-- Volcando estructura para tabla saas_gimnasio.coupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `saas_plan_id` bigint unsigned DEFAULT NULL COMMENT 'NULL = aplica a cualquier plan',
  `max_uses` int DEFAULT NULL COMMENT 'NULL = ilimitado',
  `used_count` int NOT NULL DEFAULT '0',
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.coupons: ~0 rows (aproximadamente)
DELETE FROM `coupons`;
INSERT INTO `coupons` (`id`, `code`, `description`, `type`, `value`, `saas_plan_id`, `max_uses`, `used_count`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'BIENVENIDA20', '20% de descuento de bienvenida', 'percent', 20.00, NULL, 100, 0, NULL, 1, '2026-06-06 05:51:26', '2026-06-06 05:51:26');

-- Volcando estructura para tabla saas_gimnasio.electronic_documents
CREATE TABLE IF NOT EXISTS `electronic_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `member_id` bigint unsigned DEFAULT NULL,
  `tipo_doc` varchar(2) NOT NULL,
  `serie` varchar(4) NOT NULL,
  `correlativo` varchar(8) NOT NULL,
  `fecha_emision` datetime NOT NULL,
  `moneda` varchar(3) NOT NULL DEFAULT 'PEN',
  `cliente_tipo_doc` varchar(1) NOT NULL DEFAULT '1',
  `cliente_num_doc` varchar(15) DEFAULT NULL,
  `cliente_razon_social` varchar(255) DEFAULT NULL,
  `cliente_direccion` varchar(255) DEFAULT NULL,
  `items` json DEFAULT NULL,
  `mto_oper_gravadas` decimal(12,2) NOT NULL DEFAULT '0.00',
  `mto_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `doc_afectado_tipo` varchar(2) DEFAULT NULL,
  `doc_afectado_serie_num` varchar(20) DEFAULT NULL,
  `cod_motivo` varchar(2) DEFAULT NULL,
  `des_motivo` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','enviado','aceptado','rechazado','anulado','error') NOT NULL DEFAULT 'pendiente',
  `sunat_code` varchar(10) DEFAULT NULL,
  `sunat_description` varchar(255) DEFAULT NULL,
  `hash` varchar(100) DEFAULT NULL,
  `xml_path` varchar(255) DEFAULT NULL,
  `cdr_path` varchar(255) DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ed_serie_corr` (`gymnasium_id`,`tipo_doc`,`serie`,`correlativo`),
  KEY `ed_gym` (`gymnasium_id`),
  KEY `ed_payment` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.electronic_documents: ~0 rows (aproximadamente)
DELETE FROM `electronic_documents`;

-- Volcando estructura para tabla saas_gimnasio.gymnasiums
CREATE TABLE IF NOT EXISTS `gymnasiums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(60) DEFAULT 'PE',
  `logo` varchar(255) DEFAULT NULL,
  `primary_color` varchar(20) DEFAULT '#7c3aed',
  `saas_plan_id` bigint unsigned DEFAULT NULL,
  `owner_id` bigint unsigned DEFAULT NULL,
  `status` enum('active','trial','suspended','cancelled') NOT NULL DEFAULT 'trial',
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `max_members` int NOT NULL DEFAULT '100',
  `settings` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.gymnasiums: ~1 rows (aproximadamente)
DELETE FROM `gymnasiums`;
INSERT INTO `gymnasiums` (`id`, `name`, `slug`, `email`, `phone`, `address`, `city`, `country`, `logo`, `primary_color`, `saas_plan_id`, `owner_id`, `status`, `trial_ends_at`, `max_members`, `settings`, `created_at`, `updated_at`) VALUES
	(1, 'GymSaaS', 'demo', 'admin@gymsaas.com', '555-0100', 'Av. Fitness 123', 'Lima', 'PE', NULL, '#7c3aed', 2, 1, 'active', NULL, 200, NULL, '2026-06-06 02:15:09', '2026-07-13 19:06:27');

-- Volcando estructura para tabla saas_gimnasio.gym_classes
CREATE TABLE IF NOT EXISTS `gym_classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `trainer_id` bigint unsigned DEFAULT NULL,
  `capacity` int NOT NULL DEFAULT '20',
  `duration_minutes` int NOT NULL DEFAULT '60',
  `schedule_day` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo') DEFAULT NULL,
  `schedule_time` time DEFAULT NULL,
  `room` varchar(100) DEFAULT NULL,
  `color` varchar(10) DEFAULT '#7c3aed',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trainer_id` (`trainer_id`),
  CONSTRAINT `gym_classes_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.gym_classes: ~6 rows (aproximadamente)
DELETE FROM `gym_classes`;
INSERT INTO `gym_classes` (`id`, `gymnasium_id`, `name`, `description`, `trainer_id`, `capacity`, `duration_minutes`, `schedule_day`, `schedule_time`, `room`, `color`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Musculación Avanzada', 'Entrenamiento de fuerza y hipertrofia', 1, 15, 60, 'lunes', '07:00:00', 'Sala Pesas', '#7c3aed', 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(2, 1, 'Yoga Matutino', 'Yoga para comenzar el día con energía', 2, 20, 60, 'martes', '08:00:00', 'Sala Yoga', '#059669', 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(3, 1, 'CrossFit', 'Alta intensidad funcional', 3, 12, 45, 'miercoles', '18:00:00', 'Box CrossFit', '#dc2626', 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(4, 1, 'Zumba Fitness', 'Baile y cardio al ritmo de la música', 4, 25, 60, 'jueves', '19:00:00', 'Sala Principal', '#ec4899', 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(5, 1, 'Pilates', 'Fortalecimiento del core y flexibilidad', 2, 15, 55, 'viernes', '09:00:00', 'Sala Yoga', '#0891b2', 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(6, 1, 'Spinning', 'Ciclismo indoor de alta intensidad', 3, 20, 45, 'sabado', '08:00:00', 'Sala Spinning', '#f59e0b', 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38');

-- Volcando estructura para tabla saas_gimnasio.gym_notifications
CREATE TABLE IF NOT EXISTS `gym_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'NULL = para todo el gimnasio',
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `title` varchar(255) NOT NULL,
  `body` text,
  `icon` varchar(40) DEFAULT 'fa-bell',
  `color` varchar(20) DEFAULT '#7c3aed',
  `url` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notif_gym` (`gymnasium_id`),
  KEY `idx_notif_user` (`user_id`),
  KEY `idx_notif_read` (`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.gym_notifications: ~0 rows (aproximadamente)
DELETE FROM `gym_notifications`;

-- Volcando estructura para tabla saas_gimnasio.gym_subscriptions
CREATE TABLE IF NOT EXISTS `gym_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned NOT NULL,
  `saas_plan_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `billing_cycle` enum('monthly','yearly') NOT NULL DEFAULT 'monthly',
  `starts_at` date NOT NULL,
  `ends_at` date NOT NULL,
  `status` enum('active','expired','cancelled','pending') NOT NULL DEFAULT 'active',
  `payment_ref` varchar(100) DEFAULT NULL,
  `coupon_code` varchar(40) DEFAULT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.gym_subscriptions: ~0 rows (aproximadamente)
DELETE FROM `gym_subscriptions`;
INSERT INTO `gym_subscriptions` (`id`, `gymnasium_id`, `saas_plan_id`, `amount`, `billing_cycle`, `starts_at`, `ends_at`, `status`, `payment_ref`, `coupon_code`, `discount`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 29.00, 'monthly', '2026-06-18', '2026-07-18', 'active', 'SIM-6A3496E07984B', NULL, 0.00, 'Cambio de plan desde el panel del gimnasio.', '2026-06-19 01:09:52', '2026-06-19 01:09:52'),
	(2, 1, 2, 59.00, 'monthly', '2026-07-13', '2026-08-13', 'active', 'SIM-6A55280E4327E', NULL, 0.00, 'Cambio de plan desde el panel del gimnasio.', '2026-07-13 18:01:50', '2026-07-13 18:01:50');

-- Volcando estructura para tabla saas_gimnasio.inventory
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text,
  `quantity` int NOT NULL DEFAULT '0',
  `min_quantity` int NOT NULL DEFAULT '5',
  `unit_price` decimal(10,2) DEFAULT '0.00',
  `supplier` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `status` enum('disponible','agotado','mantenimiento') NOT NULL DEFAULT 'disponible',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.inventory: ~8 rows (aproximadamente)
DELETE FROM `inventory`;
INSERT INTO `inventory` (`id`, `gymnasium_id`, `name`, `category`, `description`, `quantity`, `min_quantity`, `unit_price`, `supplier`, `purchase_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Mancuernas 5kg', 'Pesas', 'Set de mancuernas ajustables', 20, 5, 25.00, NULL, NULL, 'disponible', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(2, 1, 'Barras Olímpicas', 'Pesas', 'Barras de 20kg para halterofilia', 10, 3, 150.00, NULL, NULL, 'disponible', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(3, 1, 'Colchonetas Yoga', 'Accesorios', 'Colchonetas antideslizantes', 25, 8, 18.00, NULL, NULL, 'disponible', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(4, 1, 'Bicicletas Spinning', 'Cardio', 'Bicicletas de ciclismo indoor', 20, 5, 450.00, NULL, NULL, 'disponible', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(5, 1, 'Elípticas', 'Cardio', 'Máquinas elípticas electrónicas', 8, 2, 800.00, NULL, NULL, 'disponible', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(6, 1, 'Cintas de correr', 'Cardio', 'Cintas eléctricas con inclinación', 6, 2, 1200.00, NULL, NULL, 'mantenimiento', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(7, 1, 'Guantes de box', 'Artes Marciales', 'Guantes para sparring', 3, 5, 35.00, NULL, NULL, 'agotado', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(8, 1, 'Cuerdas de saltar', 'Cardio', 'Cuerdas de velocidad', 15, 5, 8.00, NULL, NULL, 'disponible', NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38');

-- Volcando estructura para tabla saas_gimnasio.members
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('M','F','otro') DEFAULT NULL,
  `address` text,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `plan_id` bigint unsigned DEFAULT NULL,
  `membership_start` date DEFAULT NULL,
  `membership_end` date DEFAULT NULL,
  `status` enum('activo','inactivo','suspendido','vencido') NOT NULL DEFAULT 'activo',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `members_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.members: ~38 rows (aproximadamente)
DELETE FROM `members`;
INSERT INTO `members` (`id`, `gymnasium_id`, `user_id`, `code`, `first_name`, `last_name`, `email`, `password`, `phone`, `birth_date`, `gender`, `address`, `emergency_contact`, `emergency_phone`, `photo`, `plan_id`, `membership_start`, `membership_end`, `status`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'GYM-001', 'Juan', 'Pérez', 'juan@email.com', NULL, '555-2001', NULL, 'M', NULL, NULL, NULL, NULL, 2, '2026-06-01', '2026-06-30', 'activo', NULL, '2026-01-05 14:00:00', '2026-01-05 14:00:00'),
	(2, 1, NULL, 'GYM-002', 'Laura', 'Martínez', 'laura@email.com', NULL, '555-2002', NULL, 'F', NULL, NULL, NULL, NULL, 3, '2026-05-15', '2026-06-14', 'activo', NULL, '2026-01-12 15:00:00', '2026-01-12 15:00:00'),
	(3, 1, NULL, 'GYM-003', 'Pedro', 'Sánchez', 'pedro@email.com', NULL, '555-2003', NULL, 'M', NULL, NULL, NULL, NULL, 1, '2026-06-01', '2026-06-30', 'activo', NULL, '2026-02-03 13:30:00', '2026-02-03 13:30:00'),
	(4, 1, NULL, 'GYM-004', 'María', 'González', 'maria@email.com', NULL, '555-2004', NULL, 'F', NULL, NULL, NULL, NULL, 2, '2026-04-01', '2026-04-30', 'vencido', NULL, '2026-02-15 16:00:00', '2026-02-15 16:00:00'),
	(5, 1, NULL, 'GYM-005', 'Roberto', 'Flores', 'roberto@email.com', NULL, '555-2005', NULL, 'M', NULL, NULL, NULL, NULL, 4, '2026-01-01', '2026-12-31', 'activo', NULL, '2026-03-08 14:00:00', '2026-03-08 14:00:00'),
	(6, 1, NULL, 'GYM-006', 'Carmen', 'Ruiz', 'carmen@email.com', NULL, '555-2006', NULL, 'F', NULL, NULL, NULL, NULL, 2, '2026-06-01', '2026-06-30', 'activo', NULL, '2026-03-20 15:30:00', '2026-03-20 15:30:00'),
	(7, 1, NULL, 'GYM-007', 'Diego', 'López', 'diego@email.com', NULL, '555-2007', NULL, 'M', NULL, NULL, NULL, NULL, 1, '2026-05-01', '2026-05-31', 'vencido', NULL, '2026-04-10 14:00:00', '2026-04-10 14:00:00'),
	(8, 1, NULL, 'GYM-008', 'Valeria', 'Castro', 'valeria@email.com', NULL, '555-2008', NULL, 'F', NULL, NULL, NULL, NULL, 3, '2026-06-01', '2026-06-30', 'activo', NULL, '2026-04-22 13:30:00', '2026-04-22 13:30:00'),
	(9, 1, NULL, 'GYM-009', 'Andrés', 'Vargas', 'andres.vargas@email.com', NULL, '555-3001', '1990-03-14', 'M', NULL, NULL, NULL, NULL, 2, '2026-01-08', '2026-02-07', 'vencido', NULL, '2026-01-08 14:00:00', '2026-01-08 14:00:00'),
	(10, 1, NULL, 'GYM-010', 'Daniela', 'Paredes', 'daniela.p@email.com', NULL, '555-3002', '1995-07-22', 'F', NULL, NULL, NULL, NULL, 3, '2026-01-15', '2026-02-14', 'vencido', NULL, '2026-01-15 15:00:00', '2026-01-15 15:00:00'),
	(11, 1, NULL, 'GYM-011', 'Miguel', 'Torres', 'miguel.t@email.com', NULL, '555-3003', '1988-11-05', 'M', NULL, NULL, NULL, NULL, 1, '2026-01-20', '2026-02-19', 'vencido', NULL, '2026-01-20 13:00:00', '2026-01-20 13:00:00'),
	(12, 1, NULL, 'GYM-012', 'Gabriela', 'Mendoza', 'gaby.m@email.com', NULL, '555-3004', '1993-04-18', 'F', NULL, NULL, NULL, NULL, 4, '2026-02-03', '2027-02-02', 'activo', NULL, '2026-02-03 16:00:00', '2026-02-03 16:00:00'),
	(13, 1, NULL, 'GYM-013', 'Carlos', 'Ramos', 'carlos.ramos@email.com', NULL, '555-3005', '1985-09-30', 'M', NULL, NULL, NULL, NULL, 2, '2026-02-10', '2026-03-11', 'vencido', NULL, '2026-02-10 14:30:00', '2026-02-10 14:30:00'),
	(14, 1, NULL, 'GYM-014', 'Isabella', 'Cruz', 'isa.cruz@email.com', NULL, '555-3006', '1998-01-25', 'F', NULL, NULL, NULL, NULL, 3, '2026-02-18', '2026-03-19', 'vencido', NULL, '2026-02-18 15:00:00', '2026-02-18 15:00:00'),
	(15, 1, NULL, 'GYM-015', 'Fernando', 'Gutiérrez', 'fer.gutierrez@email.com', NULL, '555-3007', '1992-06-12', 'M', NULL, NULL, NULL, NULL, 2, '2026-02-25', '2026-03-26', 'vencido', NULL, '2026-02-25 13:30:00', '2026-02-25 13:30:00'),
	(16, 1, NULL, 'GYM-016', 'Camila', 'Herrera', 'camila.h@email.com', NULL, '555-3008', '1997-12-03', 'F', NULL, NULL, NULL, NULL, 3, '2026-03-01', '2026-03-31', 'vencido', NULL, '2026-03-01 14:00:00', '2026-03-01 14:00:00'),
	(17, 1, NULL, 'GYM-017', 'Ricardo', 'Morales', 'ricardo.m@email.com', NULL, '555-3009', '1991-08-17', 'M', NULL, NULL, NULL, NULL, 2, '2026-03-10', '2026-04-09', 'vencido', NULL, '2026-03-10 16:30:00', '2026-03-10 16:30:00'),
	(18, 1, NULL, 'GYM-018', 'Valentina', 'Jiménez', 'vale.jimenez@email.com', NULL, '555-3010', '1996-02-28', 'F', NULL, NULL, NULL, NULL, 4, '2026-03-15', '2027-03-14', 'activo', NULL, '2026-03-15 15:00:00', '2026-03-15 15:00:00'),
	(19, 1, NULL, 'GYM-019', 'Sebastián', 'Ortega', 'seba.ortega@email.com', NULL, '555-3011', '1989-10-08', 'M', NULL, NULL, NULL, NULL, 3, '2026-03-22', '2026-04-21', 'vencido', NULL, '2026-03-22 13:00:00', '2026-03-22 13:00:00'),
	(20, 1, NULL, 'GYM-020', 'Luciana', 'Reyes', 'luci.reyes@email.com', NULL, '555-3012', '1994-05-14', 'F', NULL, NULL, NULL, NULL, 2, '2026-03-28', '2026-04-27', 'vencido', NULL, '2026-03-28 14:30:00', '2026-03-28 14:30:00'),
	(21, 1, NULL, 'GYM-021', 'Mateo', 'Silva', 'mateo.silva@email.com', NULL, '555-3013', '1987-07-21', 'M', NULL, NULL, NULL, NULL, 3, '2026-04-02', '2026-05-01', 'vencido', NULL, '2026-04-02 15:00:00', '2026-04-02 15:00:00'),
	(22, 1, NULL, 'GYM-022', 'Sofía', 'Delgado', 'sofia.delgado@email.com', NULL, '555-3014', '1999-11-30', 'F', NULL, NULL, NULL, NULL, 2, '2026-04-08', '2026-05-07', 'vencido', NULL, '2026-04-08 13:30:00', '2026-04-08 13:30:00'),
	(23, 1, NULL, 'GYM-023', 'Pablo', 'Castillo', 'pablo.c@email.com', NULL, '555-3015', '1986-03-17', 'M', NULL, NULL, NULL, NULL, 1, '2026-04-15', '2026-05-14', 'vencido', NULL, '2026-04-15 14:00:00', '2026-04-15 14:00:00'),
	(24, 1, NULL, 'GYM-024', 'Natalia', 'Espinoza', 'nati.e@email.com', NULL, '555-3016', '2000-08-09', 'F', NULL, NULL, NULL, NULL, 3, '2026-04-22', '2026-05-21', 'vencido', NULL, '2026-04-22 16:00:00', '2026-04-22 16:00:00'),
	(25, 1, NULL, 'GYM-025', 'Javier', 'Núñez', 'javier.n@email.com', NULL, '555-3017', '1993-12-25', 'M', NULL, NULL, NULL, NULL, 2, '2026-04-28', '2026-05-27', 'vencido', NULL, '2026-04-28 15:30:00', '2026-04-28 15:30:00'),
	(26, 1, NULL, 'GYM-026', 'Mariana', 'Vega', 'mari.vega@email.com', NULL, '555-3018', '1997-06-14', 'F', NULL, NULL, NULL, NULL, 2, '2026-05-05', '2026-06-04', 'vencido', NULL, '2026-05-05 14:00:00', '2026-05-05 14:00:00'),
	(27, 1, NULL, 'GYM-027', 'Emilio', 'Salazar', 'emilio.s@email.com', NULL, '555-3019', '1984-02-20', 'M', NULL, NULL, NULL, NULL, 3, '2026-05-10', '2026-06-09', 'activo', NULL, '2026-05-10 13:30:00', '2026-05-10 13:30:00'),
	(28, 1, NULL, 'GYM-028', 'Renata', 'Campos', 'renata.c@email.com', NULL, '555-3020', '1991-09-11', 'F', NULL, NULL, NULL, NULL, 4, '2026-05-15', '2027-05-14', 'activo', NULL, '2026-05-15 15:00:00', '2026-05-15 15:00:00'),
	(29, 1, NULL, 'GYM-029', 'Alejandro', 'Ponce', 'alejo.p@email.com', NULL, '555-3021', '1988-04-30', 'M', NULL, NULL, NULL, NULL, 2, '2026-05-20', '2026-06-19', 'activo', NULL, '2026-05-20 16:00:00', '2026-05-20 16:00:00'),
	(30, 1, NULL, 'GYM-030', 'Patricia', 'Luna', 'patri.l@email.com', NULL, '555-3022', '1995-01-07', 'F', NULL, NULL, NULL, NULL, 3, '2026-05-25', '2026-06-24', 'activo', NULL, '2026-05-25 14:30:00', '2026-05-25 14:30:00'),
	(31, 1, NULL, 'GYM-031', 'Tomás', 'Fuentes', 'tomas.f@email.com', NULL, '555-3023', '1990-11-18', 'M', NULL, NULL, NULL, NULL, 1, '2026-05-28', '2026-06-27', 'activo', NULL, '2026-05-28 13:00:00', '2026-05-28 13:00:00'),
	(32, 1, NULL, 'GYM-032', 'Valeria', 'Castro', 'vale.castro@email.com', NULL, '555-3024', '1996-07-03', 'F', NULL, NULL, NULL, NULL, 3, '2026-06-01', '2026-06-30', 'activo', NULL, '2026-06-01 14:00:00', '2026-06-01 14:00:00'),
	(33, 1, NULL, 'GYM-033', 'Diego', 'Rojas', 'diego.rojas@email.com', NULL, '555-3025', '1993-03-22', 'M', NULL, NULL, NULL, NULL, 2, '2026-06-01', '2026-06-30', 'activo', NULL, '2026-06-01 15:00:00', '2026-06-01 15:00:00'),
	(34, 1, NULL, 'GYM-034', 'Camilo', 'Navarro', 'camilo.n@email.com', NULL, '555-3026', '1989-08-15', 'M', NULL, NULL, NULL, NULL, 3, '2026-06-02', '2026-07-01', 'activo', NULL, '2026-06-02 14:00:00', '2026-06-02 14:00:00'),
	(35, 1, NULL, 'GYM-035', 'Laura', 'Medina', 'laura.med@email.com', NULL, '555-3027', '1998-05-20', 'F', NULL, NULL, NULL, NULL, 4, '2026-06-03', '2027-06-02', 'activo', NULL, '2026-06-03 15:30:00', '2026-06-03 15:30:00'),
	(36, 1, NULL, 'GYM-036', 'Nicolás', 'Aguilar', 'nico.a@email.com', NULL, '555-3028', '1994-10-12', 'M', NULL, NULL, NULL, NULL, 2, '2026-06-04', '2026-07-03', 'activo', NULL, '2026-06-04 13:30:00', '2026-06-04 13:30:00'),
	(37, 1, NULL, 'GYM-037', 'Carolina', 'Bermúdez', 'caro.b@email.com', NULL, '555-3029', '1992-01-29', 'F', NULL, NULL, NULL, NULL, 3, '2026-06-05', '2026-07-04', 'activo', NULL, '2026-06-05 14:00:00', '2026-06-05 14:00:00'),
	(38, 1, NULL, 'GYM-038', 'Rodrigo', 'Santana', 'rodri.s@email.com', NULL, '555-3030', '1987-06-18', 'M', NULL, NULL, NULL, NULL, 2, '2026-06-05', '2026-07-04', 'activo', NULL, '2026-06-05 16:00:00', '2026-06-05 16:00:00');

-- Volcando estructura para tabla saas_gimnasio.member_measurements
CREATE TABLE IF NOT EXISTS `member_measurements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `measured_on` date NOT NULL,
  `weight` decimal(6,2) DEFAULT NULL COMMENT 'kg',
  `height` decimal(6,2) DEFAULT NULL COMMENT 'cm',
  `body_fat` decimal(5,2) DEFAULT NULL COMMENT '%',
  `chest` decimal(6,2) DEFAULT NULL,
  `waist` decimal(6,2) DEFAULT NULL,
  `hips` decimal(6,2) DEFAULT NULL,
  `arm` decimal(6,2) DEFAULT NULL,
  `thigh` decimal(6,2) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_meas_gym` (`gymnasium_id`),
  KEY `idx_meas_member` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.member_measurements: ~0 rows (aproximadamente)
DELETE FROM `member_measurements`;

-- Volcando estructura para tabla saas_gimnasio.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `member_id` bigint unsigned NOT NULL,
  `plan_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_date` date NOT NULL,
  `payment_method` enum('efectivo','tarjeta','transferencia','qr') NOT NULL DEFAULT 'efectivo',
  `reference` varchar(100) DEFAULT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `status` enum('pagado','pendiente','cancelado') NOT NULL DEFAULT 'pagado',
  `notes` text,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `plan_id` (`plan_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.payments: ~86 rows (aproximadamente)
DELETE FROM `payments`;
INSERT INTO `payments` (`id`, `gymnasium_id`, `member_id`, `plan_id`, `amount`, `payment_date`, `payment_method`, `reference`, `period_start`, `period_end`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 2, 49.99, '2026-01-03', 'efectivo', NULL, '2026-01-03', '2026-02-02', 'pagado', NULL, 1, '2026-01-03 14:00:00', '2026-01-03 14:00:00'),
	(2, 1, 2, 3, 89.99, '2026-01-05', 'tarjeta', NULL, '2026-01-05', '2026-02-04', 'pagado', NULL, 1, '2026-01-05 15:00:00', '2026-01-05 15:00:00'),
	(3, 1, 3, 1, 29.99, '2026-01-07', 'efectivo', NULL, '2026-01-07', '2026-02-06', 'pagado', NULL, 1, '2026-01-07 14:30:00', '2026-01-07 14:30:00'),
	(4, 1, 5, 4, 399.99, '2026-01-01', 'tarjeta', NULL, '2026-01-01', '2026-12-31', 'pagado', NULL, 1, '2026-01-01 13:00:00', '2026-01-01 13:00:00'),
	(5, 1, 6, 2, 49.99, '2026-01-08', 'efectivo', NULL, '2026-01-08', '2026-02-07', 'pagado', NULL, 1, '2026-01-08 15:30:00', '2026-01-08 15:30:00'),
	(6, 1, 9, 2, 49.99, '2026-01-08', 'qr', NULL, '2026-01-08', '2026-02-07', 'pagado', NULL, 1, '2026-01-08 14:00:00', '2026-01-08 14:00:00'),
	(7, 1, 10, 3, 89.99, '2026-01-15', 'tarjeta', NULL, '2026-01-15', '2026-02-14', 'pagado', NULL, 1, '2026-01-15 15:00:00', '2026-01-15 15:00:00'),
	(8, 1, 11, 1, 29.99, '2026-01-20', 'efectivo', NULL, '2026-01-20', '2026-02-19', 'pagado', NULL, 1, '2026-01-20 13:00:00', '2026-01-20 13:00:00'),
	(9, 1, 4, 2, 49.99, '2026-01-10', 'tarjeta', NULL, '2026-01-10', '2026-02-09', 'pagado', NULL, 1, '2026-01-10 14:00:00', '2026-01-10 14:00:00'),
	(10, 1, 7, 3, 89.99, '2026-01-12', 'transferencia', NULL, '2026-01-12', '2026-02-11', 'pagado', NULL, 1, '2026-01-12 16:00:00', '2026-01-12 16:00:00'),
	(11, 1, 8, 3, 89.99, '2026-01-14', 'tarjeta', NULL, '2026-01-14', '2026-02-13', 'pagado', NULL, 1, '2026-01-14 15:00:00', '2026-01-14 15:00:00'),
	(12, 1, 1, 2, 49.99, '2026-02-03', 'efectivo', NULL, '2026-02-03', '2026-03-04', 'pagado', NULL, 1, '2026-02-03 14:00:00', '2026-02-03 14:00:00'),
	(13, 1, 2, 3, 89.99, '2026-02-05', 'tarjeta', NULL, '2026-02-05', '2026-03-06', 'pagado', NULL, 1, '2026-02-05 15:00:00', '2026-02-05 15:00:00'),
	(14, 1, 3, 1, 29.99, '2026-02-07', 'efectivo', NULL, '2026-02-07', '2026-03-08', 'pagado', NULL, 1, '2026-02-07 14:30:00', '2026-02-07 14:30:00'),
	(15, 1, 12, 4, 399.99, '2026-02-03', 'tarjeta', NULL, '2026-02-03', '2027-02-02', 'pagado', NULL, 1, '2026-02-03 16:00:00', '2026-02-03 16:00:00'),
	(16, 1, 13, 2, 49.99, '2026-02-10', 'qr', NULL, '2026-02-10', '2026-03-11', 'pagado', NULL, 1, '2026-02-10 14:30:00', '2026-02-10 14:30:00'),
	(17, 1, 14, 3, 89.99, '2026-02-18', 'tarjeta', NULL, '2026-02-18', '2026-03-19', 'pagado', NULL, 1, '2026-02-18 15:00:00', '2026-02-18 15:00:00'),
	(18, 1, 15, 2, 49.99, '2026-02-25', 'efectivo', NULL, '2026-02-25', '2026-03-26', 'pagado', NULL, 1, '2026-02-25 13:30:00', '2026-02-25 13:30:00'),
	(19, 1, 6, 2, 49.99, '2026-02-08', 'transferencia', NULL, '2026-02-08', '2026-03-09', 'pagado', NULL, 1, '2026-02-08 14:00:00', '2026-02-08 14:00:00'),
	(20, 1, 8, 3, 89.99, '2026-02-14', 'tarjeta', NULL, '2026-02-14', '2026-03-15', 'pagado', NULL, 1, '2026-02-14 15:30:00', '2026-02-14 15:30:00'),
	(21, 1, 4, 2, 49.99, '2026-02-10', 'efectivo', NULL, '2026-02-10', '2026-03-11', 'pagado', NULL, 1, '2026-02-10 13:00:00', '2026-02-10 13:00:00'),
	(22, 1, 7, 3, 89.99, '2026-02-20', 'tarjeta', NULL, '2026-02-20', '2026-03-21', 'pagado', NULL, 1, '2026-02-20 16:00:00', '2026-02-20 16:00:00'),
	(23, 1, 1, 2, 49.99, '2026-03-03', 'efectivo', NULL, '2026-03-03', '2026-04-02', 'pagado', NULL, 1, '2026-03-03 14:00:00', '2026-03-03 14:00:00'),
	(24, 1, 2, 3, 89.99, '2026-03-05', 'tarjeta', NULL, '2026-03-05', '2026-04-04', 'pagado', NULL, 1, '2026-03-05 15:00:00', '2026-03-05 15:00:00'),
	(25, 1, 3, 1, 29.99, '2026-03-08', 'efectivo', NULL, '2026-03-08', '2026-04-07', 'pagado', NULL, 1, '2026-03-08 14:30:00', '2026-03-08 14:30:00'),
	(26, 1, 16, 3, 89.99, '2026-03-01', 'qr', NULL, '2026-03-01', '2026-03-31', 'pagado', NULL, 1, '2026-03-01 14:00:00', '2026-03-01 14:00:00'),
	(27, 1, 17, 2, 49.99, '2026-03-10', 'efectivo', NULL, '2026-03-10', '2026-04-09', 'pagado', NULL, 1, '2026-03-10 16:30:00', '2026-03-10 16:30:00'),
	(28, 1, 18, 4, 399.99, '2026-03-15', 'tarjeta', NULL, '2026-03-15', '2027-03-14', 'pagado', NULL, 1, '2026-03-15 15:00:00', '2026-03-15 15:00:00'),
	(29, 1, 19, 3, 89.99, '2026-03-22', 'transferencia', NULL, '2026-03-22', '2026-04-21', 'pagado', NULL, 1, '2026-03-22 13:00:00', '2026-03-22 13:00:00'),
	(30, 1, 20, 2, 49.99, '2026-03-28', 'efectivo', NULL, '2026-03-28', '2026-04-27', 'pagado', NULL, 1, '2026-03-28 14:30:00', '2026-03-28 14:30:00'),
	(31, 1, 6, 2, 49.99, '2026-03-08', 'tarjeta', NULL, '2026-03-08', '2026-04-07', 'pagado', NULL, 1, '2026-03-08 15:00:00', '2026-03-08 15:00:00'),
	(32, 1, 8, 3, 89.99, '2026-03-14', 'qr', NULL, '2026-03-14', '2026-04-13', 'pagado', NULL, 1, '2026-03-14 14:00:00', '2026-03-14 14:00:00'),
	(33, 1, 4, 2, 49.99, '2026-03-10', 'efectivo', NULL, '2026-03-10', '2026-04-09', 'pagado', NULL, 1, '2026-03-10 13:30:00', '2026-03-10 13:30:00'),
	(34, 1, 7, 3, 89.99, '2026-03-20', 'tarjeta', NULL, '2026-03-20', '2026-04-19', 'pagado', NULL, 1, '2026-03-20 16:00:00', '2026-03-20 16:00:00'),
	(35, 1, 13, 2, 49.99, '2026-03-12', 'efectivo', NULL, '2026-03-12', '2026-04-11', 'pagado', NULL, 1, '2026-03-12 14:00:00', '2026-03-12 14:00:00'),
	(36, 1, 1, 2, 49.99, '2026-04-03', 'efectivo', NULL, '2026-04-03', '2026-05-02', 'pagado', NULL, 1, '2026-04-03 14:00:00', '2026-04-03 14:00:00'),
	(37, 1, 2, 3, 89.99, '2026-04-05', 'tarjeta', NULL, '2026-04-05', '2026-05-04', 'pagado', NULL, 1, '2026-04-05 15:00:00', '2026-04-05 15:00:00'),
	(38, 1, 3, 1, 29.99, '2026-04-07', 'efectivo', NULL, '2026-04-07', '2026-05-06', 'pagado', NULL, 1, '2026-04-07 14:30:00', '2026-04-07 14:30:00'),
	(39, 1, 21, 3, 89.99, '2026-04-02', 'qr', NULL, '2026-04-02', '2026-05-01', 'pagado', NULL, 1, '2026-04-02 15:00:00', '2026-04-02 15:00:00'),
	(40, 1, 22, 2, 49.99, '2026-04-08', 'tarjeta', NULL, '2026-04-08', '2026-05-07', 'pagado', NULL, 1, '2026-04-08 13:30:00', '2026-04-08 13:30:00'),
	(41, 1, 23, 1, 29.99, '2026-04-15', 'efectivo', NULL, '2026-04-15', '2026-05-14', 'pagado', NULL, 1, '2026-04-15 14:00:00', '2026-04-15 14:00:00'),
	(42, 1, 24, 3, 89.99, '2026-04-22', 'transferencia', NULL, '2026-04-22', '2026-05-21', 'pagado', NULL, 1, '2026-04-22 16:00:00', '2026-04-22 16:00:00'),
	(43, 1, 25, 2, 49.99, '2026-04-28', 'efectivo', NULL, '2026-04-28', '2026-05-27', 'pagado', NULL, 1, '2026-04-28 15:30:00', '2026-04-28 15:30:00'),
	(44, 1, 6, 2, 49.99, '2026-04-08', 'tarjeta', NULL, '2026-04-08', '2026-05-07', 'pagado', NULL, 1, '2026-04-08 14:00:00', '2026-04-08 14:00:00'),
	(45, 1, 8, 3, 89.99, '2026-04-14', 'qr', NULL, '2026-04-14', '2026-05-13', 'pagado', NULL, 1, '2026-04-14 15:00:00', '2026-04-14 15:00:00'),
	(46, 1, 4, 2, 49.99, '2026-04-10', 'efectivo', NULL, '2026-04-10', '2026-05-09', 'pagado', NULL, 1, '2026-04-10 13:30:00', '2026-04-10 13:30:00'),
	(47, 1, 7, 3, 89.99, '2026-04-18', 'tarjeta', NULL, '2026-04-18', '2026-05-17', 'pagado', NULL, 1, '2026-04-18 16:00:00', '2026-04-18 16:00:00'),
	(48, 1, 16, 3, 89.99, '2026-04-01', 'efectivo', NULL, '2026-04-01', '2026-04-30', 'pagado', NULL, 1, '2026-04-01 14:00:00', '2026-04-01 14:00:00'),
	(49, 1, 17, 2, 49.99, '2026-04-10', 'tarjeta', NULL, '2026-04-10', '2026-05-09', 'pagado', NULL, 1, '2026-04-10 14:30:00', '2026-04-10 14:30:00'),
	(50, 1, 13, 2, 49.99, '2026-04-12', 'qr', NULL, '2026-04-12', '2026-05-11', 'pagado', NULL, 1, '2026-04-12 13:00:00', '2026-04-12 13:00:00'),
	(51, 1, 1, 2, 49.99, '2026-05-03', 'efectivo', NULL, '2026-05-03', '2026-06-02', 'pagado', NULL, 1, '2026-05-03 14:00:00', '2026-05-03 14:00:00'),
	(52, 1, 2, 3, 89.99, '2026-05-05', 'tarjeta', NULL, '2026-05-05', '2026-06-04', 'pagado', NULL, 1, '2026-05-05 15:00:00', '2026-05-05 15:00:00'),
	(53, 1, 3, 1, 29.99, '2026-05-07', 'efectivo', NULL, '2026-05-07', '2026-06-05', 'pagado', NULL, 1, '2026-05-07 14:30:00', '2026-05-07 14:30:00'),
	(54, 1, 26, 2, 49.99, '2026-05-05', 'qr', NULL, '2026-05-05', '2026-06-04', 'pagado', NULL, 1, '2026-05-05 14:00:00', '2026-05-05 14:00:00'),
	(55, 1, 27, 3, 89.99, '2026-05-10', 'tarjeta', NULL, '2026-05-10', '2026-06-09', 'pagado', NULL, 1, '2026-05-10 13:30:00', '2026-05-10 13:30:00'),
	(56, 1, 28, 4, 399.99, '2026-05-15', 'tarjeta', NULL, '2026-05-15', '2027-05-14', 'pagado', NULL, 1, '2026-05-15 15:00:00', '2026-05-15 15:00:00'),
	(57, 1, 29, 2, 49.99, '2026-05-20', 'efectivo', NULL, '2026-05-20', '2026-06-19', 'pagado', NULL, 1, '2026-05-20 16:00:00', '2026-05-20 16:00:00'),
	(58, 1, 30, 3, 89.99, '2026-05-25', 'transferencia', NULL, '2026-05-25', '2026-06-24', 'pagado', NULL, 1, '2026-05-25 14:30:00', '2026-05-25 14:30:00'),
	(59, 1, 31, 1, 29.99, '2026-05-28', 'efectivo', NULL, '2026-05-28', '2026-06-27', 'pagado', NULL, 1, '2026-05-28 13:00:00', '2026-05-28 13:00:00'),
	(60, 1, 6, 2, 49.99, '2026-05-08', 'tarjeta', NULL, '2026-05-08', '2026-06-07', 'pagado', NULL, 1, '2026-05-08 14:00:00', '2026-05-08 14:00:00'),
	(61, 1, 8, 3, 89.99, '2026-05-14', 'qr', NULL, '2026-05-14', '2026-06-13', 'pagado', NULL, 1, '2026-05-14 15:00:00', '2026-05-14 15:00:00'),
	(62, 1, 4, 2, 49.99, '2026-05-10', 'efectivo', NULL, '2026-05-10', '2026-06-08', 'pagado', NULL, 1, '2026-05-10 13:30:00', '2026-05-10 13:30:00'),
	(63, 1, 7, 3, 89.99, '2026-05-18', 'tarjeta', NULL, '2026-05-18', '2026-06-17', 'pagado', NULL, 1, '2026-05-18 16:00:00', '2026-05-18 16:00:00'),
	(64, 1, 22, 2, 49.99, '2026-05-08', 'efectivo', NULL, '2026-05-08', '2026-06-06', 'pagado', NULL, 1, '2026-05-08 14:30:00', '2026-05-08 14:30:00'),
	(65, 1, 24, 3, 89.99, '2026-05-22', 'tarjeta', NULL, '2026-05-22', '2026-06-20', 'pagado', NULL, 1, '2026-05-22 15:00:00', '2026-05-22 15:00:00'),
	(66, 1, 25, 2, 49.99, '2026-05-28', 'qr', NULL, '2026-05-28', '2026-06-26', 'pagado', NULL, 1, '2026-05-28 14:00:00', '2026-05-28 14:00:00'),
	(67, 1, 17, 2, 49.99, '2026-05-10', 'efectivo', NULL, '2026-05-10', '2026-06-08', 'pagado', NULL, 1, '2026-05-10 15:00:00', '2026-05-10 15:00:00'),
	(68, 1, 19, 3, 89.99, '2026-05-22', 'transferencia', NULL, '2026-05-22', '2026-06-20', 'pagado', NULL, 1, '2026-05-22 14:00:00', '2026-05-22 14:00:00'),
	(69, 1, 32, 3, 89.99, '2026-06-01', 'efectivo', NULL, '2026-06-01', '2026-06-30', 'pagado', NULL, 1, '2026-06-01 14:00:00', '2026-06-01 14:00:00'),
	(70, 1, 33, 2, 49.99, '2026-06-01', 'tarjeta', NULL, '2026-06-01', '2026-06-30', 'pagado', NULL, 1, '2026-06-01 15:00:00', '2026-06-01 15:00:00'),
	(71, 1, 34, 3, 89.99, '2026-06-02', 'qr', NULL, '2026-06-02', '2026-07-01', 'pagado', NULL, 1, '2026-06-02 14:00:00', '2026-06-02 14:00:00'),
	(72, 1, 35, 4, 399.99, '2026-06-03', 'tarjeta', NULL, '2026-06-03', '2027-06-02', 'pagado', NULL, 1, '2026-06-03 15:30:00', '2026-06-03 15:30:00'),
	(73, 1, 36, 2, 49.99, '2026-06-04', 'efectivo', NULL, '2026-06-04', '2026-07-03', 'pagado', NULL, 1, '2026-06-04 13:30:00', '2026-06-04 13:30:00'),
	(74, 1, 37, 3, 89.99, '2026-06-05', 'tarjeta', NULL, '2026-06-05', '2026-07-04', 'pagado', NULL, 1, '2026-06-05 14:00:00', '2026-06-05 14:00:00'),
	(75, 1, 38, 2, 49.99, '2026-06-05', 'qr', NULL, '2026-06-05', '2026-07-04', 'pagado', NULL, 1, '2026-06-05 16:00:00', '2026-06-05 16:00:00'),
	(76, 1, 1, 2, 49.99, '2026-06-03', 'efectivo', NULL, '2026-06-03', '2026-07-02', 'pagado', NULL, 1, '2026-06-03 14:00:00', '2026-06-03 14:00:00'),
	(77, 1, 2, 3, 89.99, '2026-06-05', 'tarjeta', NULL, '2026-06-05', '2026-07-04', 'pagado', NULL, 1, '2026-06-05 15:00:00', '2026-06-05 15:00:00'),
	(78, 1, 3, 1, 29.99, '2026-06-01', 'efectivo', NULL, '2026-06-01', '2026-06-30', 'pagado', NULL, 1, '2026-06-01 13:00:00', '2026-06-01 13:00:00'),
	(79, 1, 6, 2, 49.99, '2026-06-01', 'transferencia', NULL, '2026-06-01', '2026-06-30', 'pagado', NULL, 1, '2026-06-01 14:30:00', '2026-06-01 14:30:00'),
	(80, 1, 8, 3, 89.99, '2026-06-01', 'tarjeta', NULL, '2026-06-01', '2026-06-30', 'pagado', NULL, 1, '2026-06-01 15:30:00', '2026-06-01 15:30:00'),
	(81, 1, 4, 2, 49.99, '2026-06-02', 'qr', NULL, '2026-06-02', '2026-07-01', 'pagado', NULL, 1, '2026-06-02 14:00:00', '2026-06-02 14:00:00'),
	(82, 1, 7, 3, 89.99, '2026-06-02', 'efectivo', NULL, '2026-06-02', '2026-07-01', 'pagado', NULL, 1, '2026-06-02 15:00:00', '2026-06-02 15:00:00'),
	(83, 1, 26, 2, 49.99, '2026-06-05', 'tarjeta', NULL, '2026-06-05', '2026-07-04', 'pagado', NULL, 1, '2026-06-05 13:30:00', '2026-06-05 13:30:00'),
	(84, 1, 27, 3, 89.99, '2026-06-10', 'qr', NULL, '2026-06-10', '2026-07-09', 'pagado', NULL, 1, '2026-06-10 14:00:00', '2026-06-10 14:00:00'),
	(85, 1, 29, 2, 49.99, '2026-06-20', 'efectivo', NULL, '2026-06-20', '2026-07-19', 'pagado', NULL, 1, '2026-06-20 15:00:00', '2026-06-20 15:00:00'),
	(86, 1, 30, 3, 89.99, '2026-06-25', 'tarjeta', NULL, '2026-06-25', '2026-07-24', 'pagado', NULL, 1, '2026-06-25 14:30:00', '2026-06-25 14:30:00');

-- Volcando estructura para tabla saas_gimnasio.plans
CREATE TABLE IF NOT EXISTS `plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `duration_days` int NOT NULL DEFAULT '30',
  `features` text COMMENT 'JSON array de características',
  `color` varchar(10) DEFAULT '#7c3aed',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.plans: ~5 rows (aproximadamente)
DELETE FROM `plans`;
INSERT INTO `plans` (`id`, `gymnasium_id`, `name`, `description`, `price`, `duration_days`, `features`, `color`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Plan Básico', 'Acceso al gimnasio en horario regular', 29.99, 30, '["Acceso al gym","Vestuarios","Casillero básico"]', '#6b7280', 0, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(2, 1, 'Plan Estándar', 'Acceso completo + 2 clases grupales', 49.99, 30, '["Todo el Plan Básico","2 Clases grupales","Evaluación mensual","App móvil"]', '#7c3aed', 1, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(3, 1, 'Plan Premium', 'Todo incluido + entrenador personal', 89.99, 30, '["Todo el Plan Estándar","Entrenador personal 4x/mes","Nutricionista","Clases ilimitadas","Zona VIP"]', '#ec4899', 0, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(4, 1, 'Plan Anual', 'Plan Estándar por 12 meses con descuento', 399.99, 365, '["Todo el Plan Estándar","Descuento 33%","2 Meses gratis","Invitados 2x/mes"]', '#059669', 0, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(5, 1, 'PLAN DE PRUEBA', 'PLAN DE PRUEBA', 50.00, 7, '["Acceso al Gym","Acceso al Vestuario"]', '#3acaee', 1, 1, '2026-07-13 17:56:31', '2026-07-13 17:56:31');

-- Volcando estructura para tabla saas_gimnasio.routines
CREATE TABLE IF NOT EXISTS `routines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `trainer_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `exercises` text COMMENT 'JSON: [{name,sets,reps,rest,notes}]',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_routine_gym` (`gymnasium_id`),
  KEY `idx_routine_member` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.routines: ~0 rows (aproximadamente)
DELETE FROM `routines`;

-- Volcando estructura para tabla saas_gimnasio.saas_plans
CREATE TABLE IF NOT EXISTS `saas_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` text,
  `price_monthly` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_yearly` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_members` int NOT NULL DEFAULT '100',
  `max_trainers` int NOT NULL DEFAULT '5',
  `max_classes` int NOT NULL DEFAULT '10',
  `features` text,
  `color` varchar(20) DEFAULT '#7c3aed',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.saas_plans: ~3 rows (aproximadamente)
DELETE FROM `saas_plans`;
INSERT INTO `saas_plans` (`id`, `name`, `slug`, `description`, `price_monthly`, `price_yearly`, `max_members`, `max_trainers`, `max_classes`, `features`, `color`, `is_popular`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 'Starter', 'starter', 'Ideal para gimnasios pequenos que estan comenzando', 29.00, 290.00, 50, 3, 5, '["Hasta 50 socios","3 entrenadores","5 clases","Soporte por email","Reportes basicos"]', '#6b7280', 0, 1, 1, '2026-06-06 02:15:09', '2026-06-06 02:15:09'),
	(2, 'Pro', 'pro', 'Para gimnasios en crecimiento con todas las funciones', 59.00, 590.00, 200, 10, 20, '["Hasta 200 socios","10 entrenadores","20 clases","Soporte prioritario","Reportes avanzados","Inventario","App movil"]', '#7c3aed', 1, 1, 2, '2026-06-06 02:15:09', '2026-06-06 02:15:09'),
	(3, 'Enterprise', 'enterprise', 'Sin limites para cadenas y gimnasios grandes', 99.00, 990.00, 999999, 999999, 999999, '["Socios ilimitados","Entrenadores ilimitados","Clases ilimitadas","Soporte 24/7","API access","White-label","Multi-sucursal"]', '#ec4899', 0, 1, 3, '2026-06-06 02:15:09', '2026-06-06 02:15:09');

-- Volcando estructura para tabla saas_gimnasio.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.sessions: ~0 rows (aproximadamente)
DELETE FROM `sessions`;

-- Volcando estructura para tabla saas_gimnasio.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `key` varchar(100) NOT NULL,
  `value` text,
  `group` varchar(50) DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_gym_key` (`gymnasium_id`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.settings: ~16 rows (aproximadamente)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `gymnasium_id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
	(1, 1, 'gym_name', 'GymSaaS Pro', 'general', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(2, 1, 'gym_address', 'Av. Fitness 123, Ciudad', 'general', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(3, 1, 'gym_phone', '555-0100', 'general', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(4, 1, 'gym_email', 'info@gymsaas.com', 'general', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(5, 1, 'gym_opening', '06:00', 'schedule', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(6, 1, 'gym_closing', '22:00', 'schedule', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(7, 1, 'currency', 'PEN', 'billing', '2026-06-06 01:13:38', '2026-06-06 05:55:43'),
	(8, 1, 'currency_symbol', 'S/', 'billing', '2026-06-06 01:13:38', '2026-06-06 05:55:43'),
	(9, 1, 'logo', '', 'branding', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(10, 1, 'primary_color', '#7c3aed', 'branding', '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(11, NULL, 'currency', 'PEN', 'saas', NULL, '2026-07-13 18:27:29'),
	(12, NULL, 'currency_symbol', 'S/', 'saas', NULL, '2026-07-13 18:27:29'),
	(13, NULL, 'currency_position', 'before', 'saas', NULL, '2026-07-13 18:27:29'),
	(14, NULL, 'decimals', '2', 'saas', NULL, '2026-07-13 18:27:29'),
	(15, NULL, 'thousands_sep', 'coma', 'saas', NULL, '2026-07-13 18:27:29'),
	(16, NULL, 'decimal_sep', 'punto', 'saas', NULL, '2026-07-13 18:27:29');

-- Volcando estructura para tabla saas_gimnasio.support_tickets
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `priority` enum('low','normal','high') NOT NULL DEFAULT 'normal',
  `status` enum('open','pending','closed') NOT NULL DEFAULT 'open',
  `last_reply_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ticket_gym` (`gymnasium_id`),
  KEY `idx_ticket_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.support_tickets: ~0 rows (aproximadamente)
DELETE FROM `support_tickets`;

-- Volcando estructura para tabla saas_gimnasio.support_ticket_messages
CREATE TABLE IF NOT EXISTS `support_ticket_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `is_staff` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = respuesta del Super Admin',
  `body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_msg_ticket` (`ticket_id`),
  CONSTRAINT `support_ticket_messages_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.support_ticket_messages: ~0 rows (aproximadamente)
DELETE FROM `support_ticket_messages`;

-- Volcando estructura para tabla saas_gimnasio.trainers
CREATE TABLE IF NOT EXISTS `trainers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `speciality` varchar(255) DEFAULT NULL,
  `bio` text,
  `photo` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `trainers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.trainers: ~4 rows (aproximadamente)
DELETE FROM `trainers`;
INSERT INTO `trainers` (`id`, `gymnasium_id`, `user_id`, `name`, `email`, `phone`, `speciality`, `bio`, `photo`, `hire_date`, `salary`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'Carlos Mendoza', 'carlos@gym.com', '555-1001', 'Musculación y Fuerza', 'Especialista en fuerza con 8 años de experiencia', NULL, '2023-01-15', 1200.00, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(2, 1, NULL, 'Ana García', 'ana@gym.com', '555-1002', 'Yoga y Pilates', 'Instructora certificada en Hatha Yoga y Pilates reformer', NULL, '2023-03-01', 1100.00, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(3, 1, NULL, 'Luis Torres', 'luis@gym.com', '555-1003', 'CrossFit y Cardio', 'Coach CrossFit nivel 2 con certificación internacional', NULL, '2022-06-10', 1300.00, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(4, 1, NULL, 'Sofia Ríos', 'sofia@gym.com', '555-1004', 'Zumba y Baile', 'Instructora de Zumba con 5 años de experiencia', NULL, '2023-09-01', 1000.00, 1, '2026-06-06 01:13:38', '2026-06-06 01:13:38');

-- Volcando estructura para tabla saas_gimnasio.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'member',
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla saas_gimnasio.users: ~4 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `gymnasium_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `avatar`, `phone`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Administrador', 'admin@gymsaas.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NULL, 1, NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(2, 1, 'Carlos Mendoza', 'trainer@gymsaas.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', NULL, NULL, 1, NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(3, 1, 'María López', 'recep@gymsaas.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'receptionist', NULL, NULL, 1, NULL, '2026-06-06 01:13:38', '2026-06-06 01:13:38'),
	(4, NULL, 'Super Admin', 'superadmin@gymsaas.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin', NULL, NULL, 1, NULL, '2026-06-06 02:15:09', '2026-06-06 02:15:09');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
