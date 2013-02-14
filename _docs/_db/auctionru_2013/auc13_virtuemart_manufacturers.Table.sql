--
-- Описание для таблицы auc13_virtuemart_manufacturers
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_manufacturers (
  virtuemart_manufacturer_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_manufacturercategories_id int(11) DEFAULT NULL,
  hits int(11) UNSIGNED NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_manufacturer_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 48
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Manufacturers are those who deliver products';