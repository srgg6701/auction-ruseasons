SELECT cats.virtuemart_category_id,
        (   SELECT count(p.virtuemart_product_id)
              FROM `auc13_virtuemart_products` AS p,
                   `auc13_virtuemart_product_categories` AS pc
             WHERE pc.`virtuemart_category_id` = cats.virtuemart_category_id
               AND p.`virtuemart_product_id` = pc.`virtuemart_product_id`
               AND p.`published` = "1"
               AND p.`product_in_stock` > 0
        ) AS "product_count"
   FROM auc13_virtuemart_categories AS cats
   WHERE cats.`published` = "1"
               AND cats.virtuemart_category_id = 6
