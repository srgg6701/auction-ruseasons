SELECT `auc13_menu`.id , cats.category_layout
  FROM  `auc13_menu`, auc13_virtuemart_categories cats
 WHERE  `menutype` =  'mainmenu'
   AND link REGEXP  '(^|/?|&|&)layout=shop($|&|&)'
            AND  cats.category_layout = 'shop'
        LIMIT 1