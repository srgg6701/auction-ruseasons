<?php
/**
 * Replacements controller
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: templatetypes.php 1760 2012-01-02 19:50:19Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controlleradmin');

/**
 * Replacements Controller
 *
 * @package    CSVI
 */
class CsviControllerReplacements extends JControllerAdmin {
	
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
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
}
?>
