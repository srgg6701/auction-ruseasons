<?php
/**
 * XML parser for ODS files
 *
 * Parses the content.xml file
 *
 * @package 	CSVI
 * @subpackage 	Helpers
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: ods_reader.php 2275 2013-01-03 21:08:43Z RolandD $
 *
 * @todo add support for zip file
 * @todo Check if file xists
 * @todo Better error handling, no die usage
 */
 
/**
 * Parse ODS files
 *
 * @package CSVI
 * @subpackage Helpers
 */
class ODSParser {
	
	/* Set private variables */
	/** @var string filename */
	private $_file = null;
	/** @var string tag */
	private $_tag = null;
	/** @var object the xml parser */
	private $_xml_parser = null;
	/** @var bool set if inside a tag */
	private $_insideitem = false;
	/** @var array tags to process */
	private $_tagprocess = array('TABLE:TABLE-CELL', 'TABLE:TABLE-ROW');
	/** @var array holds the data per node */
	public $_data = array();
	/** @var integer counts the positions */
	private $_poscount = 0;
	/** @var integer counts the number of times a string is repeated */
	private $_columns_repeated = 0;
	
	/* Set public variables */
	/** @var array holds the parsed data */
	public $fulldata = array();
	/** @var int holds the number of rows */
	public $rows = null;
	/** @var int holds the number of columns */
	public $cols = null;
	
	private $_linecount = 0;
	
	/**
	* Start to process the XML file
	*
	* @todo change die to a proper return result
	* @todo add support for <table:table-cell/>
	 */
	public function read($filename) {
		$this->_file = $filename;
		list($this->_xml_parser, $fp) = $this->new_xml_parser($this->_file);
		if (!$this->_xml_parser) {
			die("could not open XML input");
		}           
		$data = '';
		while (!feof($fp)) {
			$data .= fread($fp, 4096);
		}
		fclose($fp);
		
		if (!xml_parse($this->_xml_parser, $data, true)) {
			die(sprintf("XML error: %s at line %d\n",
						xml_error_string(xml_get_error_code($this->_xml_parser)),
						xml_get_current_line_number($this->_xml_parser)));
		}
		
		xml_parser_free($this->_xml_parser);
		$this->cols = count($this->_data[1]);
		$this->rows = count($this->_data);
		return true;
	}
	
	/**
	 * Handle the opening element
	 */
	private function startElement($parser, $tagname, $attribs) {
		switch ($tagname) {
			case 'TABLE:TABLE-ROW':
				$this->_linecount++;
				break;
			case 'TABLE:TABLE-CELL':
				$styles = array_keys($attribs);
				if (empty($styles)) {
					$this->_data[$this->_linecount]['options']['repeat'] = 1;
				}
				else {
					foreach ($styles as $stylekey => $style) {
						switch ($style) {
							case 'TABLE:NUMBER-COLUMNS-REPEATED':
								$this->_data[$this->_linecount]['options']['repeat'] = $attribs[$style];
								break;
							case 'OFFICE:VALUE-TYPE':
								$this->_data[$this->_linecount]['options']['type'] = $attribs[$style];
								break;
						}
					}
				}
				break;
			case 'TEXT:P':
				$this->_data[$this->_linecount];
				break;
		}
	}
	
	/**
	* Handle the closing element
	 */
	private function endElement($parser, $name) {
		if (array_key_exists($this->_linecount, $this->_data)) {
			if (!array_key_exists('data', $this->_data[$this->_linecount])
				&& array_key_exists('options', $this->_data[$this->_linecount])) {
				foreach ($this->_data[$this->_linecount]['options'] as $option => $value) {
					switch ($option) {
						case 'type':
							break;
						case 'repeat':
							for ($r=0; $r < $value; $r++) {
								$this->_data[$this->_linecount][] = '';
							}
							break;
					}
				}
			}
			else unset($this->_data[$this->_linecount]['data']);
			if (array_key_exists('options', $this->_data[$this->_linecount])) unset($this->_data[$this->_linecount]['options']);
		}
	}
	
	/**
	* Handle the data
	*
	* @todo parse <text:s text:c="2"/>
	 */
	private function characterData($parser, $data) {
		foreach ($this->_data[$this->_linecount]['options'] as $option => $value) {
			switch ($option) {
				case 'type':
					break;
				case 'repeat':
					for ($r=1; $r < $value; $r++) {
						$this->_data[$this->_linecount][] = $data;
					}
					break;
			}
		}
		$this->_data[$this->_linecount][] = $data;
		unset($this->_data[$this->_linecount]['options']);
		$this->_data[$this->_linecount]['data'] = true;
	}
	
	private function new_xml_parser($file) {
		$this->_xml_parser = xml_parser_create("UTF-8");
		xml_parser_set_option($this->_xml_parser, XML_OPTION_CASE_FOLDING, 1);
		xml_set_object($this->_xml_parser, $this);
		xml_set_element_handler($this->_xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($this->_xml_parser, "characterData");
		$fp = fopen($file, "rb");
		if (!$fp) return false;
		else return array($this->_xml_parser, $fp);
	}
}
?>
