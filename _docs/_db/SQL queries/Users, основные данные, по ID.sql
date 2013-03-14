SELECT
  id,
  -- `name`,
  username,
  email,
  -- usertype,
  `activation`,
  `block`,
  sendEmail,
  registerDate,
  lastvisitDate,
  params,
  `password`,
  lastResetTime,
  resetCount
FROM auc13_users ORDER BY  id DESC;