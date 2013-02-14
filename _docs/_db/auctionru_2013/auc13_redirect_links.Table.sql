--
-- Описание для таблицы auc13_redirect_links
--
CREATE TABLE IF NOT EXISTS auc13_redirect_links (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  old_url varchar(255) NOT NULL,
  new_url varchar(255) NOT NULL,
  referer varchar(150) NOT NULL,
  comment varchar(255) NOT NULL,
  hits int(10) UNSIGNED NOT NULL DEFAULT 0,
  published tinyint(4) NOT NULL,
  created_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id),
  INDEX idx_link_modifed (modified_date),
  UNIQUE INDEX idx_link_old (old_url)
)
ENGINE = MYISAM
AUTO_INCREMENT = 19
AVG_ROW_LENGTH = 106
character SET utf8
COLLATE utf8_general_ci;