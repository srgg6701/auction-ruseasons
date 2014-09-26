--
-- Описание для таблицы auc13_core_log_searches
--
CREATE TABLE IF NOT EXISTS auc13_core_log_searches (
  search_term varchar(128) NOT NULL DEFAULT '',
  hits int(10) UNSIGNED NOT NULL DEFAULT 0
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;