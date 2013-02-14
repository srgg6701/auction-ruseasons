--
-- Описание для таблицы auc13_virtuemart_order_histories
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_order_histories (
  virtuemart_order_history_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_order_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  order_status_code char(1) NOT NULL DEFAULT '0',
  customer_notified tinyint(1) NOT NULL DEFAULT 0,
  comments varchar(21000) DEFAULT NULL,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_order_history_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Stores all actions and changes that occur to an order';