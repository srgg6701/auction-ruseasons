<?php
/**
 * Export controller
 *
 * @package 	CSVI
 * @author	 	Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: exportfile.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Export Controller
 *
* @package CSVI
 */
class CsviControllerExportfile extends JController {

	/**
	 * Load export model files
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
	public function process() {
		// Create the view object
		$jinput = JFactory::getApplication()->input;
		$view = $this->getView('exportfile', 'html');

		// Default model
		$view->setModel( $this->getModel('exportfile', 'CsviModel' ), true );
		// Log functions
		$view->setModel( $this->getModel('log', 'CsviModel' ));
		// Settings functions
		$view->setModel( $this->getModel('settings', 'CsviModel' ));
		// General import functions
		$view->setModel( $this->getModel('export', 'CsviModel' ));
		// General category functions
		$view->setModel( $this->getModel('category', 'CsviModel' ));
		// Available fields
		$view->setModel( $this->getModel('availablefields', 'CsviModel' ));

		// Load the model
		$model = $this->getModel('exportfile');

		// Add extra helper paths
		$view->addHelperPath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/xml');
		$view->addHelperPath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html');

		// Load the helper classes
		$view->loadHelper('csvidb');
		$view->loadHelper('template');
		$view->loadHelper('csvisef');

		// Prepare for export
		if ($model->getPrepareExport()) {
			// Set the export override
			$app = JFactory::getApplication();
			$template = $jinput->get('template', null, null);
			$overridefile = JPATH_BASE.'/templates/'.$app->getTemplate().'/html/com_csvi/models/export/'.$template->get('operation', 'options').'.php';

			// Add the export model path if override exists
			if (file_exists($overridefile)) $this->addModelPath(JPATH_BASE.'/templates/'.$app->getTemplate().'/html/com_csvi/models/'.$template->get('component', 'options').'/export');
			else $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/'.$template->get('component', 'options').'/export');

			// Load export specifc helper
			$view->loadHelper($template->get('component', 'options'));
			$view->loadHelper($template->get('component', 'options').'_config');

			// Start the export
			$view->display();
		}
		else {
			// Clean up first
			$model->getCleanSession();
			// Redirect back to the export page
			$this->setRedirect('index.php?option=com_csvi&view=process', JText::_('COM_CSVI_ERROR_EXPORT_PREP'), 'error');
		}
	}
}
?>