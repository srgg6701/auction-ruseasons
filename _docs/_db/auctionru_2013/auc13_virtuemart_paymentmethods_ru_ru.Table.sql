--
-- Описание для таблицы auc13_virtuemart_paymentmethods_ru_ru
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_paymentmethods_ru_ru (
  virtuemart_paymentmethod_id int(1) UNSIGNED NOT NULL,
  payment_name char(180) NOT NULL DEFAULT '',
  payment_desc varchar(20000) NOT NULL DEFAULT '',
  slug char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (virtuemart_paymentmethod_id),
  UNIQUE INDEX slug (slug)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;