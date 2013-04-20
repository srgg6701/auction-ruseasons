<?php
// No direct access
defined('_JEXEC') or die('Restricted access'); 
/**
 * получим методы основного класса:
 */
require_once JPATH_SITE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';

class modVlotscatsHelper extends JModuleHelper
{	
 /**
 * Извлечь данные категорий верхнего уровня
 * @package
 * @subpackage
 */
	function getTopCategories($published=false,$db=false){
		$query='SELECT cats.virtuemart_category_id, 
        cats_ru.category_name,
		cats_ru.slug AS "alias"
FROM #__virtuemart_categories AS cats
LEFT JOIN #__virtuemart_categories_ru_ru AS cats_ru 
    ON  cats_ru.virtuemart_category_id = cats.virtuemart_category_id
LEFT JOIN #__virtuemart_category_categories AS cat_cats 
    ON  cat_cats.id = cats.virtuemart_category_id
WHERE cat_cats.category_parent_id = 0';
		if($published)
			$query.=' AND cats.`published` = "1"';
		$query.='
ORDER BY cats.ordering';  // echo "<div class=''><hr>getTopCategories()<pre><b>query:</b> ".str_replace("#_","auc13",$query)."</pre><hr></div>"; //die();

		if(!$db) 
			$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 
	}
/**
 * Извлечь все (или только опубликованные) категории
 * @package
 * @subpackage
 */
	function getCategoriesData($published=false,$db=false,$inStockOnly=true){
		if (!$db)
			$db=JFactory::getDBO();
		
		$session =& JFactory::getSession();
		$prods=array();
		$session->set('products_data',$prods);
		
		$top_cats=modVlotscatsHelper::getTopCategories($db);
		//var_dump($top_cats); die();
	/*	array
		  0 => 
			array
			  'virtuemart_category_id' => string '21' (length=2)
			  'category_name' => string 'Онлайн торги' (length=23)
			  'alias' => string 'onlajn-torgi' (length=12)
		  1 => 
			array
			  'virtuemart_category_id' => string '22' (length=2)
			  'category_name' => string 'Очные торги' (length=21)
			  'alias' => string 'торги-в-помещении' (length=32)
		  2 => 
			array
			  'virtuemart_category_id' => string '23' (length=2)
			  'category_name' => string 'Магазин' (length=14)
			  'alias' => string 'магазин' (length=14)
			*/
		$topLayouts=AuctionStuff::getTopCatsLayouts();
		//var_dump($topLayouts); die();
	/*	array
		  0 => string 'online' (length=6)
		  1 => string 'fulltime' (length=8)
		  2 => string 'shop' (length=4)
			*/
			
			$query='SELECT cats.virtuemart_category_id, 
        cats_ru.category_name,
        cats_ru.slug AS "alias",
        (   SELECT count(p.virtuemart_product_id)
              FROM `#__virtuemart_products` AS p,
                   `#__virtuemart_product_categories` AS pc
             WHERE pc.`virtuemart_category_id` = cats.virtuemart_category_id
               AND p.`virtuemart_product_id` = pc.`virtuemart_product_id`';
			if($published){
				$query.='
               AND p.`published` = "1"';
				$pub=' 
               AND cats.`published` = "1"';
			}else{
				$pub='';
			}
			if($inStockOnly)
				$query.='
               AND p.`product_in_stock` > 0';
			// НЕ отображать проданные предметы!:
			$query.='
               AND ( SELECT COUNT(*) FROM #__dev_sales_price 
                      WHERE virtuemart_product_id = p.virtuemart_product_id 
                   ) = 0 ';
			$query.='	  
        ) AS "product_count"
   FROM #__virtuemart_categories AS cats
   LEFT JOIN #__virtuemart_categories_ru_ru AS cats_ru 
     ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
   LEFT JOIN #__virtuemart_category_categories AS cat_cats 
     ON cat_cats.id = cats.virtuemart_category_id
  WHERE cat_cats.category_parent_id = ';

			$order='
  ORDER BY cat_cats.category_parent_id,cats.ordering';
		foreach($top_cats as $i=>$top_cat){
			$prods[$topLayouts[$i]]=array();
			$q = $query .
				 $top_cat['virtuemart_category_id'] .
				 $pub .
				 $order;
			//echo "<div class=''><hr><pre>q= ".str_replace("#_","auc13",$q)."</pre><hr></div>"; 
			$db->setQuery($q);
			$children=$db->loadAssocList();
			//var_dump($children); 
			$records[$top_cat['virtuemart_category_id']]=array(
						'top_category_alias'=>$top_cat['alias'],
						'top_category_name'=>$top_cat['category_name'],
						'top_category_layout' => $topLayouts[$i],
						'children'=>$children
				);
			$prods[$topLayouts[$i]]['prod_count']=0;
			//if($children)
				foreach ($children as $c=>$child){
					if ($child['alias']){
						$prods[$topLayouts[$i]]['prod_count']+=(int)$child['product_count'];
						$prods[$topLayouts[$i]][$child['alias']]=$child;
					}
				}
		}
		$session->set('products_data',$prods); //die();
		return $records;
	}
}
