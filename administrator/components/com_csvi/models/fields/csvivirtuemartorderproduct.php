<?php
/**
 * List the order products
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvivirtuemartorderproduct.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * Select list form field with order products
 *
 * @package CSVI
 */
class JFormFieldCsviVirtuemartOrderProduct extends JFormFieldCsviForm {

	protected $type = 'CsviVirtuemartOrderProduct';

	/**
	 * Specify the options to load
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		array	an array of options
	 * @since 		4.0
	 */
	protected function getOptions() {
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
				$db = JFactory::getDbo();
		$products = $template->get('orderproduct', 'order', array(), null);
		$skus = implode(',', $products);
		if (!empty($skus)) {
			foreach ($products as $pkey => $product) {
				$products[$pkey] = $db->Quote($product);
			}
			$q = "SELECT DISTINCT product_sku, product_name
				FROM #__virtuemart_products p, #__virtuemart_order_items o
				WHERE p.virtuemart_product_id = o.virtuemart_product_id
				AND p.product_sku IN (".$skus.")
				ORDER BY product_name;";
			$db->setQuery($q);
			$orderproducts = $db->loadObjectList();
			if (empty($orderproducts)) $orderproducts = array();
			return array_merge(parent::getOptions(), $orderproducts);
		}
		else return parent::getOptions();
	}
}
?>
