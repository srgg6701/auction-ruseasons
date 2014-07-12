SET @user_id    = 385;
SET @product_id = 2702;
SELECT                           virtuemart_product_id,
  TRUNCATE(product_price,0)  AS  price,
  ( SELECT MAX(sum) FROM auc13_dev_bids 
      WHERE virtuemart_product_id = @product_id
            AND bidder_user_id = @user_id ) 
                                    AS  user_max_bid_value,
  ( SELECT sum FROM auc13_dev_bids 
      WHERE virtuemart_product_id = @product_id
      ORDER BY sum DESC LIMIT 1,1) 
                                    AS  second_max_bid_value
     FROM auc13_virtuemart_product_prices
WHERE virtuemart_product_id = @product_id