--
-- Описание для таблицы auc13_languages
--
CREATE TABLE IF NOT EXISTS auc13_languages (
  lang_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  lang_code char(7) NOT NULL,
  title varchar(50) NOT NULL,
  title_native varchar(50) NOT NULL,
  sef varchar(50) NOT NULL,
  image varchar(50) NOT NULL,
  description varchar(512) NOT NULL,
  metakey text NOT NULL,
  metadesc text NOT NULL,
  sitename varchar(1024) NOT NULL DEFAULT '',
  published int(11) NOT NULL DEFAULT 0,
  access int(10) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (lang_id),
  INDEX idx_access (access),
  UNIQUE INDEX idx_image (image),
  UNIQUE INDEX idx_langcode (lang_code),
  INDEX idx_ordering (ordering),
  UNIQUE INDEX idx_sef (sef)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 56
character SET utf8
COLLATE utf8_general_ci;