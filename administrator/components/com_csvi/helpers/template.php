<?php
/**
 * CSVI Template helper
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: template.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Helper class for templates
* @package CSVI
 */
class CsviTemplate {

	/** @var object contains the form data */
	private $_settings = array();
	private $_name = null;

	/**
	 * Construct the template helper
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		public
	 * @param
	 * @return
	 * @since		4.0
	 */
	public function __construct($data=null) {
		if (!is_null($data)) {
	 		$this->_settings = $data;
		}
	}

    /**
     * Find a value in the template
     *
     * @copyright
     * @author 		RolandD
     * @todo		JRequest::_cleanVar
     * @see 		JFilterInput
     * @access 		public
     * @param 		string	$name		the name of the parameter to find
     * @param 		string	$group		the group in which to find the parameter
     * @param		string	$default	the default value to use when not found
     * @param		string	$filter		the filter to apply
     * @param 		int 	$mask		Filter bit mask. 1=no trim: If this flag is cleared and the
	 * 									input is a string, the string will have leading and trailing whitespace
	 * 									trimmed. 2=allow_raw: If set, no more filtering is performed, higher bits
	 * 									are ignored. 4=allow_html: HTML is allowed, but passed through a safe
	 * 									HTML filter first. If set, no more filtering is performed. If no bits
	 * 									other than the 1 bit is set, a strict filter is applied.
	 * @param		bool	$special	if the field should require special processing
     * @return 		mixed	the value found
     * @since 		3.0
     */
    public function get($name, $group='', $default = '', $filter=null, $mask=0, $special=true) {
    	// Set the initial value
    	$value = '';

    	// Find the value
    	if (empty($group)) {
			if (array_key_exists($name, $this->_settings)) $value = $this->_settings[$name];
    	}
    	else {
			if (array_key_exists($group, $this->_settings)) {

				if (array_key_exists($name, $this->_settings[$group])) {
					$value = $this->_settings[$group][$name];
				}
			}
    	}

    	// Return the found value
    	if (is_array($value) && empty($value)) $value = $default;
    	else if ('' === $value) $value = $default;

    	// Special processing
    	if ($special) {
	    	switch ($name) {
	    		case 'language':
	    		case 'target_language':
	    			$value = strtolower(str_replace('-', '_', $value));
	    			break;
	    		case 'field_delimiter':
	    			if (strtolower($value) == 't') $value = "\t";
	    			break;
	    	}
    	}
    	
    	// Clean up and return
    	if (is_null($filter) && $mask == 0) return $value;
    	else return JRequest::_cleanVar($value, $mask, $filter);
    }

    /**
     * Set a value in the template
     *
     * @copyright
     * @author 		RolandD
     * @todo
     * @see
     * @access 		public
     * @param 		string	$name		the name of the parameter to find
     * @param 		string	$group		the group in which to find the parameter
     * @param		string	$value		the value to set
     * @return 		void
     * @since 		3.0
     */
    public function set($name, $group='', $value = '') {
    	// Set the value
    	if (empty($group)) {
			$this->_settings[$name] = $value;
    	}
    	else {
    		$this->_settings[$group][$name] = $value;
    	}
    }
    
    /**
     * Load a template 
     * 
     * @copyright 
     * @author 		RolandD
     * @todo 
     * @see 
     * @access 		public
     * @param 		int	$id	the ID of the template to load
     * @return 
     * @since 		4.0
     */
    public function load($id) {
    	if ($id > 0) {
	    	// Load the data
	    	$db = JFactory::getDbo();
	    	$query = $db->getQuery(true);
	    	$query->select($db->quoteName('settings').', '.$db->quoteName('name'));
	    	$query->from($db->quoteName('#__csvi_template_settings'));
	    	$query->where($db->quoteName('id').' = '.$db->quote($id));
	    	$db->setQuery($query);
	    	$data = $db->loadObject();
	    	
	    	// Fill the settings
	    	 $settings = json_decode($data->settings, true);
	    	 if (!is_array($settings)) $settings = array();
	    	 $this->_settings = $settings;
	    	
	    	// Set the name
	    	if (!empty($data)) $this->setName($data->name);
    	}
    }
    
    /**
     * Set the template name 
     * 
     * @copyright 
     * @author 		RolandD
     * @todo 
     * @see 
     * @access 		public
     * @param 		string	$name	the name of the template
     * @return 
     * @since 		4.0
     */
    public function setName($name) {
 	   // Set the template name
    	$this->_name = $name;
    }
    
    /**
     * Get the name of the template 
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
    public function getName() {
    	return $this->_name;
    }
    
    /**
     * Return all settings 
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
    public function getSettings() {
    	return $this->_settings;
    }
}
?>
