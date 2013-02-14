--
-- Описание для таблицы auc13_virtuemart_shoppergroups
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_shoppergroups (
  virtuemart_shoppergroup_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(11) NOT NULL DEFAULT 1,
  shopper_group_name char(32) DEFAULT NULL,
  shopper_group_desc char(128) DEFAULT NULL,
  custom_price_display tinyint(1) NOT NULL DEFAULT 0,
  price_display blob DEFAULT NULL,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_shoppergroup_id),
  INDEX idx_shopper_group_name (shopper_group_name),
  INDEX idx_shopper_group_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 62
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Shopper Groups that users can be assigned to';