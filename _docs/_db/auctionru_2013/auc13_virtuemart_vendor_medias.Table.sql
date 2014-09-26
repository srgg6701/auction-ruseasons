--
-- Описание для таблицы auc13_virtuemart_vendor_medias
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_vendor_medias (
  id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_media_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_vendor_id (virtuemart_vendor_id, virtuemart_media_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;