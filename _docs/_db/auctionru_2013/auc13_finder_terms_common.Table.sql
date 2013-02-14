--
-- Описание для таблицы auc13_finder_terms_common
--
CREATE TABLE IF NOT EXISTS auc13_finder_terms_common (
  term varchar(75) NOT NULL,
  language varchar(3) NOT NULL,
  INDEX idx_lang (language),
  INDEX idx_word_lang (term, language)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 20
character SET utf8
COLLATE utf8_general_ci;