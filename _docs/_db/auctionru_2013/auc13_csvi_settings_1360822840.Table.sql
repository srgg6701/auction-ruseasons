--
-- Описание для таблицы auc13_csvi_settings_1360822840
--
CREATE TABLE IF NOT EXISTS auc13_csvi_settings_1360822840 (
  id int(11) NOT NULL AUTO_INCREMENT,
  params text NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Configuration values for CSVI';