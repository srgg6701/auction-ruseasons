<?php
/**
 * Virtuemart Calculation table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: calcs.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 */
class TableCalcs extends JTable {

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
		parent::__construct('#__virtuemart_calcs', 'virtuemart_calc_id', $db );
	}

	/**
	 * Check if a discount already exists. If so, retrieve the discount ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool	true
	 * @since 		4.0
	 */
	public function check() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);
		if ($this->calc_value) {
			// Check if the amount exists in the database
			$query = $db->getQuery(true);
			$query->select($this->_tbl_key);
			$query->from($this->_tbl);
			$query->where($db->quoteName('calc_kind').' = '.$db->quote($this->calc_kind));
			$query->where($db->quoteName('calc_value_mathop').' = '.$db->quote($this->calc_value_mathop));
			$query->where($db->quoteName('calc_value').' BETWEEN '.$db->quote(($this->calc_value-0.1)).' AND '.$db->quote(($this->calc_value+0.1)));
			if (!empty($this->publish_up)) $query->where('publish_up = '.$db->Quote($this->publish_up));
			if (!empty($this->publish_down)) $query->where('publish_down = '.$db->Quote($this->publish_down));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			$csvilog->addDebug(JText::_('COM_CSVI_CHECK_CALC_EXISTS'), true);
			// There are multiple discount ids, we take the first one
			if (count($ids) > 0) {
				$csvilog->addDebug(JText::sprintf('COM_CSVI_USE_CALC_ID', $ids[0]));
				$this->virtuemart_calc_id = $ids[0];
				return true;
			}
			else {
				$this->virtuemart_calc_id = null;
				return false;
			}
		}
		return false;
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