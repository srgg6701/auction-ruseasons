CREATE TABLE IF NOT EXISTS `#__csvi_available_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `csvi_name` varchar(255) NOT NULL,
  `component_name` varchar(55) NOT NULL,
  `component_table` varchar(55) NOT NULL,
  `component` varchar(55) NOT NULL,
  `isprimary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `component_name_table` (`component_name`,`component_table`,`component`)
) CHARSET=utf8 COMMENT='Available fields for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_currency` (
  `currency_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `currency_code` varchar(3) DEFAULT NULL,
  `currency_rate` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  UNIQUE KEY `currency_code` (`currency_code`)
) CHARSET=utf8 COMMENT='Curriencies and exchange rates for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_icecat_index` (
  `path` varchar(100) DEFAULT NULL,
  `product_id` int(2) DEFAULT NULL,
  `updated` int(14) DEFAULT NULL,
  `quality` varchar(6) DEFAULT NULL,
  `supplier_id` int(1) DEFAULT NULL,
  `prod_id` varchar(16) DEFAULT NULL,
  `catid` int(3) DEFAULT NULL,
  `m_prod_id` varchar(10) DEFAULT NULL,
  `ean_upc` varchar(10) DEFAULT NULL,
  `on_market` int(1) DEFAULT NULL,
  `country_market` varchar(10) DEFAULT NULL,
  `model_name` varchar(26) DEFAULT NULL,
  `product_view` int(5) DEFAULT NULL,
  `high_pic` varchar(51) DEFAULT NULL,
  `high_pic_size` int(5) DEFAULT NULL,
  `high_pic_width` int(3) DEFAULT NULL,
  `high_pic_height` int(3) DEFAULT NULL,
  `m_supplier_id` int(3) DEFAULT NULL,
  `m_supplier_name` varchar(51) DEFAULT NULL,
  KEY `product_mpn` (`prod_id`),
  KEY `manufacturer_name` (`supplier_id`)
) CHARSET=utf8 COMMENT='ICEcat index data for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_icecat_suppliers` (
  `supplier_id` int(11) unsigned NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  UNIQUE KEY `Unique supplier` (`supplier_id`,`supplier_name`),
  KEY `Supplier name` (`supplier_name`)
) CHARSET=utf8 COMMENT='ICEcat supplier data for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `logstamp` datetime NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_type` varchar(255) NOT NULL DEFAULT '',
  `template_name` varchar(255) DEFAULT NULL,
  `records` int(11) NOT NULL,
  `run_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `run_cancelled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Log results for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_log_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `line` int(11) NOT NULL,
  `description` text NOT NULL,
  `result` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Log details for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_related_products` (
  `product_sku` varchar(64) NOT NULL,
  `related_sku` text NOT NULL
) CHARSET=utf8 COMMENT='Related products import for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_replacements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `findtext` text NOT NULL,
  `replacetext` text NOT NULL,
  `multivalue` ENUM('0','1') NOT NULL,
  `method` enum('text','regex') NOT NULL DEFAULT 'text',
  `checked_out` int(11) unsigned DEFAULT '0',
  `checked_out_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Replacement rules for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Configuration values for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_template_settings` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the saved setting',
	`name` VARCHAR(255) NOT NULL COMMENT 'Name for the saved setting',
	`settings` TEXT NOT NULL COMMENT 'The actual settings',
	PRIMARY KEY (`id`)
) CHARSET=utf8 COMMENT='Stores the template settings for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_template_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `template_type_name` varchar(55) NOT NULL,
  `template_table` varchar(55) NOT NULL,
  `component` varchar(55) NOT NULL,
  `indexed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`template_type_name`,`template_table`,`component`)
) CHARSET=utf8 COMMENT='Template tables used per template type for CSVI';

CREATE TABLE IF NOT EXISTS `#__csvi_template_types` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `checked_out` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `template_type_name` VARCHAR(55) NOT NULL,
  `template_type` VARCHAR(55) NOT NULL,
  `component` VARCHAR(55) NOT NULL COMMENT 'Name of the component',
  `url` VARCHAR(100) NULL DEFAULT NULL COMMENT 'The URL of the page the import is for',
  `options` VARCHAR(255) NOT NULL DEFAULT 'fields' COMMENT 'The template pages to show for the template type',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`template_type_name`,`template_type`,`component`)
) CHARSET=utf8 COMMENT='Template types for CSVI';