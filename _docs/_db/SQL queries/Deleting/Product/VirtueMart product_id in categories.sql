SELECT
  PRODS.virtuemart_product_id
  -- auc13_virtuemart_product_categories.virtuemart_product_id
FROM auc13_virtuemart_products PRODS
  JOIN auc13_virtuemart_product_categories CATS
    ON PRODS.virtuemart_product_id = CATS.virtuemart_product_id
WHERE CATS.virtuemart_category_id IN (1,9,10,6) 