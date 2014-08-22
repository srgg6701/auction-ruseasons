--
-- Описание для таблицы auc13_virtuemart_order_items
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_order_items (
  virtuemart_order_item_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_order_id int(11) DEFAULT NULL,
  virtuemart_vendor_id smallint(11) NOT NULL DEFAULT 1,
  virtuemart_product_id int(11) DEFAULT NULL,
  order_item_sku char(64) NOT NULL DEFAULT '',
  order_item_name char(255) NOT NULL DEFAULT '',
  product_quantity int(11) DEFAULT NULL,
  product_item_price decimal(15, 5) DEFAULT NULL,
  product_tax decimal(15, 5) DEFAULT NULL,
  product_basePriceWithTax decimal(15, 5) DEFAULT NULL,
  product_final_price decimal(15, 5) NOT NULL DEFAULT 0.00000,
  product_subtotal_discount decimal(15, 5) NOT NULL DEFAULT 0.00000,
  product_subtotal_with_tax decimal(15, 5) NOT NULL DEFAULT 0.00000,
  order_item_currency int(11) DEFAULT NULL,
  order_status char(1) DEFAULT NULL,
  product_attribute text DEFAULT NULL,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_order_item_id),
  INDEX idx_order_item_virtuemart_order_id (virtuemart_order_id),
  INDEX idx_order_item_virtuemart_vendor_id (virtuemart_vendor_id),
  INDEX virtuemart_product_id (virtuemart_product_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Stores all items (products) which are part of an order';