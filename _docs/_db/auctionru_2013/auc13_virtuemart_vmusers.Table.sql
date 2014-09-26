--
-- Описание для таблицы auc13_virtuemart_vmusers
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_vmusers (
  virtuemart_user_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  user_is_vendor tinyint(1) NOT NULL DEFAULT 0,
  customer_number char(32) DEFAULT NULL,
  perms char(40) NOT NULL DEFAULT 'shopper',
  virtuemart_paymentmethod_id mediumint(1) UNSIGNED DEFAULT NULL,
  virtuemart_shipmentmethod_id mediumint(1) UNSIGNED DEFAULT NULL,
  agreed tinyint(1) NOT NULL DEFAULT 0,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_user_id),
  UNIQUE INDEX i_virtuemart_user_id (virtuemart_user_id, virtuemart_vendor_id),
  INDEX i_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 108
AVG_ROW_LENGTH = 267
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Holds the unique user data';