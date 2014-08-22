--
-- Описание для таблицы auc13_session
--
CREATE TABLE IF NOT EXISTS auc13_session (
  session_id varchar(200) NOT NULL DEFAULT '',
  client_id tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  guest tinyint(4) UNSIGNED DEFAULT 1,
  time varchar(14) DEFAULT '',
  data mediumtext DEFAULT NULL,
  userid int(11) DEFAULT 0,
  username varchar(150) DEFAULT '',
  usertype varchar(50) DEFAULT '',
  PRIMARY KEY (session_id),
  INDEX time (time),
  INDEX userid (userid),
  INDEX whosonline (guest, usertype)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 3308
character SET utf8
COLLATE utf8_general_ci;