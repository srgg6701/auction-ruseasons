<?php
/**
 * Import controller
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: importfile.json.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Import Controller
 *
 * Importing a file follows this process:
 * 1. controllers/importfile.php -> importFile
 * 2. models/importfile.php -> prepareImport (sets session values)
 * 3. views/importfile/view.html.php -> display
 * 4. views/importfile/tmpl/default.php JS calls import
 * 5. controllers/importfile.php -> doImport
 * 6. models/importfile.php -> getDoImport (sets session values)
 * 7. views/importfile/view.json.php -> return result
 *
* @package CSVI
 */
class CsviControllerImportfile extends JController {

	/**
	 * Import records called via JavaScript
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		remove global from session vars
	 * @todo
	 * @see 		prepareImport (models/importfile) where the session data is set
	 * @see 		_finishProcess (models/importfile) where the session data is unset
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function doImport() {
		$jinput = JFactory::getApplication()->input;
		// Create the view object
		$vtype = ($jinput->get('cron', false, 'bool')) ? 'cron' : 'json';
		$view = $this->getView('importfile', $vtype);

		// Load the data from the session
		$session = JFactory::getSession();
		$option = $jinput->get('option');

		// Set the run ID
		$jinput->set('run_id', $session->get($option.'.run_id'));

		// Check which helper files to include
		$helper_files = unserialize($session->get($option.'.helper_files'));

		// Load helper files
		$view->addHelperPath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/file/import');
		$view->loadHelper('file');
		$view->loadHelper('template');
		$view->loadHelper('icecat');
		$view->loadHelper('settings');
		if (!empty($helper_files)) {
			foreach ($helper_files as $helper) {
				$view->loadHelper($helper);
			}
		}

		// The template
		$template = unserialize($session->get($option.'.global.template'));

		if (is_object($template)) {
			// Enable the session
			$jinput->set('importsession', true);

			// Set the template
			$jinput->set('template', $template);
			// The logger
			$jinput->set('csvilog', unserialize($session->get($option.'.csvilog')));
			// Set the file handler
			$jinput->set('csvifile', unserialize($session->get($option.'.csvifile')));
			// Load the total line counter
			$jinput->set('totalline', unserialize($session->get($option.'.totalline')));
			// Load the total of records processed
			$jinput->set('recordsprocessed', unserialize($session->get($option.'.recordsprocessed')));
			// Set the fields found in the import file
			$jinput->set('csvifields', unserialize($session->get($option.'.csvifields')));
			// Set the list of available fields
			$jinput->set('avfields', unserialize($session->get($option.'.avfields')));
			// Load the column headers
			$jinput->set('columnheaders', unserialize($session->get($option.'.csvicolumnheaders')));
			// Load the preview handler
			$jinput->set('csvipreview', unserialize($session->get($option.'.csvipreview')));

			// Set the override for the operation model if exists
			$app = JFactory::getApplication();
			$overridefile = JPATH_BASE.'/templates/'.$app->getTemplate().'/html/com_csvi/models/'.$template->get('component', 'options').'/import/'.$template->get('operation', 'options').'.php';
			if (file_exists($overridefile)) $this->addModelPath(JPATH_BASE.'/templates/'.$app->getTemplate().'/html/com_csvi/models/'.$template->get('component', 'options').'/import');
			else $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/'.$template->get('component', 'options').'/import');

			// Load the model for the component
			$view->setModel($this->getModel('importfile', 'CsviModel'), true);
			// Log functions
			$view->setModel($this->getModel('log', 'CsviModel'));
			// General category functions
			$view->setModel($this->getModel('category', 'CsviModel'));
			// Available fields
			$view->setModel($this->getModel('availablefields', 'CsviModel'));

			// Load import specifc helper
			$view->loadHelper($template->get('component', 'options'));
			$view->loadHelper($template->get('component', 'options').'_config');

			// Prepare for import
			$view->get('DoImport');
		}
		else {
			$jinput->set('importsession', false);
		}
		
		// Set the output screen
		switch ($vtype) {
			case 'cron':
				$view->setLayout('cron');
				break;
			default:
				break;
		}
		
		// Show the screen
		$view->display();
	}
}
?>
