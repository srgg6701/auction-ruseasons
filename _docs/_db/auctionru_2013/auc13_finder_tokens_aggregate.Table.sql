--
-- Описание для таблицы auc13_finder_tokens_aggregate
--
CREATE TABLE IF NOT EXISTS auc13_finder_tokens_aggregate (
  term_id int(10) UNSIGNED NOT NULL,
  map_suffix char(1) NOT NULL,
  term varchar(75) NOT NULL,
  stem varchar(75) NOT NULL,
  common tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  phrase tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  term_weight float UNSIGNED NOT NULL,
  context tinyint(1) UNSIGNED NOT NULL DEFAULT 2,
  context_weight float UNSIGNED NOT NULL,
  total_weight float UNSIGNED NOT NULL,
  INDEX keyword_id (term_id),
  INDEX token (term)
)
ENGINE = MEMORY
AVG_ROW_LENGTH = 474
character SET utf8
COLLATE utf8_general_ci;