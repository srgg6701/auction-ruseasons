--
-- Описание для таблицы auc13_finder_types
--
CREATE TABLE IF NOT EXISTS auc13_finder_types (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL,
  mime varchar(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX title (title)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;