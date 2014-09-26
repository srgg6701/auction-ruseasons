--
-- Описание для таблицы auc13_newsfeeds
--
CREATE TABLE IF NOT EXISTS auc13_newsfeeds (
  catid int(11) NOT NULL DEFAULT 0,
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL DEFAULT '',
  alias varchar(255) binary character SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  link varchar(200) NOT NULL DEFAULT '',
  filename varchar(200) DEFAULT NULL,
  published tinyint(1) NOT NULL DEFAULT 0,
  numarticles int(10) UNSIGNED NOT NULL DEFAULT 1,
  cache_time int(10) UNSIGNED NOT NULL DEFAULT 3600,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ordering int(11) NOT NULL DEFAULT 0,
  rtl tinyint(4) NOT NULL DEFAULT 0,
  access int(10) UNSIGNED NOT NULL DEFAULT 0,
  language char(7) NOT NULL DEFAULT '',
  params text NOT NULL,
  created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(10) UNSIGNED NOT NULL DEFAULT 0,
  created_by_alias varchar(255) NOT NULL DEFAULT '',
  modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(10) UNSIGNED NOT NULL DEFAULT 0,
  metakey text NOT NULL,
  metadesc text NOT NULL,
  metadata text NOT NULL,
  xreference varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  publish_up datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_down datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id),
  INDEX idx_access (access),
  INDEX idx_catid (catid),
  INDEX idx_checkout (checked_out),
  INDEX idx_createdby (created_by),
  INDEX idx_language (language),
  INDEX idx_state (published),
  INDEX idx_xreference (xreference)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;