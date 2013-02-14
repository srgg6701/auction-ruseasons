--
-- Описание для таблицы auc13_virtuemart_shipmentmethods
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_shipmentmethods (
  virtuemart_shipmentmethod_id mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(11) NOT NULL DEFAULT 1,
  shipment_jplugin_id int(11) NOT NULL DEFAULT 0,
  slug char(255) NOT NULL DEFAULT '',
  shipment_element char(50) NOT NULL DEFAULT '',
  shipment_params varchar(19000) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_shipmentmethod_id),
  INDEX idx_shipment_element (shipment_element, virtuemart_vendor_id),
  INDEX idx_shipment_jplugin_id (shipment_jplugin_id),
  INDEX idx_shipment_method_ordering (ordering)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Shipment created from the shipment plugins';