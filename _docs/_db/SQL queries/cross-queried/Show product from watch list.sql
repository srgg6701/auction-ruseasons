SET @notify_id = 11;
SELECT
  prod_ru.virtuemart_product_id,
  prod_ru.product_name,
  cats.virtuemart_category_id   AS category_id,
  cats_ru_parent.category_name  AS parent_category_name,
  cats_ru.category_name  AS category_name
FROM auc13_virtuemart_products_ru_ru AS prod_ru
  
  LEFT OUTER JOIN auc13_virtuemart_product_categories AS cats
    ON cats.virtuemart_product_id = prod_ru.virtuemart_product_id

  LEFT JOIN auc13_virtuemart_category_categories AS cats_cats
    ON cats_cats.category_child_id = cats.virtuemart_category_id
  
  LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru_parent
    ON cats_ru_parent.virtuemart_category_id = cats_cats.category_parent_id
  
  LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id

WHERE prod_ru.product_name LIKE CONCAT('%', (SELECT
    auc13_dev_product_notify.name
  FROM auc13_dev_product_notify
  WHERE auc13_dev_product_notify.id = @notify_id), '%')
OR prod_ru.product_s_desc LIKE CONCAT('%', (SELECT
    auc13_dev_product_notify.name
  FROM auc13_dev_product_notify
  WHERE auc13_dev_product_notify.id = @notify_id), '%')

  ORDER BY prod_ru.product_name