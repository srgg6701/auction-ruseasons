SELECT
  cats.virtuemart_category_id,
  cats_ruru.virtuemart_category_id AS '[ruru]',
  prod_cats.virtuemart_category_id AS '[prod_cats]',
  cat_cats.category_child_id AS '[cat_cats].child_id',
  cats_ruru.category_name,
  cat_cats.category_parent_id,
  ( SELECT category_name
  FROM auc13_virtuemart_categories_ru_ru
  WHERE virtuemart_category_id = cat_cats.category_parent_id
  ) AS 'parent_name',
  prod_cats.virtuemart_product_id
FROM auc13_virtuemart_categories cats
  INNER JOIN auc13_virtuemart_category_categories cat_cats
    ON cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_product_categories prod_cats
    ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id 
   AND prod_cats.virtuemart_category_id = cat_cats.category_child_id 
   AND prod_cats.virtuemart_product_id = 506
 INNER JOIN auc13_virtuemart_categories_ru_ru cats_ruru
    ON cats.virtuemart_category_id = cats_ruru.virtuemart_category_id