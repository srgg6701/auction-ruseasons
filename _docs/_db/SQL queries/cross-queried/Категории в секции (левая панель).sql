SELECT cats.virtuemart_category_id, 
        cats_ru.category_name,
        cats_ru.slug AS "alias",
        (   SELECT count(p.virtuemart_product_id)
              FROM `auc13_virtuemart_products` AS p,
                   `auc13_virtuemart_product_categories` AS pc
             WHERE pc.`virtuemart_category_id` = cats.virtuemart_category_id
               AND p.`virtuemart_product_id` = pc.`virtuemart_product_id`
               AND p.`published` = "1"
               AND p.`product_in_stock` > 0
               AND p.virtuemart_product_id NOT IN ( 
                     IF ( (SELECT COUNT(*) FROM auc13_dev_sales_price),
                         (SELECT virtuemart_product_id FROM auc13_dev_sales_price),0 )
                  ) 
        ) AS "product_count"
   FROM auc13_virtuemart_categories AS cats
   LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru 
     ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
   LEFT JOIN auc13_virtuemart_category_categories AS cat_cats 
     ON cat_cats.id = cats.virtuemart_category_id
  WHERE cat_cats.category_parent_id = 23 
               AND cats.`published` = "1"
  ORDER BY cat_cats.category_parent_id,cats.ordering