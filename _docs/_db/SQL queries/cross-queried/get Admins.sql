SELECT
  -- users.id,
  -- users.username,
  users.email,
  users.sendEmail,
  users.name,
  users.middlename,
  users.lastname
      FROM auc13_user_usergroup_map AS users_map
INNER JOIN auc13_usergroups         AS usergroups
           ON users_map.group_id = usergroups.id
              AND ( usergroups.title = 'Super Users'
                    OR usergroups.title = 'Administrator' )
INNER JOIN auc13_users              AS users
           ON users_map.user_id = users.id 
              AND sendEmail = 1