<?php
/**
 * Virtuemart Product Category Cross reference table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: product_categories_xref.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVI
 */
class TableProduct_categories_xref extends JTable {

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
		parent::__construct('#__virtuemart_product_categories', 'virtuemart_product_id', $db );
	}

	/**
	 * Stores a product category relation
	 *
	 * The product category relation is always inserted
	 */
	public function store() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Check if the entry already exists
		if (!$this->checkDuplicate()) {
			$ret = $this->_db->insertObject( $this->_tbl, $this);
			$csvilog->addDebug(JText::_('COM_CSVI_ADD_NEW_CATEGORY_REFERENCES'), true);
			if (!$ret) {
				$this->setError(get_class($this).'::store failed - '.$this->_db->getErrorMsg());
				return false;
			}
			else return true;
		}
		else return true;
	}

	/**
	 * Check if the entry already exists
	 */
	private function checkDuplicate() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$q = "SELECT COUNT(*) AS total
			FROM ".$this->_tbl."
			WHERE virtuemart_product_id = ".$this->virtuemart_product_id."
			AND virtuemart_category_id = ".$this->virtuemart_category_id;
		$db->setQuery($q);
		$csvilog->addDebug(JText::_('COM_CSVI_CHECK_IF_CATEGORY_REFERENCE_ALREADY_EXISTS'), true);
		$total = $db->loadResult();
		if ($total > 0) {
			$csvilog->addDebug(JText::_('COM_CSVI_CATEGORY_REFERENCE_ALREADY_EXISTS'));
			return true;
		}
		else {
			$csvilog->addDebug(JText::_('COM_CSVI_CATEGORY_REFERENCE_DOES_NOT_YET_EXIST'));
			return false;
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