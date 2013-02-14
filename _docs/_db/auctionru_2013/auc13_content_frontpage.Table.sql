--
-- Описание для таблицы auc13_content_frontpage
--
CREATE TABLE IF NOT EXISTS auc13_content_frontpage (
  content_id int(11) NOT NULL DEFAULT 0,
  ordering int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (content_id)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;