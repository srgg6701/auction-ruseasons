--
-- Описание для таблицы auc13_csvi_currency
--
CREATE TABLE IF NOT EXISTS auc13_csvi_currency (
  currency_id tinyint(4) NOT NULL AUTO_INCREMENT,
  currency_code varchar(3) DEFAULT NULL,
  currency_rate varchar(55) DEFAULT NULL,
  PRIMARY KEY (currency_id),
  UNIQUE INDEX currency_code (currency_code)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Curriencies and exchange rates for CSVI';