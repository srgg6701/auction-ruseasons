<?php
/**
 * Import view
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

/**
 * Import View
 *
* @package CSVI
 */
class CsviViewProcess extends JView {

	/**
	 * Import view display method
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function display($tpl = null) {
		$jinput = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		$option = $jinput->get('option');
		
		// Load the models
		$model = $this->getModel();
		$this->setModel(JModel::getInstance('templates', 'CsviModel'));
		$this->setModel(JModel::getInstance('availablefields', 'CsviModel'));

		// Load stylesheet
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'administrator/components/com_csvi/assets/css/process.css');

		// Set the template ID
		$template_id = $jinput->get('template_id', $session->get($option.'.select_template', 0), 'int');
		$jinput->set('template_id', $template_id);

		// Load the saved templates
		$template_model = $this->getModel('Templates');
		$this->templates = JHtml::_('select.genericlist', $template_model->getTemplates(), 'select_template', '', 'value', 'text', $jinput->get('template_id', 0, 'int'));

		// Load the selected template
		$this->loadHelper('template');
		$this->template = new CsviTemplate();
		$this->template->load($template_id);
		$jinput->set('template', $this->template);

		// Set the action, component and operation for the form
		if ($template_id > 0) $jinput->set('jform', $this->template->getSettings());

		// Load the option templates
		$this->optiontemplates = $model->getOptions();

		// Get the options for the user
		$this->form = $model->getForm(array(), true, $this->optiontemplates);

		// Load the fields
		$av_model = $this->getModel('availablefields');
		$this->templatefields = $av_model->getAvailableFields($this->form->getValue('operation','options'), $this->form->getValue('component','options'), 'object', $this->form->getValue('custom_table'));
		
		// Load the replacements
		$this->replacements = $this->get('Replacements');
		
		// Add the component path to find template files
		$this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.'/views/process/tmpl/'.$this->form->getValue('component','options').'/'.$this->form->getValue('action','options'));
		$this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.'/views/process/tmpl/'.$this->form->getValue('action','options'));

		// Load the helper
		$this->loadHelper($this->form->getValue('component','options'));
		
		// Load the configuration helper
		$this->loadHelper($this->form->getValue('component','options').'_config');
		$classname = 'Csvi'.$this->form->getValue('component','options').'_config';
		if (class_exists($classname)) $this->config = new $classname;
		
		// Get the panel
		$this->loadHelper('panel');

		// Get the toolbar title
		JToolBarHelper::title(JText::_('COM_CSVI_PROCESS'), 'csvi_process_48');

		// Get the toolbar
		JToolBarHelper::custom('cronline', 'csvi_cron_32', 'csvi_cron_32', JText::_('COM_CSVI_CRONLINE'), false);
		JToolBarHelper::custom('process.imexport', 'csvi_process_32', 'csvi_process_32', JText::_('COM_CSVI_PROCESS'), false);
		//JToolBarHelper::help('process.html', true);

		// Display it all
		parent::display($tpl);
	}
}
?>
