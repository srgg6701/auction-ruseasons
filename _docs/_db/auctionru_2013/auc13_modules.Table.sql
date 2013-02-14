--
-- Описание для таблицы auc13_modules
--
CREATE TABLE IF NOT EXISTS auc13_modules (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL DEFAULT '',
  note varchar(255) NOT NULL DEFAULT '',
  content text NOT NULL,
  ordering int(11) NOT NULL DEFAULT 0,
  position varchar(50) NOT NULL DEFAULT '',
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_up datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_down datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  published tinyint(1) NOT NULL DEFAULT 0,
  module varchar(50) DEFAULT NULL,
  access int(10) UNSIGNED NOT NULL DEFAULT 0,
  showtitle tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  params text NOT NULL,
  client_id tinyint(4) NOT NULL DEFAULT 0,
  language char(7) NOT NULL,
  PRIMARY KEY (id),
  INDEX idx_language (language),
  INDEX newsfeeds (module, published),
  INDEX published (published, access)
)
ENGINE = MYISAM
AUTO_INCREMENT = 105
AVG_ROW_LENGTH = 538
character SET utf8
COLLATE utf8_general_ci;