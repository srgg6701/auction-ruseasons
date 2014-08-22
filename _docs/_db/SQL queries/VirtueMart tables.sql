SELECT TABLE_NAME
FROM information_schema.`TABLES` 
WHERE 
  TABLE_SCHEMA =  "auctionru_2013"
AND TABLE_NAME LIKE 'auc13_virtuemart%'
LIMIT 0 , 30;