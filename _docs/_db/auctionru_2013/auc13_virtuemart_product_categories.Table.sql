--
-- Описание для таблицы auc13_virtuemart_product_categories
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_product_categories (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_category_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_product_id (virtuemart_product_id, virtuemart_category_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 15
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Maps Products to Categories';