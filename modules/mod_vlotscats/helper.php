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
		//var_dump($categories);
		$VirtuemartViewCategory->assignRef('categories', $categories);
		$VirtuemartViewCategory->assignRef('catmodel',	$model);
		return 	$VirtuemartViewCategory;
	}
}