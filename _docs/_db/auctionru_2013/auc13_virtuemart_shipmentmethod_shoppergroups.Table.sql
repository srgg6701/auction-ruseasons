--
-- Описание для таблицы auc13_virtuemart_shipmentmethod_shoppergroups
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_shipmentmethod_shoppergroups (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_shipmentmethod_id mediumint(1) UNSIGNED DEFAULT NULL,
  virtuemart_shoppergroup_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_shipmentmethod_id (virtuemart_shipmentmethod_id, virtuemart_shoppergroup_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'xref table for shipment to shoppergroup';