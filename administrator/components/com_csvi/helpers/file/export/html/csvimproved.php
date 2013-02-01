<?php
/**
 * HTML class
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
 * HTML Export Class
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
	 * Creates the HTML header
	 *
	 * @return string HTML header
	 */
	function HeaderText() {
		$this->contents = '<html><head></head>'.chr(10);
		$this->contents .= '<body><table>'.chr(10);
		return $this->contents;
	}
	
	/**
	 * Start the table header
	 *
	 * @return string start table header
	 */
	function StartTableHeaderText() {
		return '<thead><tr>';
	}
	
	/**
	 * End the table header
	 *
	 * @return string end table header
	 */
	function EndTableHeaderText() {
		return '</tr></thead>'.chr(10);
	}
	
	/**
	 * Creates the table header
	 *
	 * @return string th field
	 */
	public function TableHeaderText($headers) {
		return '<th>'.$headers.'</th>';
	}
	
	/**
	 * Start the table body header
	 *
	 * @see $contents
	 * @return string table body header
	 */
	public function BodyText() {
		return '<tbody>';
	}
	
	/**
	 * Creates the HTML footer
	 *
	 * @see $contents
	 * @return string HTML footer
	 */
	function FooterText() {
		$this->contents = '</tbody></table></body></html>'.chr(10);
		return $this->contents;
	}
	
	/**
	* Opens a table row
	*
	* @see $contents
	* @return string tr opening tag
	 */
	function NodeStart() {
		$this->contents = '<tr>'.chr(10); 
		return $this->contents;
	}
	
	/**
	* Closes a table row
	*
	* @see $contents
	* @return string tr closing tag
	 */
	function NodeEnd() {
		$this->contents = '</tr>'.chr(10); 
		return $this->contents;
	}
	
	/**
	* Adds a table td element
	*
	* @see $node
	* @return string td row
	 */
	function Element($column_header, $cdata=false) {
		$this->node = '<td>';
		$this->node .= $this->contents;
		$this->node .= '</td>';
		$this->node .= "\n";
		return $this->node;
	}
	
	/**
	* Handles all content and modifies special cases
	*
	* @see $contents
	* @return string formatted table row
	 */
	function ContentText($content, $column_header, $fieldname, $cdata=false) {
		switch ($fieldname) {
			default:
				$this->contents = $content;
				break;
		}
		return $this->Element($column_header, $cdata);
	}
}
?>
