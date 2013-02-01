<?php
/**
 * List the templates
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: log.php 1776 2012-01-08 22:18:33Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * Select list form field with templates
 *
 * @package CSVI
 */
class JFormFieldCsviTemplates extends JFormFieldCsviForm {

	protected $type = 'CsviTemplates';

	/**
	 * Get the export templates set for front-end export
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id').' AS value ,'.$db->quoteName('name').' AS text');
		$query->from($db->quoteName('#__csvi_template_settings'));
		$query->where($db->quoteName('settings').' LIKE '.$db->quote('%"action":"export"%'));
		$query->where($db->quoteName('settings').' LIKE '.$db->Quote('%"export_frontend":"1"%'));
		$query->order($db->quoteName('name'));
		$db->setQuery($query);
		$templates = $db->loadObjectList();
		return $templates;
	}
}
?>
