--
-- Описание для таблицы auc13_template_styles
--
CREATE TABLE IF NOT EXISTS auc13_template_styles (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  template varchar(50) NOT NULL DEFAULT '',
  client_id tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  home char(7) NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  params text NOT NULL,
  PRIMARY KEY (id),
  INDEX idx_home (home),
  INDEX idx_template (template)
)
ENGINE = MYISAM
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 116
character SET utf8
COLLATE utf8_general_ci;