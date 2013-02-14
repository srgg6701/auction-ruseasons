--
-- Описание для таблицы auc13_geodesic_logins
--
CREATE TABLE IF NOT EXISTS auc13_geodesic_logins (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(25) NOT NULL,
  password varchar(64) NOT NULL,
  status tinyint(4) NOT NULL DEFAULT 1,
  api_token varchar(40) NOT NULL,
  PRIMARY KEY (id),
  INDEX api_token (api_token),
  UNIQUE INDEX username (username)
)
ENGINE = MYISAM
AUTO_INCREMENT = 245
AVG_ROW_LENGTH = 27
character SET utf8
COLLATE utf8_general_ci;