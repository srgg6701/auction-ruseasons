<?php
/**
 * Category related functions
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: category.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
* @package CSVI
 */
class CsviModelCategory extends JModel {

	// Private tables
	/** @var object contains the vm_categories table */
	private $_categories = null;
	/** @var object contains the vm_categories language table */
	private $_categories_lang = null;
	/** @var object contains the vm_categories_xref table */
	private $_categories_xref = null;
	/** @var object contains the vm_product_categories_xref table */
	private $_product_categories_xref = null;
	/** @var array contains the category cache */
	private $_category_cache = array();
	/** @var string Category separator */
	private $_catsep = null;

	// Public variables
	/** @var integer contains the category path for a product */
	public $category_path = null;
	/** @var integer contains the category ID for a product */
	public $category_id = null;
	/** @var integer contains the category setting for publishing */
	public $category_publish = 1;

	// Private variables
	/** @var bool contains setting if the database tables have been loaded */
	private $_tables_loaded = false;

	/**
	 * Here starts the processing
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getStart() {
		if (!$this->_tables_loaded) $this->_loadTables();
	}

	/**
	 * Gets the ID belonging to the category path
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		string	$category_path	the path to get the ID for
	 * @param		int		$vendor_id		the vendor ID the category belongs to
	 * @return		array	containing category_id
	 * @since 		3.0
	 */
	public function getCategoryIdFromPath($category_path, $vendor_id=1) {
		// Check for any missing categories, otherwise create them
		$category = $this->_csvCategory($category_path, $vendor_id);

		return array('category_id' => $category[0]);
	}

  	/**
  	 * Inserts the category/categories for a product
	 *
	 * Any existing categories will be removed first, after that the new
	 * categories will be imported.
  	 *
  	 * @copyright
  	 * @author		RolandD
  	 * @todo
  	 * @see 		_csvCategory()
  	 * @access
  	 * @param 		integer	$product_id 	contains the product ID the category/categories belong to
	 * @param 		integer	$category_path 	contains the category/categories path for the product
	 * @param 		integer	$category_id 	contains a single or array of category IDs
	 * @param 		integer	$product_list 	the product order in the category
	 * @param		integer	$vendor_id		the id of the vendor the category belongs to
  	 * @return
  	 * @since
  	 */
  	public function checkCategoryPath($product_id=false, $category_path=false, $category_id=false, $ordering='NULL', $vendor_id=1) {
  		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		$csvilog->addDebug('Checking category');

		// Check if there is a product ID
		if (!$product_id) return false;
		else {
			// If product_parent_id is true, we have a child product, child products do not have category paths
			// We have a category path, need to find the ID
			if (!$category_id) {
				// Use CsvCategory() method to confirm/add category tree for this product
				// Modification: $category_id now is an array
				$category_id = $this->_csvCategory($category_path, $vendor_id);
			}
			// We have a category_id, no need to find the path
			if ($category_id) {
				// Delete old entries only if the user wants us to
				if (!$template->get('append_categories', 'product', false)) {
					$db = JFactory::getDBO();
					$q = "DELETE FROM #__virtuemart_product_categories WHERE virtuemart_product_id = ".$product_id;
					$db->setQuery($q);
					$db->query();
					$csvilog->addDebug(JText::_('COM_CSVI_DELETE_OLD_CATEGORIES_XREF'), true);
				}
				else $csvilog->addDebug(JText::_('COM_CSVI_NOT_DELETE_OLD_CATEGORIES_XREF'));

				// Insert new product/category relationships
				$category_xref_values = array('virtuemart_product_id' => $product_id, 'ordering' => $ordering);
				foreach( $category_id as $value ) {
					$category_xref_values['virtuemart_category_id'] = $value;
					$this->_product_categories_xref->bind($category_xref_values);
					$this->_product_categories_xref->store();
					$this->_product_categories_xref->reset();
					$category_xref_values['virtuemart_category_id'] = '';
				}
			}
		}
	}

	/**
	 * Load the category related tables
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _loadTables() {
		$this->_categories = $this->getTable('categories');
		$this->_categories_lang = $this->getTable('categories_lang');
		$this->_categories_xref = $this->getTable('categories_xref');
		$this->_product_categories_xref = $this->getTable('product_categories_xref');
		$this->_tables_loaded = true;
	}

	/**
	 * Cleaning the product related tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _cleanTables() {
		$this->_categories->reset();
		$this->_categories_lang->reset();
		$this->_categories_xref->reset();

		// Clean the local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') $this->$name = $value;
		}
	}

	/**
	 * Creates categories from slash delimited line
	 *
	 * @copyright
	 * @author 		John Syben, RolandD
	 * @todo
	 * @see
	 * @access
	 * @param 		array	$category_path	contains the category/categories for a product
	 * @param		int		$vendor_id		the id of the vendor the category belongs to
	 * @return 		array containing category IDs
	 * @since
	 */
	private function _csvCategory($category_path, $vendor_id=1) {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		// Load the category separator
		if (is_null($this->_catsep)) {
			$this->_catsep = $template->get('category_separator', 'general', '/');
		}

		// Check if category_path is an array, if not make it one
		if (!is_array($category_path)) $category_path = array($category_path);

		// Get all categories in this field delimited with |
		foreach ($category_path as $line) {
			$csvilog->addDebug('Checking category path: '.$line);

			// Explode slash delimited category tree into array
			$category_list = explode($this->_catsep, $line);
			$category_count = count($category_list);

			$category_parent_id = '0';

			// For each category in array
			for($i = 0; $i < $category_count; $i++) {
				// Check the cache first
				if (array_key_exists($category_parent_id.'.'.$category_list[$i], $this->_category_cache)) {
					$category_id = $this->_category_cache[$category_parent_id.'.'.$category_list[$i]];
				}
				else {
					// See if this category exists with it's parent in xref
					$lang = $template->get('language', 'general');
					$query = $db->getQuery(true);
					$query->select('c.virtuemart_category_id');
					$query->from('#__virtuemart_categories c');
					$query->leftJoin('#__virtuemart_category_categories x ON c.virtuemart_category_id = x.category_child_id');
					$query->leftJoin('#__virtuemart_categories_'.$lang.' l ON l.virtuemart_category_id = c.virtuemart_category_id');
					$query->where('l.category_name = '.$db->Quote($category_list[$i]));
					$query->where('x.category_child_id = c.virtuemart_category_id');
					$query->where('x.category_parent_id = '.$category_parent_id);
					$db->setQuery($query);
					$category_id = $db->loadResult();
					$csvilog->addDebug(JText::_('COM_CSVI_CHECK_CATEGORY_EXISTS'), true);

					// Add result to cache
					$this->_category_cache[$category_parent_id.'.'.$category_list[$i]] = $category_id;
				}

				// Category does not exist - create it
				if (is_null($category_id)) {
					$timestamp = time();

					// Let's find out the last category in the level of the new category
					$query = $db->getQuery(true);
					$query->select('MAX(c.ordering) + 1 AS ordering');
					$query->from('#__virtuemart_categories c');
					$query->leftJoin('#__virtuemart_category_categories x ON c.virtuemart_category_id = x.category_child_id');
					$query->where('x.category_child_id = c.virtuemart_category_id');
					$query->where('x.category_parent_id = '.$category_parent_id);
					$db->setQuery($query);
					$list_order = $db->loadResult();
					if (is_null($list_order)) $list_order = 1;

					// Find the category and flypage setting
					$configname = 'Csvi'.$template->get('component', 'options').'_Config';
					$config = new $configname();

					// Add category
					$this->_categories->set('virtuemart_vendor_id', $vendor_id);
					$this->_categories->set('created_on', $timestamp);
					$this->_categories->set('modified_on', $timestamp);
					$this->_categories->set('ordering', $list_order);
					$this->_categories->set('published', $this->category_publish);
					$this->_categories->set('category_template', $config->get('categorytemplate'));
					$this->_categories->set('category_layout', $config->get('categorylayout'));
					$this->_categories->set('products_per_row', $config->get('products_per_row'));
					$this->_categories->set('category_product_layout', $config->get('productlayout'));
					$this->_categories->store();
					$csvilog->addDebug('Add new category:', true);
					$category_id = $this->_categories->get('virtuemart_category_id');

					// Add the category name to the language table
					$this->_categories_lang->set('virtuemart_category_id', $category_id);
					$this->_categories_lang->set('category_name', $category_list[$i]);
					$this->_categories_lang->check();
					$this->_categories_lang->store();

					// Add result to cache
					$this->_category_cache[$category_parent_id.'.'.$category_list[$i]] = $category_id;

					// Create xref with parent
					$this->_categories_xref->set('category_parent_id', $category_parent_id);
					$this->_categories_xref->set('category_child_id', $category_id);
					$this->_categories_xref->store();

					$csvilog->addDebug('Add new category xref:', true);

					// Clean for the next row
					$this->_categories->reset();
					$this->_categories_lang->reset();
					$this->_categories_xref->reset();

				}
				// Set this category as parent of next in line
				$category_parent_id = $category_id;
			}
			$category[] = $category_id;
		}
		// Return an array with the last category_ids which is where the product goes
		return $category;
	}
}
?>