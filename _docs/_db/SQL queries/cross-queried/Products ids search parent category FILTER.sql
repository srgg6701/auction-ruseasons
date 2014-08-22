SELECT l.`virtuemart_product_id` 
FROM `auc13_virtuemart_products_ru_ru` as l 
JOIN `auc13_virtuemart_products` AS p using (`virtuemart_product_id`) 
LEFT JOIN `auc13_virtuemart_product_categories` as pc 
	ON p.`virtuemart_product_id` = `pc`.`virtuemart_product_id` 
LEFT JOIN `auc13_virtuemart_categories_ru_ru` as c 
	ON c.`virtuemart_category_id` = `pc`.`virtuemart_category_id` 
LEFT JOIN `auc13_virtuemart_product_shoppergroups` 
	ON p.`virtuemart_product_id` = `auc13_virtuemart_product_shoppergroups`.`virtuemart_product_id` 
LEFT OUTER JOIN `auc13_virtuemart_shoppergroups` as s 
	ON s.`virtuemart_shoppergroup_id` = `auc13_virtuemart_product_shoppergroups`.`virtuemart_shoppergroup_id` 
WHERE ( p.`published`="1" 
		AND `pc`.`virtuemart_category_id` > 0 
		AND ( s.`virtuemart_shoppergroup_id`= "1" 
			  OR 
			  s.`virtuemart_shoppergroup_id` IS NULL 
			)
    AND  p.`virtuemart_product_id` IN (
        SELECT
  cats.virtuemart_product_id
FROM auc13_virtuemart_product_categories AS cats
  INNER JOIN auc13_virtuemart_category_categories AS catcats
    ON cats.virtuemart_category_id = catcats.category_child_id
  WHERE category_parent_id = 23
      )
	  ) 
group by p.`virtuemart_product_id` 
ORDER BY product_name