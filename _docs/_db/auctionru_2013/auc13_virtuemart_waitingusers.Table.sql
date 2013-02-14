--
-- Описание для таблицы auc13_virtuemart_waitingusers
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_waitingusers (
  virtuemart_waitinguser_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_user_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  notify_email char(150) NOT NULL DEFAULT '',
  notified tinyint(1) NOT NULL DEFAULT 0,
  notify_date timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  ordering int(2) NOT NULL DEFAULT 0,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_waitinguser_id),
  INDEX notify_email (notify_email),
  INDEX virtuemart_product_id (virtuemart_product_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Stores notifications, users waiting f. products out of stock';