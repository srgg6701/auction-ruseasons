--
-- Описание для таблицы auc13_virtuemart_invoices
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_invoices (
  virtuemart_invoice_id int(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 1,
  virtuemart_order_id int(1) UNSIGNED DEFAULT NULL,
  invoice_number char(64) DEFAULT NULL,
  order_status char(2) DEFAULT NULL,
  xhtml text DEFAULT NULL,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_invoice_id),
  UNIQUE INDEX idx_invoice_number (invoice_number, virtuemart_vendor_id),
  INDEX idx_virtuemart_order_id (virtuemart_order_id),
  INDEX idx_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'custom fields definition';