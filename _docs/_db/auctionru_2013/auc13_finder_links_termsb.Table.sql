--
-- Описание для таблицы auc13_finder_links_termsb
--
CREATE TABLE IF NOT EXISTS auc13_finder_links_termsb (
  link_id int(10) UNSIGNED NOT NULL,
  term_id int(10) UNSIGNED NOT NULL,
  weight float UNSIGNED NOT NULL,
  PRIMARY KEY (link_id, term_id),
  INDEX idx_link_term_weight (link_id, term_id, weight),
  INDEX idx_term_weight (term_id, weight)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;