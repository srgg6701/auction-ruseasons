SELECT prods3.virtuemart_product_id AS prod_id, 
  'before union', 
  'before union', 
  'before union', 
  'before union', 
  'before union'
  FROM `auc13_virtuemart_products` AS prods3
 WHERE (  prods3.virtuemart_product_id = ( 
		      SELECT MAX(prods1.virtuemart_product_id)
            FROM `auc13_virtuemart_products` AS prods1
           WHERE prods1.virtuemart_product_id < 5048
             AND prods1.virtuemart_product_id IN (
                 SELECT virtuemart_product_id 
                   FROM auc13_virtuemart_product_categories
                  WHERE virtuemart_category_id = 13
              )
        )  
        OR prods3.virtuemart_product_id = 5048
       AND prods3.virtuemart_product_id IN (
           SELECT virtuemart_product_id 
             FROM auc13_virtuemart_product_categories
            WHERE virtuemart_category_id = 13
           )
        OR prods3.virtuemart_product_id = ( 
           SELECT MIN(prods2.virtuemart_product_id)
             FROM `auc13_virtuemart_products` AS prods2
            WHERE prods2.virtuemart_product_id > 5048
              AND prods2.virtuemart_product_id IN (
                  SELECT virtuemart_product_id 
                    FROM auc13_virtuemart_product_categories
                   WHERE virtuemart_category_id = 13
                  )
        )
       )
  UNION 
        SELECT prods.virtuemart_product_id,
        
        prod_ru.product_name,
        cat_cats.category_child_id,
        cat_ru.category_name,
        cat_cats.category_parent_id,
        cat_ru2.category_name

 FROM `auc13_virtuemart_products` AS prods
  
  INNER JOIN `auc13_virtuemart_product_categories` AS prod_cats
          ON prods.virtuemart_product_id = prod_cats.virtuemart_product_id            
  INNER JOIN `auc13_virtuemart_category_categories` AS cat_cats
          ON prod_cats.virtuemart_category_id = cat_cats.category_child_id  
  INNER JOIN `auc13_virtuemart_categories` AS cats
          ON prod_cats.virtuemart_category_id = cats.virtuemart_category_id
  AND cats.virtuemart_category_id = cat_cats.id

  -- // 
  
  INNER JOIN `auc13_virtuemart_products_ru_ru` AS prod_ru
          ON prod_ru.virtuemart_product_id = prods.virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru AS cat_ru
          ON cat_ru.virtuemart_category_id = cats.virtuemart_category_id

  INNER JOIN auc13_virtuemart_categories_ru_ru cat_ru2
          ON cat_ru2.virtuemart_category_id = cat_cats.category_parent_id



WHERE cat_cats.category_parent_id = ( SELECT cat_cats1.category_parent_id
            FROM `auc13_virtuemart_category_categories` AS cat_cats1
           WHERE cat_cats1.category_child_id = 13
                                    )
   AND prod_cats.virtuemart_category_id = 13