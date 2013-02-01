<?php
/**
 * Akeeba Subscriptions helper file
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: com_virtuemart.php 1779 2012-01-09 21:02:20Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Akeeba Subscriptions helper file
 *
 * @package CSVI
 */
class Com_Akeebasubs {

	private $_csvidata = null;

	/**
	 * Constructor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	public function __construct() {
		$jinput = JFactory::getApplication()->input;
		$this->_csvidata = $jinput->get('csvi_data', null, null);
	}

	/**
	 * Get a user ID 
	 * 
	 * @copyright 
	 * @author 		RolandD
	 * @todo 
	 * @see 
	 * @access 		public
	 * @param 		string	$username	the username to find the ID for
	 * @return 		int	the ID of the user
	 * @since 		4.0
	 */
	public function getUser($username) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__users'));
		$query->where($db->quoteName('username').' = '.$db->quote($username));
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	* Get a subscription ID
	*
	* @copyright
	* @author 		RolandD
	* @todo
	* @see
	* @access 		public
	* @param		string	$subscription_title	the name(s) of the subscription
	* @return
	* @since 		4.0
	*/
	public function getSubscription($subscription_title) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('akeebasubs_level_id'));
		$query->from($db->quoteName('#__akeebasubs_levels'));
		$titles = explode(',', $subscription_title);
		$newtitles = array();
		foreach ($titles as $title) {
			$newtitles[] = $db->quote(trim($title));
		}
		if (!empty($newtitles)) {
			$query->where($db->quoteName('title').' IN ('.implode(',', $newtitles).')');
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if (!empty($ids)) {
				return implode(',', $ids);
			}
		}
		else return '';
	}
	
	/**
	* Get the list of order users
	*
	* @copyright
	* @author		RolandD
	* @todo
	* @see
	* @access 		public
	* @param
	* @return 		array of objects
	* @since 		4.0
	*/
	public function getOrderUser() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$filter = $jinput->get('filter');
		$query->select($db->quoteName('user_id'));
		$query->select($db->quoteName('name', 'user_name'));
		$query->from($db->quoteName('#__akeebasubs_subscriptions', 's'));
		$query->leftJoin($db->quoteName('#__users', 'u').' ON '.$db->quoteName('s').'.'.$db->quoteName('user_id').' = '.$db->quoteName('u').'.'.$db->quoteName('id'));
		$query->where($db->quoteName('u').'.'.$db->quoteName('name').' LIKE '.$db->quote('%'.$filter.'%'));
		$query->order($db->quoteName('name'));
		$query->group($db->quoteName('user_id'));
		$db->setQuery($query, 0, 10);
		$users = $db->loadObjectList();
		if ($users) return $users;
		else return array();
	}
	
	/**
	 * Get the list of order products
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of objects
	 * @since 		4.0
	 */
	public function getOrderProduct() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$filter = $jinput->get('filter');
		$query->select($db->quoteName('s').'.'.$db->quoteName('akeebasubs_level_id', 'product_sku'));
		$query->select($db->quoteName('title', 'product_name'));
		$query->from($db->quoteName('#__akeebasubs_subscriptions', 's'));
		$query->leftJoin($db->quoteName('#__akeebasubs_levels', 'l').' ON '.$db->quoteName('s').'.'.$db->quoteName('akeebasubs_level_id').' = '.$db->quoteName('l').'.'.$db->quoteName('akeebasubs_level_id'));
		$query->where($db->quoteName('l').'.'.$db->quoteName('title').' LIKE '.$db->quote('%'.$filter.'%'));
		$query->order($db->quoteName('title'));
		$query->group($db->quoteName('s').'.'.$db->quoteName('akeebasubs_level_id'));
		$db->setQuery($query, 0, 10);
		$products = $db->loadObjectList();
		if ($products) return $products;
		else return array();
	}
	
}
?>