<?php
/**
 * Available Fields view
 *
 * Gives an overview of all available fields that can be used for import/export
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Available Fields View
 *
* @package CSVI
 */
class CsviViewAvailableFields extends JView {

	/**
	* Items to be displayed
	*/
	protected $items;

	/**
	* Pagination for the items
	*/
	protected $pagination;

	/**
	* User state
	*/
	protected $state;

	/**
	 * Available fields view display method
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Replace JError
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function display($tpl = null) {
		// Get the list of available fields
		$this->availablefields = $this->get('Items');

		// Load the pagination
		$this->pagination = $this->get('Pagination');

		// Load the user state
		$this->state = $this->get('State');

		if (!$this->get('FieldCheck')) Throw new Exception(JText::_('COM_CSVI_NO_AVAILABLE_FIELDS'), 0);

		// Get the list of actions
		$options = array();
		$options[] = JHtml::_('select.option', 'import', JText::_('COM_CSVI_IMPORT'));
		$options[] = JHtml::_('select.option', 'export', JText::_('COM_CSVI_EXPORT'));
		$this->actions = JHtml::_('select.genericlist', $options, 'jform_options_action', 'onchange="Csvi.loadTemplateTypes();"', 'value', 'text', $this->state->get('filter.action', ''));

		// Get the list of supported components
		$this->components = JHtml::_('select.genericlist', CsviHelper::getComponents(), 'jform_options_component', 'onchange="Csvi.loadTemplateTypes();"', 'value', 'text', $this->state->get('filter.component'));

		// Get the list of template types
		$model = $this->getModel();
		$templates_model = $model->getModel('templates');
		$operations = $templates_model->getTemplateTypes($this->state->get('filter.action', 'import'), $this->state->get('filter.component', false));

		// Create the operations list
		$this->operations = JHtml::_('select.genericlist', $operations, 'jform_options_operation', '', 'value', 'name', $this->state->get('filter.operation'), false, true);

		// Get the panel
		$this->loadHelper('panel');

		// Show the toolbar
		$this->addToolbar();

		// Display it all
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.0
	 */
	protected function addToolbar() {
		JToolBarHelper::title(JText::_('COM_CSVI_AVAILABLE_FIELDS'), 'csvi_availablefields_48');
		JToolBarHelper::custom('maintenance.updateavailablefields', 'csvi_availablefields_32', 'csvi_availablefields_32', JText::_('COM_CSVI_UPDATE'), false);
		//JToolBarHelper::help('available_fields.html', true);
	}
}
?>