SELECT cats.virtuemart_category_id, cat_cats.category_parent_id
  FROM auc13_virtuemart_categories AS cats,
       auc13_virtuemart_category_categories AS cat_cats
 WHERE cat_cats.category_child_id = cats.virtuemart_category_id
  AND cat_cats.category_parent_id IN ( 
    SELECT cat_cats1.category_parent_id
    FROM auc13_virtuemart_categories AS cats1
      INNER JOIN auc13_virtuemart_category_categories AS cat_cats1
        ON cats1.virtuemart_category_id = cat_cats1.id
    WHERE cats1.virtuemart_category_id = 9
) 
  AND 
    ( cats.virtuemart_category_id = (
        SELECT MAX(cats2.virtuemart_category_id)
          FROM auc13_virtuemart_categories AS cats2
         WHERE cats2.virtuemart_category_id < 9
      ) 
      OR cats.virtuemart_category_id = 9 
      OR cats.virtuemart_category_id = (
        SELECT MIN(cats2.virtuemart_category_id)
          FROM auc13_virtuemart_categories AS cats2
         WHERE cats2.virtuemart_category_id > 9
      )  
    )
  ORDER BY cats.virtuemart_category_id LIMIT 3
