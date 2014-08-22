SELECT DISTINCT
  prices.virtuemart_product_id,
  prices.product_price_publish_up,
  prices.product_price_publish_down

FROM auc13_virtuemart_product_categories            cats
  INNER JOIN auc13_virtuemart_category_categories   cat_cats
    ON cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_product_prices        prices
    ON prices.virtuemart_product_id = cats.virtuemart_product_id
WHERE cat_cats.category_parent_id = 21
  AND prices.product_price_publish_up < NOW() 
  AND prices.product_price_publish_down > NOW()
ORDER BY prices.product_price_publish_up