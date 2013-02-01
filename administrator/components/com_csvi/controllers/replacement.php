<?php
/**
 *
 * Controller for the replacement editing
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: templatetype.php 1760 2012-01-02 19:50:19Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controllerform');

/**
 * Controller for the template type editing
 *
 * @package Csvi
 * @author 	RolandD
 * @since 	4.0
 */
class CsviControllerReplacement extends JControllerForm {

	/**
	 * Proxy for getModel
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		object of a database model
	 * @since 		4.0
	 */
	public function getModel($name = 'Replacement', $prefix = 'CsviModel') {
		$model = parent::getModel($name, $prefix, array('ignore_request' => false));
		return $model;
	}


}
?>