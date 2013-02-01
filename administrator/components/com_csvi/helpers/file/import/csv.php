<?php
/**
 * CSV file processor class
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csv.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class CsvFile extends CsviFile {

	/** @var string Contains the field delimiter */
	private $_field_delimiter = null;

	/** @var string Contains the text enclosure */
	private $_text_enclosure = null;

	/** @var bool Sets to true if a file delimiters have been checked */
	private $_checked_delimiter = false;

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
		// Column headers are always the first line of the file
		// 1. Store current position
		$curpos = $this->getFilePos();
		if ($curpos > 0) {
			// 2. Go to the beginning of the file
			$this->setFilePos(0);
		}
		// 3. Read the line
		$jinput->set('columnheaders', $this->ReadNextLine());
		if ($curpos > 0) {
			// 4. Set the position back
			$this->setFilePos($curpos);
		}
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
		return ftell($this->fp);
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
	 * @return 		int	0 if success | -1 if not success
	 * @since 		3.0
	 */
	public function setFilePos($pos) {
		return fseek($this->fp, $pos);
	}

	/**
	 * Close the file
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
	public function closeFile($removefolder=true) {
		fclose($this->fp);
		$this->_closed = true;
		parent::closeFile($removefolder);
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
		// Check if the file is still open
		if ($this->_closed) return;

		// Make sure we have delimiters
		if (is_null($this->_field_delimiter)) return false;

		// Load some settings
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);
		$newdata = array();

		// Ignore empty records
		$csvdata = array(0=>'');
		while (is_array($csvdata) && count($csvdata)==1 && $csvdata[0]=='') {
			if (!is_null($this->_text_enclosure)) $csvdata = fgetcsv($this->fp, 0, $this->_field_delimiter, $this->_text_enclosure);
			else $csvdata = fgetcsv($this->fp, 0, $this->_field_delimiter);
		}

		// Check if we can read the line correctly
		if (count($csvdata) == 1 && !$this->_checked_delimiter) {
			$current_field = $this->_field_delimiter;
			$current_text = $this->_text_enclosure;
			$this->_findDelimiters(true);
			if ($template->show_preview) {
				if ($current_field != $this->_field_delimiter) JError::raiseNotice(0, JText::sprintf('COM_CSVI_UNEQUAL_FIELD_DELIMITER', $current_field, $this->_field_delimiter));
				if ($current_text != $this->_text_enclosure) JError::raiseNotice(0, JText::sprintf('COM_CSVI_UNEQUAL_TEXT_ENCLOSURE', $current_text, $this->_text_enclosure));
			}
			else {
				if ($current_field != $this->_field_delimiter) $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_UNEQUAL_FIELD_DELIMITER', $current_field, $this->_field_delimiter));
				if ($current_text != $this->_text_enclosure) $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_UNEQUAL_FIELD_DELIMITER', $current_field, $this->_field_delimiter));
			}
			$this->_field_delimiter = $current_field;
			$this->_text_enclosure = $current_text;
		}

		if ($csvdata) {
			// Do BOM check
			if ($jinput->get('currentline', 0, 'int') == 1 || is_null($jinput->get('currentline', null, null))) {
				// Remove text delimiters as they are not recognized by fgetcsv
				$csvdata[0] = $this->_removeTextDelimiters($this->_checkBom($csvdata[0]));
			}
			$this->linepointer++;

			return $csvdata;
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
		// Open the csv file
		$this->fp = fopen($this->filename, "r");
		$this->_closed = false;

		// Load the delimiters
		$this->_findDelimiters();

		return true;
	}

	/**
	 * Find the delimiters used
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		bool	$force	Force to read the delimiters from the imported file
	 * @return 		bool	true if delimiters found | false if delimiters not found
	 * @since 		3.0
	 */
	private function _findDelimiters($force=false) {
		$jinput = JFactory::getApplication()->input;
		if (!$this->_checked_delimiter) {
			$csvilog = $jinput->get('csvilog', null, null);
			$template = $jinput->get('template', null, null);
			if (!$template->get('auto_detect_delimiters', 'general', true) && !$force) {
				// Set the field delimiter
				if (strtolower($template->get('field_delimiter', 'general')) == 't') $this->_field_delimiter = "\t";
				else $this->_field_delimiter = $template->get('field_delimiter', 'general');

				// Set the text enclosure
				$this->_text_enclosure = ($template->get('text_enclosure', 'general', '')) ? $template->get('text_enclosure', 'general') : null;
			}
			else {
				// Read the first line
				rewind($this->fp);
				$line = fgets($this->fp);

				// 1. Is the user using text enclosures
				$first_char = substr($line, 0, 1);
				$pattern = '/[a-zA-Z0-9_]/';
				$matches = array();
				preg_match($pattern, $first_char, $matches);
				if (count($matches) == 0) {
					// User is using text delimiter
					$this->_text_enclosure = $first_char;
					$csvilog->addDebug(JText::sprintf('COM_CSVI_FOUND_TEXT_ENCLOSURE', $first_char));

					// 2. What field delimiter is being used
					$match_next_char = strpos($line, $this->_text_enclosure, 1);
					$second_char = substr($line, $match_next_char+1, 1);
					if ($first_char == $second_char) {
						$jinput->set('error_found', true);
						JError::raiseWarning(0, JText::_('COM_CSVI_CANNOT_FIND_TEXT_DELIMITER'));
						return false;
					}
					else {
						$this->_field_delimiter = $second_char;
					}
				}
				else {
					$totalchars = strlen($line);
					// 2. What field delimiter is being used
					for ($i = 0;$i <= $totalchars; $i++) {
						$current_char = substr($line, $i, 1);
						preg_match($pattern, $current_char, $matches);
						if (count($matches) == 0) {
							$this->_field_delimiter = $current_char;
							$i = $totalchars;
						}
					}
				}
				$csvilog->addDebug(JText::sprintf('COM_CSVI_FOUND_FIELD_DELIMITER', $this->_field_delimiter));
				rewind($this->fp);
			}
			$this->_checked_delimiter = true;
		}
		return true;
	}

	/**
	 * Checks if the uploaded file has a BOM
	 *
	 * If the uploaded file has a BOM, remove it since it only causes
	 * problems on import.
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see			ReadNextLine()
	 * @access 		private
	 * @param 		string	$data	the string to check for a BOM
	 * @return 		string return the cleaned string
	 * @since 		3.0
	 */
	private function _checkBom($data) {
		// Check the first three characters
		if (strlen($data) > 3) {
			if (ord($data{0}) == 239 && ord($data{1}) == 187 && ord($data{2}) == 191) {
				return substr($data, 3, strlen($data));
			}
			else return $data;
		}
		else return $data;
	}

	/**
	 * Removes the text delimiters when fgetcsv() has failed to do so because the file contains a BOM.
	 * This allows for the possibility that the data value contains embedded text enclosure characters
	 * (which should be doubled up for correct csv file format).
	 * The string [32" TV] (ignore brackets) should be encoded as ["32"" TV"]
	 * This function correctly decodes ["32"" TV"] back to [32" TV]
	 *
	 * @copyright
	 * @author		doorknob
	 * @todo
	 * @see
	 * @access		private
	 * @param 		string	$data	the string to clean
	 * @return 		string	the cleaned string
	 * @since
	 */
	private function _removeTextDelimiters($data) {
		if( substr($data, 0, 1) == $this->_text_enclosure && substr($data, -1, 1) == $this->_text_enclosure ) {
			return str_replace($this->_text_enclosure.$this->_text_enclosure, $this->_text_enclosure, substr($data, 1, -1));
		}
		else {
			return $data;
		}
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
		$this->setFilePos(0);
	}
}
?>
