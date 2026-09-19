-- ============================================================
-- GymSaaS Pro — Corregir fechas de socios para gráfico
-- Ejecutar: mysql -u root saas_gimnasio < database\fix_fechas_socios.sql
-- ============================================================

USE `saas_gimnasio`;

-- ============================================================
-- PASO 1: Actualizar fechas de los socios originales (id 1-8)
-- para distribuirlos en Enero, Febrero y Marzo
-- ============================================================
UPDATE `members` SET
  `created_at` = '2026-01-05 09:00:00',
  `updated_at` = '2026-01-05 09:00:00'
WHERE id = 1;  -- Juan Pérez → Enero

UPDATE `members` SET
  `created_at` = '2026-01-12 10:00:00',
  `updated_at` = '2026-01-12 10:00:00'
WHERE id = 2;  -- Laura Martínez → Enero

UPDATE `members` SET
  `created_at` = '2026-02-03 08:30:00',
  `updated_at` = '2026-02-03 08:30:00'
WHERE id = 3;  -- Pedro Sánchez → Febrero

UPDATE `members` SET
  `created_at` = '2026-02-15 11:00:00',
  `updated_at` = '2026-02-15 11:00:00'
WHERE id = 4;  -- María González → Febrero

UPDATE `members` SET
  `created_at` = '2026-03-08 09:00:00',
  `updated_at` = '2026-03-08 09:00:00'
WHERE id = 5;  -- Roberto Flores → Marzo

UPDATE `members` SET
  `created_at` = '2026-03-20 10:30:00',
  `updated_at` = '2026-03-20 10:30:00'
WHERE id = 6;  -- Carmen Ruiz → Marzo

UPDATE `members` SET
  `created_at` = '2026-04-10 09:00:00',
  `updated_at` = '2026-04-10 09:00:00'
WHERE id = 7;  -- Diego López → Abril

UPDATE `members` SET
  `created_at` = '2026-04-22 08:30:00',
  `updated_at` = '2026-04-22 08:30:00'
WHERE id = 8;  -- Valeria Castro → Abril

-- ============================================================
-- PASO 2: Insertar socios nuevos con INSERT IGNORE
-- (si ya existen por un SQL anterior, los omite sin error)
-- ============================================================
INSERT IGNORE INTO `members`
  (`code`,`first_name`,`last_name`,`email`,`phone`,`birth_date`,`gender`,`plan_id`,`membership_start`,`membership_end`,`status`,`created_at`,`updated_at`)
VALUES

-- ENERO (3 socios)
('GYM-009','Andrés','Vargas','andres.vargas@email.com','555-3001','1990-03-14','M',2,'2026-01-08','2026-02-07','vencido','2026-01-08 09:00:00','2026-01-08 09:00:00'),
('GYM-010','Daniela','Paredes','daniela.p@email.com','555-3002','1995-07-22','F',3,'2026-01-15','2026-02-14','vencido','2026-01-15 10:00:00','2026-01-15 10:00:00'),
('GYM-011','Miguel','Torres','miguel.t@email.com','555-3003','1988-11-05','M',1,'2026-01-22','2026-02-21','vencido','2026-01-22 08:00:00','2026-01-22 08:00:00'),

-- FEBRERO (3 socios)
('GYM-012','Gabriela','Mendoza','gaby.m@email.com','555-3004','1993-04-18','F',4,'2026-02-03','2027-02-02','activo','2026-02-03 11:00:00','2026-02-03 11:00:00'),
('GYM-013','Carlos','Ramos','carlos.ramos@email.com','555-3005','1985-09-30','M',2,'2026-02-10','2026-03-11','vencido','2026-02-10 09:30:00','2026-02-10 09:30:00'),
('GYM-014','Isabella','Cruz','isa.cruz@email.com','555-3006','1998-01-25','F',3,'2026-02-20','2026-03-21','vencido','2026-02-20 10:00:00','2026-02-20 10:00:00'),

-- MARZO (4 socios)
('GYM-015','Fernando','Gutiérrez','fer.gutierrez@email.com','555-3007','1992-06-12','M',2,'2026-03-01','2026-03-31','vencido','2026-03-01 08:30:00','2026-03-01 08:30:00'),
('GYM-016','Camila','Herrera','camila.h@email.com','555-3008','1997-12-03','F',3,'2026-03-10','2026-04-09','vencido','2026-03-10 09:00:00','2026-03-10 09:00:00'),
('GYM-017','Ricardo','Morales','ricardo.m@email.com','555-3009','1991-08-17','M',2,'2026-03-18','2026-04-17','vencido','2026-03-18 11:30:00','2026-03-18 11:30:00'),
('GYM-018','Valentina','Jiménez','vale.jimenez@email.com','555-3010','1996-02-28','F',4,'2026-03-25','2027-03-24','activo','2026-03-25 10:00:00','2026-03-25 10:00:00'),

-- ABRIL (4 socios)
('GYM-019','Sebastián','Ortega','seba.ortega@email.com','555-3011','1989-10-08','M',3,'2026-04-02','2026-05-01','vencido','2026-04-02 08:00:00','2026-04-02 08:00:00'),
('GYM-020','Luciana','Reyes','luci.reyes@email.com','555-3012','1994-05-14','F',2,'2026-04-08','2026-05-07','vencido','2026-04-08 09:30:00','2026-04-08 09:30:00'),
('GYM-021','Mateo','Silva','mateo.silva@email.com','555-3013','1987-07-21','M',3,'2026-04-15','2026-05-14','vencido','2026-04-15 10:00:00','2026-04-15 10:00:00'),
('GYM-022','Sofía','Delgado','sofia.delgado@email.com','555-3014','1999-11-30','F',1,'2026-04-25','2026-05-24','vencido','2026-04-25 08:30:00','2026-04-25 08:30:00'),

-- MAYO (6 socios)
('GYM-023','Pablo','Castillo','pablo.c@email.com','555-3015','1986-03-17','M',2,'2026-05-02','2026-06-01','activo','2026-05-02 09:00:00','2026-05-02 09:00:00'),
('GYM-024','Natalia','Espinoza','nati.e@email.com','555-3016','2000-08-09','F',3,'2026-05-08','2026-06-07','activo','2026-05-08 11:00:00','2026-05-08 11:00:00'),
('GYM-025','Javier','Núñez','javier.n@email.com','555-3017','1993-12-25','M',2,'2026-05-12','2026-06-11','activo','2026-05-12 10:30:00','2026-05-12 10:30:00'),
('GYM-026','Mariana','Vega','mari.vega@email.com','555-3018','1997-06-14','F',3,'2026-05-15','2026-06-14','activo','2026-05-15 09:00:00','2026-05-15 09:00:00'),
('GYM-027','Emilio','Salazar','emilio.s@email.com','555-3019','1984-02-20','M',2,'2026-05-20','2026-06-19','activo','2026-05-20 08:30:00','2026-05-20 08:30:00'),
('GYM-028','Renata','Campos','renata.c@email.com','555-3020','1991-09-11','F',4,'2026-05-26','2027-05-25','activo','2026-05-26 10:00:00','2026-05-26 10:00:00'),

-- JUNIO (7 socios)
('GYM-029','Alejandro','Ponce','alejo.p@email.com','555-3021','1988-04-30','M',2,'2026-06-01','2026-06-30','activo','2026-06-01 09:00:00','2026-06-01 09:00:00'),
('GYM-030','Patricia','Luna','patri.l@email.com','555-3022','1995-01-07','F',3,'2026-06-01','2026-06-30','activo','2026-06-01 09:30:00','2026-06-01 09:30:00'),
('GYM-031','Tomás','Fuentes','tomas.f@email.com','555-3023','1990-11-18','M',1,'2026-06-02','2026-07-01','activo','2026-06-02 08:00:00','2026-06-02 08:00:00'),
('GYM-032','Valeria','Castro2','vale.castro2@email.com','555-3024','1996-07-03','F',3,'2026-06-02','2026-07-01','activo','2026-06-02 09:00:00','2026-06-02 09:00:00'),
('GYM-033','Diego','Rojas','diego.rojas@email.com','555-3025','1993-03-22','M',2,'2026-06-03','2026-07-02','activo','2026-06-03 10:00:00','2026-06-03 10:00:00'),
('GYM-034','Camilo','Navarro','camilo.n@email.com','555-3026','1989-08-15','M',3,'2026-06-04','2026-07-03','activo','2026-06-04 09:00:00','2026-06-04 09:00:00'),
('GYM-035','Carolina','Bermúdez','caro.b@email.com','555-3029','1992-01-29','F',2,'2026-06-05','2026-07-04','activo','2026-06-05 09:00:00','2026-06-05 09:00:00');

-- ============================================================
-- PASO 3: Actualizar socios vencidos
-- ============================================================
UPDATE `members`
SET `status` = 'vencido'
WHERE `membership_end` < CURDATE() AND `status` = 'activo';

-- ============================================================
-- RESUMEN — Socios por mes
-- ============================================================
SELECT
  DATE_FORMAT(created_at, '%M %Y') AS mes,
  COUNT(*) AS socios_registrados
FROM `members`
GROUP BY YEAR(created_at), MONTH(created_at)
ORDER BY YEAR(created_at), MONTH(created_at);

SELECT
  CONCAT('Total socios: ', COUNT(*)) AS resumen,
  CONCAT('Activos: ', SUM(status='activo')) AS activos,
  CONCAT('Vencidos: ', SUM(status='vencido')) AS vencidos
FROM `members`;
