<?php
/**
 * Import file model
 *
 * @author		Roland Dalmulder
 * @link		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version		$Id: importfile.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );

/**
 * Import file Model
 */
class CsviModelImportfile extends JModel {

	// Private variables
	/** @var integer for keeping track when the script started */
	private $_starttime = 0;
	/** @var array - Indexed array of field names (mixed case) */
	private $_supported_fields = array();
	/** @var array - Associative array of field_name (lower case) => field default value */
	private $_field_defaults = array();
	/** @var array - Associative array of field_name (lower case) => field published? value */
	private $_field_published = array();
	/** @var integer the database ID for the vendor */
	private $_vendor_id = null;
	/** @var bool sets if the default value should be used or not */
	private $_skip_default_value = null;
	/** @var array contains a list of vendor currencies */
	private $_vendor_currencies = array();
	/** @var object contains the ICEcat helper */
	private $_icecat = null;

	// Protected variables
	/** @var object contains the data to import */
	protected $csvi_data = null;
	/** @var object contains the fields to import */
	protected $_csvifields = null;
	/** @var array contains the available fields */
	protected $_avfields = null;
	/** @var array contains the ICEcat data */
	protected $icecat_data = null;
	/** @var array contains the fields to combine */
	protected $combine_fields = array();
	/** @var array contains the combine settings */
	protected $combine_settings = array();
	/** @var string contains the name of the last import field */
	protected $_lastfield = null;

	/**
	 * Compile a list of helper files needed to include
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Make the image class loader smarter
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array	list of helper files to include
	 * @since 		3.0
	 */
	public function getHelperFiles() {
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->get('jform', array(), null);
		$helpers = array();
		$upload_parts = array();

		// Get the file extension of the import file
		switch (strtolower($data['general']['source'])) {
			case 'fromupload':
				$upload['name'] = $_FILES['jform']['name']['general']['import_file'];
				$upload['type'] = $_FILES['jform']['type']['general']['import_file'];
				$upload['tmp_name'] = $_FILES['jform']['tmp_name']['general']['import_file'];
				$upload['error'] = $_FILES['jform']['error']['general']['import_file'];
				if (isset($upload['name'])) $upload_parts = pathinfo($upload['name']);
				break;
			case 'fromserver':
				$upload_parts = pathinfo($data['general']['local_csv_file']);
				break;
			case 'fromurl':
				$upload_parts = pathinfo($data['general']['urlfile']);
				break;
			case 'fromftp':
				$upload_parts = pathinfo($data['general']['ftpfile']);
				break;
		}

		// Set the file helper
		if (!array_key_exists('extension', $upload_parts)) return false;
		else {
			switch ($upload_parts['extension']) {
				case 'xml':
					$helpers[] = $upload_parts['extension'];
					$fileclass = 'Xml';
					break;
				case 'xls':
					$helpers[] = $upload_parts['extension'];
					$helpers[] = 'excel_reader2';
					$fileclass = 'Xls';
					break;
				case 'ods':
					$helpers[] = $upload_parts['extension'];
					$helpers[] = 'ods_reader';
					$fileclass = 'Ods';
					break;
				default:
					// Treat any unknown type as CSV
					$helpers[] = 'csv';
					$fileclass = 'Csv';
					break;
			}
			// Set the file class name
			$jinput->set('fileclass', $fileclass.'File');

			// Do we need to load the image helper
			switch ($data['options']['operation']) {
				case 'productimport':
				case 'categoryimport':
				case 'mediaimport':
					$helpers[] = 'images';
					break;
			}

			// Add the helpers to the session
			$session = JFactory::getSession();
			$option = $jinput->get('option');
			$session->set($option.'.helper_files', serialize($helpers));

			return $helpers;
		}
	}

	/**
	 * Make preparations for the import
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Fix storing of log when file cannot be retrieved from FTP
	 * @todo		Fix the template name
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool	true on file OK | false on file NOK
	 * @since 		3.0
	 */
	public function getPrepareImport() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		// Get the form data
		$session = JFactory::getSession();
		$option = $jinput->get('option');
		$data 	= $jinput->get('jform', array(), null);

		// Set the template name
		$query = $db->getQuery(true);
		$query->select('name');
		$query->from('#__csvi_template_settings');
		$query->where('id = '.$jinput->get('select_template', '', null));
		$db->setQuery($query);
		$data['template_name'] = $db->loadResult();

		$template = new CsviTemplate($data);
		$jinput->set('template', $template);
		$session->set($option.'.global.template', serialize($template));

		// Initiate the log
		$csvilog = new CsviLog();
		$jinput->set('csvilog', $csvilog);

		// Create a new Import ID in the logger
		$csvilog->setId();

		// Set to collect debug info
		$csvilog->setDebug($template->get('collect_debug_info', 'general'));

		// Retrieve the available fields
		$availablefields = $this->getModel('Availablefields');
		$this->_avfields = $availablefields->getAvailableFields($template->get('operation', 'options'), $template->get('component', 'options'));
		$session->set($option.'.avfields', serialize($this->_avfields));
		// Needed for file class
		$jinput->set('avfields', $this->_avfields);

		// Set some log info
		$csvilog->SetAction('import');
		$csvilog->SetActionType($template->get('operation', 'options'), $template->get('template_name'));

		// Process the file to import
		if ($template->get('im_mac', 'general', false)) {
			// Auto detect line-endings to also support Mac line-endings
			ini_set('auto_detect_line_endings', true);
		}

		// Process the file to import
		$fileclass = $jinput->get('fileclass', null, null);
		$csvifile = new $fileclass;
		if ($csvifile->validateFile()) {
			$csvifile->processFile();
			if (!$csvifile->fp) {
				return false;
			}
			else {
				// Load column headers
				if ($template->get('use_column_headers', 'general')) {
					$csvifile->loadColumnHeaders();
					$session->set($option.'.csvicolumnheaders', serialize($jinput->get('columnheaders', array(), 'array')));
				}
				else if ($template->get('skip_first_line', 'general')) {
					// Move 1 row forward as we are skipping the first line
					$csvifile->next();
				}

				// Load the fields
				if ($this->_retrieveConfigFields($csvifile)) $session->set($option.'.csvifields', serialize($jinput->get('csvifields', null, 'array')));
				else {
					JError::raiseNotice(0, JText::_('COM_CSVI_CANNOT_LOAD_FIELDS'));
					return false;
				}

				// Write out some import settings
				$this->_importDetails();

				// Store the file position
				$session->set($option.'.filepos', serialize($csvifile->getFilePos()));

				// Empty the data first so we don't break the session
				$csvifile->clearData();

				// Store the CSVI file handler
				$session->set($option.'.csvifile', serialize($csvifile));

				// Store the CSVI log handler
				$session->set($option.'.csvilog', serialize($csvilog));

				// Store the preview handler
				$session->set($option.'.csvipreview', serialize($template->get('show_preview', 'general')));

				// Set the combine separator
				$this->combine_settings['separator'] = ' ';
				$this->combine_settings['fieldname'] = null;

				// Unpublish any products if needed
				if ($template->get('unpublish_before_import', 'product', 0)) $this->_unpublishProducts();
				return true;
			}
		}
		else {
			return false;
		}
	}

	/**
	 * Make preparations to do an import
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Fix the setting of the file position on subsequent imports
	 * @see 		finishProcess
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getDoImport() {
		$jinput = JFactory::getApplication()->input;
		// Set the system limits
		$this->_systemLimits();

		// Open the file
		$csvifile = $jinput->get('csvifile', null, null);
		if ($csvifile->processFile()) {
			// Set the file pointer
			$session = JFactory::getSession();
			$option = $jinput->get('option');
			$csvifile->setFilePos(unserialize($session->get($option.'.filepos', 0)));

			// Set the line counter
			$jinput->set('currentline', 1);

			// Set the fields found in the file
			$this->_csvifields = $jinput->get('csvifields', null, 'array');

			return true;
		}
		else return false;
	}

	/**
	 * Start the import
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Separate view for preview
	 * @todo		Rewrite memory usage for debug
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getProcessData() {
		$jinput = JFactory::getApplication()->input;
		// Set some variables
		$data_preview = array();
		$processdata = true;
		$redirect = false;

		// Load the log
		$csvilog = $jinput->get('csvilog', null, null);

		// Load the settings
		$settings = new CsviSettings();

		// Load the template
		$template = $jinput->get('template', null, null);

		// Load the file
		$csvifile = $jinput->get('csvifile', null, null);

		// Set the table path
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables/'.$template->get('component', 'options'));

		// Load the import routine
		$classname = $this->getModel($template->get('operation', 'options'));
		if (class_exists($classname)) $routine = new $classname;
		else $routine = false;

		if ($routine) {
			// Start processing data
			while ($processdata) {
				// Pass the total log line to the logger
				$csvilog->setLinenumber(($jinput->get('currentline', 0, 'int')+$jinput->get('totalline', 0, 'int')));

				// If the number of lines is set to 0, do unlimited import
				if (($settings->get('import.import_nolines', 0) == 0) || $jinput->get('cron', false, 'bool')) {
					$nolines = $jinput->get('currentline', 0, 'int')+1;
				}
				else $nolines = $settings->get('import.import_nolines');

				if ($jinput->get('currentline', 0, 'int') <= $nolines) {
					// For XML files, it may be necessary to refresh the headers before reading the next record
					if ($csvifile->extension == 'xml' && $template->get('refresh_xml_headers', 'general')) {
						$csvifile->loadColumnHeaders();
						if ($this->_retrieveConfigFields() == false) {
							// Error found - Finish processing
							$redirect = $this->finishProcess(false);
							$processdata = false;
							continue;
						}
					}
					// Load the data
					$this->csvi_data = $csvifile->ReadNextLine();

					if ($this->csvi_data == false) {
						if ($jinput->get('csvipreview', false, 'bool')) {
							// Set the headers
							$headers = array();
							foreach ($this->_csvifields as $fieldname => $value) {
								if ($value['published']) {
									if (isset($routine->$fieldname) || empty($routine->$fieldname)) $headers[] = $fieldname;
								}
							}
							$jinput->set('headers_preview', $headers);

							// Set the data
							$jinput->set('data_preview', $data_preview);

							// Clean the session
							$this->getCleanPreview();

							$processdata = false;
							continue;
						}
						else {
							// Finish processing
							$this->finishProcess(true);
							$processdata = false;
						}
					}
					else {
						// Check if we need to add any extra fields
						if (count($this->_csvifields) > count($this->csvi_data)) {
							foreach ($this->_csvifields as $fieldname => $details) {
								if (!array_key_exists($details['order'], $this->csvi_data)) {
									if (!empty($details['default_value'])) {
										$this->csvi_data[$details['order']] = $details['default_value'];
									}
								}
							}
							// Check if the fields are now equal
							if (count($this->_csvifields) > count($this->csvi_data)) {
								$message =  JText::sprintf('COM_CSVI_INCORRECT_COLUMN_COUNT', count($this->_csvifields), count($this->csvi_data));
								$message .= '<br />'.JText::_('COM_CSVI_FIELDS').'<br />';
								$message .= '<table class="adminlist"><thead><tr><th>Position</th><th>Configuration</th><th>Import file</th></tr></thead><tfoot></tfoot>';
								$message .= '<tbody>';
								foreach($this->_csvifields as $fieldname => $field_details) {
									$message .= '<tr><td>'.$field_details['order'].'</td><td>'.$fieldname.'</td><td>';
									if (isset($this->csvi_data[$field_details['order']])) $message .= $this->csvi_data[$field_details['order']];
									$message .= '</td></tr>';
								}
								$message .= '</tbody></table>';
								$csvilog->AddStats('incorrect', $message, true);

								// Finish processing
								$this->finishProcess(true);
								$processdata = false;
							}
						}

						// Load ICEcat data if user wants to
						$this->getIcecat();

						// Validate the fields
						$csvi_data = new JObject();
						foreach ($this->_csvifields as $name => $details) {
							if ($details['published']) {
								$datafield = $this->validateInput($details['name'], $details['replace']);
								if ($datafield !== false) {
									// Check if we are dealing with the last field
									if ($details == $this->_lastfield) $details['combine'] = false;

									// See if we are combining the field
									if ($details['combine']) $this->setCombineField($datafield, $name);
									else {
										// Check if there are any fields to be combined
										if (!empty($this->combine_fields)) {
											// Get the fieldname the combine is for
											$name = $this->combine_settings['fieldname'];
											// Add the current data
											$this->setCombineField($datafield);
											// Get the combined data
											$datafield = $this->getCombineField();
										}
									}

									// Set the new value
									$csvi_data->$name = $datafield;
								}
							}
						}

						$jinput->set('csvi_data', $csvi_data);

						if ($this->_checkLimits()) {
							// Notify the debug log what line we are one
							$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_PROCESS_LINE', ($jinput->get('currentline', 0, 'int')+$jinput->get('totalline', 0, 'int'))));

							// Start processing record
							if ($routine->getStart()) {
								if ($jinput->get('csvipreview', false, 'bool')) {
									$this->loadSettings();
									// Update preview data
									foreach ($this->_csvifields as $fieldname => $value) {
										if ($value['published']) {
											if (isset($routine->$fieldname)) $preview_data[$value['order']] = $routine->$fieldname;
											else if (empty($routine->$fieldname)) $preview_data[$value['order']] = '';
										}
									}
									$data_preview[$jinput->get('currentline', 0, 'int')] = $preview_data;

									if ($jinput->get('currentline', 0, 'int') == $settings->get('import.import_preview', 5)) {
										// Set the headers
										$headers = array();
										foreach ($this->_csvifields as $fieldname => $value) {
											if ($value['published']) {
												if (isset($routine->$fieldname) || empty($routine->$fieldname)) $headers[] = $fieldname;
											}
										}
										$jinput->set('headers_preview', $headers);

										// Set the data
										$jinput->set('data_preview', $data_preview);

										// Clean the session
										$this->getCleanPreview();

										$processdata = false;
										continue;
									}
								}
								else {
									// Now we import the rest of the records
									$routine->getProcessRecord();
								}

								// Increase the number of records processed
								$jinput->set('recordsprocessed', $jinput->get('recordsprocessed', 0, 'int')+1);
							}
							else {
								// The routine reports a problem, usually unmet conditions

								// Finish processing
								$this->finishProcess(true);

								// Stop from processing any further, no time left
								$processdata = false;

							}
							// Increase linenumber
							$jinput->set('currentline', $jinput->get('currentline', 0, 'int')+1);
						}
						else {
							// Finish processing
							$this->finishProcess(false);

							// Stop from processing any further, no time left
							$processdata = false;
						}
					}
				}
				// Prepare for page reload
				else {
					// Finish processing
					$this->finishProcess(false);

					// Stop from processing any further, no time left
					$processdata = false;
				}
			}

			// Post Processing
			if (method_exists($routine, 'getPostProcessing')) {
				$routine->getPostProcessing(array_keys($this->_csvifields));
			}
		}
		else {
			$csvilog->AddStats('incorrect', 'COM_CSVI_NO_VALID_CLASS_FOUND');

			// Finish processing
			$this->finishProcess(true);
		}
	}

	/**
	 * Clean the session
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		void
	 * @since 		3.0
	 */
	public function getCleanSession() {
		$jinput = JFactory::getApplication()->input;
		// Store the log results first
		$log = $this->getModel('log');
		$log->getStoreLogResults();

		// Get session handler
		$session = JFactory::getSession();
		$option = $jinput->get('option');

		// Unset all session values
		$session->clear($option.'.csvicolumnheaders');
		$session->clear($option.'.csvifields');
		$session->clear($option.'.avfields');
		$session->clear($option.'.csvifile');
		$session->clear($option.'.filepos');
		$session->clear($option.'.recordsprocessed');
		$session->clear($option.'.template_id');
		$session->clear($option.'.totalline');
		$session->clear($option.'.csvilog');
		$session->clear($option.'.global.template');
		$session->clear($option.'.csvisettings');
		$session->clear($option.'.helper_files');
	}

	/**
	 * Clean the session after preview
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		void
	 * @since 		3.0
	 */
	private function getCleanPreview() {
		$jinput = JFactory::getApplication()->input;
		// Get session handler
		$session = JFactory::getSession();
		$option = $jinput->get('option');

		// Load the correct position
		if ($jinput->get('csvipreview', false, 'bool')) {
			$template = $jinput->get('template', null, null);
			$csvifile = $jinput->get('csvifile', null, null);

			// Move back to the beginning
			$csvifile->rewind();

			// Move 1 row forward as this is the column header
			if ($template->get('use_column_headers', 'general') || $template->get('skip_first_line', 'general')) {
				$csvifile->next(true);
			}

			// Get the current position
			$filepos = $csvifile->getFilePos();
		}
		else $filepos = 0;

		// Unset all session values
		$session->set($option.'.filepos', serialize($filepos));
		$session->set($option.'.recordsprocessed', serialize(0));
		$session->set($option.'.csvipreview', serialize(false));
	}

	/**
	 * Sets the system limits to user defined values
	 *
	 * Sets the system limits to user defined values to allow for longer and
	 * bigger uploaded files
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Allow 0 or -1 value
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _systemLimits() {
		$jinput = JFactory::getApplication()->input;
		// Set the start time of the script
		$this->_starttime = time();

		// Get the logger
		$csvilog =  $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

	// See if we need to use the new limits
		if ($template->get('use_system_limits', 'limit')) {
			$csvilog->addDebug('Setting system limits:');
			// Apply the new memory limits
			$execution_time = $template->get('max_execution_time');
			if (strlen($exection_time) > 0) {
				$csvilog->addDebug('Setting max_execution_time to '.$execution_time.' seconds');
				@ini_set('max_execution_time', $execution_time);
			}
			$memory_limit = $template->get('memory_limit', 'limit');
			if ($memory_limit == '-1') {
				$csvilog->addDebug('Setting memory_limit to '.$memory_limit);
				@ini_set('memory_limit', $memory_limit);
			}
			else if (strlen($memory_limit) > 0) {
				$csvilog->addDebug('Setting memory_limit to '.$memory_limit.'M');
				@ini_set('memory_limit', $memory_limit.'M');
			}
			$post_size = $template->get('post_max_size', 'limit');
			if (strlen($post_size) > 0) {
				$csvilog->addDebug('Setting post_max_size to '.$post_size.'M');
				@ini_set('post_max_size', $post_size.'M');
			}
			$file_size = $template->get('upload_max_filesize', 'limit');
			if (strlen($file_size) > 0) {
				$csvilog->addDebug('Setting upload_max_filesize to '.$file_size.'M');
				@ini_set('upload_max_filesize', $file_size.'M');
			}
		}
	}

	/**
	 * Function to check if execution time is going to be passed.
	 *
	 * Memory can only be checked if the function memory_get_usage is available.
	 * If the function is not available always return true. This could lead to
	 * out of memory failure.
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see 		http://www.php.net/memory_get_usage
	 * @access 		private
	 * @param
	 * @return 		bool true when limits are not reached|false when limit is reached
	 * @since 		3.0
	 */
	private function _checkLimits() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);

		// Check for maximum execution time
		$timepassed = time() - $this->_starttime;
		if (($timepassed + 5) > ini_get('max_execution_time') && ini_get('max_execution_time') > 0) {
			$csvilog->AddStats('information', JText::sprintf('COM_CSVI_MAXIMUM_EXECUTION_LIMIT_EXCEEDED', $timepassed));
			return false;
		}

		// Check for maximum memory usage
		if (!function_exists('memory_get_usage')) return true;
		else {
			// Get the size of the statistics
			$statslength = 0;
			if (isset($csvilog->stats)) {
				foreach ($csvilog->stats as $type => $value) {
					if (isset($value['message'])) $statslength += strlen($value['message']);
				}
			}
			$statslength = round($statslength/(1024*1024));

			// Get the size of the debug message
			$debuglength = round(strlen($csvilog->debug_message)/(1024*1024));

			// Get the size of the active memory in use
			$activelength = round(memory_get_usage()/(1024*1024));

			// Combine memory limits
			$totalmem = $activelength + $statslength + $debuglength;

			// Set the memory limit
			$jinput->set('maxmem', $totalmem);

			// Check if we are passing the memory limit
			if (($totalmem * 1.5) > (int)ini_get('memory_limit')) {
				$csvilog->AddStats('information', JText::_('COM_CSVI_MAXIMUM_MEMORY_LIMIT_EXCEEDED', $totalmem));
				return false;
			}

			// All is good return true
			return true;
		}
	}

	/**
	 * Print out import details
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _importDetails() {
		$jinput = JFactory::getApplication()->input;
		// Get the logger
		$csvilog = $jinput->get('csvilog', null, null);
		// Get the template settings to see if we need a preview
		$template = $jinput->get('template', null, null);

		$csvilog->addDebug(JText::_('COM_CSVI_CSVI_VERSION_TEXT').JText::_('COM_CSVI_CSVI_VERSION'));
		if (function_exists('phpversion')) $csvilog->addDebug(JText::sprintf('COM_CSVI_PHP_VERSION', phpversion()));

		// General settings
		$csvilog->addDebug(JText::_('COM_CSVI_GENERAL_SETTINGS'));
		// Show which template is used
		$csvilog->addDebug(JText::sprintf('COM_CSVI_TEMPLATE_NAME', $template->get('template_name')));
		// Auto detect delimiters
		$auto_detect = ($template->get('auto_detect_delimiters', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_AUTO_DETECT_DELIMITERS', $auto_detect));
		if ($auto_detect == JText::_('COM_CSVI_NO')) {
			// Check delimiter char
			$csvilog->addDebug(JText::sprintf('COM_CSVI_USING_DELIMITER', $template->get('field_delimiter', 'general')));
			// Check enclosure char
			$csvilog->addDebug(JText::sprintf('COM_CSVI_USING_ENCLOSURE', $template->get('text_enclosure', 'general')));
		}

		// Show import settings
		// Show template type
		$csvilog->addDebug(JText::sprintf('COM_CSVI_CHOSEN_IMPORT_TYPE', JText::_('COM_CSVI_'.$template->get('operation', 'options'))));

		// Use column headers as configuration
		$use_header = ($template->get('use_column_headers', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_USE_HEADER', $use_header));

		// Refresh xml headers for every record
		$refresh_xml_headers = ($template->get('refresh_xml_headers', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_REFRESH_XML_HEADER', $use_header));

		// Skip first line
		$skip_first = ($template->get('skip_first_line', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_SKIP_FIRST_LINE', $skip_first));

		// Ignore non-existing products
		$ignore_non_exist = ($template->get('ignore_non_exist', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_IGNORE_NON_EXIST', $ignore_non_exist));

		// Overwrite existing data
		$overwrite = ($template->get('overwrite_existing_data', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_OVERWRITE_EXISTING_DATA', $overwrite));

		// Skip default value
		$skip_default = ($template->get('skip_default_value', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_SKIP_DEFAULT_VALUE', $skip_default));

		// Show preview
		$use_preview = ($template->get('show_preview', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_USING_PREVIEW', $use_preview));

		// Products
		// Unpublish products before import
		$unpublish = ($template->get('unpublish_before_import', 'product')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_UNPUBLISH_BEFORE_IMPORT', $unpublish));


		// Categories
		// Append categories
		$append_cats = ($template->get('append_categories', 'product')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_APPEND_CATEGORIES', $append_cats));

		// Images
		// General options
		$process_image = ($template->get('process_image', 'image', false)) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_PROCESS_IMAGE', $process_image));

		// Create image name
		$create_name = ($template->get('auto_generate_image_name', 'image')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_CREATE_IMAGE_NAME', $create_name));

		// Generate image name
		$csvilog->addDebug(JText::sprintf('COM_CSVI_IMAGE_BASED_ON', $template->get('type_generate_image_name', 'image')));

		// Image name format
		$csvilog->addDebug(JText::sprintf('COM_CSVI_IMAGE_NAME_FORMAT', $template->get('auto_generate_image_name_ext', 'image')));

		// Full image
		// Convert image
		$csvilog->addDebug(JText::sprintf('COM_CSVI_CONVERT_IMAGE', $template->get('convert_type', 'image')));

		// Save images on server
		$on_server = ($template->get('save_images_on_server', 'image')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_SAVE_IMAGES_ON_SERVER', $on_server));

		// Thumbnail image
		// Automatic thumbnail creation
		$auto_thumb = ($template->get('thumb_create', 'image')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_AUTOMATIC_THUMBS', $auto_thumb));

		if ($auto_thumb == JText::_('COM_CSVI_YES')) {
			// Thumbnail format
			$csvilog->addDebug(JText::sprintf('COM_CSVI_FORMAT_THUMBS', $template->get('thumb_extension', 'image')));

			// Thumbnail width x height
			$csvilog->addDebug(JText::sprintf('COM_CSVI_DIMENSION_THUMBS', $template->get('thumb_width', 'image'), $template->get('thumb_height', 'image')));
		}

		// Show the file paths
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_FILE_PATH_PRODUCT_IMAGES', $template->get('file_location_product_images', 'path')));
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_FILE_PATH_CATEGORY_IMAGES', $template->get('file_location_category_images', 'path')));
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_FILE_PATH_MEDIA', $template->get('file_location_media', 'path')));

		// Show the max execution time
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_MAX_EXECUTION_TIME', ini_get('max_execution_time')));
	}

	/**
	 * Unpublish products before import
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see 		prepareImport()
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _unpublishProducts() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__virtuemart_products');
		$query->set('published = 0');
		$db->setQuery($query);
		if ($db->query()) $csvilog->addDebug(JText::_('COM_CSVI_PRODUCT_UNPUBLISH_BEFORE_IMPORT'));
		else $csvilog->addDebug(JText::_('COM_CSVI_COULD_NOT_UNPUBLISH_BEFORE_IMPORT'), true);
	}

	/**
	* Builds arrays of field names and default values to be used during the creation of the headers list
	* The creation of the headers from the data file may need to be carried out for every row when processing
	* XML files and so efficiency is important for performance.
	*
	* Note: The array supported_fields should not be used as the basis for these arrays because it is a list of
	*   all available fields and some of these fields may not be mapped in the template.
	*/
	public function _fieldArrays() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$db = JFactory::getDBO();
		$this->_supportedfields = array();
		$this->_field_defaults = array();
		$this->_field_published = array();

		if ($template->get('use_column_headers', 'general')) {
			$supportedfields = array_flip($this->_avfields);
			foreach ($supportedfields as $name => $value) {
				$this->_supported_fields[] = $name;
				$this->_field_defaults[strtolower($name)] = null;
				$this->_field_published[strtolower($name)] = 1;
			}
		}
		// Use the template fields assigned to the template
		else {
			$import_fields = $template->get('import_fields');
			if (isset($import_fields['_selected_name'])) {
				$count = count($import_fields['_selected_name']);
				for ($rows = 0; $rows < $count; $rows++) {
					$this->_supported_fields[] = $import_fields['_selected_name'][$rows];
					$this->_field_defaults[strtolower($import_fields['_selected_name'][$rows])] = $import_fields['_default_value'][$rows];
					$this->_field_published[strtolower($import_fields['_selected_name'][$rows])] = $import_fields['_process_field'][$rows];
				}
			}
		}
		// Create the inverted array used to lookup the field name using lowercase
		$this->_supportedfields = array();
		foreach( $this->_supported_fields as $key => $value ) {
			$this->_supportedfields[strtolower($value)] = $key;
		}
	}

	/**
	 * Get the configuration fields the user wants to use
	 *
	 * The configuration fields can be taken from the uploaded file or from
	 * the database. Depending on the template settings.
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Expand the no support fields message on line 398
	 * @see 		Templates()
	 * @access 		private
	 * @param
	 * @return 		bool true|false true when there are config fields|false when there are no or unsupported fields
	 * @since 		3.0
	 */
	private function _retrieveConfigFields($csvifile=false) {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);
		if (!$csvifile) $csvifile = $jinput->get('csvifile', '', 'string');
		if (empty($this->_supportedfields)) $this->_fieldArrays();
		$columnheaders = $jinput->get('columnheaders', array(), 'array');
		$csvifields = array();
		$nosupport = array();
		$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_LOAD_CONFIG_FIELDS'));
		if ($template->get('use_column_headers', 'general')) {
			// The user has column headers in the file
			$jinput->set('error_found', false);
			if ($columnheaders) {
				foreach ($columnheaders as $order => $name) {
					// Trim the name in case the name contains any preceding or trailing spaces
					$name = strtolower(trim($name));

					// Check if the fieldname is supported
					// No special field checking for Product Type Names upload
					if (array_key_exists($name, $this->_supportedfields)) {
						$csvilog->addDebug('Field: '.$name);
						$mixed_name = $this->_supported_fields[$this->_supportedfields[$name]];
						$csvifields[$mixed_name]['name'] = $mixed_name;
						$csvifields[$mixed_name]['order'] = $order;
						$csvifields[$mixed_name]['default_value'] = (array_key_exists($name, $this->_field_defaults)) ? $this->_field_defaults[$name] : null;
						$csvifields[$mixed_name]['published'] = (array_key_exists($name, $this->_field_published)) ? $this->_field_published[$name] : 'Y';
						$csvifields[$mixed_name]['combine'] = false;
						$csvifields[$mixed_name]['replace'] = null;
					}
					else {
						// Check if the user has any field that is not supported
						if (strlen($name) == 0) $name = JText::_('COM_CSVI_FIELD_EMPTY');

						// Field is not supported, let's skip it
						$csvifields[$name]['name'] = $name;
						$csvifields[$name]['order'] = $order;
						$csvifields[$name]['default_value'] = null;
						$csvifields[$name]['published'] = 'N';
						$csvifields[$name]['combine'] = false;
						$csvifields[$name]['replace'] = null;

						// Unset the column header so we can check if any fields are left over
						unset($columnheaders[$order]);

						// Collect the fieldnames to report them
						$nosupport[] = $name;
					}
				}
				//if (empty($columnheaders)) {
				//	$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_COLUMN_HEADERS_FOUND'));
				//	$jinput->set('error_found', true);
				//	return false;
				//}

				if (!empty($nosupport)) {
					// Ensure the error message matches the file type
					switch($csvifile->extension) {
						case 'xml':
						case 'sql':
							$csvilog->AddStats('nosupport', implode(',', $nosupport).JText::_('COM_CSVI_FIELD_NOT_INCLUDED'));
							break;
						default:
							$csvilog->AddStats('nosupport', JText::sprintf('COM_CSVI_NO_SUPPORT', '<ul><li>'.implode('</li><li>', $nosupport).'</li></ul>'));
							break;
					}
					$csvilog->AddStats('information', JText::_('COM_CSVI_UNSUPPORTED_FIELDS'));
				}

				$csvilog->addDebug(JText::_('COM_CSVI_USING_FILE_FOR_CONFIGURATION'));
			}
			else {
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_COLUMN_HEADERS_FOUND'));
				$jinput->set('error_found', true);
				return false;
			}
		}
		// Use the fields assigned to the template
		else {
			$fields = $template->get('import_fields');

			if (!empty($fields)) {
				foreach ($fields['_selected_name'] as $fid => $name) {
					// Check if we are handling a combine field
					if ($name == 'combine') $name .= $fid;

					// Collect the data
					$csvifields[$name]['name'] = $name;
					$csvifields[$name]['order'] = $fid;
					$csvifields[$name]['default_value'] = $fields['_default_value'][$fid];
					$csvifields[$name]['published'] = $fields['_process_field'][$fid];
					$csvifields[$name]['combine'] = $fields['_combine_field'][$fid];
					$csvifields[$name]['replace'] = $fields['_replace_field'][$fid];

					if (!$csvifields[$name]['published']) $name .= ' ('.JText::_('COM_CSVI_FIELD_SKIPPED').')';
					$csvilog->addDebug(JText::sprintf('COM_CSVI_IMPORT_FIELD', $name));
				}
				$csvilog->addDebug(JText::_('COM_CSVI_USE_DATABASE_FOR_CONFIGURATION'));
			}
			else {
				$csvilog->AddStats('incorrect', JText::_('NO_COLUMN_HEADERS_FOUND'));
				return false;
			}
		}

		// Make the fields to process global
		$jinput->set('csvifields', $csvifields);
		return true;
	}

	/**
	 * Handle the end of the import
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo		Optimize adding ID to session
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function finishProcess($finished=false) {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvifile = $jinput->get('csvifile', null, null);
		$csvilog = $jinput->get('csvilog', null, null);

		// Adjust the current line, since it is not processing
		$jinput->set('currentline', $jinput->get('currentline', 0, 'int')-1);

		// Session init
		$session = JFactory::getSession();
		$option = $jinput->get('option');
		if ($finished) {
			// Close the file
			if (is_object($csvifile)) $csvifile->closeFile(true);

			// Clean the session
			$this->getCleanSession();

			// Add the ID to the session as we need it for the redirect to the result page
			$session->set($option.'.run_id', $csvilog->getId());
			$jinput->set('run_id', $csvilog->getId());
		}
		else {
			// Flush the log details
			// Store the log results first
			$log = $this->getModel('log');
			$log->getStoreLogResults();

			// Create session variables
			$session->set($option.'.global.template', serialize($template));
			$session->set($option.'.csvicolumnheaders', serialize($jinput->get('columnheaders', null, 'array')));
			$session->set($option.'.csvifields', serialize($this->_csvifields));
			$session->set($option.'.csvifile', serialize($csvifile));
			$session->set($option.'.csvilog', serialize($csvilog));
			$session->set($option.'.filepos', serialize($csvifile->getFilePos()));
			$session->set($option.'.recordsprocessed', serialize($jinput->get('recordsprocessed', 0, 'int')));
			$session->set($option.'.totalline', serialize($jinput->get('currentline', 0, 'int') + $jinput->get('totalline', 0, 'int')));

			// Close the file
			$csvifile->closeFile(false);
		}
	}

	/**
	 * Create a proxy for including other models
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
	protected function getModel($model) {
		return $this->getInstance($model, 'CsviModel');
	}

	/**
	 * Load some settings we need for the functions
	 * This will make the data available to the child product
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	protected function loadSettings() {
		$jinput = JFactory::getApplication()->input;
		// Load the settings
		$template = $jinput->get('template', null, null);
		$this->_avfields = $jinput->get('avfields', array(), 'array');
		$this->_skip_default_value = $template->get('skip_default_value', 'general');

		// Set the last field, needed for the combine function
		$this->_lastfield = end($jinput->get('csvifields', array(), 'array'));
	}

	/**
	 * Load the data to import
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.4
	 */
	protected function loadData() {
		$jinput = JFactory::getApplication()->input;
		$this->csvi_data = $jinput->get('csvi_data', null, null);
	}

	/**
	 * Get the product currency from the vendor
	 *
	 * If the user does not use product currency we take the one from the current vendor
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		integer	$vendor_id	the database ID of the vendor
	 * @return 		string	the 3 letter product currency
	 * @since 		3.0
	 */
	protected function productCurrency($vendor_id) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_PRODUCT_CURRENCY'));
		if (array_key_exists($vendor_id, $this->_vendor_currencies)) {
			$product_currency = strtoupper($this->_vendor_currencies[$vendor_id]);
		}
		else {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('vendor_currency');
			$query->from('#__virtuemart_vendors');
			$query->where('virtuemart_vendor_id = '.$vendor_id);
			$db->setQuery($query);
			$product_currency = $db->loadResult();
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_PRODUCT_CURRENCY'), true);

			// Add the vendor currency to the cache
			$this->_vendor_currencies[$vendor_id] = $product_currency;
		}
		return $product_currency;
	}

	/**
	 * Validate input data
	 *
	 * Checks if the field has a value, if not check if the user wants us to
	 * use the default value
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string 	$fieldname 	the fieldname to validate
	 * @param		int		$replaceid	the ID of the replacement rule
	 * @return		true returns validated value | return false if the column count does not match
	 * @since
	 */
	protected function validateInput($fieldname, $replaceid=null) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$newvalue = '';
		// Check if the user wants ICEcat data
		if ($template->get('use_icecat', 'product', false, 'bool') && !empty($this->icecat_data) && (array_key_exists($fieldname, $this->icecat_data))) {
			$csvilog->addDebug(JText::sprintf('COM_CSVI_USE_ICECAT_FIELD', $fieldname));
			$newvalue = $this->icecat_data[$fieldname];
		}
		else if (isset($this->_csvifields[$fieldname])) {
			// Check if the field has a value
			if (array_key_exists($this->_csvifields[$fieldname]["order"], $this->csvi_data)
				&& strlen($this->csvi_data[$this->_csvifields[$fieldname]["order"]]) > 0) {
				$csvilog->addDebug(JText::_('COM_CSVI_USE_FIELD_VALUE'));
				$newvalue = trim($this->csvi_data[$this->_csvifields[$fieldname]["order"]]);
			}
			// Field has no value, check if we can use default value
			else if (!$this->_skip_default_value) {
				$csvilog->addDebug(JText::_('COM_CSVI_USE_DEFAULT_VALUE'));
				$newvalue = $this->_csvifields[$fieldname]["default_value"];
			}
			else {
				$csvilog->addDebug(JText::_('COM_CSVI_USE_NO_VALUE'));
				return '';
			}
		}
		else return false;

		// Replace the value and return
		if (!empty($newvalue) && !empty($replaceid)) return CsviHelper::replaceValue($replaceid, $newvalue);
		else return $newvalue;
	}

	/**
	 * Replace commas with periods for correct DB insertion of the prices
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Handle multiple separators by removing them
	 * @see
	 * @access 		protected
	 * @param 		string	$value	the value to clean up
	 * @return 		string	the cleaned up value with dots as separator
	 * @since 		3.0
	 */
	protected function toPeriod($value) {
		$clean = str_replace(",", ".", $value);
		$lastpos = strrpos($clean, '.');
		return str_replace('.', '' , substr($clean, 0, $lastpos)).substr($clean, $lastpos);
	}

	/**
	 * Format a datetime format
	 *
	 * Format of the date is day/month/year or day-month-year.
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		use JDate
	 * @see
	 * @access 		protected
	 * @param 		string	$date	the date to convert
	 * @return		integer	UNIX timestamp if date is valid otherwise return 0
	 * @since
	 */
	protected function convertDate($date) {
		$new_date = preg_replace('/-|\./', '/', $date);
		$date_parts = explode('/', $new_date);

		if ((count($date_parts) == 3) && ($date_parts[0] > 0 && $date_parts[0] < 32 && $date_parts[1] > 0 && $date_parts[1] < 13 && (strlen($date_parts[2]) == 4))) {
			$old_date = mktime(0,0,0,$date_parts[1],$date_parts[0],$date_parts[2]);
		}
		else $old_date = 0;
		$date = JFactory::getDate($old_date);
		return $date->toMySQL();
	}

	/**
	 * Add the query statistics to the log
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
	 protected function queryResult() {
	 	$db = JFactory::getDBO();
	 	return substr($db->getQuery(), 0, strpos($db->getQuery(),' '));
	 }

	 /**
	 * Clean up a price to only exist of numbers
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string	$price	the price to clean
	 * @return		float	cleaned up price
	 * @since
	 */
	protected function cleanPrice($price) {
		return JFilterInput::clean($this->toPeriod($price), 'float');
	}

	/**
	 * Load the ICEcat data for a product
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
	protected function getIcecat() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		if ($template->get('use_icecat', 'product')) {
			$csvilog = $jinput->get('csvilog', null, null);

			// Load the ICEcat helper
			if (is_null($this->_icecat)) $this->_icecat = new IcecatHelper();

			// Clean the data holder
			$this->icecat_data = null;

			// Check conditions
			// 1. Do we have an MPN
			$mpn = $this->validateInput($template->get('mpn_column_name', 'product', 'product_sku'));
			if ($mpn) {
				$csvilog->addDebug(JText::sprintf('COM_CSVI_ICECAT_FOUND_REFERENCE', $mpn));
				// 2. Do we have a manufacturer name
				$mf_name = $this->validateInput('manufacturer_name');
				$csvilog->addDebug(JText::sprintf('COM_CSVI_ICECAT_FOUND_MF_NAME', $mf_name));
				if ($mf_name) {
					// Load the ICEcat data
					$this->icecat_data = $this->_icecat->getData($mpn, $mf_name);
				}
				else {
					$csvilog->addDebug(JText::_('COM_CSVI_ICECAT_NO_MANUFACTURER'));
					return false;
				}
			}
			else {
				$csvilog->addDebug(JText::_('COM_CSVI_ICECAT_NO_REFERENCE'));
				return false;
			}
		}
		return false;

	}

	/**
	 * Set a field to combine
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string	$data		the data to be combined
	 * @param 		string	$fieldname	the name of the current field
	 * @return
	 * @since 		3.0
	 */
	protected function setCombineField($data, $fieldname=null) {
		if (!empty($data)) {
			// Add the data to the array
			$this->combine_fields[] = $data;

			// Set the fieldname the data is for
			if (empty($this->combine_settings['fieldname'])) $this->combine_settings['fieldname'] = $fieldname;
			switch ($fieldname) {
				case 'category_path':
					$jinput = JFactory::getApplication()->input;
					$template = $jinput->get('template', null, null);
					$this->combine_settings['separator'] = $template->get('category_separator', 'general', '/');
					break;
			}
		}
	}

	/**
	 * Get the combined fields
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		string	the space separated combined data
	 * @since 		3.0
	 */
	protected function getCombineField() {
		// Get the combined data
		$data = implode($this->combine_settings['separator'], $this->combine_fields);

		// Empty some settings
		$this->combine_fields = array();
		$this->combine_settings['fieldname'] = null;
		$this->combine_settings['separator'] = ' ';

		// Return the data
		return $data;
	}
}