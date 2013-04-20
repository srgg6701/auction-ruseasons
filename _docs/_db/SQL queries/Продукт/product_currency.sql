SELECT currency_symbol
FROM auc13_virtuemart_currencies, auc13_virtuemart_product_prices
WHERE virtuemart_product_id = 1717 
  AND virtuemart_currency_id = product_currency