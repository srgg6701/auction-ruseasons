SELECT
  cat_cats.id,
  cats.virtuemart_category_id,
  cats_ru.virtuemart_category_id,
  cat_cats.category_parent_id,
  cat_cats.category_child_id,
  cats_ru.category_name,
  cats.category_layout,
  cats_ru.category_description,
  cats_ru.slug
FROM auc13_virtuemart_category_categories cat_cats
  LEFT OUTER JOIN auc13_virtuemart_categories cats
    ON cat_cats.id = cats.virtuemart_category_id
  LEFT OUTER JOIN auc13_virtuemart_categories_ru_ru cats_ru
    ON cats_ru.virtuemart_category_id = cat_cats.id AND cats_ru.virtuemart_category_id = cats.virtuemart_category_id
ORDER BY cat_cats.category_parent_id, cats_ru.virtuemart_category_id