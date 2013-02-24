SELECT cats_ru_ru.virtuemart_category_id,
       cats_ru_ru.category_name,
        ( SELECT  category_name FROM auc13_virtuemart_categories_ru_ru
            WHERE virtuemart_category_id = (
              SELECT category_parent_id FROM auc13_virtuemart_category_categories
              WHERE category_child_id = cats_ru_ru.virtuemart_category_id
            )
        )  AS parent_section
FROM auc13_virtuemart_categories_ru_ru AS cats_ru_ru
WHERE cats_ru_ru.virtuemart_category_id = 1