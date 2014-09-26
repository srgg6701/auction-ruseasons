--
-- Описание для таблицы auc13_finder_tokens
--
CREATE TABLE IF NOT EXISTS auc13_finder_tokens (
  term varchar(75) NOT NULL,
  stem varchar(75) NOT NULL,
  common tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  phrase tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  weight float UNSIGNED NOT NULL DEFAULT 1,
  context tinyint(1) UNSIGNED NOT NULL DEFAULT 2,
  INDEX idx_context (context),
  INDEX idx_word (term)
)
ENGINE = MEMORY
AVG_ROW_LENGTH = 459
character SET utf8
COLLATE utf8_general_ci;