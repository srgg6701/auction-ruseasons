--
-- Описание для таблицы auc13_acymailing_config
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_config (
  namekey varchar(200) NOT NULL,
  value text DEFAULT NULL,
  PRIMARY KEY (namekey)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 31
character SET utf8
COLLATE utf8_general_ci;