SELECT prices.   virtuemart_product_id,
       prods_ru. product_name,
     ( SELECT CONCAT(cats_ruru_p.virtuemart_category_id,":",cats_ruru_p.category_name)
      FROM auc13_virtuemart_categories_ru_ru AS cats_ruru_p
     WHERE cats_ruru_p.virtuemart_category_id = (
                        SELECT category_parent_id 
                          FROM auc13_virtuemart_category_categories
                         WHERE id = cats_ruru.virtuemart_category_id 
                        )
          )  AS section, 
    CONCAT(cats_ruru.virtuemart_category_id,":",cats_ruru.category_name)
                                         AS category
      FROM auc13_virtuemart_products            AS p

 /*  ¿“≈√Œ–»»  */
 LEFT JOIN auc13_virtuemart_product_categories  AS pc
           ON p.virtuemart_product_id = pc.virtuemart_product_id
 
 /*  ¿“≈√Œ–»» ru */
 LEFT JOIN auc13_virtuemart_categories_ru_ru  AS cats_ruru
           ON cats_ruru.virtuemart_category_id = pc.virtuemart_category_id
 
 /* œ–Œƒ” “€ ru */
 LEFT JOIN auc13_virtuemart_products_ru_ru      AS prods_ru
           ON prods_ru.virtuemart_product_id = pc.virtuemart_product_id

 /*  ¿“≈√Œ–»»  ¿“≈√Œ–»…  */
 LEFT JOIN auc13_virtuemart_category_categories AS cat_cats
           ON pc.virtuemart_category_id = cat_cats.category_child_id

 /* œ–¿…—€  */
 LEFT JOIN auc13_virtuemart_product_prices      AS prices
           ON prices.virtuemart_product_id = pc.virtuemart_product_id

     WHERE ( cat_cats.category_parent_id =  21 
             OR cat_cats.category_parent_id = 23 )
 
           AND cat_cats.category_child_id = pc.virtuemart_category_id
           
           AND (
                p.`virtuemart_product_id` IN (
                   SELECT virtuemart_product_id
                     FROM auc13_dev_sold
                    WHERE `section` = 1 )
                OR
                p.`virtuemart_product_id` IN (
                   SELECT virtuemart_product_id
                     FROM auc13_dev_shop_orders )
            )

  ORDER BY prices.product_price_publish_up