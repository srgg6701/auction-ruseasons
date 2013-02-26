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
	/*public static function addSubmenu()
	{
		//die('addSubmenu');
		JSubMenuHelper::addEntry(
							JText::_('Импорт лотов'),
							'index.php?option=com_auction2013&view=importlots'
						);
	}*/	
}
