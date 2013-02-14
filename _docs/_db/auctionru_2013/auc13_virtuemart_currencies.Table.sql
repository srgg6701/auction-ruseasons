--
-- Описание для таблицы auc13_virtuemart_currencies
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_currencies (
  virtuemart_currency_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 1,
  currency_name char(64) DEFAULT NULL,
  currency_code_2 char(2) DEFAULT NULL,
  currency_code_3 char(3) DEFAULT NULL,
  currency_numeric_code int(4) DEFAULT NULL,
  currency_exchange_rate decimal(10, 5) DEFAULT NULL,
  currency_symbol char(4) DEFAULT NULL,
  currency_decimal_place char(4) DEFAULT NULL,
  currency_decimal_symbol char(4) DEFAULT NULL,
  currency_thousands char(4) DEFAULT NULL,
  currency_positive_style char(64) DEFAULT NULL,
  currency_negative_style char(64) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 1,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_currency_id),
  INDEX idx_currency_code_3 (currency_code_3),
  INDEX idx_currency_numeric_code (currency_numeric_code),
  INDEX virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 202
AVG_ROW_LENGTH = 697
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Used to store currencies';