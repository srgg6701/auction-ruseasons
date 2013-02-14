--
-- Описание для таблицы auc13_virtuemart_calc_categories
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_calc_categories (
  id mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_calc_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_category_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_calc_id (virtuemart_calc_id, virtuemart_category_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;