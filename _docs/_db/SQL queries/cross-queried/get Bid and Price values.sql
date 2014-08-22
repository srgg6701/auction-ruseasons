SELECT
  prices.virtuemart_product_id,
  TRUNCATE(prices.product_price,0) AS price,
  MAX(sum) AS bid_value
     FROM auc13_virtuemart_product_prices AS prices  
LEFT JOIN auc13_dev_bids AS bids
    ON prices.virtuemart_product_id = bids.virtuemart_product_id
  WHERE prices.virtuemart_product_id = 2702