--
-- Описание для таблицы auc13_virtuemart_permgroups
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_permgroups (
  virtuemart_permgroup_id tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 1,
  group_name char(128) DEFAULT NULL,
  group_level int(11) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_permgroup_id),
  INDEX i_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 434
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Holds all the user groups';