SELECT cats.virtuemart_category_id AS vm_cat_id, 
        cats_ru.category_name
   FROM auc13_virtuemart_categories AS cats
   LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru 
     ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
               AND cats.`published` = "1"
ORDER BY vm_cat_id