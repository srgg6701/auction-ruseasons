--
-- Описание для таблицы auc13_messages_cfg
--
CREATE TABLE IF NOT EXISTS auc13_messages_cfg (
  user_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  cfg_name varchar(100) NOT NULL DEFAULT '',
  cfg_value varchar(255) NOT NULL DEFAULT '',
  UNIQUE INDEX idx_user_var_name (user_id, cfg_name)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;