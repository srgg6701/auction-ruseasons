SELECT DISTINCT TABLE_NAME
FROM information_schema.`COLUMNS`
WHERE TABLE_SCHEMA="auctionru_2013" 
  AND COLUMN_NAME LIKE '%published%'
  AND TABLE_NAME LIKE '%virtuemart%'
  AND TABLE_NAME LIKE '%product%'
