--
-- Описание для таблицы auc13_modules_menu
--
CREATE TABLE IF NOT EXISTS auc13_modules_menu (
  moduleid int(11) NOT NULL DEFAULT 0,
  menuid int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (moduleid, menuid)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 9
character SET utf8
COLLATE utf8_general_ci;