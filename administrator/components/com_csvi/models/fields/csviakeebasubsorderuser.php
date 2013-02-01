<?php
/**
 * List the order user
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvivirtuemartorderuser.php 1798 2012-01-14 11:53:10Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * Select list form field with order users
 *
 * @package CSVI
 */
class JFormFieldCsviAkeebasubsOrderUser extends JFormFieldCsviForm {

	protected $type = 'CsviAkeebasubsOrderUser';

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
		$query = $db->getQuery(true);
		$orderuser = $template->get('orderuser', 'order', array(), 'array');
		if (!empty($orderuser)) {
			$query->select($db->quoteName('user_id', 'value'));
			$query->select($db->quoteName('name', 'text'));
			$query->from($db->quoteName('#__akeebasubs_subscriptions', 's'));
			$query->leftJoin($db->quoteName('#__users', 'u').' ON '.$db->quoteName('s').'.'.$db->quoteName('user_id').' = '.$db->quoteName('u').'.'.$db->quoteName('id'));
			$query->where($db->quoteName('s').'.'.$db->quoteName('user_id').' IN ('.implode(',', $orderuser).')');
			$query->order($db->quoteName('name'));
			$query->group($db->quoteName('user_id'));
			$db->setQuery($query);
			$customers = $db->loadObjectList();
			if (empty($customers)) $customers = array();
			return array_merge(parent::getOptions(), $customers);
		}
		else return parent::getOptions();
	}
}
?>
