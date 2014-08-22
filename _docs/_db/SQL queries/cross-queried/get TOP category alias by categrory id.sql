SELECT cats.category_layout
      
      FROM auc13_virtuemart_category_categories AS cats_cats
INNER JOIN auc13_virtuemart_categories          AS cats 
           
           ON cats.virtuemart_category_id = cats_cats.category_child_id              
              AND cats_cats.category_parent_id = 21 LIMIT 1;