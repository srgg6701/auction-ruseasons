
SELECT
  cats.virtuemart_category_id
FROM auc13_virtuemart_category_categories cat_cats
  LEFT OUTER JOIN auc13_virtuemart_categories cats
    ON cat_cats.id = cats.virtuemart_category_id
  LEFT OUTER JOIN auc13_virtuemart_categories_ru_ru cats_ru
    ON cats_ru.virtuemart_category_id = cat_cats.id 
   AND cats_ru.virtuemart_category_id = cats.virtuemart_category_id
WHERE cat_cats.category_parent_id = 23 AND cats.virtuemart_category_id > 0
 ORDER BY cats.virtuemart_category_id