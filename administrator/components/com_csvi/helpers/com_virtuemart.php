<?php
/**
 * VirtueMart helper file
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: com_virtuemart.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

/**
 * The VirtueMart Config Class
 *
* @package CSVI
 */
class Com_VirtueMart {

	private $_csvidata = null;
	private $_vendor_id = null;
	private $_related_id = null;
	private $_catsep = null;
	private $_options = array();

	/**
	 * Constructor
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
	public function __construct() {
		$jinput = JFactory::getApplication()->input;
		$this->_csvidata = $jinput->get('csvi_data', null, null);
	}

	/**
	 * Get the product id, this is necessary for updating existing products
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo 		Reduce number of calls to this function
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		integer	product_id is returned
	 * @since 		3.0
	 */
	public function getProductId() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$update_based_on = $template->get('update_based_on', 'product', 'product_sku');
		switch ($update_based_on) {
			case 'product_sku':
				$product_id = $this->_csvidata->get('product_id');
				if ($product_id) {
					return $product_id;
				}
				else {
					$product_sku = $this->_csvidata->get('product_sku');
					if ($product_sku) {
						$query = $db->getQuery(true);
						$query->select('virtuemart_product_id');
						$query->from('#__virtuemart_products');
						$query->where('product_sku = '.$db->Quote($product_sku));
						$db->setQuery($query);
						$csvilog->addDebug(JText::_('COM_CSVI_FIND_PRODUCT_SKU'), true);
						return $db->loadResult();
					}
					else return false;
				}
				break;
			case 'product_mpn':
				$mpn_column = $template->get('mpn_column_name', 'product', false);
				$product_mpn = $this->_csvidata->get($mpn_column);
				if ($product_mpn) {
					$query = $db->getQuery(true);
					$query->select('virtuemart_product_id');
					$query->from('#__virtuemart_products');
					$query->where($db->quoteName($mpn_column)." = ".$db->Quote($product_mpn));
					$db->setQuery($query);
					$csvilog->addDebug(JText::_('COM_CSVI_FIND_PRODUCT_MPN'), true);
					return $db->loadResult();
				}
				else return false;
				break;
			case 'product_child_sku':
				$product_sku = $this->_csvidata->get('product_sku');
				$product_parent_sku = $this->_csvidata->get('product_parent_sku');
				if ($product_sku && $product_parent_sku) {
					// Load the product parent ID
					$query = $db->getQuery(true);
					$query->select('virtuemart_product_id');
					$query->from('#__virtuemart_products');
					$query->where('product_sku = '.$db->Quote($product_parent_sku));
					$db->setQuery($query);
					$csvilog->addDebug(JText::_('COM_CSVI_FIND_PRODUCT_CHILD_PARENT_SKU'), true);
					$product_parent_id = $db->loadResult();

					// Load the product ID of the child
					$query = $db->getQuery(true);
					$query->select('virtuemart_product_id');
					$query->from('#__virtuemart_products');
					$query->where('product_sku = '.$db->Quote($product_sku));
					$query->where('product_parent_id = '.$product_parent_id);
					$db->setQuery($query);
					$csvilog->addDebug(JText::_('COM_CSVI_FIND_PRODUCT_CHILD_SKU'), true);
					return $db->loadResult();
				}
				else if ($product_sku) {
					$query = $db->getQuery(true);
					$query->select('virtuemart_product_id');
					$query->from('#__virtuemart_products');
					$query->where('product_sku = '.$db->Quote($product_sku));
					$db->setQuery($query);
					$csvilog->addDebug(JText::_('COM_CSVI_FIND_PRODUCT_SKU_BASED_CHILD'), true);
					return $db->loadResult();
				}
				else {
					$csvilog->addDebug(JText::_('COM_CSVI_NO_CHILD_NO_PARENT'));
					return false;
				}
				break;
			default:
				return false;
				break;
		}
	}

	/**
	 * Determine vendor ID
	 *
	 * Determine for which vendor we are importing product details.
	 *
	 * The default vendor is the one with the lowest vendor_id value
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Add full vendor support when VirtueMart supports it
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		integer	the vendor database ID
	 * @since 		3.0
	 */
	public function getVendorId() {
		if (!$this->_vendor_id) {
			$jinput = JFactory::getApplication()->input;
			$db = JFactory::getDbo();
			$csvilog = $jinput->get('csvilog', null, null);

			// Get some values
			$vendor_id = $this->_csvidata->get('virtuemart_vendor_id');
			$product_sku = $this->_csvidata->get('product_sku', false);

			// User is uploading vendor_id
			if ($vendor_id) return $vendor_id;

			// User is not uploading vendor_id
			// First get the vendor with the lowest ID
			$query = $db->getQuery(true);
			$query->select('MIN(virtuemart_vendor_id) AS vendor_id');
			$query->from('#__virtuemart_vendors');
			$db->setQuery($query);
			$min_vendor_id = $db->loadResult();

			if ($min_vendor_id) {
				if ($product_sku) {
					$query = $db->getQuery(true);
					$query->select('IF (COUNT(virtuemart_vendor_id) = 0, '.$min_vendor_id.', virtuemart_vendor_id) AS vendor_id');
					$query->from('#__virtuemart_products');
					$query->where('product_sku = '.$db->Quote($product_sku));
					$db->setQuery($query);

					// Existing vendor_id
					$vendor_id = $db->loadResult();
					$csvilog->addDebug(JText::_('COM_CSVI_CHECK_VENDOR_EXISTS'), true);

					$this->_vendor_id = $vendor_id;
					return $vendor_id;
				}
				// No product_sku uploaded
				else {
					$this->_vendor_id = $min_vendor_id;
					return $min_vendor_id;
				}
			}
			else {
				// No vendor found, so lets default to 1
				$this->_vendor_id = 1;
			}
		}
		return $this->_vendor_id;
	}

	/**
	 * Get the shopper group id
	 *
	 * Only get the shopper group id when the shopper_group_name is set
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param		string	$shopper_group_name	the name of the shopper group to find
	 * @return 		integer	the database ID of the shopper group
	 * @since 		3.0
	 */
	public function getShopperGroupId($shopper_group_name) {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$csvilog = $jinput->get('csvilog', null, null);
		$query->select('virtuemart_shoppergroup_id');
		$query->from('#__virtuemart_shoppergroups');
		$query->where('shopper_group_name = '.$db->q($shopper_group_name));
		$db->setQuery($query);
		$shopper_group_id = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_SHOPPER_GROUP_NAME'), true);
		return $shopper_group_id;
	}

	/**
	 * Get the currency ID of the specified vendor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$vendor_id	the ID of the vendor
	 * @return
	 * @since 		4.0
	 */
	public function getVendorCurrency($vendor_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('vendor_currency'));
		$query->from($db->quoteName('#__virtuemart_vendors'));
		$query->where($db->quoteName('vendor_currency').' = '.$db->quote($vendor_id));
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
  	 * Gets the default Shopper Group ID
  	 *
  	 * @copyright
  	 * @author 		RolandD
  	 * @todo 		add error checking
  	 * @see
  	 * @access 		protected
  	 * @param
  	 * @return 		integer	the database shopper ID
  	 * @since		4.0
  	 */
	public function getDefaultShopperGroupID() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);

		$vendor_id = $this->getVendorId();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('virtuemart_shoppergroup_id');
		$query->from('#__virtuemart_shoppergroups');
		$query->where($db->quoteName('default').' = 1');
		$query->where($db->quoteName('virtuemart_vendor_id').' = '.$vendor_id);
		$db->setQuery($query);
		$default = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_GET_DEFAULT_SHOPPER_GROUP'), true);
		return $default;
	}

	/**
	 * Create a slug
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$name	the string to turn into a slug
	 * @return 		string	the slug for the product
	 * @since 		4.0
	 */
	public function createSlug($name) {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);

		// Transliterate
		$lang = new JLanguage($template->get('language', 'general', '', null, 0, false));
		$str = $lang->transliterate($name);

		// Trim white spaces at beginning and end of alias and make lowercase
		$str = trim(JString::strtolower($str));

		// Remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '-', $str);

		// Trim dashes at beginning and end of alias
		$str = trim($str, '-');

		// If we are left with an empty string, make a date with random number
		if (trim(str_replace('-', '', $str)) == '') {
			$jdate = JFactory::getDate();
			$str = $jdate->format("Y-m-d-h-i-s").mt_rand();
		}
		return $str;
	}

	/**
	 * Get the custom related field ID
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
	public function getRelatedId() {
		if (!$this->_related_id) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('virtuemart_custom_id');
			$query->from('#__virtuemart_customs');
			$query->where('virtuemart_vendor_id = '.$this->getVendorId());
			$query->where('field_type = '.$db->Quote('R'));
			$db->setQuery($query);
			$this->_related_id = $db->loadResult();
		}
		return $this->_related_id;
	}

	/**
	 * Load the order status code
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$order_status_name	the name of the order status
	 * @return 		string 	the order status code
	 * @since 		2.3.11
	 */
	public function getOrderStatus($order_status_name) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('order_status_code');
		$query->from('#__virtuemart_orderstates');
		$query->where('order_status_name = '.$db->Quote($order_status_name));
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	 * Get the currency ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		string	$currency_name	the name of the currency
	 * @param		int		$vendor_id		the ID of the vendor
	 * @return
	 * @since 		4.0
	 */
	public function getCurrencyId($currency_name, $vendor_id) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('virtuemart_currency_id');
		$query->from('#__virtuemart_currencies');
		$query->where('currency_code_3 = '.$db->Quote($currency_name));
		$query->where('virtuemart_vendor_id = '.$vendor_id);
		$db->setQuery($query);
		$currency_id = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_GET_CURRENCY_ID'), true);
		return $currency_id;
	}

	/**
	 * Get the country ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		string	$country_name	the name of the country
	 * @param		string	$country_2_code	the 2 letter notification
	 * @param		string	$country_3_code	the 3 letter notification
	 * @return
	 * @since 		4.0
	 */
	public function getCountryId($country_name=null, $country_2_code=null, $country_3_code=null) {
		$country_id = null;
		if (isset($country_name) || isset($country_2_code) || isset($country_3_code)) {
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('virtuemart_country_id');
			$query->from('#__virtuemart_countries');
			if (isset($country_name)) $query->where('country_name = '.$db->Quote($country_name));
			else if (isset($country_2_code)) $query->where('country_2_code = '.$db->Quote($country_2_code));
			else if (isset($country_3_code)) $query->where('country_3_code = '.$db->Quote($country_3_code));
			$db->setQuery($query);
			$country_id = $db->loadResult();
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_GET_COUNTRY_ID'), true);
		}
		return $country_id;
	}

	/**
	 * Get the state ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		string	$state_name		the name of the state
	 * @param		string	$state_2_code	the 2 letter notification
	 * @param		string	$state_3_code	the 3 letter notification
	 * @return
	 * @since 		4.0
	 */
	public function getStateId($state_name=null, $state_2_code=null, $state_3_code=null) {
		$state_id = null;
		if (isset($state_name) || isset($state_2_code) || isset($state_3_code)) {
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('virtuemart_state_id');
			$query->from('#__virtuemart_states');
			if (isset($state_name)) $query->where('state_name = '.$db->Quote($state_name));
			else if (isset($state_2_code)) $query->where('state_2_code = '.$db->Quote($state_2_code));
			else if (isset($state_3_code)) $query->where('state_3_code = '.$db->Quote($state_3_code));
			$db->setQuery($query);
			$state_id = $db->loadResult();
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_GET_STATE_ID'), true);
		}
		return $state_id;
	}

	/**
	 * Get category list
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		string	$language	the language code for the category names
	 * @return
	 * @since 		4.0
	 */
	public function getCategoryTree($language) {
		// Clean up the language if needed
		$language = strtolower(str_replace('-', '_', $language));

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		// 1. Get all categories
		$query->select('x.category_parent_id AS parent_id, x.category_child_id AS id, l.category_name AS catname');
		$query->from('#__virtuemart_categories c');
		$query->leftJoin('#__virtuemart_category_categories x ON c.virtuemart_category_id = x.category_child_id');
		$query->leftJoin('#__virtuemart_categories_'.$language.' l ON l.virtuemart_category_id = c.virtuemart_category_id');
		$db->setQuery($query);
		$rawcats = $db->loadObjectList();
		if (!empty($rawcats)) {
			// 2. Group categories based on their parent_id
			$categories = array();
			foreach ($rawcats as $key => $rawcat) {
				$categories[$rawcat->parent_id][$rawcat->id]['pid'] = $rawcat->parent_id;
				$categories[$rawcat->parent_id][$rawcat->id]['cid'] = $rawcat->id;
				$categories[$rawcat->parent_id][$rawcat->id]['catname'] = $rawcat->catname;
			}
			if (count($rawcats) > 10) $categorysize = 10;
			else $categorysize = count($rawcats)+1;
		}
		$this->_options = array();
		// Add a don't use option
		$this->_options[] = JHtml::_('select.option', '', JText::_('COM_CSVI_EXPORT_DONT_USE'));

		if (isset($categories)) {
			if (count($categories) > 0) {
				// Take the toplevels first
				foreach ($categories[0] as $key => $category) {
					$this->_options[] = JHtml::_('select.option', $category['cid'], $category['catname']);

					// Write the subcategories
					$suboptions = $this->buildCategory($categories, $category['cid'], array());
				}
			}
		}
		return $this->_options;
	}

	/**
	* Create the subcategory layout
	*
	* @copyright
	* @author		RolandD
	* @todo
	* @see
	* @access 		private
	* @param
	* @return 		array	select options for the category tree
	* @since 		3.0
	*/
	private function buildCategory($cattree, $catfilter, $subcats, $loop=1) {
		if (isset($cattree[$catfilter])) {
			foreach ($cattree[$catfilter] as $subcatid => $category) {
				$this->_options[] = JHtml::_('select.option', $category['cid'], str_repeat('>', $loop).' '.$category['catname']);
				$subcats = $this->buildCategory($cattree, $subcatid, $subcats, $loop+1);
			}
		}
	}

	/**
	 * Construct the category path
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function constructCategoryPath($catids) {
		$catpaths = array();
		if (is_array($catids)) {
			$jinput = JFactory::getApplication()->input;
			$template = $jinput->get('template', null, null);
			$csvilog = $jinput->get('csvilog', null, null);
			$db = JFactory::getDbo();

			// Load the category separator
			if (is_null($this->_catsep)) {
				$jinput = JFactory::getApplication()->input;
				$template = $jinput->get('template', null, null);
				$this->_catsep = $template->get('category_separator', 'general', '/');
			}

			// Get the paths
			foreach ($catids as $category_id) {
				// Create the path
				$paths = array();
				while ($category_id > 0) {
					$query = $db->getQuery(true);
					$query->select('category_parent_id, l.category_name');
					$query->from('#__virtuemart_category_categories x');
					$query->leftJoin('#__virtuemart_categories c ON x.category_child_id = c.virtuemart_category_id');
					$query->leftJoin('#__virtuemart_categories_'.$template->get('language', 'general').' l ON x.category_child_id = l.virtuemart_category_id');
					$query->where('category_child_id = '.$category_id);
					$db->setQuery($query);
					$path = $db->loadObject();
					$csvilog->addDebug('Get cat ID'.$category_id, true);
					// $catpaths[] = $this->_getJoomFishCategory($category_id, trim($path->category_name));
					if (is_object($path)) {
						$paths[] = $path->category_name;
						$category_id = $path->category_parent_id;
					}
					else {
						$csvilog->addDebug('COM_CSVI_CANNOT_GET_CATEGORY_ID');
						$csvilog->AddStats('incorrect', 'COM_CSVI_CANNOT_GET_CATEGORY_ID');
						return '';
					}
				}

				// Create the path
				$paths = array_reverse($paths);
				$catpaths[] = implode($this->_catsep, $paths);
			}
		}
		return $catpaths;
	}

	/**
	 * Creates the category path based on a category ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		int	$category_id the ID to create the category path from
	 * @return 		string	the category path
	 * @since 		3.0
	 */
	public function createCategoryPath($product_id, $id=false) {
		$db = JFactory::getDbo();

		// Get the category paths
		$query = $db->getQuery(true);
		$query->select($db->quoteName('virtuemart_category_id'));
		$query->from($db->quoteName('#__virtuemart_product_categories'));
		$query->where($db->quoteName('virtuemart_product_id').' = '.$db->quote($product_id));
		$db->setQuery($query);
		$catids = $db->loadColumn();

		if (!empty($catids)) {
			// Return the paths
			if ($id) {
				$result = $db->loadResultArray();
				if (is_array($result)) return implode('|', $result);
				else return null;
			}
			else {
				$catpaths = $this->constructCategoryPath($catids);
				if (is_array($catpaths)) return implode('|', $catpaths);
				else return null;
			}
		}
		else return null;
	}

	/**
	 * Create a category path based on ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		$catids array	list of IDs to generate category path for
	 * @return
	 * @since 		4.0
	 */
	public function createCategoryPathById($catids) {
		if (!is_array($catids)) $catids = (array)$catids;
		$paths = $this->constructCategoryPath($catids);
		if (is_array($paths)) return implode('|', $paths);
		else return '';
	}

	/**
	* Get the category ID for a product
	*
	* @copyright
	* @author 		RolandD
	* @todo
	* @see
	* @access 		protected
	* @param 		int	$product_id	the product ID to get the category for
	* @return 		int	the category ID the product is linked to limited to 1
	* @since 		3.0
	*/
	public function getCategoryId($product_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('virtuemart_category_id');
		$query->from('#__virtuemart_product_categories');
		$query->where('virtuemart_product_id = '.$product_id);
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	/**
	 * Determine the shipping cost
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.5
	 */
	public function shippingCost($product_price) {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$prices = $template->get('shopper_shipping_export_fields', '', array());
		$fee = null;
		if (!empty($prices)) {
			foreach ($prices['_price_from'] as $kfrom => $price_from) {
				// Check if we have an end price
				$price_from = str_replace(',', '.', $price_from);
				$price_to = str_replace(',', '.', $prices['_price_to'][$kfrom]);
				if (!empty($price_to)) {
					if ($product_price >= $price_from && $product_price < $price_to) {
						$fee = $kfrom;
						break;
					}
				}
				else {
					if ($product_price >= $price_from) {
						$fee = $kfrom;
						break;
					}
				}
			}
		}

		if (!is_null($fee)) return $prices['_fee'][$fee];
		else return false;
	}

	/**
	* Get the list of order users
	*
	* @copyright
	* @author		RolandD
	* @todo
	* @see
	* @access 		public
	* @param
	* @return 		array of objects
	* @since 		4.0
	*/
	public function getOrderUser() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$filter = $jinput->get('filter');
		$q = "SELECT DISTINCT virtuemart_user_id AS user_id,
				IF (LENGTH(TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name))) = 0, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."',
				IF (TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name)) is NULL, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."', CONCAT(first_name, ' ', middle_name, ' ', last_name))) AS user_name
				FROM #__virtuemart_order_userinfos
				WHERE (first_name LIKE ".$db->Quote('%'.$filter.'%')."
					OR middle_name LIKE ".$db->Quote('%'.$filter.'%')."
					OR last_name LIKE ".$db->Quote('%'.$filter.'%').")
				ORDER BY user_name
				LIMIT 10;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of order products
	*
	* @copyright
	* @author		RolandD
	* @todo
	* @see
	* @access 		public
	* @param
	* @return 		array of objects
	* @since 		4.0
	*/
	public function getOrderProduct() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$filter = $jinput->get('filter');
		$q = "SELECT DISTINCT order_item_sku AS product_sku, order_item_name AS product_name
				FROM #__virtuemart_order_items o
				WHERE (order_item_sku LIKE ".$db->Quote('%'.$filter.'%')."
					OR order_item_name LIKE ".$db->Quote('%'.$filter.'%').")
				ORDER BY order_item_name
				LIMIT 10;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	 * Get the list of order item products
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of objects
	 * @since 		4.0
	 */
	public function getOrderItemProduct() {
		$db = JFactory::getDBO();
		$filter = JRequest::getVar('filter');
		$q = "SELECT DISTINCT order_item_sku AS product_sku, order_item_name AS product_name
				FROM #__virtuemart_order_items o
				WHERE (o.order_item_sku LIKE ".$db->Quote('%'.$filter.'%')."
					OR o.order_item_name LIKE ".$db->Quote('%'.$filter.'%').")
				ORDER BY order_item_name
				LIMIT 10;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
}
?>