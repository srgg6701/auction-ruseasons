--
-- Описание для таблицы auc13_virtuemart_manufacturers_ru_ru
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_manufacturers_ru_ru (
  virtuemart_manufacturer_id int(1) UNSIGNED NOT NULL,
  mf_name char(180) NOT NULL DEFAULT '',
  mf_email char(255) NOT NULL DEFAULT '',
  mf_desc varchar(20000) NOT NULL DEFAULT '',
  mf_url char(255) NOT NULL DEFAULT '',
  slug char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (virtuemart_manufacturer_id),
  UNIQUE INDEX slug (slug)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 92
character SET utf8
COLLATE utf8_general_ci;