<?php
/**
 * List the order user
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvivirtuemartorderuser.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * Select list form field with order users
 *
 * @package CSVI
 */
class JFormFieldCsviVirtuemartOrderUser extends JFormFieldCsviForm {

	protected $type = 'CsviVirtuemartOrderUser';

	/**
	 * Specify the options to load
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		array	an array of options
	 * @since 		4.0
	 */
	protected function getOptions() {
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$db = JFactory::getDbo();
		$orderuser = $template->get('orderuser', 'order', array(), null);
		$userids = implode(',', $orderuser);
		if (!empty($userids)) {
			$q = "SELECT DISTINCT user_id,
				IF (LENGTH(TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name))) = 0, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."',
				IF (TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name)) is NULL, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."', CONCAT(first_name, ' ', middle_name, ' ', last_name))) AS user_name
				FROM #__virtuemart_order_userinfos
				WHERE user_id IN (".$userids.")
				ORDER BY user_name;";
			$db->setQuery($q);
			$customers = $db->loadObjectList();
			if (empty($customers)) $customers = array();
			return array_merge(parent::getOptions(), $customers);
		}
		else return parent::getOptions();
		
	}
}
?>
