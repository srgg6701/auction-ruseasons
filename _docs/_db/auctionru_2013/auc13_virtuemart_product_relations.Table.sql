--
-- Описание для таблицы auc13_virtuemart_product_relations
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_product_relations (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  related_products int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_product_id (virtuemart_product_id, related_products)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;