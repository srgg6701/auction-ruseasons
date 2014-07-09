SELECT MAX(sum) AS max_bid_value, 
  prods.auction_date_finish AS date_finish,
  NOW() AS now,
  TRUNCATE((UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60,0) AS 'rest_in_minutes',
  FROM_UNIXTIME(UNIX_TIMESTAMP(prods.auction_date_finish)+5*60) AS '5_min_plus'
     FROM auc13_dev_bids AS bids
LEFT JOIN auc13_virtuemart_products AS prods
          ON prods.virtuemart_product_id = bids.virtuemart_product_id
WHERE prods.virtuemart_product_id = 2772