<?php
/**
 * Maintenance controller
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: maintenance.json.php 2275 2013-01-03 21:08:43Z RolandD $
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
		$view->setModel($this->getModel( 'availablefields', 'CsviModel' ));

		// Now display the view
		$view->display();
	}
}
?>
