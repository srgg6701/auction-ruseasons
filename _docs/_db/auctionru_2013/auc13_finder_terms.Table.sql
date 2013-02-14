--
-- Описание для таблицы auc13_finder_terms
--
CREATE TABLE IF NOT EXISTS auc13_finder_terms (
  term_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  term varchar(75) NOT NULL,
  stem varchar(75) NOT NULL,
  common tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  phrase tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  weight float UNSIGNED NOT NULL DEFAULT 0,
  soundex varchar(75) NOT NULL,
  links int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (term_id),
  INDEX idx_soundex_phrase (soundex, phrase),
  INDEX idx_stem_phrase (stem, phrase),
  UNIQUE INDEX idx_term (term),
  INDEX idx_term_phrase (term, phrase)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;