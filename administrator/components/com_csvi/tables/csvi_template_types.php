<?php
/**
 * Template types table
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvi_template_types.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 */
class TableCsvi_template_settings extends JTable {

	// @var int Primary key
	var $id = 0;
	// @var string The name of the template type
	var $template_type_name = null;
	// @var string The type of template
	var $template_type = null;

	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		parent::__construct('#__csvi_template_types', 'id', $db );
	}
}
?>