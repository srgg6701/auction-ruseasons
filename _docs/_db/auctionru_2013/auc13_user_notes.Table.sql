--
-- Описание для таблицы auc13_user_notes
--
CREATE TABLE IF NOT EXISTS auc13_user_notes (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  catid int(10) UNSIGNED NOT NULL DEFAULT 0,
  subject varchar(100) NOT NULL DEFAULT '',
  body text NOT NULL,
  state tinyint(3) NOT NULL DEFAULT 0,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_user_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  created_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_user_id int(10) UNSIGNED NOT NULL,
  modified_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  review_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_up datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_down datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id),
  INDEX idx_category_id (catid),
  INDEX idx_user_id (user_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;