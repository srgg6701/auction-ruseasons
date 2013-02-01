<?php
/**
 * Export File model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: exportfile.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Export File Model
 *
* @package CSVI
 */
class CsviModelExportfile extends JModel {

	/** @var string Field delimiter */
	private $_field_delim = null;
	/** @var string Text delimiter */
	private $_text_delim = null;
	/** @var string Category separator */
	private $_catsep = null;
	/** @var array Holds the data for combined fields */
	private $_outputfield = array();
	/** @var string Contains the header name to be added */
	private $_headername = null;
	/** @var string Contains the last field to be exported */
	private $_last_field = null;
	/** @var string Contains the last field to be exported */
	private $_contents = array();

	/**
	 * Prepare for export
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Update the output type to include multiple destinations
	 * @todo		Is the setting of the export_type in JRequest necessary?
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getPrepareExport() {
		// Load the form handler
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$option = $jinput->get('option');
		$data	= $jinput->get('jform', array(), 'array');

		// Prepare the template
		$template = $jinput->get('template', null, null);
		if (is_null($template)) {
			$data['id'] = $jinput->get('template_id', 0, 'int');
		$template = new CsviTemplate($data);
		$jinput->set('template', $template);
		}
		$template->setName($jinput->get('template_name', '', 'string'));

		// Set the export type
		$jinput->set('export_type', $data['options']['operation']);

		// Initiate the log
		$csvilog = new CsviLog();

		// Create a new Import ID in the logger
		$csvilog->setId();

		// Set to collect debug info
		$csvilog->setDebug($template->get('collect_debug_info', 'general'));

		// Set some log info
		$csvilog->SetAction('export');
		$csvilog->SetActionType($jinput->get('export_type'), $template->getName('template_name'));

		// Add the logger to the registry
		$jinput->set('csvilog', $csvilog);

		// Load the fields to export
		$exportfields = $this->getExportFields();
		if (!empty($exportfields)) {
			// Set the last export field
			$jinput->set('export.fields', $exportfields);

			// Allow big SQL selects
			$db->setQuery("SET OPTION SQL_BIG_SELECTS=1");
			$db->query();

			// Get the filename for the export file
			$jinput->set('export.filename', $this->exportFilename());

			// See if we need to get an XML/HTML class
			$export_format = $template->get('export_file', 'general');
			if ($export_format == 'xml' || $export_format == 'html') {
				$exportclass = $this->getExportClass();
				if ($exportclass) $jinput->set('export.class', $exportclass);
				else {
					$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_EXPORT_CLASS'));
					$app->enqueueMessage(JText::_('COM_CSVI_NO_EXPORT_CLASS'), 'error');
					$jinput->set('logcount', 0);
					return false;
				}
			}

			// Return all is good
			return true;
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_EXPORT_FIELDS'));
			$app->enqueueMessage(JText::_('COM_CSVI_NO_EXPORT_FIELDS'), 'error');
			$jinput->set('logcount', 0);
			return false;
		}
	}

	/**
	 * Set the delimiters
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
	private function _setDelimiters() {
		if (is_null($this->_field_delim)) {
			$jinput = JFactory::getApplication()->input;
			$template = $jinput->get('template', null, null);
			// Set the delimiters
			$this->_field_delim = $template->get('field_delimiter', 'general', ',');
			$this->_text_delim = $template->get('text_enclosure', 'general', '');
		}
	}

	/**
	 * Process the export data
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
	public function getProcessData() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);
		$export_format = $template->get('export_file', 'general');
		$export_fields = $jinput->get('export.fields', null, null);
		$export_class = $jinput->get('export.class', null, null);

		// Write out some import settings
		$this->_exportDetails();

		// Start the export
		if (!$this->_outputStart()) {
			// Store the log results
			$log_model = $this->_getModel('log');
			$log_model->getStoreLogResults();
			return false;
		}

		// Add signature for Excel
		if ($template->get('signature', 'general')) $this->_contents['signature'] = "\xEF\xBB\xBF";

		// Add header for XML
		if ($export_format == 'xml') $this->_contents[] = $export_class->HeaderText();
		// Add header for HTML
		else if ($export_format == 'html') {
			$this->_contents[] = $export_class->HeaderText();
			if ($template->get('include_column_headers', 'general')) {
				$this->_contents[] = $export_class->StartTableHeaderText();
				foreach ($export_fields as $column_id => $field) {
					if ($field->process) {
						$header = ($field->column_header) ? $field->column_header : $field->field_name;
						$this->_contents[] = $export_class->TableHeaderText($header);
					}
				}
				$this->_contents[] = $export_class->EndTableHeaderText();
			}
			$this->_contents[] = $export_class->BodyText();
		}
		else {
			// Add the header from the template
			$header = $template->get('header', 'layout', false);
			if ($header) {
				$this->_contents[] = $header;
				$this->writeOutput();
			}

			// Get the delimiters
			// See if the user wants column headers
			// Product type names export needs to be excluded here otherwise the column headers are incorrect
			if ($template->get('include_column_headers', 'general', true)) {
				$this->_setDelimiters();
				$addheader = true;
				foreach ($export_fields as $column_id => $field) {
					if ($field->process) {
						$header = (empty($field->column_header)) ? $field->field_name : $field->column_header;
						if ($addheader) $this->_contents[] = $this->_text_delim.$header.$this->_text_delim;
						if ($field->combine) $addheader = false;
						else $addheader = true;
					}
				}
			}
		}

		// Output content
		$this->writeOutput();

		// Start the export from the chosen template type
		$exportmodel = $this->_getModel($jinput->get('export_type'));
		$exportmodel->getStart();

		if ($export_format == 'xml' || $export_format == 'html') {
			$footer = $export_class->FooterText();
		}
		else {
			// Add the footer from the template
			$footer = $template->get('footer', 'layout');
		}

		// Write the footer
		if ($footer && !empty($footer)) {
			$this->_contents[] = $footer;
			$this->writeOutput();
		}

		// End the export
		$this->_outputEnd();

		// Store the log results
		$log_model = $this->_getModel('log');
		$log_model->getStoreLogResults();

		// Process some settings
		switch ($template->get('exportto', 'general')) {
			case 'tofile':
			case 'toemail':
			case 'toftp':
				if (!$jinput->get('cron', false, 'bool')) {
					$app = JFactory::getApplication();
					$app->redirect(JURI::root().'administrator/index.php?option=com_csvi&task=process.finished&run_id='.$csvilog->getId());
				}
				break;
			case 'todownload':
				jexit();
				break;
			case 'tofront':
				return true;
				break;
		}
	}

	/**
	 * Cleanup after export
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
		// Store the log results first
		$log = $this->_getModel('log');
		$log->getStoreLogResults();

	}

	/**
	 * Load the export class that handles the file export
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool true when xml class is found|false when when no site is given
	 * @since 		3.0
	 */
	public function getExportClass() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$exportclass = false;
		$exporttype = $template->get('export_file', 'general');
		$exportsite = $template->get('export_site', 'general', 'csvimproved');

		// Construct the file name
		$filename = $exportsite.'.php';

		// Find the export class
		$helper = JPath::find(array(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/file/export/'.$exporttype), $filename);
		if (!$helper) return false;
		else {
			// Load the file and instantite it
			include_once($helper);
			$classname = 'Csvi'.ucfirst($exportsite);
			$exportclass = new $classname;
		}
		return $exportclass;
	}

	/**
	 * Get the export filename
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	Returns the filename of the exported file
	 * @since 		3.0
	 */
	public function exportFilename() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		// Check if the export is limited, if so add it to the filename
		// Check if both values are greater than 0
		if (($template->get('recordstart', 'general') > 0) && ($template->get('recordend', 'general') > 0)) {
			// We have valid limiters, add the limit to the filename
			$filelimit = "_".$template->get('recordend', 'general').'_'.($template->get('recordend', 'general')-1)+$template->get('recordstart', 'general');
		}
		else $filelimit = '';

		// Set the filename to use for export
		$export_filename = trim($template->get('export_filename', 'general'));
		$local_path = JPath::clean($template->get('localpath', 'general'), '/');
		$export_file = $template->get('export_file', 'general');

		// Do some customizing
		// Replace year
		$export_filename = str_replace('[Y]', date('Y', time()), $export_filename);
		$export_filename = str_replace('[y]', date('y', time()), $export_filename);
		// Replace month
		$export_filename = str_replace('[M]', date('M', time()), $export_filename);
		$export_filename = str_replace('[m]', date('m', time()), $export_filename);
		$export_filename = str_replace('[F]', date('F', time()), $export_filename);
		$export_filename = str_replace('[n]', date('n', time()), $export_filename);
		// Replace day
		$export_filename = str_replace('[d]', date('d', time()), $export_filename);
		$export_filename = str_replace('[D]', date('D', time()), $export_filename);
		$export_filename = str_replace('[j]', date('j', time()), $export_filename);
		$export_filename = str_replace('[l]', date('l', time()), $export_filename);
		// Replace hour
		$export_filename = str_replace('[g]', date('g', time()), $export_filename);
		$export_filename = str_replace('[G]', date('G', time()), $export_filename);
		$export_filename = str_replace('[h]', date('h', time()), $export_filename);
		$export_filename = str_replace('[H]', date('H', time()), $export_filename);
		// Replace minute
		$export_filename = str_replace('[i]', date('i', time()), $export_filename);
		// Replace seconds
		$export_filename = str_replace('[s]', date('s', time()), $export_filename);

		// Setup the full path for the filename
		switch ($template->get('exportto', 'general')) {
			case 'toemail':
			case 'toftp':
				if (!empty($export_filename)) $localfile = CSVIPATH_TMP.'/'.$export_filename;
				else $localfile = CSVIPATH_TMP.'/CSVI_VM_'.$jinput->get('template_name', '', 'cmd').'_'.date("j-m-Y_H.i").$filelimit.".".$export_file;
				break;
			case 'tofile':
				if (!empty($local_path) && !empty($export_filename)) $localfile = $local_path.'/'.$export_filename;
				else if (!empty($local_path))  $localfile = $local_path.'/CSVI_VM_'.$jinput->get('template_name', '', 'cmd').'_'.date("j-m-Y_H.i").$filelimit.".".$export_file;
				else 'CSVI_VM_'.$jinput->get('template_name', '', 'cmd').'_'.date("j-m-Y_H.i").$filelimit.".".$export_file;
				break;
			case 'tofront':
				$uri = JURI::getInstance();
				$localfile = $uri->toString();
				break;
			default:
				if (!empty($export_filename)) $localfile = $export_filename;
				else $localfile = 'CSVI_VM_'.$jinput->get('template_name', '', 'cmd').'_'.date("j-m-Y_H.i").$filelimit.".".$export_file;
				break;
		}

		// Clean up
		$localfile = JPath::clean($localfile, '/');

		// Set the filename
		$csvilog->setFilename($localfile);

		// Return the filename
		return $localfile;
	}

	/**
	 * Get the fields to use for the export
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array 	Returns an array of required fields and default field values
	 * @since 		3.0
	 */
	public function getExportFields() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$template = $jinput->get('template', null, null);

		// Get the field configuration
		$export_fields = $template->get('export_fields');

		// Set the field options for export
		if (!empty($export_fields)) {
			foreach ($export_fields['_selected_name'] as $kef => $field_name) {
				$field = new StdClass;
				$field->field_name = $field_name;
				$field->column_header = $export_fields['_column_header'][$kef];
				$field->default_value = $export_fields['_default_value'][$kef];
				$field->process = $export_fields['_process_field'][$kef];
				$field->combine = $export_fields['_combine_field'][$kef];
				$field->sort = $export_fields['_sort_field'][$kef];
				$field->replace = $export_fields['_replace_field'][$kef];
				$field->field_id = ($kef+1);
				$fields[($kef+1)] = $field;
			}
		}
		else return array();

		// Return the required and default values
		return $fields;
	}

	/**
	 * Print out export details
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
	private function _exportDetails() {
		$jinput = JFactory::getApplication()->input;
		// Get the logger
		$csvilog = $jinput->get('csvilog', null, null);
		// Get the template settings to see if we need a preview
		$template = $jinput->get('template', null, null);

		$csvilog->addDebug(JText::_('COM_CSVI_CSVI_VERSION_TEXT').JText::_('COM_CSVI_CSVI_VERSION'));
		if (function_exists('phpversion')) $csvilog->addDebug(JText::sprintf('COM_CSVI_PHP_VERSION', phpversion()));

		// General settings
		$csvilog->addDebug(JText::_('COM_CSVI_GENERAL_SETTINGS'));
		// Show which template is used */
		$csvilog->addDebug(JText::sprintf('COM_CSVI_TEMPLATE_NAME', $jinput->get('template_name')));

		// Destination settings
		$exportto = $template->get('exportto', 'general');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_CHOSEN_DESTINATION', $exportto));

		switch ($exportto) {
			case 'tofile':
				$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_PATH', $template->get('localpath', 'general')));
				break;
			case 'toftp':
				$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FTP_HOST', $template->get('ftphost', 'general')));
				$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FTP_PORT', $template->get('ftpport', 'general')));
				$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FTP_USERNAME', $template->get('ftpusername', 'general')));
				$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FTP_ROOT', $template->get('ftproot', 'general')));
				break;
		}

		// Export filename
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_GENERATED_FILENAME', $jinput->get('export.filename')));

		// Export type
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_CHOSEN_EXPORT_TYPE', $template->get('export_type')));

		// User given filename
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FILENAME', $template->get('export_filename')));

		// Export type
		$export_file = $template->get('export_file', 'general');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FILE', $export_file));
		if ($export_file == 'xml') {
			$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_SITE', $template->get('export_site', 'general')));
		}

		// Check delimiter char
		$csvilog->addDebug(JText::sprintf('COM_CSVI_USING_DELIMITER', $template->get('field_delimiter', 'general')));
		// Check enclosure char
		$csvilog->addDebug(JText::sprintf('COM_CSVI_USING_ENCLOSURE', $template->get('text_enclosure', 'general')));

		// Include column headers
		$use_header = ($template->get('include_column_headers', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_INCLUDE_HEADER', $use_header));

		// Add signature
		$signature = ($template->get('signature', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_SIGNATURE', $signature));

		// Export frontend
		$export_frontend = ($template->get('export_frontend', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FRONTEND', $export_frontend));

		// Export state
		switch ($template->get('publish_state', 'general')) {
			case 'Y':
				$publish_state = JText::_('COM_CSVI_YES');
				break;
			case 'N':
				$publish_state = JText::_('COM_CSVI_NO');
				break;
			default:
				$publish_state = JText::_('COM_CSVI_ALL_STATES');
				break;
		}
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_PUBLISH_STATE', $publish_state));

		// Number of records to export
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_RECORD_START', $template->get('recordstart', 'general')));
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_RECORD_END', $template->get('recordend', 'general')));

		// Record grouping
		$groupby = ($template->get('groupby', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_GROUPBY', $groupby));

		// VirtueMart Item ID
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_VM_ITEMID', $template->get('vm_itemid', 'general')));

		// Date format
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_DATE_FORMAT', $template->get('export_date_format', 'general')));

		// Price format
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_DECIMALS', $template->get('export_price_format_decimal', 'general')));
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_DEC_SEP', $template->get('export_price_format_decsep', 'general')));
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_THOUSAND_SEP', $template->get('export_price_format_thousep', 'general')));

		// Record grouping
		$add_currency = ($template->get('add_currency_to_price', 'general')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO');
		$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_ADD_CURRENCY_TO_PRICE', $add_currency));

		// Exporting fields
		$export_fields = $jinput->get('export.fields', null, null);
		$addheader = true;
		foreach ($export_fields as $column_id => $field) {
			if ($addheader) $csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_EXPORT_FIELD', $field->column_header));
			if ($field->combine) $addheader = false;
			else $addheader = true;
		}

	}

	/**
	 * Output the collected data
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		void
	 * @since		3.0
	 */
	private function _outputStart() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$exportfilename = $jinput->get('export.filename', null, 'string');
		$result = false;
		if ($template->get('use_system_limits', 'limit')) {
			$csvilog->addDebug('Setting system limits:');
			// Apply the new memory limits
			$csvilog->addDebug('Setting max_execution_time to '.$template->get('max_execution_time', 'limit').' seconds');
			@ini_set('max_execution_time', $template->get('max_execution_time', 'limit'));
			$csvilog->addDebug('Setting memory_limit to '.$template->get('memory_limit', 'limit').'M');
			if ($template->get('memory_limit', 'limit') == '-1') {
				$csvilog->addDebug('Setting memory_limit to '.$template->get('memory_limit', 'limit'));
				@ini_set('memory_limit', $template->get('memory_limit', 'limit'));
			}
			else {
				$csvilog->addDebug('Setting memory_limit to '.$template->get('memory_limit', 'limit').'M');
				@ini_set('memory_limit', $template->get('memory_limit', 'limit').'M');
			}
		}
		switch ($template->get('exportto', 'general', 'todownload')) {
			case 'todownload':
				if (preg_match('/Opera(\/| )([0-9].[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT'])) {
					$UserBrowser = "Opera";
				}
				elseif (preg_match('/MSIE ([0-9].[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT'])) {
					$UserBrowser = "IE";
				} else {
					$UserBrowser = '';
				}
				$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

				// Clean the buffer
				while( @ob_end_clean() );

				header('Content-Type: ' . $mime_type);
				header('Content-Encoding: UTF-8');
				header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

				if ($UserBrowser == 'IE') {
					header('Content-Disposition: inline; filename="'.$exportfilename.'"');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
				} else {
					header('Content-Disposition: attachment; filename="'.$exportfilename.'"');
					header('Pragma: no-cache');
				}
				$result = true;
				break;
			case 'tofile':
				jimport('joomla.filesystem.folder');

				// Check if the folder exists
				if (!JFolder::exists(dirname($exportfilename))) {
					if (!JFolder::create(dirname($exportfilename))) {
						$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_CREATE_FOLDER', dirname($exportfilename)));
						$result = false;
					}
				}

				// open the file for writing
				$handle = @fopen($exportfilename, 'w+');
				if (!$handle) {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_OPEN_FILE', $exportfilename));
					$result = false;
				}
				// Let's make sure the file exists and is writable first.
				if (is_writable($exportfilename)) {
				    $jinput->set('handle', $handle);
				    $result = true;
				}
				else {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_WRITE_FILE', $exportfilename));
					$result = false;
				}
				break;
			case 'toftp':
			case 'toemail':
				// open the file for writing
				$handle = fopen($exportfilename, 'w+');
				if (!$handle) {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_OPEN_FILE', $exportfilename));
					$result = false;
				}
				// Let's make sure the file exists and is writable first.
				if (is_writable($exportfilename)) {
				    $jinput->set('handle', $handle);
				    $result = true;
				}
				else {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_WRITE_FILE', $exportfilename));
					$result = false;
				}
				break;
			case 'tofront':
				$result = true;
				break;
		}

		return $result;
	}

	/**
	 * Write the output to download or to file
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string	$contents	the content to output
	 * @return 		bool	true if data is output | false if data is not output
	 * @since 		3.0
	 */
	protected function writeOutput() {
		// Let's take the local contents if nothing is supplied
		$contents = $this->_contents;

		// Clean the local contents
		$this->_contents = array();

		if (!empty($contents)) {
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			$template = $jinput->get('template', null, null);
			$exportfilename = $jinput->get('export.filename', null, 'string');

			if (!is_array($contents)) $contents = (array) $contents;

			switch ($template->get('exportto', 'general')) {
				case 'todownload':
				case 'tofront':
					if (isset($contents['signature'])) {
						echo $contents['signature'];
						unset($contents['signature']);
					}
					if ($template->get('export_file', 'general') == 'xml' || $template->get('export_file', 'general') == 'html') {
						echo implode("", $contents)."\r\n";
					}
					else {
						echo implode($this->_field_delim, $contents)."\r\n";
					}
					break;
				case 'tofile':
				case 'toftp':
				case 'toemail':
					if ($template->get('export_file', 'general') == 'xml' || $template->get('export_file', 'general') == 'html') {
						$writedata = '';
						if (isset($contents['signature'])) {
							$writedata = $contents['signature'];
							unset($contents['signature']);
						}
						$writedata .= implode('', $contents);
						if (fwrite($jinput->get('handle', null, null), $writedata."\r\n") === FALSE) {
							$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_WRITE_FILE', $exportfilename));
					   		return false;
						}
					}
					else {
						if (fwrite($jinput->get('handle', null, null), implode($this->_field_delim, $contents)."\r\n") === FALSE) {
							$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CANNOT_WRITE_FILE', $exportfilename));
					   		return false;
						}
					}
					break;
			}
		}
		return true;
	}

	/**
	 * Finalize export output
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		void
	 * @since 		3.0
	 */
	private function _outputEnd() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$exportfilename = $jinput->get('export.filename', null, 'string');

		jimport('joomla.filesystem.file');
		switch ($template->get('exportto', 'general')) {
			case 'todownload':
				break;
			case 'tofile':
				$csvilog->AddStats('information', JText::sprintf('COM_CSVI_EXPORTFILE_CREATED', $exportfilename));
				fclose($jinput->get('handle', null, null));
				break;
			case 'toftp':
				// Close the file handle
				fclose($jinput->get('handle', null, null));

				// Start the FTP
				jimport('joomla.client.ftp');
				$ftp = JFTP::getInstance($template->get('ftphost', 'general', '', 'string'), $template->get('ftpport', 'general'), null, $template->get('ftpusername', 'general', '', 'string'), $template->get('ftppass', 'general', '', 'string'));
				$ftp->chdir($template->get('ftproot', 'general', '/', 'string'));
				$ftp->store($exportfilename);
				$ftp->quit();

				// Remove the temporary file
				JFile::delete($exportfilename);
				
				$csvilog->AddStats('information', JText::sprintf('COM_CSVI_EXPORTFILE_CREATED', $exportfilename));
				break;
			case 'toemail':
				fclose($jinput->get('handle', null, null));
				$this->_getMailer();
				// Add the email address
				$addresses = explode(',', $template->get('export_email_addresses', 'email'));
				foreach ($addresses as $address) {
					if (!empty($address)) $this->mailer->AddAddress($address);
				}
				$addresses_cc = explode(',', $template->get('export_email_addresses_cc', 'email'));
				if (!empty($addresses_cc)) {
					foreach ($addresses_cc as $address) {
						if (!empty($address)) $this->mailer->AddCC($address);
					}
				}
				$addresses_bcc = explode(',', $template->get('export_email_addresses_bcc', 'email'));
				if (!empty($addresses_bcc)) {
					foreach ($addresses_bcc as $address) {
						if (!empty($address)) $this->mailer->AddBCC($address);
					}
				}

				// Mail submitter
				$htmlmsg = '<html><body>'.$this->_getRelToAbs($template->get('export_email_body', 'email')).'</body></html>';
				$this->mailer->setBody($htmlmsg);
				$this->mailer->setSubject($template->get('export_email_subject', 'email'));

				// Add the attachemnt
				$this->mailer->AddAttachment($exportfilename);

				// Send the mail
				$sendmail = $this->mailer->Send();
				if (is_a($sendmail, 'JException')) $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_NO_MAIL_SEND', $sendmail->getMessage()));
				else $csvilog->AddStats('information', JText::_('COM_CSVI_MAIL_SEND'));

				// Clear the mail details
				$this->mailer->ClearAddresses();

				// Remove the temporary file
				JFile::delete($exportfilename);
				
				$csvilog->AddStats('information', JText::sprintf('COM_CSVI_EXPORTFILE_CREATED', $exportfilename));
				break;
		}
	}

	/**
	 * Search through the export fields if a certain field is being exported
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string	$fieldname	the fieldname to check if it is being exported
	 * @return 		bool	true if field is being exported | false if field is not being exported
	 * @since 		3.0
	 */
	protected function searchExportFields($fieldname) {
		$jinput = JFactory::getApplication()->input;
		$exportfields = $jinput->get('export.fields', array(), 'array');
	 	foreach ($exportfields as $column_id => $field) {
	 		if ($field->field_name == $fieldname) return true;
	 	}
	 	return false;
	}

	/**
	 * Constructs a limit for a query
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		string	the limit to apply to the query
	 * @since 		3.0
	 */
	protected function getExportLimit() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$recordstart = $template->get('recordstart', 'general', 0, 'int');
		$recordend = $template->get('recordend', 'general', 0, 'int');
		$limits = array();
		$limits['offset'] = 0;
		$limits['limit'] = 0;
		// Check if the user only wants to export some products
		if ($recordstart && $recordend) {
			// Check if both values are greater than 0
			if (($recordstart > 0) && ($recordend > 0)) {
				// We have valid limiters, add the limit to the query
				// Recordend needs to have 1 deducted because MySQL starts from 0
				$limits['offset'] = $recordend-1;
				$limits['limit'] = $recordstart;
			}
		}
		return $limits;
	}

	/**
	 * Create an SQL filter
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string	$filter		what kind of SQL type should be created
	 * @param 		array	$ignore		an array of fields not to process
	 * @param		array	$special	an array of special fields not to nameQuote
	 * @return 		string	the SQL part to add to the query
	 * @since 		3.0
	 */
	protected function getFilterBy($filter, $ignore=array(), $special=array()) {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$export_fields = $jinput->get('export.fields', array(), 'array');
		$fields = array();

		// Add some basic fields never to be handled
		$ignore[] = 'custom';

		// Collect the fields to process
		foreach ($export_fields as $column_id => $field) {
			switch ($filter) {
				case 'groupby':
					$process = true;
					break;
				case 'sort':
					$process = $field->sort;
					break;
				default:
					$process = false;
			}
			if ($process) {
				// Check if field needs to be skipped
				if (!in_array($field->field_name, $ignore)) {
					// Check if field is special
					if (!array_key_exists($field->field_name, $special)) {
						$fields[] = $db->quoteName($field->field_name);
					}
					else {
						$fields[] = $special[$field->field_name];
					}
				}
			}
		}

		// Construct the SQL part
		if (!empty($fields)) {
			switch ($filter) {
				case 'groupby':
					$groupby_fields = array_unique($fields);
					$q = implode(',', $groupby_fields);
					break;
				case 'sort':
					$sort_fields = array_unique($fields);
					$q = implode(', ', $sort_fields);
					break;
				default:
					$q = '';
					break;
			}
		}
		else $q = '';

		return $q;
	}

	/**
	 * Add a field to the output
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		$combine 		boolean	true if the fields need to be combined
	 * @param 		$data 			string	Data to output
	 * @param 		$fieldname 		string	Name of the field currently being processed
	 * @param 		$column_header 	string	Name of the column
	 * @param 		$cdata			boolean true to add cdata tag for XML|false not to add it
	 * @return 		string containing the field for the export file
	 * @since 		3.0
	 */
	protected function addExportField($combine=false, $data, $fieldname, $column_header, $cdata=false) {
		// Data is not going to be combined
		if (!$combine) {
			if (!empty($this->_outputfield)) {
				$this->_outputfield[] = $data;
				$data = implode(' ', $this->_outputfield);
				$this->_outputfield = array();
			}
			$jinput = JFactory::getApplication()->input;
			$template = $jinput->get('template', null, null);

			// Load the session
			$exportclass =  $jinput->get('export.class', null, null);

			// Set the delimiters
			$this->_setDelimiters();

			// Clean up the data by removing linebreaks
			$find = array("\r\n", "\r", "\n");
			$replace = array('','','');
			$data = str_ireplace($find, $replace, $data);

			if ($template->get('export_file', 'general') == 'xml' || $template->get('export_file', 'general') == 'html') {
				if (!is_null($this->_headername)) {
					$column_header = $this->_headername;
					$this->_headername = null;
				}
				$this->_contents[] = $exportclass->ContentText($data, $column_header, $fieldname, $cdata);
			}
			else {
				$data = str_replace($this->_text_delim, $this->_text_delim.$this->_text_delim, $data);
				$this->_contents[] = $this->_text_delim.$data.$this->_text_delim;
			}
		}
		// Combine with previous field
		else {
			if (is_null($this->_headername)) $this->_headername = $column_header;
			$this->_outputfield[] = $data;
		}
	}

	/**
	 * Add data to the export content
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string	$content	the content to export
	 * @return
	 * @since 		3.0
	 */
	protected function addExportContent($content) {
		$this->_contents[] = $content;
	}


	/**
	 * Convert links in a text from relative to absolute
	 *
	 * @copyright
	 * @author
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$text	the text to parse for links
	 * @return 		string	the parsed text
	 * @since 		3.0
	 */
	private function _getRelToAbs($text) {
		$base = JURI::root();
  		$text = preg_replace("/(href|src)=\"(?!http|ftp|https|mailto)([^\"]*)\"/", '$1="$base\$2"', $text);

		return $text;
	}

	/**
	 * Initialise the mailer object to start sending mails
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
	private function _getMailer() {
		$mainframe = Jfactory::getApplication();
		jimport('joomla.mail.helper');

		// Start the mailer object
		$this->mailer = JFactory::getMailer();
		$this->mailer->isHTML(true);
		$this->mailer->From = $mainframe->getCfg('mailfrom');
		$this->mailer->FromName = $mainframe->getCfg('sitename');
		$this->mailer->AddReplyTo(array($mainframe->getCfg('mailfrom'), $mainframe->getCfg('sitename')));
	}

	/**
	 * Create a proxy for including other models
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
	private function _getModel($model) {
		return $this->getInstance($model, 'CsviModel');
	}

	/**
	 * Get the JoomFish translated value for a category name
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		int		$category_id	the category ID to find the translation for
	 * @param		string	$default		the default value to return if nothing found or JoomFish not used
	 * @return 		string	the JoomFish translated value
	 * @since 		3.0
	 */
	protected function _getJoomFishCategory($category_id, $default='') {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);

		if ($template->get('use_joomfish', 'product', false)) {
			$q = "SELECT value
				FROM #__jf_content
				WHERE reference_table = 'vm_category'
				AND reference_field = 'category_name'
				AND language_id = ".$template->get('joomfish_language', 'product')."
				AND reference_id = ".$category_id;
			$db->setQuery($q);
			$csvilog->addDebug(JText::_('COM_CSVI_GET_CATEGORY_JF'), true);
			$category_name_jf = $db->loadResult();
			if (empty($category_name_jf)) return $default;
			else return $category_name_jf;
		}
		else return $default;
	}
}
?>