SET @product_id = 2762;
DELETE FROM auc13_dev_bids WHERE virtuemart_product_id = @product_id; 
DELETE FROM auc13_dev_user_bids WHERE virtuemart_product_id = @product_id;