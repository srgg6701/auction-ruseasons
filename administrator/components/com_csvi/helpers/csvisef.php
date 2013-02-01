<?php
/**
 * CSVI SEF helper
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvisef.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * SEF helper class for the component
* @package CSVI
 */
Class CsviSef {
	
	// Private variables
	private $_sef = null;
	private $_domainname = null;
	
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
	 * @since 		4.0
	 */
	public function __construct() {
		$this->_domainname = CsviHelper::getDomainName();
	}
	
	/**
	* Create a SEF URL
	*
	* @copyright
	* @author		RolandD
	* @todo			Change exportsef to template
	* @see
	* @access 		private
	* @param 		string	$url	The url to change to SEF
	* @return 		string	the new url
	* @since 		3.0
	*/
	public function getSiteRoute($url) {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);
		$parsed_url = null;
		// Check which SEF component is installed
		if (empty($this->_sef)) {
			if ($template->get('exportsef', 'product', false)) {
				// Joomla SEF
				if (JPluginHelper::isEnabled('system', 'sef')) $this->_sef = 'joomla';
	
				// sh404SEF check
				if (JPluginHelper::isEnabled('system', 'shsef')) $this->_sef = 'sh404sef';
	
				// JoomSEF check
				if (JPluginHelper::isEnabled('system', 'joomsef')) $this->_sef = 'joomsef';
	
				// AceSEF check
				if (JPluginHelper::isEnabled('system', 'acesef')) $this->_sef = 'acesef';
	
				// There is no SEF enabled
				if (empty($this->_sef)) $this->_sef = 'nosef';
			}
			else $this->_sef = 'nosef';
		}
	
		switch ($this->_sef) {
			case 'sh404sef':
				$parsed_url = $this->_sh404Sef($url);
				break;
			case 'joomsef':
				$parsed_url = $this->_joomSef($url);
				break;
			case 'joomla':
				$parsed_url = $this->_joomlaSef($url);
				break;
			case 'acesef':
				$parsed_url = $this->_aceSef($url);
				break;
			case 'nosef':
			default:
				// No SEF router found, returning regular URL
				return $this->_domainname.JRoute::_($url);
				break;
		}
	
		// Clean up the parsed SEF URL
		if (!empty($parsed_url)) {
			// Clean up the parsed SEF URL
			if (substr($parsed_url, 4) == 'http') return $parsed_url;
			else {
				// Check for administrator in the domain
				$adminpos = strpos($parsed_url,'/administrator/');
				if ($adminpos !== false) $parsed_url = substr($parsed_url,$adminpos+15);
	
				// Check if we have a domain name in the URL
				if (!empty($this->_domainname)) {
					$check_domain = str_replace('https', 'http', $this->_domainname);
					$domain = strpos($parsed_url, $check_domain);
					if ($domain === false) {
						if (substr($parsed_url, 0, 1) == '/') $parsed_url = $this->_domainname.$parsed_url;
						else $parsed_url = $this->_domainname.'/'.$parsed_url;
					}
					return $parsed_url;
				}
				else {
					$csvilog->addDebug(JText::_('COM_CSVI_NO_DOMAINNAME_SET'));
					return $url;
				}
			}
		}
	}
	
	/**
	 * Create sh404SEF URLs
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see 		http://dev.anything-digital.com/sh404SEF/
	 * @see			getSiteRoute()
	 * @access 		private
	 * @param 		string	$url	the original URL to turn into SEF
	 * @return 		string SEF URL
	 * @since 		3.0
	 */
	private function _sh404Sef($url) {
		// Load sh404SEF
		require_once(JPATH_ADMINISTRATOR.'/components/com_sh404sef/sh404sef.class.php');
		$sefConfig = shRouter::shGetConfig();
	
		// Turn off any security and flooding setting
		$sefConfig->shSecEnableSecurity = 0;
		$sefConfig->shSecActivateAntiFlood = 0;
	
		// Require some necessary files
		require_once(JPATH_ROOT.'/components/com_sh404sef/shCache.php');
		require_once(JPATH_ROOT.'/components/com_sh404sef/shSec.php');
	
		// Start the sh404sef Router
		if (class_exists('shRouter')) $shRouter = new shRouter();
		else return $this->_domainname.'/'.$url;
	
		// Force the domain name
		$GLOBALS['shConfigLiveSite'] = $this->_domainname;
	
		// Initialize sh404sef
		include_once(JPATH_ROOT.'/components/com_sh404sef/shInit.php');
	
		// Build the SEF URL
		$uri = $shRouter->build($url);
		$sefurl = $uri->toString();
		if (strpos($sefurl, 'http://') === false) {
			$sefurl = str_ireplace('http:/', 'http://', $sefurl);
		}
		return $sefurl;
	}
	
	/**
	 * Create JoomSEF URLs
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see 		http://www.artio.net/joomla-extensions/joomsef
	 * @see			_getSiteRoute()
	 * @access 		private
	 * @param 		string	$url	the original URL to turn into SEF
	 * @return 		string SEF URL
	 * @since 		3.0
	 */
	private function _joomSef($url) {
		// Include Joomla files
		jimport('joomla.application.router');
		require_once(JPATH_ROOT.'/includes/application.php');
	
		// Include JoomSEF
		require_once(JPATH_ROOT.'/components/com_sef/sef.router.php');
		$shRouter = new JRouterJoomSef();
	
		// Build the SEF URL
		$uri = $shRouter->build($url);
		return $uri->toString();
	}
	
	/**
	 * Create Joomla SEF URLs
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see 		http://www.joomla.org/
	 * @see			_getSiteRoute()
	 * @access 		private
	 * @param 		string	$url	the original URL to turn into SEF
	 * @return 		string SEF URL
	 * @since 		3.0
	 */
	private function _joomlaSef($url) {
		// Load Joomla core files for SEF
		jimport('joomla.application.router');
		require_once(JPATH_ROOT.'/includes/application.php');
		require_once(JPATH_ROOT.'/includes/router.php');
		$router = new JRouterSite(array('mode' => 1));
		$uri = $router->build($url);
		return $uri->toString();
	}
	
	/**
	 * Create aceSEF URLs
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see 		http://www.joomace.net/joomla-extensions/acesef
	 * @see			_getSiteRoute()
	 * @access 		private
	 * @param 		string	$url	the original URL to turn into SEF
	 * @return 		string SEF URL
	 * @since 		3.0
	 */
	private function _aceSef($url) {
		jimport('joomla.application.router');
		require_once(JPATH_ROOT.'/includes/application.php');
		require_once(JPATH_ADMINISTRATOR.'/components/com_acesef/library/router.php');
		require_once(JPATH_ADMINISTRATOR.'/components/com_acesef/library/loader.php');
	
		$router = new JRouterAcesef();
		$uri = $router->build($url);
		return $uri->toString();
	}
}
?>
