--
-- Описание для таблицы auc13_csvi_logs_1360822840
--
CREATE TABLE IF NOT EXISTS auc13_csvi_logs_1360822840 (
  id int(11) NOT NULL AUTO_INCREMENT,
  userid int(11) NOT NULL,
  logstamp datetime NOT NULL,
  action varchar(255) NOT NULL,
  action_type varchar(255) NOT NULL DEFAULT '',
  template_name varchar(255) DEFAULT NULL,
  records int(11) NOT NULL,
  run_id int(11) DEFAULT NULL,
  file_name varchar(255) DEFAULT NULL,
  run_cancelled tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Log results for CSVI';