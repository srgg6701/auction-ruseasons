<?php
/**
 * beslist.nl XML class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: beslist.php 2275 2013-01-03 21:08:43Z RolandD $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * XML Export for beslist.nl
 *
 * @package 	CSVI
 * @subpackage 	Export
 *
 */
class CsviBeslist {
	
	/** @var string contains the data to export */
	var $contents = "";
	/** @var string contains the XML node to export */
	var $node = "";
	
	function HeaderText() {
		$this->contents = '<?xml version="1.0" encoding="UTF-8"?>'.chr(10);
		$this->contents .= '<export>'.chr(10);
		return $this->contents;
	}
	
	function FooterText() {
		$this->contents = '</export>'.chr(10);
		return $this->contents;
	}
	
	function NodeStart() {
		$this->contents = '<product>'.chr(10); 
		return $this->contents;
	}
	
	function NodeEnd() {
		$this->contents = '</product>'.chr(10); 
		return $this->contents;
	}
	
	function Element($column_header, $cdata=false) {
		$this->node = '<'.$column_header.'>';
		if ($cdata) $this->node .= '<![CDATA[';
		$this->node .= $this->contents;
		if ($cdata) $this->node .= ']]>';
		$this->node .= '</'.$column_header.'>';
		$this->node .= "\n"; 
		return $this->node;
	}
	
	function ContentText($content, $column_header, $fieldname, $cdata=false) {
		switch ($fieldname) {
			case 'category_path':
				$this->CategoryPath($content);
				break;
			case 'manufacturer_name':
			case 'product_url':
				$cdata = true;
			default:
				$this->contents = $content;
				break;
		}
		if (empty($column_header)) $column_header = $fieldname;
		return $this->Element($column_header, $cdata);
	}
	
	function CategoryPath($category) {
		$this->contents = str_replace("/", " &gt; ", trim($category));
	}
}
?>
