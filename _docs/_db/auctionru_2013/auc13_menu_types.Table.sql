--
-- Описание для таблицы auc13_menu_types
--
CREATE TABLE IF NOT EXISTS auc13_menu_types (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  menutype varchar(24) NOT NULL,
  title varchar(48) NOT NULL,
  description varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  UNIQUE INDEX idx_menutype (menutype)
)
ENGINE = MYISAM
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 88
character SET utf8
COLLATE utf8_general_ci;