--
-- Описание для таблицы auc13_virtuemart_orderstates
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_orderstates (
  virtuemart_orderstate_id tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(11) NOT NULL DEFAULT 1,
  order_status_code char(1) NOT NULL DEFAULT '',
  order_status_name char(64) DEFAULT NULL,
  order_status_description varchar(20000) DEFAULT NULL,
  order_stock_handle char(1) NOT NULL DEFAULT 'A',
  ordering int(2) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_orderstate_id),
  INDEX idx_order_status_ordering (ordering),
  INDEX idx_order_status_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 8
AVG_ROW_LENGTH = 41
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'All available order statuses';