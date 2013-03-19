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
		JSubMenuHelper::addEntry(JText::_('Импорт лотов'),$common_link_segment.'importlots');
		JSubMenuHelper::addEntry(JText::_('Тесты'),$common_link_segment.'auction2013&layout=test');
	}	
}
class Test{
/**
 * Описание
 * @package
 * @subpackage
 */
	function getDataToExport(){
		$query="SELECT
  prods.id,
  prods.title,
  prods.description,
  cats.category_id,
  cats.category_name,
  prods.price,
  -- prods.image,
  ( SELECT COUNT(*) FROM #__geodesic_classifieds_images_urls 
      WHERE classified_id = prods.id
  ) AS 'imgs_count_check',  
  prods.category,
  prods.ends,
  prods.date,
  -- prods.order_item_id,
  -- prods.item_type,
  -- prods.quantity,
  -- prods.auction_type,
  -- prods.price_plan_id,
  -- prods.seller,
  -- prods.live,
  -- prods.precurrency,
  -- prods.postcurrency,
  prods.duration,
  prods.optional_field_2,
  prods.optional_field_1,
  prods.optional_field_3,
  prods.optional_field_4,
  prods.optional_field_5-- ,
FROM #__geodesic_classifieds_cp prods
  INNER JOIN #__geodesic_categories cats
    ON prods.category = cats.category_id
ORDER BY cats.category_name, prods.title";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 
	}	
}
