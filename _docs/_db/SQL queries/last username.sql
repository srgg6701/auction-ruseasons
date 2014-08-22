SELECT MAX(username) AS username
FROM auc13_users 
WHERE username REGEXP '[0-9]{1,10}';