--
-- Описание для таблицы auc13_content_rating
--
CREATE TABLE IF NOT EXISTS auc13_content_rating (
  content_id int(11) NOT NULL DEFAULT 0,
  rating_sum int(10) UNSIGNED NOT NULL DEFAULT 0,
  rating_count int(10) UNSIGNED NOT NULL DEFAULT 0,
  lastip varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (content_id)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;