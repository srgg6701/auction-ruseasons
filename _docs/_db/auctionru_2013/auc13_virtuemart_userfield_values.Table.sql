--
-- Описание для таблицы auc13_virtuemart_userfield_values
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_userfield_values (
  virtuemart_userfield_value_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_userfield_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  fieldtitle char(255) NOT NULL DEFAULT '',
  fieldvalue char(255) NOT NULL DEFAULT '',
  sys tinyint(4) NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_userfield_value_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 20
AVG_ROW_LENGTH = 1578
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Holds the different values for dropdown and radio lists';