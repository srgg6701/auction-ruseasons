<?php
/**
 * CSV Improved XML class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvimproved.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * CSV Improved XML Export Class
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviCsvimproved {
	
	/** @var string contains the data to export */
	var $contents = "";
	/** @var string contains the XML node to export */
	var $node = "";
	
	/**
	* Creates the XML header
	*
	* @see $contents
	* @return string XML header
	 */
	public function HeaderText() {
		$this->contents = '<?xml version="1.0" encoding="UTF-8"?>'.chr(10);
		$this->contents .= '<channel>'.chr(10);
		return $this->contents;
	}
	
	/**
	* Creates the XML footer
	*
	* @see $contents
	* @return string XML header
	 */
	public function FooterText() {
		$this->contents = '</channel>'.chr(10);
		return $this->contents;
	}
	
	/**
	* Opens an XML item node
	*
	* @see $contents
	* @return string XML node data
	 */
	public function NodeStart() {
		$this->contents = '<item>'.chr(10); 
		return $this->contents;
	}
	
	/**
	* Closes an XML item node
	*
	* @see $contents
	* @return string XML node data
	 */
	public function NodeEnd() {
		$this->contents = '</item>'.chr(10); 
		return $this->contents;
	}
	
	/**
	* Adds an XML element
	*
	* @see $node
	* @return string XML element
	 */
	private function Element($column_header, $cdata=false) {
		$this->node = '<'.$column_header.'>';
		if ($cdata) $this->node .= '<![CDATA[';
		$this->node .= $this->contents;
		if ($cdata) $this->node .= ']]>';
		$this->node .= '</'.$column_header.'>';
		$this->node .= "\n";
		return $this->node;
	}
	
	/**
	* Handles all content and modifies special cases
	*
	* @see $contents
	* @return string formatted XML element
	 */
	public function ContentText($content, $column_header, $fieldname, $cdata=false) {
		switch ($fieldname) {
			case 'field_default_value':
			case 'product_attribute':
				$cdata = true;
			default:
				$this->contents = $content;
				break;
		}
		if (empty($column_header)) $column_header = $fieldname;
		return $this->Element($column_header, $cdata);
	}
}
?>
