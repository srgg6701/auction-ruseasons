<?php
/**
 * Maintenance controller
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: maintenance.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controllerform');

/**
 * Maintenance Controller
 *
 * @package    CSVIVirtueMart
 */
class CsviControllerMaintenance extends JControllerForm {

	/**
	 * Show the maintenance screen
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
	public function Maintenance() {
		// Create the view object
		$view = $this->getView('maintenance', 'html');

		// Standard model
		$view->setModel( $this->getModel( 'maintenance', 'CsviModel' ), true );

		// Extra models
		$view->setModel( $this->getModel( 'log', 'CsviModel' ));
		$view->setModel( $this->getModel( 'availablefields', 'CsviModel' ));

		// View
		if (!JRequest::getBool('cron', false)) {
			if (JRequest::getInt('run_id') > 0) $view->setLayout('log');
		}
		else $view->setLayout('cron');

		// Now display the view
		$view->display();
	}

	/**
	 * Redirect to the log screen
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.3
	 */
	private function _outputHtml() {
		$this->setRedirect('index.php?option=com_csvi&task=maintenance.maintenance&run_id='.JRequest::getInt('import_id'));
	}

	/**
	 * Handle the cron output
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.3
	 */
	private function _outputCron() {
		// Create the view object
		$view = $this->getView('maintenance', 'html');

		// Standard model
		$view->setModel( $this->getModel( 'maintenance', 'CsviModel' ), true );

		// Extra models
		$view->setModel( $this->getModel( 'log', 'CsviModel' ));

		// View
		$view->setLayout('cron');

		// Now display the view
		$view->display();
	}

	/**
	 * Update available fields
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function updateAvailableFields() {
		// Prepare
		$jinput = JFactory::getApplication()->input;
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Check if we are running a cron job
		if ($jinput->get('cron', false, 'bool')) {
			// Pre-configuration
			$available_fields = $this->getModel('availablefields', 'CsviModel');
			$available_fields->prepareAvailableFields();

			// Update the available fields
			$available_fields->getFillAvailableFields();

			// Finish
			$model->getFinishProcess();

			// Redirect
			$this->_outputCron();
		}
		else {
			// Create the view object
			$view = $this->getView('maintenance', 'json');

			// Pre-configuration
			$available_fields = $this->getModel('availablefields', 'CsviModel');
			$available_fields->prepareAvailableFields();

			// View
			$view->setLayout('availablefields');

			// Now display the view
			$view->display();
		}
	}

	/**
	 * Update available fields in steps
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function updateAvailableFieldsSingle() {
		// Create the view object
		$view = $this->getView('maintenance', 'json');

		// View
		$view->setLayout('availablefields');

		// Load the model
		$view->setModel($this->getModel('maintenance', 'CsviModel'), true);
		$view->setModel($this->getModel('availablefields', 'CsviModel'));

		// Now display the view
		$view->display();
	}

	/**
	 * Install sample templates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function installDefaultTemplates() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getInstallDefaultTemplates();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Sort categories
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function sortCategories() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getSortCategories();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Remove empty categories
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function removeEmptyCategories() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getRemoveEmptyCategories();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Load the exchange rates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function exchangeRates() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getExchangeRates();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Clean the cache folder
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function cleanTemp() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getCleanTemp();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Backup the CSVI VirtueMart templates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function backupTemplates() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getBackupTemplates();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Restore the CSVI VirtueMart templates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function restoreTemplates() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getRestoreTemplates();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Load the ICEcat index files
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function icecatIndex() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Check if we are running a cron job
		if (JRequest::getBool('cron', false)) {
			JRequest::setVar('loadtype', false);
		}

		// Perform the task
		$result = $model->getIcecatIndex();

		// See if we need to do the staggered import of the index file
		switch ($result) {
			case 'full':
				// Finish
				$model->getFinishProcess();

				// Redirect
				if (!JRequest::getBool('cron', false)) $this->_outputHtml();
				else $this->_outputCron();
				break;
			case 'single':
				// Create the view object
				$view = $this->getView('maintenance', 'json');

				// View
				$view->setLayout('icecat');

				// Now display the view
				$view->display();
				break;
			case 'cancel':
				// Finish
				$model->getFinishProcess();

				// Redirect
				if (!JRequest::getBool('cron', false)) $this->_outputHtml();
				else $this->_outputCron();
				break;
		}
	}

	/**
	 * Empty the VirtueMart tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function icecatSingle() {
		// Create the view object
		$view = $this->getView('maintenance', 'json');

		// View
		$view->setLayout('icecat');

		// Load the model
		$view->setModel($this->getModel('maintenance', 'CsviModel'), true);
		$view->setModel($this->getModel( 'log', 'CsviModel' ));

		// Now display the view
		$view->display();
	}

	/**
	 * Optimize the database tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function optimizeTables() {
		// Prepare
		$jinput = JFactory::getApplication()->input;
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getOptimizeTables();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!$jinput->get('cron', false, 'bool')) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Backup the VirtueMart tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function backupVm() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getBackupVirtueMart();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Empty the VirtueMart tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function emptyDatabase() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getEmptyDatabase();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Unpublish products in unpublished categories
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
	public function unpublishProductByCategory() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->getUnpublishProductByCategory();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}

	/**
	 * Cancel the loading of the ICEcat index
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function cancelImport() {
		// Clean the session
		$session = JFactory::getSession();
		$option = JRequest::getVar('option');

		$session->set($option.'.icecat_index_file', serialize('0'));
		$session->set($option.'.icecat_rows', serialize('0'));
		$session->set($option.'.icecat_position', serialize('0'));
		$session->set($option.'.icecat_records', serialize('0'));
		$session->set($option.'.icecat_wait', serialize('0'));

		// Redirect back to the maintenance page
		$this->setRedirect('index.php?option='.JRequest::getCmd('option').'&view=maintenance');
	}

	/**
	 * Delete all CSVI VirtueMart backup tables
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
	public function removeCsviTables() {
		// Prepare
		$model = $this->getModel('maintenance');
		$model->getPrepareMaintenance();

		// Perform the task
		$model->removeCsviTables();

		// Finish
		$model->getFinishProcess();

		// Redirect
		if (!JRequest::getBool('cron', false)) $this->_outputHtml();
		else $this->_outputCron();
	}
}
?>
