SELECT
  auc13_virtuemart_categories.virtuemart_category_id,
  auc13_virtuemart_categories_ru_ru.virtuemart_category_id,
  auc13_virtuemart_categories_ru_ru.category_name,
  auc13_virtuemart_category_categories.category_parent_id,
  auc13_virtuemart_category_categories.category_child_id,
  auc13_virtuemart_category_categories.id
FROM auc13_virtuemart_categories
  INNER JOIN auc13_virtuemart_categories_ru_ru
    ON auc13_virtuemart_categories.virtuemart_category_id = auc13_virtuemart_categories_ru_ru.virtuemart_category_id
  INNER JOIN auc13_virtuemart_category_categories
    ON auc13_virtuemart_categories.virtuemart_category_id = auc13_virtuemart_category_categories.category_child_id