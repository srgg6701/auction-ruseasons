<?php
/**
 * Virtuemart Manufacturer table
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: manufacturer_categories_lang.php 2307 2013-02-03 07:23:02Z RolandD $
 */

// No direct access
defined('_JEXEC') or die;

class TableManufacturer_categories_lang extends JTable {

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
		parent::__construct('#__virtuemart_manufacturercategories_'.$template->get('language', 'general'), 'virtuemart_manufacturercategories_id', $db );
	}

	/**
	 * Check if the manufacturer category exists
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		bool	$create		Set true if a dummy entry needs to be added
	 * @return
	 * @since 		4.0
	 */
	public function check($create = true) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		if (!empty($this->virtuemart_manufacturercategories_id)) {
			$query = $db->getQuery(true);
			$query->select($this->_tbl_key);
			$query->from($this->_tbl);
			$query->where($db->qn($this->_tbl_key).' = '.$db->q($this->virtuemart_manufacturercategories_id));
			$db->setQuery($query);
			$id = $db->loadResult();
			if ($id > 0) {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURERCATEGORY_EXISTS'), true);
				return true;
			}
			else {
				if ($create) {
					// Create a dummy entry for updating
					$query = "INSERT IGNORE INTO ".$this->_tbl." (".$db->qn($this->_tbl_key).") VALUES (".$db->q($this->virtuemart_manufacturercategories_id).")";
					$db->setQuery($query);
					if ($db->query()) return true;
					else {
						$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURERCATEGORY_NOT_EXISTS'), true);
						return false;
					}
				}
				else return false;
			}
		}
		else {
			$query = $db->getQuery(true);
			$query->select($this->_tbl_key);
			$query->from($this->_tbl);
			$query->where('mf_category_name = '.$db->q($this->mf_category_name));
			$db->setQuery($query);
			$id = $db->loadResult();
			if ($id > 0) {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURERCATEGORY_EXISTS'), true);
				$this->virtuemart_manufacturercategories_id = $id;
				return true;
			}
			else {
				if ($create) {
					// Create a dummy entry for updating
					$query = "INSERT IGNORE INTO ".$this->_tbl." (".$db->qn($this->_tbl_key).") VALUES (".$db->q($this->virtuemart_manufacturercategories_id).")";
					$db->setQuery($query);
					if ($db->query()) {
						$this->virtuemart_manufacturercategories_id = $db->insertid();
						return true;
					}
					else {
						$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURERCATEGORY_NOT_EXISTS'), true);
						return false;
					}
				}
				else {
					$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURERCATEGORY_NOT_EXISTS'), true);
					return false;
				}
			}
		}
	}

	/**
	 * Create a slug if needed and store the product
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
	public function store() {
		if (empty($$this->_tbl_key)) {
			// Create the slug
			$this->slug = Com_virtuemart::createSlug($this->mf_category_name);
		}

		return parent::store();
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