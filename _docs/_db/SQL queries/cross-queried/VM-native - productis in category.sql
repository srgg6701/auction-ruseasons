      SELECT l.virtuemart_product_id 
        FROM auc13_virtuemart_products_ru_ru l
  INNER JOIN auc13_virtuemart_products                AS p
ON l.virtuemart_product_id = p.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_product_categories AS pc
ON p.virtuemart_product_id = pc.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_categories_ru_ru   AS c
ON c.virtuemart_category_id = pc.virtuemart_category_id
  LEFT OUTER JOIN auc13_virtuemart_product_shoppergroups
ON p.virtuemart_product_id = auc13_virtuemart_product_shoppergroups.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_shoppergroups      AS s
ON s.virtuemart_shoppergroup_id = auc13_virtuemart_product_shoppergroups.virtuemart_shoppergroup_id
       WHERE p.published = "1"
         AND p.product_in_stock - p.product_ordered > "0"
         AND pc.virtuemart_category_id = 21
         AND pc.virtuemart_category_id > 0
         AND (s.virtuemart_shoppergroup_id = "2" OR s.virtuemart_shoppergroup_id IS NULL)
    GROUP BY p.virtuemart_product_id
    ORDER BY l.product_name