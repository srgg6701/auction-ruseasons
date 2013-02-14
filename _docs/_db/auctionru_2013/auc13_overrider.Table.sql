--
-- Описание для таблицы auc13_overrider
--
CREATE TABLE IF NOT EXISTS auc13_overrider (
  id int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  constant varchar(255) NOT NULL,
  string text NOT NULL,
  file varchar(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;