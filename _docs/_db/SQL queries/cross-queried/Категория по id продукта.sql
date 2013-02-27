SELECT
  cats.virtuemart_category_id,
  cat_cats.category_parent_id,
  prod_cats.virtuemart_product_id,
  prod_cats.virtuemart_category_id,
  cat_cats.category_child_id
FROM auc13_virtuemart_categories cats
  INNER JOIN auc13_virtuemart_category_categories cat_cats
    ON cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_product_categories prod_cats
    ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id 
   AND prod_cats.virtuemart_category_id = cat_cats.category_child_id
   AND prod_cats.virtuemart_product_id = 506