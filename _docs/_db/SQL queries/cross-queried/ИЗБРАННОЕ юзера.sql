SELECT
  auc13_product_favorites.virtuemart_product_id,
  auc13_virtuemart_products_ru_ru.product_name,
  auc13_virtuemart_products.auction_date_start,
  auc13_virtuemart_products.auction_date_finish,
  auc13_virtuemart_product_prices.product_price,
  auc13_virtuemart_products_ru_ru.slug,
  auc13_virtuemart_product_categories.virtuemart_category_id,
  auc13_virtuemart_product_categories.virtuemart_product_id
FROM auc13_virtuemart_products_ru_ru
  INNER JOIN auc13_product_favorites
    ON auc13_virtuemart_products_ru_ru.virtuemart_product_id = auc13_product_favorites.virtuemart_product_id
  INNER JOIN auc13_virtuemart_products
    ON auc13_virtuemart_products.virtuemart_product_id = auc13_product_favorites.virtuemart_product_id AND auc13_virtuemart_products.virtuemart_product_id = auc13_virtuemart_products_ru_ru.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_prices
    ON auc13_virtuemart_product_prices.virtuemart_product_id = auc13_virtuemart_products_ru_ru.virtuemart_product_id AND auc13_virtuemart_product_prices.virtuemart_product_id = auc13_virtuemart_products.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories
    ON auc13_product_favorites.virtuemart_product_id = auc13_virtuemart_product_categories.virtuemart_product_id
WHERE auc13_product_favorites.user_id = 204