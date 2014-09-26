--
-- Описание для таблицы auc13_virtuemart_product_medias
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_product_medias (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_media_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_product_id (virtuemart_product_id, virtuemart_media_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 17
character SET utf8
COLLATE utf8_general_ci;