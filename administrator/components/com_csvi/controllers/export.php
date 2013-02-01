<?php
/**
 * Export controller
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: export.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Export Controller
 *
* @package CSVI
 */
class CsviControllerExport extends JController {

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
	 * @since 		3.0
	 */
	public function __construct() {
		parent::__construct();

		$this->registerTask('getUser','getData');
		$this->registerTask('getProduct','getData');
		$this->registerTask('getItemProduct','getData');
		$this->registerTask('loadfields','getData');
		$this->registerTask('loadtables','getData');
		$this->registerTask('loadsites','getData');
	}

	/**
	 * Show the export option screen
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
	public function Export() {
		$jinput = JFactory::getApplication()->input;
		// Create the view object
		$view = $this->getView('export', $jinput->get('format', 'html'));

		// Standard model
		$view->setModel( $this->getModel( 'export', 'CsviModel' ), true );
		$view->setModel( $this->getModel( 'templates', 'CsviModel' ));
		$view->setModel( $this->getModel( 'availablefields', 'CsviModel' ));

		// Now display the view
		$view->display();
	}

	/**
	 * Retrieve different kinds of data in JSON format
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getData() {
		// Create the view object
		$view = $this->getView('export', 'json');

		// Standard model
		$view->setModel( $this->getModel( 'export', 'CsviModel' ), true );
		$view->setModel( $this->getModel( 'availablefields', 'CsviModel' ));
		$view->setLayout('export');

		// Now display the view
		$view->display();
	}




}
?>
