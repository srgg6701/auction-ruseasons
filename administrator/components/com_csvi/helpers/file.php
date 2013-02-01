<?php
/**
 * Main file processor class
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: file.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * CsviFile class
 *
 * The CsviFile class handles all file operations
 *
* @package CSVI
 */
abstract class CsviFile {

	/** @var array  Contains the list of available fields in the target table */
	protected $_supported_fields = array();

	/** @var array Contains allowed extensions for uploaded files */
	public $suffixes = array();

	/** @var array Contains allowed mimetypes for uploaded files */
	public $mimetypes = array();

	/** @var array Contains allowed archivetypes for uploaded files */
	public $archives = array();

	/** @var string Contains the name of the uploaded file */
	public $filename = '';

	/** @var string Contains the extension of the uploaded file */
	public $extension = '';

	/** @var bool Contains the value whether or not the file uses
	* an extension that is allowed.
	*
	* @see $suffixes
	*/
	public $valid_extension = false;

	/** @var bool Filepointer used when opening files */
	public $fp = false;

	/** @var integer Internal line pointer */
	public $linepointer = 1;

	/** @var array Contains the data that is read from file */
	public $data = null;

	/** @var string Path for unpacking files */
	protected $_unpackpath = null;

	/** @var bool Sets to true if a file has been uploaded */
	private $_uploaded = false;

	/** @var bool Sets to true if a file has been closed */
	private $_closed = false;



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
		$jinput = JFactory::getApplication()->input;
		// Load the necessary libraries
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		$this->_unpackpath = CSVIPATH_TMP;
		$this->_supported_fields = $jinput->get('avfields', array(), null);

		// Load some basic settings
		$this->_fileSettings();
	}

	/**
	 * Set up the basic settings
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see 		$suffixes
	 * @see			$mimetypes
	 * @see			$data
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _fileSettings() {
		$this->suffixes = array('txt','csv','xls','xml','ods');
		$this->mimetypes = array('text/html',
							'text/plain',
							'text/csv',
							'application/octet-stream',
							'application/x-octet-stream',
							'application/vnd.ms-excel',
							'application/excel',
							'application/ms-excel',
							'application/x-excel',
							'application/x-msexcel',
							'application/force-download',
							'text/comma-separated-values',
							'text/x-csv',
							'text/x-comma-separated-values',
							'application/vnd.oasis.opendocument.spreadsheet');
		$this->archives = array('zip', 'tgz');
		$this->data->sheets[0] = array();
	}

	/**
	 * Process the file to import
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
	abstract public function processFile();

	/**
	 * Validate the file
	 *
	 * Validate the file is of the supported type
	 * Types supported are csv, txt, xls, ods, xml
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		See if this code can be optimized
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool true if all OK | false if not OK
	 * @since 		3.0
	 */
	public function validateFile() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		// Workaround as it is always true
		//if ($jinput->get('filepos', 0, 'int') >= 0) {
		//	$csv_file = $template->get('local_csv_file', 'general', false);
		//	if (!$csv_file) {
		//		$csv_file = urldecode($jinput->get('csv_file'));
		//		$jinput->set('local_csv_file', $csv_file);
		//	}
		//	$this->folder = dirname($csv_file);
		//	$jinput->set('csv_file', $csv_file);
		//}
		$loadfrom = $template->get('source', 'general');
		switch (strtolower($loadfrom)) {
			// Uploaded file
			case 'fromupload':
				$upload['name'] = $_FILES['jform']['name']['general']['import_file'];
				$upload['type'] = $_FILES['jform']['type']['general']['import_file'];
				$upload['tmp_name'] = $_FILES['jform']['tmp_name']['general']['import_file'];
				$upload['error'] = $_FILES['jform']['error']['general']['import_file'];

				// Check if the file upload has an error
				if (empty($upload)) {
					$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_UPLOADED_FILE_PROVIDED'));
					return false;
				}
				else if ($upload['error'] == 0) {
					if (is_uploaded_file($upload['tmp_name'])) {
						// Get some basic info
						$folder = $this->_unpackpath.'/'.time();
						$upload_parts = pathinfo($upload['name']);

						// Create the temp folder
						if (JFolder::create($folder)) {
							$this->folder = $folder;
							// Move the uploaded file to its temp location
							if (JFile::upload($upload['tmp_name'], $folder.'/'.$upload['name'])) {
								$this->_uploaded = true;
								// Let's see if the uploaded file is an archive
								if (in_array($upload_parts['extension'], $this->archives)) {
									// It is an archive, unpack first
									if (JArchive::extract($folder.'/'.$upload['name'], $folder)) {
										// File is unpacked, let's get the filename
										$foundfiles = scandir($folder);
										foreach ($foundfiles as $ffkey => $filename) {
											$ff_parts = pathinfo($filename);
											if (in_array(strtolower($ff_parts['extension']), $this->suffixes)) {
												$jinput->set('csv_file', $folder.'/'.$filename);
												$jinput->set('upload_file_error', false);
												$this->extension = strtolower($ff_parts["extension"]);
												end($foundfiles);
											}
											else $found = false;
										}
										if (!$found) $jinput->set('upload_file_error', true);
									}
									else {
										$csvilog->AddStats('incorrect', JText::_('COM_CSVI_CANNOT_UNPACK_UPLOADED_FILE'));
										return false;
									}
								}
								// Just a regular file
								else {
									$jinput->set('csv_file', $folder.'/'.$upload['name']);
									$this->extension = strtolower($upload_parts['extension']);
								}
							}
						}
						else {
							$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_CREATE_UNPACK_FOLDER', $folder));
							return false;
						}
					}
					// Error warning cannot save uploaded file
					else {
						$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_NO_UPLOADED_FILE_PROVIDED', $upload['tmp_name']));
						return false;
					}
				}
				else {
					// There was a problem uploading the file
					switch($upload['error']) {
						case '1':
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_THE_UPLOADED_FILE_EXCEEDS_THE_MAXIMUM_UPLOADED_FILE_SIZE'));
							break;
						case '2':
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_THE_UPLOADED_FILE_EXCEEDS_THE_MAXIMUM_UPLOADED_FILE_SIZE'));
							break;
						case '3':
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_THE_UPLOADED_FILE_WAS_ONLY_PARTIALLY_UPLOADED'));
							break;
						case '4':
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_FILE_WAS_UPLOADED'));
							break;
						case '6':
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_MISSING_A_TEMPORARY_FOLDER'));
							break;
						case '7':
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_FAILED_TO_WRITE_FILE_TO_DISK'));
							break;
						case '8':
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_FILE_UPLOAD_STOPPED_BY_EXTENSION'));
							break;
						default:
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_THERE_WAS_A_PROBLEM_UPLOADING_THE_FILE'));
							break;
					}
					return false;
				}
				break;
			// Local file
			case 'fromserver':
				$csv_file = JPath::clean($template->get('local_csv_file', 'general'), '/');
				// Set the file name to use
				$jinput->set('csv_file', $csv_file);
				if (!JFile::exists($csv_file)) {
					$csvilog->addDebug('[VALIDATEFILE] '.JText::sprintf('COM_CSVI_LOCAL_FILE_DOESNT_EXIST', $csv_file));
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_LOCAL_FILE_DOESNT_EXIST', $csv_file));
					return false;
				}
				else $jinput->set('upload_file_error', false);
				$fileinfo = pathinfo($csv_file);
				if (isset($fileinfo["extension"])) {
					$this->extension = strtolower($fileinfo["extension"]);
					if ($this->extension == 'txt') $this->extension = 'csv';
				}
				break;
			case 'fromurl':
				// The temporary folder
				$folder = $this->_unpackpath.'/'.time();
				$urlfile = $template->get('urlfile', 'general', false);
				$tempfile = basename($urlfile);
				// Check if the remote file exists
				if ($urlfile) {
					if (CsviHelper::fileExistsRemote($urlfile)) {
						// Copy the remote file to a local location
						if (JFolder::create($folder)) {
							if (touch($folder.'/'.$tempfile)) {
								if (JFile::write($folder.'/'.$tempfile, JFile::read($urlfile))) {
									$csvilog->addDebug(JText::sprintf('COM_CSVI_RETRIEVE_FROM_URL', $urlfile));
									$jinput->set('csv_file', $folder.'/'.$tempfile);
									$jinput->set('upload_file_error', false);
									$this->extension = JFile::getExt($tempfile);
								}
								else {
									$csvilog->AddStats('incorrect', JText::_('COM_CSVI_CANNOT_READ_FROM_URL'));
									return false;
								}
							}
							else {
								$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_CREATE_TEMP_FILE', $folder.'/'.$tempfile));
								return false;
							}
						}
						else {
							$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_CREATE_TEMP_FOLDER', $folder));
							return false;
						}
					}
					else {
						$csvilog->AddStats('incorrect', JText::_('COM_CSVI_CANNOT_READ_FROM_URL'));
						return false;
					}
				}
				else {
					$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_FILENAME_GIVEN'));
					return false;
				}
				break;
			case 'fromftp':
				// The temporary folder
				$folder = $this->_unpackpath.'/'.time();
				$ftpfile = $template->get('ftpfile', 'general', false);
				if ($ftpfile) {
					// Create the output file
					if (JFolder::create($folder)) {
						if (touch($folder.'/'.$ftpfile)) {
							// Start the FTP
							jimport('joomla.client.ftp');
							$ftp = JFTP::getInstance($template->get('ftphost', 'general'), $template->get('ftpport', 'general'), null, $template->get('ftpusername', 'general'), $template->get('ftppass', 'general'));
							if ($ftp->get($folder.'/'.$ftpfile, $template->get('ftproot', 'general', '/').$ftpfile)) {
								$csvilog->addDebug(JText::sprintf('COM_CSVI_RETRIEVE_FROM_FTP', $template->get('ftproot', 'general', '/').$ftpfile));
								$jinput->set('csv_file', $folder.'/'.$ftpfile);
								$jinput->set('upload_file_error', false);
								$this->extension = JFile::getExt($ftpfile);
							}
							else {
								$csvilog->AddStats('incorrect', JText::_('COM_CSVI_CANNOT_READ_FROM_FTP'));
								return false;
							}
							$ftp->quit();
						}
						else {
							$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_CREATE_TEMP_FILE', $folder.'/'.$ftpfile));
							return false;
						}
					}
					else {
						$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_CREATE_TEMP_FOLDER', $folder));
						return false;
					}
				}
				else {
					$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_FILENAME_GIVEN'));
					return false;
				}
				break;
			// No file given
			default:
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_FILE_PROVIDED'));
				return false;
				break;
		}
		// Make sure txt files are not ignored
		if ($this->extension == 'txt') $this->extension = 'csv';

		// Set the filename
		$csv_file = $jinput->get('csv_file', '', 'string');
		if (JFile::exists($csv_file)) {
			$this->filename = JPath::clean($csv_file, '/');

			// Store the users filename for display purposes
			$csvilog->setFilename(basename($this->filename));
		}
		else {
			$csvilog->addDebug(JText::sprintf('COM_CSVI_LOCAL_FILE_DOESNT_EXIST', $jinput->get('csv_file')));
			return false;
		}
		if (in_array($this->extension, $this->suffixes)) $this->valid_extension = true;
		else {
			// Test the mime type
			if (!in_array($this->extension, $this->mimetypes) ) {
				$csvilog->AddStats('information', JText::sprintf('COM_CSVI_EXTENSION_NOT_ACCEPTED', $this->extension));
				return false;
			}
		}
		// Debug message to know what filetype the user is uploading
		$csvilog->addDebug(JText::sprintf('COM_CSVI_IMPORT_FILETYPE', $this->extension));

		// All is fine
		return true;
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
	abstract public function ReadNextLine();

	/**
	 * Close the file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see		 	processFile()
	 * @access
	 * @param
	 * @return
	 * @since
	 */
	public function closeFile($removefolder=true) {
		// Delete the uploaded folder
		if ($removefolder) $this->removeFolder();
	}

	/**
	 * Remove the temporary folder
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.0
	 */
	protected function removeFolder() {
		$jinput = JFactory::getApplication()->input;
		if (!$jinput->get('cron', false, 'bool')) {
			$folder = JPath::clean(dirname($this->filename), '/');
			$pos = strpos($folder, CSVIPATH_TMP);
			if ($pos !== false) if (JFolder::exists($folder)) JFolder::delete($folder);
		}
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
	abstract public function getFilePos();

	/**
	 * Set the current position in the file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$pos	the position to move to
	 * @return
	 * @since 		3.0
	 */
	abstract public function setFilePos($pos);

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
		return filesize($this->filename);
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
	abstract public function loadColumnHeaders();

	/**
	 * Advances the file pointer 1 forward
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		bool	$preview	True if called from the preview
	 * @return
	 * @since		3.0
	 */
	public function next($preview=false) {
		$discard = $this->readNextLine();
	}

	/**
	 * Sets the file pointer back to the beginning of the file
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
	abstract function rewind();

	/**
	 * Empties the data
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
	public function clearData() {
		$this->data = null;
		return true;
	}
}
?>