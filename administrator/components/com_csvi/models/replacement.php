<?php
/**
 *
 * Model class for replacement editing
 *
 * @package 	Csvi
 * @author 		RolandD
 * @link		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: templatetype.php 1760 2012-01-02 19:50:19Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the model framework
jimport('joomla.application.component.modeladmin');

/**
 * Sku editing
 *
 * @package 	Csvi
 * @author 		RolandD
  * @since 		1.0
 */
class CsviModelReplacement extends JModelAdmin {

	/**
	 * @var string Model context string
	 */
	private $context = 'com_csvi.replacement';

	/**
	 * Method to get the record form located in models/forms
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		array $data Data for the form.
	 * @param 		boolean $loadData True if the form is to load its own data (default case), false if not.
	 * @return 		mixed
	 * @since 		1.0
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm($this->context, 'replacement', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_csvi.edit.replacement.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
}
?>
