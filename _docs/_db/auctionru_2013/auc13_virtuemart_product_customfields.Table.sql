--
-- Описание для таблицы auc13_virtuemart_product_customfields
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_product_customfields (
  virtuemart_customfield_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'field id',
  virtuemart_product_id int(11) NOT NULL DEFAULT 0,
  virtuemart_custom_id int(11) NOT NULL DEFAULT 1 COMMENT 'custom group id',
  custom_value varchar(8000) DEFAULT NULL COMMENT 'field value',
  custom_price decimal(15, 5) DEFAULT NULL COMMENT 'price',
  custom_param varchar(12800) DEFAULT NULL COMMENT 'Param for Plugins',
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(1) UNSIGNED NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(1) UNSIGNED NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_customfield_id),
  INDEX idx_custom_value (custom_value (333)),
  INDEX idx_virtuemart_custom_id (virtuemart_custom_id),
  INDEX idx_virtuemart_product_id (virtuemart_product_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 16
AVG_ROW_LENGTH = 60
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'custom fields';