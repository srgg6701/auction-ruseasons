SELECT -- *
  MAX(sum) AS user_max_bid 
  FROM auc13_dev_bids 
  WHERE bidder_user_id = 386 AND virtuemart_product_id = 2702
    ORDER BY bidder_user_id, sum DESC