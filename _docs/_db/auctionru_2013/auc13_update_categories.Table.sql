--
-- Описание для таблицы auc13_update_categories
--
CREATE TABLE IF NOT EXISTS auc13_update_categories (
  categoryid int(11) NOT NULL AUTO_INCREMENT,
  name varchar(20) DEFAULT '',
  description text NOT NULL,
  parent int(11) DEFAULT 0,
  updatesite int(11) DEFAULT 0,
  PRIMARY KEY (categoryid)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Update Categories';