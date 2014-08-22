--
-- Описание для таблицы auc13_virtuemart_orders
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_orders (
  virtuemart_order_id int(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_user_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  order_number char(64) DEFAULT NULL,
  order_pass char(8) DEFAULT NULL,
  order_total decimal(15, 5) NOT NULL DEFAULT 0.00000,
  order_salesPrice decimal(15, 5) NOT NULL DEFAULT 0.00000,
  order_billTaxAmount decimal(15, 5) NOT NULL DEFAULT 0.00000,
  order_billDiscountAmount decimal(15, 5) NOT NULL DEFAULT 0.00000,
  order_discountAmount decimal(15, 5) NOT NULL DEFAULT 0.00000,
  order_subtotal decimal(15, 5) DEFAULT NULL,
  order_tax decimal(10, 5) DEFAULT NULL,
  order_shipment decimal(10, 2) DEFAULT NULL,
  order_shipment_tax decimal(10, 5) DEFAULT NULL,
  order_payment decimal(10, 2) DEFAULT NULL,
  order_payment_tax decimal(10, 5) DEFAULT NULL,
  coupon_discount decimal(12, 2) NOT NULL DEFAULT 0.00,
  coupon_code char(32) DEFAULT NULL,
  order_discount decimal(12, 2) NOT NULL DEFAULT 0.00,
  order_currency smallint(1) DEFAULT NULL,
  order_status char(1) DEFAULT NULL,
  user_currency_id smallint(1) DEFAULT NULL,
  user_currency_rate decimal(10, 5) NOT NULL DEFAULT 1.00000,
  virtuemart_paymentmethod_id mediumint(1) UNSIGNED DEFAULT NULL,
  virtuemart_shipmentmethod_id mediumint(1) UNSIGNED DEFAULT NULL,
  customer_note varchar(21000) DEFAULT NULL,
  ip_address char(15) NOT NULL DEFAULT '',
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_order_id),
  INDEX idx_orders_order_number (order_number),
  INDEX idx_orders_virtuemart_paymentmethod_id (virtuemart_paymentmethod_id),
  INDEX idx_orders_virtuemart_shipmentmethod_id (virtuemart_shipmentmethod_id),
  INDEX idx_orders_virtuemart_user_id (virtuemart_user_id),
  INDEX idx_orders_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Used to store all orders';