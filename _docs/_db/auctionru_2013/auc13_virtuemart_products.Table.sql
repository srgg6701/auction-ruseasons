--
-- Описание для таблицы auc13_virtuemart_products
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_products (
  virtuemart_product_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 1,
  product_parent_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  product_sku char(64) DEFAULT NULL,
  product_weight decimal(10, 4) DEFAULT NULL,
  product_weight_uom char(7) DEFAULT NULL,
  product_length decimal(10, 4) DEFAULT NULL,
  product_width decimal(10, 4) DEFAULT NULL,
  product_height decimal(10, 4) DEFAULT NULL,
  product_lwh_uom char(7) DEFAULT NULL,
  product_url char(255) DEFAULT NULL,
  product_in_stock int(1) DEFAULT NULL,
  product_ordered int(1) DEFAULT NULL,
  low_stock_notification int(1) UNSIGNED DEFAULT NULL,
  product_available_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  product_availability char(32) DEFAULT NULL,
  product_special tinyint(1) DEFAULT NULL,
  product_sales int(1) UNSIGNED DEFAULT NULL,
  product_unit char(4) DEFAULT NULL,
  product_packaging decimal(8, 4) UNSIGNED DEFAULT NULL,
  product_params varchar(2000) DEFAULT NULL,
  hits int(11) UNSIGNED DEFAULT NULL,
  intnotes varchar(18000) DEFAULT NULL,
  metarobot varchar(400) DEFAULT NULL,
  metaauthor varchar(400) DEFAULT NULL,
  layout char(16) DEFAULT NULL,
  published tinyint(1) DEFAULT NULL,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_product_id),
  INDEX idx_product_product_parent_id (product_parent_id),
  INDEX idx_product_sku (product_sku),
  INDEX idx_product_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 206
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'All products are stored here.';