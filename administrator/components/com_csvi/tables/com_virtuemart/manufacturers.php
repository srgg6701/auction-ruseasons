<?php
/**
 * Virtuemart Manufacturer table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: manufacturers.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 */
class TableManufacturers extends JTable {

	/**
	 * Table constructor
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
	public function __construct($db) {
		parent::__construct('#__virtuemart_manufacturers', 'virtuemart_manufacturer_id', $db );
	}

	/**
	 * Check if the manufacturer exists
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	public function check() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($this->_tbl_key);
		$query->from($this->_tbl);
		$query->where($db->quoteName($this->_tbl_key).' = '.$db->Quote($this->virtuemart_manufacturer_id));
		$db->setQuery($query);
		$id = $db->loadResult();

		if ($id > 0) {
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_EXISTS'), true);
			return true;
		}
		else {
			// Create a dummy entry for updating
			$query = "INSERT IGNORE INTO ".$this->_tbl." (".$db->quoteName($this->_tbl_key).") VALUES (".$db->Quote($this->virtuemart_manufacturer_id).")";
			$db->setQuery($query);
			if ($db->query()) {
				$this->virtuemart_manufacturer_id = $db->insertid();
				return true;
			}
			else {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_NOT_EXISTS'), true);
				return false;
			}
		}
	}
	
	/**
	 * Reset the keys including primary key
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
	public function reset() {
		// Get the default values for the class from the table.
		foreach ($this->getFields() as $k => $v) {
			// If the property is not private, reset it.
			if (strpos($k, '_') !== 0) {
				$this->$k = NULL;
			}
		}
	}
}
?>