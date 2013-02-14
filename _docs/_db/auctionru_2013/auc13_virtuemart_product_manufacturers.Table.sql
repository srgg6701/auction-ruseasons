--
-- Описание для таблицы auc13_virtuemart_product_manufacturers
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_product_manufacturers (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(11) DEFAULT NULL,
  virtuemart_manufacturer_id smallint(1) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_product_id (virtuemart_product_id, virtuemart_manufacturer_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 11
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Maps a product to a manufacturer';