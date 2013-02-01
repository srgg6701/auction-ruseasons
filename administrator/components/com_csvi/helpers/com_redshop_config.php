<?php
/**
 * redSHOP config class
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: com_redshop_config.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * The redSHOP Config Class
 *
* @package CSVI
 */
class CsviCom_Redshop_Config {

	private $_redshopcfgfile = null;
	private $_redshopcfg = array();

	public function __construct() {
		$this->_redshopcfgfile = JPATH_ADMINISTRATOR.'/components/com_redshop/helpers/redshop.cfg.php';
		$this->_redshopcfg = file($this->_redshopcfgfile);
	}

	/**
	* Finds a given redSHOP setting
	* @var string $setting The config value to find
	* @return string the value of the config setting
	*/
	public function get($setting) {
		$key = $this->array_find($setting, $this->_redshopcfg);
		if ($key) {
			$find_setting = explode('\', \'', $this->_redshopcfg[$key]);
			return substr(trim($find_setting[1]), 0, -3);
		}
		else return false;
	}

	/**
	* Searched the array for a partial value
	* @return mixed Array key if found otherwise false
	*/
	private function array_find($needle, $haystack) {
	   foreach ($haystack as $key => $item) {
		  if (stripos($item, $needle) !== FALSE) {
			 return $key;
			 break;
		  }
	   }
	   // Nothing found return false
	   return false;
	}
}
?>