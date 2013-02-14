--
-- Описание для таблицы auc13_virtuemart_product_prices
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_product_prices (
  virtuemart_product_price_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_shoppergroup_id int(11) DEFAULT NULL,
  product_price decimal(15, 5) DEFAULT NULL,
  override tinyint(1) DEFAULT NULL,
  product_override_price decimal(15, 5) DEFAULT NULL,
  product_tax_id int(11) DEFAULT NULL,
  product_discount_id int(11) DEFAULT NULL,
  product_currency smallint(1) DEFAULT NULL,
  product_price_publish_up datetime DEFAULT NULL,
  product_price_publish_down datetime DEFAULT NULL,
  price_quantity_start int(11) UNSIGNED DEFAULT NULL,
  price_quantity_end int(11) UNSIGNED DEFAULT NULL,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_product_price_id),
  INDEX idx_product_price_product_id (virtuemart_product_id),
  INDEX idx_product_price_virtuemart_shoppergroup_id (virtuemart_shoppergroup_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 101
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Holds price records for a product';