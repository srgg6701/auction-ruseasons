<?php
/**
 * VirtueMart config class
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: com_virtuemart_config.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * The VirtueMart Config Class
 *
* @package CSVI
 */
class CsviCom_VirtueMart_Config {

	private $_vmcfgfile = null;
	private $_vmcfg = array();

	public function __construct() {
		$this->_vmcfgfile = JPATH_ADMINISTRATOR.'/components/com_virtuemart/virtuemart.cfg';
		$this->_parse();
	}

	/**
	 * Finds a given VirtueMart setting
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access
	 * @param 		string $setting The config value to find
	 * @return 		mixed	value if found | false if not found
	 * @since		4.0
	 */
	public function get($setting) {
		if (isset($this->_vmcfg[$setting])) {
			return $this->_vmcfg[$setting];
		}
		else return false;
	}

	/**
	 * Parse the VirtueMart configuration
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _parse() {
		// Parse the configuration file
		if (file_exists($this->_vmcfgfile)) {
			$config = file_get_contents($this->_vmcfgfile);
			// Do some cleanup
			$config = str_replace('#', ';', $config);
			
			// Check if the command is available
			if (!function_exists('parse_ini_string') ) {
				$array = array();
				$lines = explode("\n", $config );
				foreach( $lines as $line ) {
					$statement = preg_match(
							"/^(?!;)(?P<key>[\w+\.\-]+?)\s*=\s*(?P<value>.+?)\s*$/", $line, $match );
						
					if( $statement) {
						$key    = $match[ 'key' ];
						$value    = $match[ 'value' ];

						# Remove quote
						if( preg_match( "/^\".*\"$/", $value ) || preg_match( "/^'.*'$/", $value ) ) {
							$value = mb_substr( $value, 1, mb_strlen( $value ) - 2 );
						}

						$array[ $key ] = $value;
					}
				}
				$this->_vmcfg = $array;
			}
			else $this->_vmcfg = parse_ini_string($config);
		}
	}
}
?>