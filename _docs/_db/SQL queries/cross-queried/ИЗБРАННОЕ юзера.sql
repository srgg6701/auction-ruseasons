SELECT
  auc13_product_favorites.virtuemart_product_id,
  product_name,
  auction_date_start,
  auction_date_finish,
  product_price
FROM auc13_virtuemart_products_ru_ru
  INNER JOIN auc13_product_favorites
    ON auc13_virtuemart_products_ru_ru.virtuemart_product_id = auc13_product_favorites.virtuemart_product_id
  INNER JOIN auc13_virtuemart_products
    ON auc13_virtuemart_products.virtuemart_product_id = auc13_product_favorites.virtuemart_product_id 
   AND auc13_virtuemart_products.virtuemart_product_id = auc13_virtuemart_products_ru_ru.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_prices
    ON auc13_virtuemart_product_prices.virtuemart_product_id = auc13_virtuemart_products_ru_ru.virtuemart_product_id AND auc13_virtuemart_product_prices.virtuemart_product_id = auc13_virtuemart_products.virtuemart_product_id
  WHERE user_id = 209