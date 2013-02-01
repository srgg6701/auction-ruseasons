<?php
/**
 * ODS file processor class
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: ods.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class OdsFile extends CsviFile {

	/** @var bool	sets if the ODS file has been unpacked */
	private $_unpacked = false;

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
		return $this->data->rows;
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
		$jinput->set('columnheaders', $this->data->_data[1]);
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
		if ($this->data->rows >= $this->linepointer) {
			$newdata = array();
			$newdata = $this->data->_data[$this->linepointer];
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
		if (!$this->_unpacked) {
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.archive');
			$this->fp = true;
			$this->linepointer = 1;
			$this->data = new ODSParser();
			// First we need to unpack the zipfile
			$unpackfile = $this->_unpackpath.'/ods/'.basename($this->filename).'.zip';
			$importfile = $this->_unpackpath.'/ods/content.xml';

			// Check the unpack folder
			JFolder::create($this->_unpackpath.'/ods');

			// Delete the destination file if it already exists
			if (JFile::exists($unpackfile)) JFile::delete($unpackfile);
			if (JFile::exists($importfile)) JFile::delete($importfile);

			// Now copy the file to the folder
			JFile::copy($this->filename, $unpackfile);

			// Extract the files in the folder
			if (!JArchive::extract($unpackfile, $this->_unpackpath.'/ods')) {
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_CANNOT_UNPACK_ODS_FILE'));
				return false;
			}
			// File is always called content.xml
			else $this->filename = $importfile;

			// Read the data to process
			if (!$this->data->read($this->filename)) return false;

			// Set the unpacked to true as we have unpacked the file
			$this->_unpacked = true;
		}

		// All good return true
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
		// Set the line pointer to 1 as that is the first entry in the data array
		$this->setFilePos(1);
	}

}
?>
