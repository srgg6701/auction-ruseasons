--
-- Описание для таблицы auc13_user_profiles
--
CREATE TABLE IF NOT EXISTS auc13_user_profiles (
  user_id int(11) NOT NULL,
  profile_key varchar(100) NOT NULL,
  profile_value varchar(255) NOT NULL,
  ordering int(11) NOT NULL DEFAULT 0,
  UNIQUE INDEX idx_user_id_profile_key (user_id, profile_key)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Simple user profile storage table';