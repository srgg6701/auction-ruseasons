<?php
/**
 * Virtuemart Product Manufacturer Cross reference table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: product_manufacturers.php 2275 2013-01-03 21:08:43Z RolandD $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 */
class TableProduct_manufacturers extends JTable {

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
		parent::__construct('#__virtuemart_product_manufacturers', 'id', $db );
	}

	/**
	 * Check if a product manufacturer link already exists
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		true if link exists, false if link does not exist
	 * @since 		4.0
	 */
	public function check() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($this->_tbl_key);
		$query->from($this->_tbl);
		$query->where('virtuemart_product_id = '.$db->Quote($this->virtuemart_product_id));
		$query->where('virtuemart_manufacturer_id = '.$db->Quote($this->virtuemart_manufacturer_id));
		$db->setQuery($query);
		$id = $db->loadResult();
		if ($id > 0) {
			$this->id = $id;
			return true;
		}
		else return false;
	}
	
	/**
	 * Store a manufacturer reference 
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
	public function store($updateNulls=false) {
		// First remove any existing references
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->quoteName($this->_tbl));
		$query->where($db->quoteName('virtuemart_product_id').' = '.$db->quote($this->virtuemart_product_id));
		$db->setQuery($query);
		if ($db->query()) return parent::store($updateNulls);
		else return false;
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