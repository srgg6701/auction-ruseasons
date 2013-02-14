<?php
/**
 * CSVI General helper
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvi.php 2298 2013-01-29 11:38:39Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Helper class for the component
* @package CSVI
 */
class CsviHelper {

	/**
	 * Combine 2 arrays and update existing values
	 *
	 * @copyright
	 * @author 		Amund
	 * @todo
	 * @see 		http://www.php.net/manual/en/function.array-merge.php#95294
	 * @access 		private
	 * @param 		array	$a	The array to update
	 * @param		array	$b	The array with new values
	 * @return 		array	combined array with all values
	 * @since 		3.0
	 */
	public function arrayExtend($a, $b) {
		foreach($b as $k=>$v) {
			if( is_array($v) ) {
				if( !isset($a[$k]) ) {
					$a[$k] = $v;
				} else {
					$a[$k] = self::arrayExtend($a[$k], $v);
				}
			} else {
				$a[$k] = $v;
			}
		}
		return $a;
	}

	/**
	 * Recursive array diff
	 *
	 * @copyright
	 * @author 		Amund, RolandD
	 * @todo
	 * @see 		http://www.php.net/manual/en/function.array-merge.php#91756
	 * @access 		private
	 * @param 		array	$aArray1	The array to update
	 * @param		array	$aArray2	The array with new values
	 * @return 		array	with all new values
	 * @since 		3.0
	 */
	public function recurseArrayDiff($aArray1, $aArray2) {
		$aReturn = array();
		if (is_array($aArray1) && is_array($aArray2)) {
			foreach ($aArray1 as $mKey => $mValue) {
				if (array_key_exists($mKey, $aArray2)) {
					if (is_array($mValue)) {
						$aRecursiveDiff = self::recurseArrayDiff($mValue, $aArray2[$mKey]);
						if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
					}
					else {
						if ($mValue != $aArray2[$mKey]) $aReturn[$mKey] = $mValue;
					}
				}
				else {
					$aReturn[$mKey] = $mValue;
				}
			}
		}
		return $aReturn;
	}

	/**
	 * Perform replacement on a value
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int		$id		the id of the replacement rule
	 * @param		string	$value	the text to replace
	 * @return 		string	the replaced text
	 * @since 		3.0
	 */
	public function replaceValue($id, $value) {
		static $replacements;

		if ($id > 0) {
			if (!isset($this->replacements[$id])) {
				// Get the replace details
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select($db->quoteName('findtext'));
				$query->select($db->quoteName('replacetext'));
				$query->select($db->quoteName('method'));
				$query->from('#__csvi_replacements');
				$query->where('id = '.$db->Quote($id));
				$db->setQuery($query);
				$replace = $db->loadObject();
				$this->replacements[$id] = $replace;
			}
			else {
				$replace = $this->replacements[$id];
			}

			// Perform the replacement
			if (empty($replace)) return $value;
			else {
				switch ($replace->method) {
					case 'text':
						$fieldvalue = str_ireplace($replace->findtext, $replace->replacetext, $value);
						break;
					case 'regex':
						$fieldvalue = preg_replace($replace->findtext, $replace->replacetext, $value);
						break;
				}
			}
			return $fieldvalue;
		}
		else return $value;
	}

	/**
	 * Get the list of custom tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return		array	list of order item product objects
	 * @since 		3.0
	 */
	public function getCustomTables() {
		$db = JFactory::getDbo();
		$q = "SELECT component_table
			FROM #__csvi_available_fields
			WHERE core = 0
			GROUP BY component_table";
		$db->setQuery($q);
		return $db->loadColumn();
	}

	/**
     * Check whether a file referenced by a URL exists
     *
     * Note: The time taken to check a valid format url:  0.10 secs, regardless of whether the file exists
     *
     * @copyright
     * @author 		doorknob, RolandD
     * @todo
     * @see
     * @access 		public
     * @param 		string	$file	The URL to be checked
     * @return 		boolean	true if file exists | false if file does not exist
     * @since 		2.17
     */
public function fileExistsRemote($file) {
    	// If it is an SSL file we cannot validate its existence
    	if (substr($file, 0, 5) == 'https') return true;

    	$jinput = JFactory::getApplication()->input;
    	$csvilog = $jinput->get('csvilog', null, null);
        $url_parts = @parse_url($file);
		$csvilog->addDebug('URL:'.$file);

        if (!isset($url_parts['host']) || empty($url_parts['host']))  {
            return false;
        }

        if (!isset($url_parts['path']) || empty($url_parts['path']))  {
            $documentpath = '/';
        }
        else {
            $documentpath = $url_parts['path'];
        }

        if (isset($url_parts['query']) && !empty($url_parts['query'])) {
            $documentpath .= '?'.$url_parts['query'];
        }

        $host = $url_parts['host'];

        if (!isset($url_parts['port']) || empty($url_parts['port']))  {
            if ($url_parts['scheme'] == 'https') $port = '443';
            else $port = '80';
        }
        else {
            $port = $url_parts['port'];
        }

        if ($url_parts['scheme'] == 'https') {
        	$sslhost = 'ssl://'.$host;
        }
        else $sslhost = $host;

		$errno = null;
        $errstr = null;
        $documentpath = str_replace(' ', '%20', $documentpath);
        $socket = @fsockopen($sslhost, $port, $errno, $errstr, 30);
        if ($socket) {
        	fwrite($socket, "HEAD $documentpath HTTP/1.1\r\nUser-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11\r\nHost:  $host\r\n\r\n");
        	$http_response = fgets($socket, 25);
        	fclose($socket);

        	// Parse the result
        	$csvilog->addDebug('HTTP response:'.$http_response);
        	if (stripos($http_response, '200 OK') === false && stripos($http_response, '302 Found') === false) {
        		return false;
        	}
        	else return true;
        }


        return false;
    }

    /**
	 * Find the primary key of a given table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		$table	string	the name of the table to find the primary key
	 * @return 		string	the fieldname that is the primary key
	 * @since 		3.0
	 */
	public function getPrimaryKey($tablename) {
		$db = JFactory::getDbo();
		$q = "SHOW KEYS FROM ".$db->quoteName('#__'.$tablename)."
			WHERE ".$db->quoteName('Key_name')." = ".$db->quote('PRIMARY');
		$db->setQuery($q);
		$key = $db->loadObject();
		if (!is_object($key)) return '';
		else return $key->Column_name;
	}

	/**
	 * Get the domainname
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	domain name
	 * @since 		3.4
	 */
	public function getDomainName() {
		$jinput = JFactory::getApplication()->input;
		$settings = $jinput->get('settings', null, null);

		// Get the domainname
		$domainname = $settings->get('site.hostname');
		// Check for the trailing slash at the domain name
		if (substr($domainname, -1) == '/') $domainname = substr($domainname, 0, -1);
		// Assign the domainname
		return $domainname;
	}

	/**
	 * Get supported components
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of supported components
	 * @since 		4.0
	 */
	public function getComponents() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('component AS value, component AS text');
		$query->from($db->quoteName('#__csvi_template_types'));
		$query->leftJoin('#__extensions ON #__csvi_template_types.component = #__extensions.element');
		$query->where($db->quoteName('#__extensions.type').' = '.$db->Quote('component'));
		$query->group('component');
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	/**
	 * Get the buttons for the control panel
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		object	with the button HTML data
	 * @since 		3.0
	 */
	public function getButtons() {
		// Get the Cpanel images
		$cpanel_images = new stdClass();
		$cpanel_images->process = $this->CpanelButton('csvi_process_48.png', 'index.php?option=com_csvi&view=process', 'COM_CSVI_PROCESS');
		$cpanel_images->replacements = $this->CpanelButton('csvi_replace_48.png', 'index.php?option=com_csvi&view=replacements', 'COM_CSVI_REPLACEMENTS');
		$cpanel_images->maintenance = $this->CpanelButton('csvi_maintenance_48.png', 'index.php?option=com_csvi&view=maintenance', 'COM_CSVI_MAINTENANCE');
		$cpanel_images->help = $this->CpanelButton('csvi_help_48.png', 'http://www.csvimproved.com/csv-improved-documentation/', 'COM_CSVI_HELP');
		$cpanel_images->about = $this->CpanelButton('csvi_about_48.png', 'index.php?option=com_csvi&view=about', 'COM_CSVI_ABOUT');
		$cpanel_images->log = $this->CpanelButton('csvi_log_48.png', 'index.php?option=com_csvi&view=log', 'COM_CSVI_LOG');
		$cpanel_images->availablefields = $this->CpanelButton('csvi_av_fields_48.png', 'index.php?option=com_csvi&view=availablefields', 'COM_CSVI_AVAILABLE_FIELDS');
		$cpanel_images->settings = $this->CpanelButton('csvi_settings_48.png', 'index.php?option=com_csvi&view=settings', 'COM_CSVI_SETTINGS');
		$cpanel_images->install = $this->CpanelButton('csvi_install_48.png', 'index.php?option=com_csvi&view=install', 'COM_CSVI_INSTALL');
		return $cpanel_images;
	}

	/**
	 * Creates a button for the control panel
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$image	contains the name of the image
	 * @param 		string	$link 	contains the target link for the image when clicked
	 * @param 		string	$title 	contains the title of the button
	 * @return		string returns a complete button for the control panel
	 * @since 		3.0
	 */
	private function CpanelButton($image, $link, $title) {
		if (substr($link, 0, 4) == "http") $attribs = ' target="_new"';
		else $attribs = '';
		$cpanelbutton = '<div class="cpanel_button">';
		$cpanelbutton .= '	<div class="icon">';
		$cpanelbutton .= JHTML::_('link', $link, JHTML::_('image', JURI::root().'administrator/components/com_csvi/assets/images/'.$image, JText::_($title)).'<span>'.JText::_($title).'</span>', $attribs);
		$cpanelbutton .= '	</div>';
		$cpanelbutton .= '</div>';
		return $cpanelbutton;
	}

	/**
	 * Get a Yes/No dropdown list
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$name		the name of the dropdown
	 * @param		string	$selected	pre-selected entry
	 * @param		string	$attribs	attributes to add to the dropdown
	 * @param		string	$idtag		the id to give to the dropdown
	 * @return 		string	that contains the dropdown with options
	 * @since 		4.0
	 */
	public function getYesNo($name, $selected=null, $attribs=null, $idtag=null) {
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('COM_CSVI_NO'));
		$options[] = JHtml::_('select.option', '1', JText::_('COM_CSVI_YES'));

		return JHtml::_('select.genericlist', $options, $name, $attribs, 'value', 'text', $selected, $idtag);
	}
}
?>
