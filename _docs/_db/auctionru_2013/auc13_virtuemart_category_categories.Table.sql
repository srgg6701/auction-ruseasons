--
-- Описание для таблицы auc13_virtuemart_category_categories
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_category_categories (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  category_parent_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  category_child_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  INDEX category_child_id (category_child_id),
  UNIQUE INDEX i_category_parent_id (category_parent_id, category_child_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 48
AVG_ROW_LENGTH = 17
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Category child-parent relation list';