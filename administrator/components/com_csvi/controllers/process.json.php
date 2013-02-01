<?php
/**
 * Process controller
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: process.json.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Maintenance Controller
 *
 * @package    CSVI
 */
class CsviControllerProcess extends JController {

	/**
	 * Method Description
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string json encoded values
	 * @since 		4.0
	 */
	public function getUser() {
		$jinput = JFactory::getApplication()->input;
		// Load the appropiate helper file
		$component = $jinput->get('component');
		$users = array();
		if ($component) {
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/'.$component.'.php');
			$helper = new $component;
			$users = $helper->getOrderUser();
		}
		echo json_encode($users);
	}

	/**
	 * Method Description
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string json encoded values
	 * @since 		4.0
	 */
	public function getProduct() {
		$jinput = JFactory::getApplication()->input;
		// Load the appropiate helper file
		$component = $jinput->get('component');
		$users = array();
		if ($component) {
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/'.$component.'.php');
			$helper = new $component;
			$products = $helper->getOrderProduct();
		}
		echo json_encode($products);
	}
	/**
	 * Method Description
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string json encoded values
	 * @since 		4.0
	 */
	public function getItemProduct() {
		$jinput = JFactory::getApplication()->input;
		// Load the appropiate helper file
		$component = $jinput->get('component');
		$users = array();
		if ($component) {
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/'.$component.'.php');
			$helper = new $component;
			$products = $helper->getOrderItemProduct();
		}
		echo json_encode($products);
	}

	/**
	 * Load the available sites for XML or HTML export
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
	public function loadSites() {
		$jinput = JFactory::getApplication()->input;
		$model = $this->getModel('process');
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_CSVI_CHOOSE_WEBSITE'));
		$sites = $model->getExportSites($jinput->get('exportsite'));
		foreach ($sites as $site) {
			$options[] = JHtml::_('select.option', $site, JText::_('COM_CSVI_'.strtoupper($site)));
		}
		echo json_encode(JHtml::_('select.genericlist', $options, 'jform[general][export_site]', null, 'value', 'text', $jinput->get('selected'), 'jform_general_export_site'));
	}

	/**
	 * Load fields for the custom import/export 
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
	public function loadFields() {
		$jinput = JFactory::getApplication()->input;
		$availablefields_model = $this->getModel('availablefields');
		$result = $availablefields_model->getAvailableFields($jinput->get('template_type'), $jinput->get('component', 'com_csvi'), 'array', $jinput->get('table_name', '', 'word'));
		echo json_encode($result);
	}
	
	/**
	* Load the category tree
	*
	* @copyright
	* @author		RolandD
	* @todo
	* @see
	* @access 		public
	* @param
	* @return 		string json encoded values
	* @since 		4.0
	*/
	public function loadCategoryTree() {
		$jinput = JFactory::getApplication()->input;
		$helper = new Com_VirtueMart();
		$options = $helper->getCategoryTree($jinput->get('language'));
		echo json_encode($options);
	}

	/* TO BE FIGURED OUT */


	

	public function loadTables() {
		$result = CsviHelper::getCustomTables();
		array_unshift($result, JText::_('COM_CSVI_SELECT_TABLE_FOR_EXPORT'));
		echo json_encode($result);
	}



	public function getStates() {
		$model = $this->getModel('import');
		$options = array();
		$options[] = JHtml::_('select.option', 'none', JText::_('COM_CSVI_ALL_TAX_STATES'));
		$states = array_merge($options, $model->getStates(JRequest::getCmd('country')));
		echo json_encode(JHtml::_('select.genericlist', $states, 'jform[tax][states][]', 'multiple="multiple" size="7"', 'value', 'text', 'none'));
	}
}
?>
