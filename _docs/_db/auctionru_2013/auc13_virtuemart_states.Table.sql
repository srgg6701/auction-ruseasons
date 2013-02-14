--
-- Описание для таблицы auc13_virtuemart_states
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_states (
  virtuemart_state_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 1,
  virtuemart_country_id smallint(1) UNSIGNED NOT NULL DEFAULT 1,
  virtuemart_worldzone_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  state_name char(64) DEFAULT NULL,
  state_3_code char(3) DEFAULT NULL,
  state_2_code char(2) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_state_id),
  INDEX i_virtuemart_country_id (virtuemart_country_id),
  INDEX i_virtuemart_vendor_id (virtuemart_vendor_id),
  UNIQUE INDEX idx_state_2_code (virtuemart_country_id, state_2_code),
  UNIQUE INDEX idx_state_3_code (virtuemart_country_id, state_3_code)
)
ENGINE = MYISAM
AUTO_INCREMENT = 730
AVG_ROW_LENGTH = 258
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'States that are assigned to a country';