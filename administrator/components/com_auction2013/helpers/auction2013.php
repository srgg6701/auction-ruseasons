<?php
/**
 * @version     2.1.0
 * @package     com_auction2013
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;
// require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'tables'.DS.'table_name.php';
/**
 *auction2013 helper.
 */
class Auction2013Helper
{
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = JAccess::getActions('com_auction2013');

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, 'com_auction2013'));
		}

		return $result;
	}
	
	public static function getImportFields(){
		return array(
				'auction_number'=>'Номер аукциона',
				'contract_number'=>'Номер договора',
				'date_show'=>'Дата включения отображения предмета на сайте', // ?
				'date_hide'=>'Дата отключения отображения предмета на сайте', // ?				
				'date_start'=>'Дата начала периода торгов по предмету', // ?
				'date_stop'=>'Дата окончания периода торгов по предмету', // ?
				'title'=>'Название лота',
				'short_desc'=>'Краткое описание лота',
				'desc'=>'Описание лота',
				'price'=>'Стартовая цена', // ?
				'img <span style="font-weight:200;">(до 15-ти полей)</span>'=>'Имена файлов изображений &#8212; по одному в каждом поле.',
			);
	}
	
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu()
	{
		//die('addSubmenu');
		$common_link_segment='index.php?option=com_auction2013&view=';
		JSubMenuHelper::addEntry(JText::_('Импорт предметов'),$common_link_segment.'importlots');
		JSubMenuHelper::addEntry(JText::_('Экспорт предметов'),$common_link_segment.'auction2013&layout=test');
	}	
}
class Test{
/**
 * Описание
 * @package
 * @subpackage
 */
	function getDataToExport($categories_ids=false){
		$query="SELECT
  prods.id,
  prods.title,
  prods.description,
  cats.category_id,
  prods.price,
  prods.image AS 'images',
  prods.ends,
  prods.date,";
  /*-- cats.category_name,
  -- prods.order_item_id,
  -- prods.item_type,
  -- prods.quantity,
  -- prods.auction_type,
  -- prods.price_plan_id,
  -- prods.seller,
  -- prods.live,
  -- prods.precurrency,
  -- prods.postcurrency,
  -- prods.duration,*/
  		$query.="
  prods.optional_field_2 AS 'optf-1',
  prods.optional_field_1 AS 'optf-2',
  prods.optional_field_3 AS 'optf-3',
  prods.optional_field_4 AS 'optf-4',
  prods.optional_field_5 AS 'optf-5'
FROM #__geodesic_classifieds_cp prods
  INNER JOIN #__geodesic_categories cats
    ON prods.category = cats.category_id";
		if($categories_ids){
			$query.="
	WHERE ";
			$j=0;
			foreach($categories_ids as $i=>$id){
				if($j)
					$query.=" 
           OR ";
				$query.=" cats.category_id = ".$id;
				$j++;
			}
		}
		$query.="
ORDER BY cats.category_name, prods.title";
		//echo "<div class=''>query= <pre>".$query."</pre></div>"; //die();
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function getImagesToExport($classified_id=false){
		$query="SELECT
  image_url,
  full_filename,
  thumb_url,
  thumb_filename
FROM #__geodesic_classifieds_images_urls";
		if($classified_id)
			$query.="	
	WHERE classified_id = ".$classified_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 	
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function getCategoriesToExport(){
		$query="SELECT cats.category_id, 
  category_name,
  ( SELECT COUNT(*) FROM #__geodesic_classifieds_cp
      WHERE category = cats.category_id
  ) AS 'count' 
	FROM #__geodesic_categories cats 
   ORDER BY category_name";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 	
	}
		
}
