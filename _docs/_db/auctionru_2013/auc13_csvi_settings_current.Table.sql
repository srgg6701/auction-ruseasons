--
-- Описание для таблицы auc13_csvi_settings_current
--
CREATE TABLE IF NOT EXISTS auc13_csvi_settings_current (
  id int(11) NOT NULL AUTO_INCREMENT,
  params text NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 20
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Configuration values for CSVI';