--
-- Описание для таблицы auc13_virtuemart_medias
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_medias (
  virtuemart_media_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(11) NOT NULL DEFAULT 1,
  file_title char(126) NOT NULL DEFAULT '',
  file_description char(254) NOT NULL DEFAULT '',
  file_meta char(254) NOT NULL DEFAULT '',
  file_mimetype char(64) NOT NULL DEFAULT '',
  file_type char(32) NOT NULL DEFAULT '',
  file_url varchar(1800) NOT NULL DEFAULT '',
  file_url_thumb char(254) NOT NULL DEFAULT '',
  file_is_product_image tinyint(1) NOT NULL DEFAULT 0,
  file_is_downloadable tinyint(1) NOT NULL DEFAULT 0,
  file_is_forSale tinyint(1) NOT NULL DEFAULT 0,
  file_params varchar(19000) DEFAULT NULL,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_media_id),
  INDEX i_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 278
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Additional Images and Files which are assigned to products';