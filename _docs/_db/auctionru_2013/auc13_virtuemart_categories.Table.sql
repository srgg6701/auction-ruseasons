--
-- Описание для таблицы auc13_virtuemart_categories
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_categories (
  virtuemart_category_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  category_template char(128) DEFAULT NULL,
  category_layout char(64) DEFAULT NULL,
  category_product_layout char(64) DEFAULT NULL,
  products_per_row tinyint(2) DEFAULT NULL,
  limit_list_start smallint(1) UNSIGNED DEFAULT NULL,
  limit_list_step smallint(1) UNSIGNED DEFAULT NULL,
  limit_list_max smallint(1) UNSIGNED DEFAULT NULL,
  limit_list_initial smallint(1) UNSIGNED DEFAULT NULL,
  hits int(1) UNSIGNED NOT NULL DEFAULT 0,
  metarobot char(40) NOT NULL DEFAULT '',
  metaauthor char(64) NOT NULL DEFAULT '',
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_category_id),
  INDEX idx_category_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 48
AVG_ROW_LENGTH = 1141
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Product Categories are stored here';