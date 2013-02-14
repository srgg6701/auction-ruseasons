--
-- Описание для таблицы auc13_messages
--
CREATE TABLE IF NOT EXISTS auc13_messages (
  message_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id_from int(10) UNSIGNED NOT NULL DEFAULT 0,
  user_id_to int(10) UNSIGNED NOT NULL DEFAULT 0,
  folder_id tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  date_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  state tinyint(1) NOT NULL DEFAULT 0,
  priority tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  subject varchar(255) NOT NULL DEFAULT '',
  message text NOT NULL,
  PRIMARY KEY (message_id),
  INDEX useridto_state (user_id_to, state)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;