<?php
/**
 * Virtuemart musers table
 *
 * @package 	CSVI
 * @subpackage 	Tables
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: vmusers.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 * @subpackage Tables
 */
class TableVmusers extends JTable {

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
		parent::__construct('#__virtuemart_vmusers', 'virtuemart_user_id', $db );
	}

	/**
	 * Check if an entry exists or a placeholder is needed
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
	public function check() {
		if (!empty($this->virtuemart_user_id)) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('COUNT('.$this->_tbl_key.') AS total');
			$query->from($this->_tbl);
			$query->where($db->quoteName($this->_tbl_key).' = '.$db->Quote($this->virtuemart_user_id));
			$db->setQuery($query);
			if ($db->loadResult() == 1) return true;
			else {
				$query = "INSERT IGNORE INTO ".$db->quoteName($this->_tbl)." (".$db->quoteName($this->_tbl_key).") VALUES (".$db->Quote($this->virtuemart_user_id).")";
				$db->setQuery($query);
				$db->query();
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