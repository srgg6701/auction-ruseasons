--
-- Описание для таблицы auc13_banner_clients
--
CREATE TABLE IF NOT EXISTS auc13_banner_clients (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  contact varchar(255) NOT NULL DEFAULT '',
  email varchar(255) NOT NULL DEFAULT '',
  extrainfo text NOT NULL,
  state tinyint(3) NOT NULL DEFAULT 0,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  metakey text NOT NULL,
  own_prefix tinyint(4) NOT NULL DEFAULT 0,
  metakey_prefix varchar(255) NOT NULL DEFAULT '',
  purchase_type tinyint(4) NOT NULL DEFAULT - 1,
  track_clicks tinyint(4) NOT NULL DEFAULT - 1,
  track_impressions tinyint(4) NOT NULL DEFAULT - 1,
  PRIMARY KEY (id),
  INDEX idx_metakey_prefix (metakey_prefix),
  INDEX idx_own_prefix (own_prefix)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;