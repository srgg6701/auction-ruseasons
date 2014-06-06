SELECT
  prods_ruru.virtuemart_product_id,
  prods_ruru.product_name,
  auc13_virtuemart_product_prices.product_price,
  cats.virtuemart_category_id,
  cats_ruru.category_name,
  CONCAT(cat_cats.category_parent_id, ':', (SELECT
      cats_ruru.category_name
    FROM auc13_virtuemart_categories_ru_ru AS cats_ruru
    WHERE cats_ruru.virtuemart_category_id = cat_cats.category_parent_id)) AS 'parent_name'
FROM auc13_virtuemart_categories cats
  INNER JOIN auc13_virtuemart_category_categories AS cat_cats
    ON cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_product_categories  AS prod_cats
    ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id
    AND prod_cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_categories_ru_ru    AS cats_ruru
    ON cats.virtuemart_category_id = cats_ruru.virtuemart_category_id
  INNER JOIN auc13_virtuemart_products_ru_ru      AS prods_ruru
    ON prods_ruru.virtuemart_product_id = prod_cats.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_prices
    ON auc13_virtuemart_product_prices.virtuemart_product_id = prods_ruru.virtuemart_product_id