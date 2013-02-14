--
-- Описание для таблицы auc13_updates
--
CREATE TABLE IF NOT EXISTS auc13_updates (
  update_id int(11) NOT NULL AUTO_INCREMENT,
  update_site_id int(11) DEFAULT 0,
  extension_id int(11) DEFAULT 0,
  categoryid int(11) DEFAULT 0,
  name varchar(100) DEFAULT '',
  description text NOT NULL,
  element varchar(100) DEFAULT '',
  type varchar(20) DEFAULT '',
  folder varchar(20) DEFAULT '',
  client_id tinyint(3) DEFAULT 0,
  version varchar(10) DEFAULT '',
  data text NOT NULL,
  detailsurl text NOT NULL,
  infourl text NOT NULL,
  PRIMARY KEY (update_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Available Updates';