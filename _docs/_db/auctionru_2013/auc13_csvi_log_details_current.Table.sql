--
-- Описание для таблицы auc13_csvi_log_details_current
--
CREATE TABLE IF NOT EXISTS auc13_csvi_log_details_current (
  id int(11) NOT NULL AUTO_INCREMENT,
  log_id int(11) NOT NULL,
  line int(11) NOT NULL,
  description text NOT NULL,
  result varchar(45) NOT NULL,
  status varchar(45) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 22
AVG_ROW_LENGTH = 132
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Log details for CSVI';