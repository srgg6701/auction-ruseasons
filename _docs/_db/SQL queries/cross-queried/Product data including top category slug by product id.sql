/* SELECT
  prod.*,
  sales_prices.sales_price      AS minimal_price,
  ( SELECT slug
      FROM auc13_virtuemart_category_categories AS cats_cats
 LEFT JOIN auc13_virtuemart_categories_ru_ru    AS cats_ruru 
           ON cats_ruru.virtuemart_category_id = cats_cats.id
     WHERE id IN ( SELECT virtuemart_category_id 
                    FROM auc13_virtuemart_product_categories
                  WHERE virtuemart_product_id = 2702 ) -- 14, 33
       AND category_parent_id = 0 ) AS top_category_slug
FROM auc13_virtuemart_product_prices  AS prod
  LEFT JOIN auc13_dev_sales_price     AS sales_prices
    ON sales_prices.virtuemart_product_id = prod.virtuemart_product_id
WHERE prod.virtuemart_product_id = 2702 */

  /*SELECT virtuemart_category_id 
                    FROM auc13_virtuemart_product_categories
                  WHERE virtuemart_product_id = 2702*/
  SELECT slug
      FROM auc13_virtuemart_category_categories AS cats_cats
 LEFT JOIN auc13_virtuemart_categories_ru_ru    AS cats_ruru 
           ON cats_ruru.virtuemart_category_id = cats_cats.id
     WHERE id IN (14,33) 