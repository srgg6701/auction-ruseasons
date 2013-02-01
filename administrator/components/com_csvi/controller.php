<?php
/**
 *
 * CSVI Controller
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: controller.php 427 2011-09-15 20:27:25Z roland $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * Base controller
 *
 * @package CSVI
 */
class CsviController extends JController {

	/**
	* Method to display the view
	*
	* @access	public
	*/
	public function display($cachable = false, $urlparams = false) {
		
		parent::display($cachable, $urlparams);

		return $this;
	}
}
?>
