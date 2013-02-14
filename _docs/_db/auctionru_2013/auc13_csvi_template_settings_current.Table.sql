--
-- Описание для таблицы auc13_csvi_template_settings_current
--
CREATE TABLE IF NOT EXISTS auc13_csvi_template_settings_current (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the saved setting',
  name varchar(255) NOT NULL COMMENT 'Name for the saved setting',
  settings text NOT NULL COMMENT 'The actual settings',
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 6
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Stores the template settings for CSVI';