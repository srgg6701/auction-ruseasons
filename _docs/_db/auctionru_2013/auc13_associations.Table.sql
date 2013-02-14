--
-- Описание для таблицы auc13_associations
--
CREATE TABLE IF NOT EXISTS auc13_associations (
  id varchar(50) NOT NULL COMMENT 'A reference to the associated item.',
  context varchar(50) NOT NULL COMMENT 'The context of the associated item.',
  `key` char(32) NOT NULL COMMENT 'The key for the association computed from an md5 on associated ids.',
  PRIMARY KEY (context, id),
  INDEX idx_key (`key`)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;