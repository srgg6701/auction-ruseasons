<?php
/**
 * Export model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: export.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Export Model
 *
* @package CSVI
 */
class CsviModelExport extends JModel {

	/**
	* Get the list of order statussen
	 */
	public function getOrderStatus() {
		$db = JFactory::getDBO();
		$q = "SELECT order_status_code, order_status_name
			FROM #__vm_order_status
			ORDER BY list_order";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of order users
	 */
	public function getOrderUser() {
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$filter = $jinput->get('filter');
		$q = "SELECT DISTINCT user_id,
			IF (LENGTH(TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name))) = 0, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."',
			IF (TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name)) is NULL, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."', CONCAT(first_name, ' ', middle_name, ' ', last_name))) AS user_name
			FROM #__vm_order_user_info
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
	*/
	public function getOrderProduct() {
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$filter = $jinput->get('filter');
		$q = "SELECT DISTINCT product_sku, product_name
			FROM #__vm_product p, #__vm_order_item o
			WHERE p.product_id = o.product_id
			AND (p.product_sku LIKE ".$db->Quote('%'.$filter.'%')."
				OR p.product_name LIKE ".$db->Quote('%'.$filter.'%')."
				OR p.product_s_desc LIKE ".$db->Quote('%'.$filter.'%').")
			ORDER BY product_name
			LIMIT 10;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of order products
	*/
	public function getOrderItemProduct() {
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$filter = $jinput->get('filter');
		$q = "SELECT DISTINCT order_item_sku AS product_sku, order_item_name AS product_name
			FROM #__vm_order_item o
			WHERE (o.order_item_sku LIKE ".$db->Quote('%'.$filter.'%')."
				OR o.order_item_name LIKE ".$db->Quote('%'.$filter.'%').")
			ORDER BY order_item_name
			LIMIT 10;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of order products
	*/
	public function getOrderCurrency() {
		$db = JFactory::getDBO();
		$q = "SELECT order_currency, currency_name
			FROM #__vm_orders o, #__vm_currency c
			WHERE o.order_currency = c.currency_code
			GROUP BY currency_name
			ORDER BY currency_name;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of exchange rate currencies
	*/
	public function getExchangeRateCurrency() {
		$db = JFactory::getDBO();
		$q = "SELECT #__csvi_currency.currency_code AS currency_code,
			IF (#__vm_currency.currency_name IS NULL, #__csvi_currency.currency_code, #__vm_currency.currency_name) AS currency_name
			FROM #__csvi_currency
			LEFT JOIN #__vm_currency
			on #__vm_currency.currency_code = #__csvi_currency.currency_code;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of vendors
	 */
	public function getVendors() {
		$db = JFactory::getDBO();
		$q = "SELECT vendor_id, REPLACE(vendor_name, '\\\', '') AS vendor_name
			FROM #__vm_vendor
			ORDER BY vendor_name;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of permissions
	 */
	public function getPermissions() {
		$db = JFactory::getDBO();
		$q = "SELECT group_name AS group_id, group_name
			FROM #__vm_auth_group
			ORDER BY group_name;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Check if there are any templates with fields
	 */
	public function getCountTemplateFields() {
		$db = JFactory::getDBO();
		$q = "SELECT field_template_id, COUNT(field_template_id) AS total
			FROM #__csvi_template_fields
			WHERE field_template_id in (
				SELECT template_id
				FROM #__csvi_templates
				WHERE template_type
				LIKE '%export')
			GROUP BY field_template_id";
		$db->setQuery($q);
		$nrfields = $db->loadResultArray();
		if ($db->getErrorNum() > 0) {
			JError::raiseWarning(0, $db->getErrorMsg());
			return false;
		}
		else {
			/* Check if there are any templates with more than 0 fields */
			foreach ($nrfields as $key => $nr) {
				if ($nr > 0) return true;
			}
		}
	}

	/**
	 * Get a list of all categories and put them in a select list
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	HTML multi select list
	 * @since 		3.0
	 */
	public function getProductCategories() {
		$db = JFactory::getDBO();
		// 1. Get all categories
		$q = "SELECT category_parent_id AS parent_id, category_child_id AS id, category_name AS catname
			FROM #__vm_category c
			LEFT JOIN #__vm_category_xref x
			ON c.category_id = x.category_child_id";
		$db->setQuery($q);
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
		$this->options = array();
		// Add a don't use option
		$this->options[] = JHtml::_('select.option', '', JText::_('COM_CSVI_EXPORT_DONT_USE'));

		if (isset($categories)) {
			if (count($categories) > 0) {
				// Take the toplevels first
				foreach ($categories[0] as $key => $category) {
					$this->options[] = JHtml::_('select.option', $category['cid'], $category['catname']);

					// Write the subcategories
					$suboptions = $this->buildCategory($categories, $category['cid'], array());
				}
			}
		}
		return $this->options;
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
				$this->options[] = JHtml::_('select.option', $category['cid'], str_repeat('>', $loop).' '.$category['catname']);
				$subcats = $this->buildCategory($cattree, $subcatid, $subcats, $loop+1);
			}
		}
	}

	/**
	* Get product type names
	*/
	public function getProductTypeNames() {
		$db = JFactory::getDBO();
		$q = "SELECT product_type_id, product_type_name
			FROM #__vm_product_type";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get payment methods
	*/
	public function getPaymentMethods() {
		$db = JFactory::getDBO();
		$q = "SELECT payment_method_id, payment_method_name
			FROM #__vm_payment_method
			ORDER BY list_order";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get a list of possible VM Item IDs
	*/
	public function getVmItemids() {
	 	$db = JFactory::getDBO();
	 	$q = "SELECT id AS value, name AS text
	 		FROM #__menu
	 		WHERE link LIKE '%com_virtuemart%'";
	 	$db->setQuery($q);
	 	return $db->loadObjectList();
	}

	/**
	 * Load all the shopper groups
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of shopper group objects
	 * @since
	 */
	public function getShopperGroups() {
		$db = JFactory::getDBO();
		$q = "SELECT shopper_group_id AS value, shopper_group_name AS text
			FROM #__vm_shopper_group";
		$db->setQuery($q);
		$shoppergroups = $db->loadObjectList();
		if (!empty($shoppergroups)) return $shoppergroups;
		else return array();
	}

	/**
	 * Load all the manufacturers
	 *
	 * @copyright
	 * @author
	 * @todo
	 * @see
	 * @access
	 * @param
	 * @return
	 * @since
	 */
	function getManufacturers() {
		$db = JFactory::getDBO();
		$q = "SELECT manufacturer_id AS value, mf_name AS text
			FROM #__vm_manufacturer
			ORDER BY mf_name";
		$db->setQuery($q);
		$manufacturers = $db->loadObjectList();
		if (!empty($manufacturers)) return $manufacturers;
		else return array();
	}

	/**
	 * Get the shipping address options
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$type	for what type of export the shipping addresses should be generated
	 * @return 		array	of shipping address objects
	 * @since 		3.0
	 */
	public function getShippingAddress($type) {
		// Get order shipping statusses
		$address = array();
		// Add a dont use option
		$addressoption = new StdClass();
		$addressoption->address_code = '';
		$addressoption->address_name = JText::_('COM_CSVI_EXPORT_DONT_USE');
		$address[] = $addressoption;
		$addressoption = new StdClass();
		$addressoption->address_code = 'BT';
		$addressoption->address_name = JText::_('COM_CSVI_BILLING_ADDRESS');
		$address[] = $addressoption;
		$addressoption = new StdClass();
		$addressoption->address_code = 'ST';
		$addressoption->address_name = JText::_('COM_CSVI_SHIPPING_ADDRESS');
		$address[] = $addressoption;
		if ($type == 'order') {
			$addressoption = new StdClass();
			$addressoption->address_code = 'BTST';
			$addressoption->address_name = JText::_('COM_CSVI_BILLING_SHIPPING_ADDRESS');
			$address[] = $addressoption;
		}
		return $address;
	}

	/**
	 * Get the list of selected order users
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return		array	list of order user objects
	 * @since 		3.0
	 */
	public function getSelectedOrderUser() {
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$db = JFactory::getDBO();
		$orderuser = $template->get('orderuser', 'order', array(), 'array');
		if (!empty($orderuser)) {
			$q = "SELECT DISTINCT user_id,
				IF (LENGTH(TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name))) = 0, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."',
				IF (TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name)) is NULL, '".JText::_('COM_CSVI_EXPORT_ORDER_USER_EMPTY')."', CONCAT(first_name, ' ', middle_name, ' ', last_name))) AS user_name
				FROM #__vm_order_user_info
				WHERE user_id IN (".implode(',', $orderuser).")
				ORDER BY user_name;";
			$db->setQuery($q);
			return $db->loadObjectList();
		}
		else return array();
	}

	/**
	 * Get the list of selected order products
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return		array	list of order product objects
	 * @since 		3.0
	 */
	public function getSelectedOrderProduct() {
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$db = JFactory::getDBO();
		$products = $template->get('orderproduct', 'order', array(), 'array');
		if (!empty($products)) {
			foreach ($products as $pkey => $product) {
				$products[$pkey] = $db->Quote($product);
			}
			$q = "SELECT DISTINCT product_sku, product_name
				FROM #__vm_product p, #__vm_order_item o
				WHERE p.product_id = o.product_id
				AND p.product_sku IN (".implode(',', $products).")
				ORDER BY product_name;";
			$db->setQuery($q);
			return $db->loadObjectList();
		}
		else return array();
	}

	/**
	 * Get the list of selected order item products
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return		array	list of order item product objects
	 * @since 		3.0
	 */
	public function getSelectedOrderItemProduct() {
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$db = JFactory::getDBO();
		$products = $template->get('orderitemproduct', 'orderitem', array(), 'array');
		if (!empty($products)) {
			foreach ($products as $pkey => $product) {
				$products[$pkey] = $db->Quote($product);
			}
			$q = "SELECT DISTINCT product_sku, product_name
				FROM #__vm_product p, #__vm_order_item o
				WHERE p.product_id = o.product_id
				AND p.product_sku IN (".implode(',', $products).")
				ORDER BY product_name;";
			$db->setQuery($q);
			return $db->loadObjectList();
		}
		else return array();
	}

	/**
	 * Get the list of selected order item products
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		INFORMATION_SCHEMA
	 * @see
	 * @access 		public
	 * @param
	 * @return		array	list of order item product objects
	 * @since 		3.0
	 */
	public function getJoomFishLanguages() {
		$db = JFactory::getDBO();
		$conf = JFactory::getConfig();
		$q = "SELECT table_name FROM information_schema.tables WHERE table_schema = ".$db->Quote($conf->getValue('config.db'))." AND table_name = ".$db->Quote($conf->getValue('config.dbprefix').'languages');
		$db->setQuery($q);
		$total = $db->loadResult();
		if (!empty($total)) {
			$q = "SELECT ".$db->quoteName('name')." AS ".$db->quoteName('text').",
					".$db->quoteName('id')." AS ".$db->quoteName('value')."
				FROM #__languages
				ORDER BY name";
			$db->setQuery($q);
			return $db->loadObjectList();
		}
		else return array();
	}

	/**
	 * Get a list of XML sites
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$type	the type of files to find (XML or HTML)
	 * @return 		array	list of XML sites
	 * @since 		3.0
	 */
	public function getExportSites($type) {
		jimport('joomla.filesystem.folder');
		$files = array();
		$path = JPATH_COMPONENT_ADMINISTRATOR.'/helpers/file/export/'.$type;
		if (JFolder::exists($path)) {
			$files = JFolder::files($path, '.php');
			if (!empty($files)) {
				foreach ($files as $fkey => $file) {
					$files[$fkey] = basename($file, '.php');
				}
			}
			else $files = array();
		}

		return $files;
	}

	/**
	 * Load the states to filter on
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$country the name of the country to filter on
	 * @return 		array of available states to filter on
	 * @since 		3.1
	 */
	public function getStates($country) {
		$db = JFactory::getDBO();
		$q = "SELECT tax_state AS value, state_name AS text
				FROM `#__vm_tax_rate`
				LEFT JOIN `#__vm_state`
				ON `#__vm_tax_rate`.tax_state = `#__vm_state`.state_2_code
				LEFT JOIN `#__vm_country`
				ON `#__vm_state`.country_id = `#__vm_country`.country_id
				WHERE `#__vm_country`.country_3_code = ".$db->Quote($country)."
				GROUP BY state_name";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	 * Load the countries to filter on
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of available countries to filter on
	 * @since 		3.1
	 */
	public function getCountries() {
		$db = JFactory::getDBO();
		$q = "SELECT country_3_code AS value, country_name AS text
			FROM `#__vm_tax_rate`
			LEFT JOIN `#__vm_country`
			ON `#__vm_tax_rate`.tax_country = `#__vm_country`.country_3_code
			GROUP BY country_name
			ORDER BY country_name";
		$db->setQuery($q);
		$countries = $db->loadObjectList();
		if (!empty($countries)) return $countries;
		else return array();
	}
}
?>