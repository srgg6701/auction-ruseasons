--
-- Описание для таблицы auc13_virtuemart_products_ru_ru
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_products_ru_ru (
  virtuemart_product_id int(1) UNSIGNED NOT NULL,
  product_s_desc varchar(2000) NOT NULL DEFAULT '',
  product_desc varchar(18400) NOT NULL DEFAULT '',
  product_name char(180) NOT NULL DEFAULT '',
  metadesc varchar(400) NOT NULL DEFAULT '',
  metakey varchar(400) NOT NULL DEFAULT '',
  customtitle char(255) NOT NULL DEFAULT '',
  slug char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (virtuemart_product_id),
  UNIQUE INDEX slug (slug)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 292
character SET utf8
COLLATE utf8_general_ci;