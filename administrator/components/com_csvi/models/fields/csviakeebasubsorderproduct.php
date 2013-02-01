<?php
/**
 * List the order products
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvivirtuemartorderproduct.php 1798 2012-01-14 11:53:10Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * Select list form field with order products
 *
 * @package CSVI
 */
class JFormFieldCsviAkeebasubsOrderProduct extends JFormFieldCsviForm {

	protected $type = 'CsviAkeebasubsOrderProduct';

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
		$orderproduct = $template->get('orderproduct', 'order', array(), 'array');
		if (!empty($orderproduct)) {
			$query->select($db->quoteName('s').'.'.$db->quoteName('akeebasubs_level_id', 'value'));
			$query->select($db->quoteName('title', 'text'));
			$query->from($db->quoteName('#__akeebasubs_subscriptions', 's'));
			$query->leftJoin($db->quoteName('#__akeebasubs_levels', 'l').' ON '.$db->quoteName('s').'.'.$db->quoteName('akeebasubs_level_id').' = '.$db->quoteName('l').'.'.$db->quoteName('akeebasubs_level_id'));
			$query->where($db->quoteName('s').'.'.$db->quoteName('akeebasubs_level_id').' IN ('.implode(',', $orderproduct).')');
			$query->order($db->quoteName('title'));
			$query->group($db->quoteName('s').'.'.$db->quoteName('akeebasubs_level_id'));
			$db->setQuery($query);
			$products = $db->loadObjectList();
			if (empty($products)) $products = array();
			return array_merge(parent::getOptions(), $products);
		}
		else return parent::getOptions();
	}
}
?>
