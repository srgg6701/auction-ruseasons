<?php
// No direct access
defined('_JEXEC') or die('Restricted access'); 
$virtuemart_path=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS;
require_once $virtuemart_path.'helpers'.DS.'vmmodel.php';
require_once $virtuemart_path.'models'.DS.'category.php';
require_once $virtuemart_path.'views'.DS.'category'.DS.'view.html.php';
/**
 * получим методы основного класса:
 */
class modVlotscatsHelper extends JModuleHelper
{	
	public static function getVMitems(){
/*	count: 0
	===========================================================
	  public 'virtuemart_category_id' => string '23' (length=2)
	  public 'category_description' => string '' (length=0)
	  public 'category_name' => string 'МАГАЗИН' (length=14)
	  public 'ordering' => string '3' (length=1)
	  public 'published' => string '1' (length=1)
	  public 'category_child_id' => string '23' (length=2)
	  public 'category_parent_id' => string '0' (length=1)
	  public 'shared' => string '0' (length=1)
	  public 'level' => int 1

		count: 0
		===========================================================
		  public 'virtuemart_category_id' => string '14' (length=2)
		  public 'category_description' => string '' (length=0)
		  public 'category_name' => string 'Восточное искусство' (length=37)
		  public 'ordering' => string '14' (length=2)
		  public 'published' => string '1' (length=1)
		  public 'category_child_id' => string '14' (length=2)
		  public 'category_parent_id' => string '23' (length=2)
		  public 'shared' => string '0' (length=1)
		  public 'level' => int 2	*/		
		$VirtuemartViewCategory=new VirtuemartViewCategory;
		$model = VmModel::getModel('Category','VirtueMartModel');		
		$VirtuemartViewCategory->SetViewTitle('CATEGORY_S');
		$VirtuemartViewCategory->assignRef('catmodel',	$model);
		$VirtuemartViewCategory->addStandardDefaultViewCommands();
		$VirtuemartViewCategory->addStandardDefaultViewLists($model,'category_name');		
		/* ВНИМАНИЕ! Последний аргумент (true) отсутствует в нативном методе VirtueMartModelCategory::getCategoryTree() и добавлен здесь для того, чтобы снять лимит вывода данных. В случае переустановки VirtueMart - добавить заново как ",$unlimited = false"!
			А также заменить фрагмент кода:

				if(empty($limit)){
				return $sortedCats;
				} else ...
				
			на:	if(empty($limit)||$unlimited){
				return $sortedCats;
				} else ...
		*/
		$categories = $model->getCategoryTree(0, 0, false, $VirtuemartViewCategory->lists['search'], true);
		// var_dump($categories);die();
		$VirtuemartViewCategory->assignRef('categories', $categories);
		$VirtuemartViewCategory->assignRef('catmodel',	$model);
		return 	$VirtuemartViewCategory;
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function getTopCategories($db=false){
		$query='SELECT cats.virtuemart_category_id, 
        cats_ru.category_name,
		cats_ru.slug AS "alias"
FROM #__virtuemart_categories AS cats
LEFT JOIN #__virtuemart_categories_ru_ru AS cats_ru 
    ON  cats_ru.virtuemart_category_id = cats.virtuemart_category_id
LEFT JOIN #__virtuemart_category_categories AS cat_cats 
    ON  cat_cats.id = cats.virtuemart_category_id
WHERE cat_cats.category_parent_id = 0 AND cats.`published` = "1"
ORDER BY cats.ordering'; 
		if(!$db) 
			$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function getTopCatCounts($db=false){
		$db=JFactory::getDBO();
		$top_cats=modVlotscatsHelper::getTopCategories($db);
		/* 0 => 
			array
			  'virtuemart_category_id' => string '21' (length=2)
			  'category_name' => string 'Онлайн торги' (length=23)
		  1 => 
			array
			  'virtuemart_category_id' => string '22' (length=2)
			  'category_name' => string 'Очные торги' (length=21)
		  2 => 
			array
			  'virtuemart_category_id' => string '23' (length=2)
			  'category_name' => string 'Магазин' (length=14)
	  	*/
		foreach($top_cats as $i=>$top_cat){
			$query='SELECT cats.virtuemart_category_id, 
        cats_ru.category_name,
        cats_ru.slug AS "alias",
        (   SELECT count(p.virtuemart_product_id)
			FROM `auc13_virtuemart_products` AS p, `auc13_virtuemart_product_categories` AS pc
			WHERE pc.`virtuemart_category_id` = cats.virtuemart_category_id
			AND p.`virtuemart_product_id` = pc.`virtuemart_product_id`
			AND p.`published` = "1"
        ) AS "product_count"
FROM auc13_virtuemart_categories AS cats
LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru 
    ON  cats_ru.virtuemart_category_id = cats.virtuemart_category_id
 LEFT JOIN auc13_virtuemart_category_categories AS cat_cats 
    ON  cat_cats.id = cats.virtuemart_category_id
WHERE cat_cats.category_parent_id = '.$top_cat['virtuemart_category_id'].' 
	AND cats.`published` = "1"
ORDER BY cat_cats.category_parent_id,cats.ordering';
			$db->setQuery($query);
			$records[$top_cat['virtuemart_category_id']]=array(
						'top_category_alias'=>$top_cat['alias'],
						'top_category_name'=>$top_cat['category_name'],
						'children'=>$db->loadAssocList()
				); 
		}
		return $records;
	}
}