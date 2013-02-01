<?php
/**
 * Virtuemart Manufacturer table
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: manufacturers_lang.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die;

class TableManufacturers_lang extends JTable {

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
		parent::__construct('#__virtuemart_manufacturers_'.$template->get('language', 'general'), 'virtuemart_manufacturer_id', $db );
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
	public function check($create = true) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		if (!empty($this->virtuemart_manufacturer_id)) {
			$query = $db->getQuery(true);
			$query->select($this->_tbl_key);
			$query->from($this->_tbl);
			$query->where($db->qn($this->_tbl_key).' = '.$db->q($this->virtuemart_manufacturer_id));
			$db->setQuery($query);
			$id = $db->loadResult();
			if ($id > 0) {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_EXISTS'), true);
				return false;
			}
			else {
				if ($create) {
					// Create a dummy entry for updating
					$query = "INSERT IGNORE INTO ".$this->_tbl." (".$db->qn($this->_tbl_key).") VALUES (".$db->q($this->virtuemart_manufacturer_id).")";
					$db->setQuery($query);
					if ($db->query()) return true;
					else {
						$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_NOT_EXISTS'), true);
						return false;
					}
				}
				else {
					$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_NOT_EXISTS'), true);
					return false;
				}
			}
		}
		else {
			$query = $db->getQuery(true);
			$query->select($this->_tbl_key);
			$query->from($this->_tbl);
			$query->where('mf_name = '.$db->q($this->mf_name));
			$db->setQuery($query);
			$id = $db->loadResult();
			if ($id > 0) {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_EXISTS'), true);
				$this->virtuemart_manufacturer_id = $id;
				$this->load();
				return true;
			}
			else {
				if ($create) {
					// Find the highest ID
					$query = $db->getQuery(true);
					$query->select('MAX(virtuemart_manufacturer_id)');
					$query->from($this->_tbl);
					$db->setQuery($query);
					$maxid = $db->loadResult();
					$maxid++;
					// Create a dummy entry for updating
					$query = "INSERT IGNORE INTO ".$this->_tbl." (".$db->qn($this->_tbl_key).") VALUES (".$db->q($maxid).")";
					$db->setQuery($query);
					if ($db->query()) {
						$this->virtuemart_manufacturer_id = $maxid;
						return true;
					}
					else {
						$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_NOT_EXISTS'), true);
						return false;
					}
				}
				else {
					$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_MANUFACTURER_NOT_EXISTS'), true);
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
		if (empty($this->slug)) {
			// Create the slug
			$this->_validateSlug();
		}

		return parent::store();
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
		$this->slug = Com_virtuemart::createSlug($this->mf_name);

		// Check if the slug exists
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT('.$db->qn($this->_tbl_key).')');
		$query->from($this->_tbl);
		$query->where($db->qn('slug').' = '.$db->q($this->slug));
		$db->setQuery($query);
		$slugs = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_CHECK_MANUFACTURER_SLUG'), true);
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