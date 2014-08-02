SELECT  -- ccats.category_parent_id, 
        ccats.category_child_id 
  FROM auc13_virtuemart_category_categories AS ccats, 
       auc13_virtuemart_categories          AS cts
 WHERE category_parent_id IN
      ( SELECT cats_cats.category_child_id 
          FROM auc13_virtuemart_category_categories AS cats_cats
    INNER JOIN auc13_virtuemart_categories          AS cats 
               ON cats.virtuemart_category_id = cats_cats.category_child_id
                  AND cats_cats.category_parent_id = 0 )
   AND ccats.category_child_id = cts.virtuemart_category_id
   AND cts.category_layout = 'online'
 -- LIMIT 1