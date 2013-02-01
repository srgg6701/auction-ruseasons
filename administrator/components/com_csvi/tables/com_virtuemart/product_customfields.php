<?php
/**
 * Virtuemart Custom fields table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: product_customfields.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVI
 */
class TableProduct_customfields extends JTable {

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
		parent::__construct('#__virtuemart_product_customfields', 'virtuemart_customfield_id', $db );
	}

	/**
	 * Check if an entry already exists
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		boolean	true if ID exists | false if ID doesn't exist
	 * @since 		4.0
	 */
	public function check() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->Quote($this->_tbl_key));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('virtuemart_product_id').' = '.$db->Quote($this->virtuemart_product_id));
		$query->where($db->quoteName('virtuemart_custom_id').' = '.$db->Quote($this->virtuemart_custom_id));
		$query->where($db->quoteName('custom_value').' = '.$db->Quote($this->custom_value));
		$db->setQuery($query);
		$id = $db->loadResult();
		if ($id) {
			$this->virtuemart_customfield_id = $id;
			return true;
		}
		else return false;
	}

	/**
	 * Delete all related products for given product ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$product_id	the product to delete related products for
	 * @param 		int	$vendor_id	the vendor ID to filter on
	 * @param		int	$related_id	the related ID to filter on
	 * @return
	 * @since 		4.0
	 */
	public function deleteRelated($product_id, $vendor_id, $related_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($this->_tbl);
		$query->where('virtuemart_product_id = '.$product_id);
		$query->where('virtuemart_custom_id = '.$related_id);
		$db->setQuery($query);
		return $db->query();
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