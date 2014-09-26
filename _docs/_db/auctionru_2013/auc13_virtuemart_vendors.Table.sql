--
-- Описание для таблицы auc13_virtuemart_vendors
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_vendors (
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  vendor_name char(64) DEFAULT NULL,
  vendor_currency int(11) DEFAULT NULL,
  vendor_accepted_currencies varchar(1536) NOT NULL DEFAULT '',
  vendor_params varchar(17000) DEFAULT NULL,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_vendor_id),
  INDEX idx_vendor_name (vendor_name)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 160
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Vendors manage their products in your store';