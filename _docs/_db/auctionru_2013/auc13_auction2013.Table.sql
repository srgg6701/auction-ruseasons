--
-- Описание для таблицы auc13_auction2013
--
CREATE TABLE IF NOT EXISTS auc13_auction2013 (
  id int(11) NOT NULL AUTO_INCREMENT,
  abstract varchar(255) NOT NULL COMMENT 'Заготовка',
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Заготовка таблицы для модели';