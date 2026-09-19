-- ============================================================
-- GymSaaS Pro — Facturación Electrónica SUNAT (Perú)
-- Ejecutar una vez:  mysql -u root saas_gimnasio < database\facturacion_electronica.sql
-- Crea las tablas para la configuración del emisor y los comprobantes.
-- ============================================================
USE `saas_gimnasio`;

-- Configuración del emisor electrónico (una por gimnasio) --------------------
CREATE TABLE IF NOT EXISTS `billing_settings` (
  `id`                  bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id`        bigint unsigned DEFAULT NULL,
  -- Datos del emisor
  `ruc`                 varchar(11)  DEFAULT NULL,
  `razon_social`        varchar(255) DEFAULT NULL,
  `nombre_comercial`    varchar(255) DEFAULT NULL,
  `direccion`           varchar(255) DEFAULT NULL,
  `ubigeo`              varchar(6)   DEFAULT NULL,
  `urbanizacion`        varchar(120) DEFAULT NULL,
  `distrito`            varchar(120) DEFAULT NULL,
  `provincia`           varchar(120) DEFAULT NULL,
  `departamento`        varchar(120) DEFAULT NULL,
  -- Credenciales SUNAT (SOL) y certificado digital
  `sol_user`            varchar(60)  DEFAULT NULL,
  `sol_pass`            varchar(255) DEFAULT NULL,
  `cert_path`           varchar(255) DEFAULT NULL,
  `cert_pass`           varchar(255) DEFAULT NULL,
  -- Parámetros
  `environment`         enum('beta','produccion') NOT NULL DEFAULT 'beta',
  `igv_percent`         decimal(5,2) NOT NULL DEFAULT '18.00',
  `moneda`              varchar(3)   NOT NULL DEFAULT 'PEN',
  `driver`              varchar(20)  NOT NULL DEFAULT 'greenter',
  `auto_emit`           tinyint(1)   NOT NULL DEFAULT '0',
  -- Series y correlativos
  `serie_factura`       varchar(4)   NOT NULL DEFAULT 'F001',
  `serie_boleta`        varchar(4)   NOT NULL DEFAULT 'B001',
  `serie_nc`            varchar(4)   NOT NULL DEFAULT 'FC01',
  `serie_nd`            varchar(4)   NOT NULL DEFAULT 'FD01',
  `correlativo_factura` int NOT NULL DEFAULT '0',
  `correlativo_boleta`  int NOT NULL DEFAULT '0',
  `correlativo_nc`      int NOT NULL DEFAULT '0',
  `correlativo_nd`      int NOT NULL DEFAULT '0',
  `enabled`             tinyint(1)   NOT NULL DEFAULT '0',
  `created_at`          timestamp NULL DEFAULT NULL,
  `updated_at`          timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `billing_settings_gym` (`gymnasium_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Comprobantes electrónicos emitidos -----------------------------------------
CREATE TABLE IF NOT EXISTS `electronic_documents` (
  `id`                     bigint unsigned NOT NULL AUTO_INCREMENT,
  `gymnasium_id`           bigint unsigned DEFAULT NULL,
  `payment_id`             bigint unsigned DEFAULT NULL,
  `member_id`              bigint unsigned DEFAULT NULL,
  `tipo_doc`               varchar(2)  NOT NULL,   -- 01 Factura, 03 Boleta, 07 NC, 08 ND
  `serie`                  varchar(4)  NOT NULL,
  `correlativo`            varchar(8)  NOT NULL,
  `fecha_emision`          datetime    NOT NULL,
  `moneda`                 varchar(3)  NOT NULL DEFAULT 'PEN',
  -- Cliente / adquirente
  `cliente_tipo_doc`       varchar(1)  NOT NULL DEFAULT '1',  -- 6 RUC, 1 DNI, 0 sin doc
  `cliente_num_doc`        varchar(15) DEFAULT NULL,
  `cliente_razon_social`   varchar(255) DEFAULT NULL,
  `cliente_direccion`      varchar(255) DEFAULT NULL,
  -- Totales
  `items`                  json DEFAULT NULL,
  `mto_oper_gravadas`      decimal(12,2) NOT NULL DEFAULT '0.00',
  `mto_igv`                decimal(12,2) NOT NULL DEFAULT '0.00',
  `total`                  decimal(12,2) NOT NULL DEFAULT '0.00',
  -- Notas de crédito/débito
  `doc_afectado_tipo`      varchar(2)  DEFAULT NULL,
  `doc_afectado_serie_num` varchar(20) DEFAULT NULL,
  `cod_motivo`             varchar(2)  DEFAULT NULL,
  `des_motivo`             varchar(255) DEFAULT NULL,
  -- Estado SUNAT
  `estado`                 enum('pendiente','enviado','aceptado','rechazado','anulado','error') NOT NULL DEFAULT 'pendiente',
  `sunat_code`             varchar(10) DEFAULT NULL,
  `sunat_description`      varchar(255) DEFAULT NULL,
  `hash`                   varchar(100) DEFAULT NULL,
  `xml_path`               varchar(255) DEFAULT NULL,
  `cdr_path`               varchar(255) DEFAULT NULL,
  `created_by`             bigint unsigned DEFAULT NULL,
  `created_at`             timestamp NULL DEFAULT NULL,
  `updated_at`             timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ed_serie_corr` (`gymnasium_id`,`tipo_doc`,`serie`,`correlativo`),
  KEY `ed_gym` (`gymnasium_id`),
  KEY `ed_payment` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
