SELECT
  cats_cats.id AS category_id,
  -- cats_ru.category_name,
  -- cats_ru.slug,
  -- cats_cats.category_parent_id,
  cats.category_layout AS parent_alias
FROM auc13_virtuemart_categories                  AS cats
  INNER JOIN auc13_virtuemart_categories_ru_ru    AS cats_ru
    ON cats.virtuemart_category_id = cats_ru.virtuemart_category_id
  INNER JOIN auc13_virtuemart_category_categories AS cats_cats
    ON cats.virtuemart_category_id = cats_cats.category_child_id