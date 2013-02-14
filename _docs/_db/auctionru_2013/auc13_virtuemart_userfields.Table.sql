--
-- Описание для таблицы auc13_virtuemart_userfields
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_userfields (
  virtuemart_userfield_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 1,
  userfield_jplugin_id int(11) NOT NULL DEFAULT 0,
  name char(50) NOT NULL DEFAULT '',
  title char(255) NOT NULL DEFAULT '',
  description mediumtext DEFAULT NULL,
  type char(50) NOT NULL DEFAULT '',
  maxlength int(11) DEFAULT NULL,
  size int(11) DEFAULT NULL,
  required tinyint(4) NOT NULL DEFAULT 0,
  cols int(11) DEFAULT NULL,
  rows int(11) DEFAULT NULL,
  value char(50) DEFAULT NULL,
  `default` char(255) DEFAULT NULL,
  registration tinyint(1) NOT NULL DEFAULT 0,
  shipment tinyint(1) NOT NULL DEFAULT 0,
  account tinyint(1) NOT NULL DEFAULT 1,
  readonly tinyint(1) NOT NULL DEFAULT 0,
  calculated tinyint(1) NOT NULL DEFAULT 0,
  sys tinyint(4) NOT NULL DEFAULT 0,
  params varchar(18000) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_userfield_id),
  INDEX i_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 27
AVG_ROW_LENGTH = 91
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Holds the fields for the user information';