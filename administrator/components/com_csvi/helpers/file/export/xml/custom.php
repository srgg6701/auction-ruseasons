<?php
/**
 * Custom XML class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @todo		Clean up class vars
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: custom.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Custom XML Export Class
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviCustom {

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
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		return $template->get('header', 'layout', '', null, 2);
	}

	/**
	 * Creates the XML footer
	 *
	 * @see $contents
	 * @return string XML header
	 */
	public function FooterText() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		return $template->get('footer', 'layout', '', null, 2);
	}

	/**
	* Opens an XML item node
	*
	* @see $contents
	* @return string XML node data
	 */
	public function NodeStart() {
		$this->_node();
		return;
	}

	/**
	* Closes an XML item node
	*
	* @see $contents
	* @return string XML node data
	 */
	public function NodeEnd() {
		return $this->_node;
	}

	/**
	 * A full node template
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _node() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$this->_node = $template->get('body', 'layout', '', null, 2);
	}

	/**
	* Adds an XML element
	*
	* @see $node
	* @return string XML element
	 */
	private function _element($content, $fieldname, $cdata=false) {
		$data = '';
		if ($cdata) $data .= '<![CDATA[';
		$data .= $content;
		if ($cdata) $data .= ']]>';
		$this->_node = str_ireplace('['.$fieldname.']', $data, $this->_node);
		return;
	}

	/**
	* Handles all content and modifies special cases
	*
	* @see $contents
	* @return string formatted XML element
	 */
	public function ContentText($content, $column_header, $fieldname, $cdata=false) {
		if (empty($column_header)) $column_header = $fieldname;
		return $this->_element($content, $column_header, $cdata);
	}
}
?>
