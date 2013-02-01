<?php
/**
 * Virtuemart categories table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: categories_lang.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 */
class TableCategories_lang extends JTable {

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
		if ($template->get('operation', 'options') == 'categoryimport') {
			if ($template->get('language', 'general') == $template->get('target_language', 'general')) $lang = $template->get('language', 'general');
			else $lang = $template->get('target_language', 'general');
		}
		else $lang = $template->get('language', 'general');
		parent::__construct('#__virtuemart_categories_'.$lang, 'virtuemart_category_id', $db);
	}

	/**
	 * Check if the category ID exists
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
		$query->where($db->quoteName($this->_tbl_key). ' = '.$this->virtuemart_category_id);
		$db->setQuery($query);
		$id = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_CHECK_CATEGORY_LANG'), true);
		if (!$id) {
			if (empty($this->slug)) $this->_validateSlug();
			if (!empty($this->slug)) {
				// Create a dummy entry for updating
				$query = "INSERT INTO ".$this->_tbl." (".$db->quoteName($this->_tbl_key).", ".$db->quoteName('slug').") VALUES (".$db->Quote($this->virtuemart_category_id).", ".$db->Quote($this->slug).")";
				$db->setQuery($query);
				$csvilog->addDebug(JText::_('COM_CSVI_ADD_CATGEGORY_LANG'), true);
				if ($db->query()) return true;
				else return false;
			}
			else return false;
		}
		else return true;
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
		$this->slug = Com_virtuemart::createSlug($this->category_name);
		
		// Check if the slug exists
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT('.$db->Quote($this->_tbl_key).')');
		$query->from($this->_tbl);
		$query->where($db->quoteName('slug').' = '.$db->Quote($this->slug));
		$db->setQuery($query);
		$slugs = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_CHECK_CATGEGORY_SLUG'), true);
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