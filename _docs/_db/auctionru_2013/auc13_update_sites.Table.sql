--
-- Описание для таблицы auc13_update_sites
--
CREATE TABLE IF NOT EXISTS auc13_update_sites (
  update_site_id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) DEFAULT '',
  type varchar(20) DEFAULT '',
  location text NOT NULL,
  enabled int(11) DEFAULT 0,
  last_check_timestamp bigint(20) DEFAULT 0,
  PRIMARY KEY (update_site_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 108
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Update Sites';