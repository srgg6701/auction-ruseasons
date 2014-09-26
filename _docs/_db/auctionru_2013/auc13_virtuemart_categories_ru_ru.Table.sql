--
-- Описание для таблицы auc13_virtuemart_categories_ru_ru
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_categories_ru_ru (
  virtuemart_category_id int(1) UNSIGNED NOT NULL,
  category_name char(180) NOT NULL DEFAULT '',
  category_description varchar(20000) NOT NULL DEFAULT '',
  metadesc varchar(400) NOT NULL DEFAULT '',
  metakey varchar(400) NOT NULL DEFAULT '',
  customtitle char(255) NOT NULL DEFAULT '',
  slug char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (virtuemart_category_id),
  UNIQUE INDEX slug (slug)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 70
character SET utf8
COLLATE utf8_general_ci;