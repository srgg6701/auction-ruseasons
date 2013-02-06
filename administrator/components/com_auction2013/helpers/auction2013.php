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
}
