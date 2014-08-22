--
-- Описание для таблицы auc13_banners
--
CREATE TABLE IF NOT EXISTS auc13_banners (
  id int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL DEFAULT 0,
  type int(11) NOT NULL DEFAULT 0,
  name varchar(255) NOT NULL DEFAULT '',
  alias varchar(255) binary character SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  imptotal int(11) NOT NULL DEFAULT 0,
  impmade int(11) NOT NULL DEFAULT 0,
  clicks int(11) NOT NULL DEFAULT 0,
  clickurl varchar(200) NOT NULL DEFAULT '',
  state tinyint(3) NOT NULL DEFAULT 0,
  catid int(10) UNSIGNED NOT NULL DEFAULT 0,
  description text NOT NULL,
  custombannercode varchar(2048) NOT NULL,
  sticky tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(11) NOT NULL DEFAULT 0,
  metakey text NOT NULL,
  params text NOT NULL,
  own_prefix tinyint(1) NOT NULL DEFAULT 0,
  metakey_prefix varchar(255) NOT NULL DEFAULT '',
  purchase_type tinyint(4) NOT NULL DEFAULT - 1,
  track_clicks tinyint(4) NOT NULL DEFAULT - 1,
  track_impressions tinyint(4) NOT NULL DEFAULT - 1,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_up datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_down datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  reset datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  language char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  INDEX idx_banner_catid (catid),
  INDEX idx_language (language),
  INDEX idx_metakey_prefix (metakey_prefix),
  INDEX idx_own_prefix (own_prefix),
  INDEX idx_state (state)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;