--
-- Описание для таблицы auc13_virtuemart_configs
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_configs (
  virtuemart_config_id tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  config text DEFAULT NULL,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_config_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 5124
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Holds configuration settings';