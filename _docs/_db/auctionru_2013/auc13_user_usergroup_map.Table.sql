--
-- Описание для таблицы auc13_user_usergroup_map
--
CREATE TABLE IF NOT EXISTS auc13_user_usergroup_map (
  user_id int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Foreign Key to #__users.id',
  group_id int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Foreign Key to #__usergroups.id',
  PRIMARY KEY (user_id, group_id)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 9
character SET utf8
COLLATE utf8_general_ci;