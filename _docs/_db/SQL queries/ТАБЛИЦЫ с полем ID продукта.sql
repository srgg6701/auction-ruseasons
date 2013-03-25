SELECT DISTINCT TABLE_NAME
FROM information_schema.`COLUMNS`
WHERE TABLE_SCHEMA = "auctionru_2013" 
  AND ( 
  COLUMN_NAME LIKE '%product_id%'
  OR
  COLUMN_NAME LIKE '%products_id%'
  )
