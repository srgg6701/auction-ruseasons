--
-- Описание для таблицы auc13_virtuemart_worldzones
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_worldzones (
  virtuemart_worldzone_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) DEFAULT NULL,
  zone_name char(255) DEFAULT NULL,
  zone_cost decimal(10, 2) DEFAULT NULL,
  zone_limit decimal(10, 2) DEFAULT NULL,
  zone_description varchar(18000) DEFAULT NULL,
  zone_tax_rate int(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_worldzone_id),
  INDEX i_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'The Zones managed by the Zone Shipment Module';