<?php
/**
 * List the operations
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvioperations.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * Select list form field with operations
 *
 * @package	CSVI
 */
class JFormFieldCsviOperations extends JFormFieldCsviForm {

	protected $type = 'CsviOperations';

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
		$jinput = JFactory::getApplication()->input;
		$jform = $jinput->get('jform', array(), 'array');
		$trans = array();

		if (!empty($jform) && isset($jform['options'])) {
			$db = JFactory::getDbo();
			$q = "SELECT t.template_type_name
				FROM `#__csvi_template_types` AS t
				WHERE t.template_type = ".$db->Quote($jform['options']['action'])."
				AND t.component = ".$db->Quote($jform['options']['component']);
			$db->setQuery($q);
			$types = $db->loadResultArray();

			// Get translations
			foreach ($types as $type) {
				$trans[$type] = JText::_('COM_CSVI_'.strtoupper($type));
			}
		}
		else {
			$trans = parent::getOptions();
		}
		return $trans;
	}
}
?>
