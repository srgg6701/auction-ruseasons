<?php
/**
 * XLS file processor class
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: xls.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class XlsFile extends CsviFile {

	/**
	 * Construct the class and its settings
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get the size of the file
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		int	the size of the file being read
	 * @since 		3.0
	 */
	public function getFileSize() {
		return $this->data[0]['numRows'];
	}

	/**
	 * Load the column headers from a file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool	true
	 * @since 		3.0
	 */
	public function loadColumnHeaders() {
		$jinput = JFactory::getApplication()->input;
		// Make sure we include the empty fields
		for ($i=1; $i<=$this->data[0]['numCols']; $i++) {
			if (!isset($this->data[0]['cells'][1])) $this->data[0]['cells'][1][$i] = '';
		}
		$headers = array_values($this->data[0]['cells'][1]);
		$jinput->set('columnheaders', $headers);
		$this->linepointer++;
		return true;
	}

	/**
	 * Get the file position
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		int	current position in the file
	 * @since 		3.0
	 */
	public function getFilePos() {
		return $this->linepointer;
	}

	/**
	 * Set the file position
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$pos	the position to move to
	 * @return 		int	current position in the file
	 * @since 		3.0
	 */
	public function setFilePos($pos) {
		$this->linepointer = $pos;
		return $this->linepointer;
	}

	/**
	 * Read the next line in the file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array	with the line of data read | false if data cannot be read
	 * @since 		3.0
	 */
	public function readNextLine() {
		if ($this->data[0]['numRows'] >= $this->linepointer) {
			$newdata = array();

			// Make sure we include the empty fields
			for ($i=1; $i <= $this->data[0]['numCols']; $i++) {
			   if (!isset($this->data[0]['cells'][$this->linepointer][$i])) $newdata[] = '';
			   else $newdata[] = $this->data[0]['cells'][$this->linepointer][$i];
			}
			$this->linepointer++;
			return $newdata;
		}
		else return false;
	}

	/**
	 * Process the file to import
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function processFile() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$this->fp = true;
		$this->data = new Spreadsheet_Excel_Reader($this->filename, false);
		$this->data = $this->data->sheets;
		return true;
	}

	/**
	 * Sets the file pointer back to beginning
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function rewind() {
		$this->setFilePos(1);
	}
}
?>
