--
-- Описание для таблицы auc13_virtuemart_manufacturercategories_ru_ru
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_manufacturercategories_ru_ru (
  virtuemart_manufacturercategories_id int(1) UNSIGNED NOT NULL,
  mf_category_name char(180) NOT NULL DEFAULT '',
  mf_category_desc varchar(20000) NOT NULL DEFAULT '',
  slug char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (virtuemart_manufacturercategories_id),
  UNIQUE INDEX slug (slug)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 152
character SET utf8
COLLATE utf8_general_ci;