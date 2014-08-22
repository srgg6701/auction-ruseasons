SELECT
  auc13_menu.id,
  auc13_menu.menutype,
  auc13_menu.title,
  auc13_menu.alias,
  auc13_menu.path,
  auc13_menu.link,
  auc13_menu.type,
  auc13_menu.parent_id,
  auc13_menu.component_id
FROM auc13_menu
WHERE auc13_menu.menutype = 'usermenu'