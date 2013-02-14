<?php
/**
 * Virtuemart Manufacturer table
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: manufacturers.php 2307 2013-02-03 07:23:02Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

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
		$query->where($db->qn($this->_tbl_key).' = '.$db->q($this->virtuemart_manufacturer_id));
		$db->setQuery($query);
		$id = $db->loadResult();

		if ($id > 0) {
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_EXISTS'), true);
			return true;
		}
		else {
			// Find the default category
			$query = $db->getQuery(true)
				->select('MIN('.($db->qn('virtuemart_manufacturercategories_id').')'))
				->from($db->qn('#__virtuemart_manufacturercategories'))
				->where($db->qn('published').'=1');
			$db->setQuery($query);
			$this->virtuemart_manufacturercategories_id = $db->loadResult();

			// Create a dummy entry for updating
			$query->insert($this->_tbl)
				->columns(array($this->_tbl_key.','.$db->qn('virtuemart_manufacturercategories_id')))
				->values($db->q($this->virtuemart_manufacturer_id).','.$db->q($this->virtuemart_manufacturercategories_id));
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