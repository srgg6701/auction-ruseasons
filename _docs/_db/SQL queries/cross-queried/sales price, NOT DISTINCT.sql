SELECT *
FROM auctionru_2013.auc13_dev_sales_price
GROUP BY virtuemart_product_id
HAVING count(*) > 1
ORDER BY id;