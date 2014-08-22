USE auctionru_2013;
SET @prd_id = 2702;
DELETE FROM 
auc13_dev_bids 
 -- WHERE virtuemart_product_id = @prd_id
  ;
DELETE FROM 
auc13_dev_user_bids 
 -- WHERE virtuemart_product_id = @prd_id
  ;