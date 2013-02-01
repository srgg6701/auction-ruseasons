<?php
/**
 * Virtuemart product table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: products_lang.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVI
 */
class TableProducts_lang extends JTable {

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
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		parent::__construct('#__virtuemart_products_'.$template->get('language', 'general'), 'virtuemart_product_id', $db);
	}

	/**
	 * Check if the product ID exists
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
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($this->_tbl_key);
		$query->from($this->_tbl);
		$query->where($db->quoteName($this->_tbl_key). ' = '.$this->virtuemart_product_id);
		$db->setQuery($query);
		$id = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_CHECK_PRODUCT_LANG'), true);
		if (empty($id)) {
			if (empty($this->slug)) $this->_validateSlug();
			if (!empty($this->slug)) {
				// Create a dummy entry for updating
				$query = "INSERT INTO ".$this->_tbl." (".$db->quoteName($this->_tbl_key).", ".$db->quoteName('slug').") VALUES (".$db->Quote($this->virtuemart_product_id).", ".$db->Quote($this->slug).")";
				$db->setQuery($query);
				$csvilog->addDebug(JText::_('COM_CSVI_ADD_PRODUCT_LANG'), true);
				if ($db->query()) return true;
				else return false;
			}
			else return false;
		}
		else return true;
	}

	/**
	 * Validate a slug
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
	private function _validateSlug() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);

		// Create the slug
		$this->slug = Com_virtuemart::createSlug($this->product_name);

		// Check if the slug exists
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT('.$db->Quote($this->_tbl_key).')');
		$query->from($this->_tbl);
		$query->where($db->quoteName('slug').' = '.$db->Quote($this->slug));
		$db->setQuery($query);
		$slugs = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_CHECK_PRODUCT_SLUG'), true);
		if ($slugs > 0) {
			$jdate = JFactory::getDate();
			$this->slug .= $jdate->format("Y-m-d-h-i-s").mt_rand();
		}
	}

	/**
	 * Reset the table fields, need to do it ourselves as the fields default is not NULL 
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