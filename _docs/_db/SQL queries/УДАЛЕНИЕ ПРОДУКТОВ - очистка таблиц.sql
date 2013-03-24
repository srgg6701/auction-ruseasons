SELECT DISTINCT
  prods.virtuemart_product_id,
  prod_cats.virtuemart_category_id,
  prods_ruru.product_name,
  auc13_virtuemart_categories_ru_ru.category_name,
  (SELECT
    auc13_virtuemart_category_categories.category_parent_id
  FROM auc13_virtuemart_category_categories
  WHERE auc13_virtuemart_category_categories.category_child_id = prod_cats.virtuemart_category_id) 
  AS 'Магазин category_id'
FROM auc13_virtuemart_categories_ru_ru cats_ruru,
     auc13_virtuemart_products prods
       LEFT OUTER JOIN auc13_virtuemart_product_prices
         ON prods.virtuemart_product_id = auc13_virtuemart_product_prices.virtuemart_product_id
       LEFT OUTER JOIN auc13_virtuemart_products_ru_ru prods_ruru
         ON prods.virtuemart_product_id = prods_ruru.virtuemart_product_id AND prods_ruru.virtuemart_product_id = auc13_virtuemart_product_prices.virtuemart_product_id
       INNER JOIN auc13_virtuemart_product_categories prod_cats
         ON prods.virtuemart_product_id = prod_cats.virtuemart_product_id
       INNER JOIN auc13_virtuemart_categories_ru_ru
         ON prod_cats.virtuemart_category_id = auc13_virtuemart_categories_ru_ru.virtuemart_category_id
WHERE (SELECT
  auc13_virtuemart_categories_ru_ru.category_name
FROM auc13_virtuemart_categories_ru_ru
  INNER JOIN auc13_virtuemart_category_categories
    ON auc13_virtuemart_categories_ru_ru.virtuemart_category_id = auc13_virtuemart_category_categories.category_parent_id
WHERE auc13_virtuemart_category_categories.category_child_id = prod_cats.virtuemart_category_id) = 'Магазин'