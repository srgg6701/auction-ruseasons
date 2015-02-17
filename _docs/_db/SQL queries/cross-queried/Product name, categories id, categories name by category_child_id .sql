SELECT  prods.virtuemart_product_id,
        prod_ru.product_name,
        cat_cats.category_child_id,
        cat_ru.category_name,
        cat_cats.category_parent_id,
        cat_ru2.category_name
--        cats.virtuemart_category_id AS cats_cat_id -- ,
--      prod_cats.virtuemart_category_id AS prod_cat_id 
 FROM auc13_virtuemart_products prods
  INNER JOIN auc13_virtuemart_product_categories prod_cats
          ON prods.virtuemart_product_id = prod_cats.virtuemart_product_id
   
  INNER JOIN auc13_virtuemart_categories cats
          ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id
  
  INNER JOIN auc13_virtuemart_category_categories cat_cats
          ON cat_cats.category_child_id = prod_cats.virtuemart_category_id
  
  -- // ru
  INNER JOIN auc13_virtuemart_products_ru_ru prod_ru
          ON prod_ru.virtuemart_product_id = prods.virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru cat_ru
          ON cat_ru.virtuemart_category_id = cats.virtuemart_category_id
  INNER JOIN auc13_virtuemart_categories_ru_ru cat_ru2
          ON cat_ru2.virtuemart_category_id = cat_cats.category_parent_id
  
  AND cats.virtuemart_category_id = cat_cats.id
WHERE cat_cats.category_parent_id = (
          SELECT cat_cats1.category_parent_id
            FROM auc13_virtuemart_category_categories cat_cats1
           WHERE cat_cats1.category_child_id = 13
        )
   AND  prod_cats.virtuemart_category_id = 13
  