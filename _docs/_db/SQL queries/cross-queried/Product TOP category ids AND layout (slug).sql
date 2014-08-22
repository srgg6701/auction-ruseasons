SELECT
  cats.virtuemart_category_id,
  LEFT((
  SUBSTRING(menu.link,
    LOCATE('&layout=', menu.link)+8)
            ), LOCATE('&virtuemart_category_id=', menu.link)-LOCATE('&layout=', menu.link)-8
  )
  AS 'layout'
FROM auc13_virtuemart_categories AS cats
  INNER JOIN auc13_virtuemart_category_categories AS cats_cats
    ON cats.virtuemart_category_id = cats_cats.category_child_id
  INNER JOIN auc13_menu AS menu
    ON menu.link LIKE CONCAT('%&virtuemart_category_id=',cats.virtuemart_category_id)
WHERE cats_cats.category_parent_id = 0