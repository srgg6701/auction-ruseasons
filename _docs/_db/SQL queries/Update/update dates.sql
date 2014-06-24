-- SELECT REPLACE('www.mysql.com', 'w', 'Ww') AS result;
  UPDATE 
    auc13_virtuemart_product_prices 
    SET product_price_publish_up = replace(product_price_publish_up, "2014-07-20", "2014-05-10") -- ,
        -- product_price_publish_down = replace(product_price_publish_down, "2014-00-00", "2014-08-10")
    
    -- WHERE virtuemart_product_price_id = 2693

    /* auc13_virtuemart_products
    
    SET     product_available_date = 
    replace(product_available_date, "0000-00-00", "2014-07-25"),
            auction_date_finish = 
    replace(auction_date_finish, "0000-00-00", "2014-08-01") */
    