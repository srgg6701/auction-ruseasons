--
-- Описание для таблицы auc13_virtuemart_countries
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_countries (
  virtuemart_country_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_worldzone_id tinyint(11) NOT NULL DEFAULT 1,
  country_name char(64) DEFAULT NULL,
  country_3_code char(3) DEFAULT NULL,
  country_2_code char(2) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_country_id),
  INDEX idx_country_2_code (country_2_code),
  INDEX idx_country_3_code (country_3_code)
)
ENGINE = MYISAM
AUTO_INCREMENT = 249
AVG_ROW_LENGTH = 252
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Country records';