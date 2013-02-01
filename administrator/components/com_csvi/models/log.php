<?php
/**
 * Log model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: log.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.modellist' );

/**
 * Log Model
 *
* @package CSVI
 */
class CsviModelLog extends JModelList {

	/** @var Set the context */
	var $_context = 'com_csvi.log';
	/** @var int holds the log ID */
	private $_id = null;
	/** @var object holds the pagination */
	private $_page = null;
	/** @var int holds the total number of items in database */
	private $_limittotal = null;
	/** @var int holds the number of items to display */
	private $_limit = null;
	/** @var int holds the offset where to start */
	private $_limitstart = null;

	/**
	 * Constructor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	public function __construct() {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array('l.action', 'l.action_type', 'l.template_name', 'l.records', 'l.file_name', 'l.run_cancelled', 'l.userid', 'l.logstamp');
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		protected
	 * @param
	 * @return		void
	 * @since		4.0
	 */
	protected function populateState() {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state
		$this->setState('filter.action', $app->getUserStateFromRequest($this->_context.'.filter.action', 'filter_actiontype', false, 'word'));

		// List state information.
		// Controls the query ORDER BY
		parent::populateState('l.logstamp', 'desc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return		object the query to execute
	 * @since 		4.0
	 */
	protected function getListQuery() {
		// Create a new query object.
		$jinput = JFactory::getApplication()->input;
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('run_id AS id, userid, logstamp, action, action_type, template_name, records, run_id, file_name, run_cancelled');
		$query->from('#__csvi_logs AS l');

		// Add all the filters
		$filters = array();
		if ($this->getState('filter.action')) $filters[] = $db->quoteName('action').' = '.$db->Quote($this->getState('filter.action'));

		if (!empty($filters)) {
		// Add the clauses to the query.
			$query->where('('.implode(' AND ', $filters).')');
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		return $query;
	}

	/**
	 * Set the log ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	public function setId($id) {
		 $this->_id = $id;
	}

	/**
	 * Store the log results after import/export
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
	public function getStoreLogResults() {
		// Load the settings
		$jinput = JFactory::getApplication()->input;
		$settings = $jinput->get('settings', null, null);

		if ($settings->get('log.log_store', 1)) {
			$db = JFactory::getDbo();
			$csvilog = $jinput->get('csvilog', null, null);
			$template = $jinput->get('template', null, null);
			$logresult = $csvilog->getStats();
			$details = array();
			$logcount = array();

			// Get the number of lines processed based on type
			switch ($logresult['action']) {
				case 'import':
					$logcount['import'] = $jinput->get('recordsprocessed', 0, 'int');
					break;
				case 'export':
					$logcount['export'] = $jinput->get('logcount', 0, 'int');
					break;
				case 'maintenance':
					$logcount['maintenance'] = $csvilog->GetLineNumber();
					break;
			}

			// Get the database connector
			$rowlog = $this->getTable('csvi_logs');

			// Check for an existing ID
			if (!$csvilog->getLogid()) {
				// Get user ID
				$my = JFactory::getUser();
				$details['userid'] = $my->id;
				// Create GMT timestamp
				jimport('joomla.utilities.date');
				$jnow = new JDate(time());
				$details['logstamp'] = $jnow->toMySQL();
				// Set action if it is import or export
				$details['action'] = $logresult['action'];
				// Type of action
				$details['action_type'] = $logresult['action_type'];
				// Name of template used
				$details['template_name'] = $logresult['action_template'];
				// Get the number of records
				$details['records'] = $logcount[$logresult['action']];
				// Get the import ID
				$details['run_id'] = $csvilog->getId();
				// Get the import filename
				$details['file_name'] = $csvilog->getFilename();

				// Bind the data
				if (!$rowlog->bind($details)) {
					JError::raiseWarning(0, JText::_('COM_CSVI_CANNOT_BIND_LOG_DATA'));
				}
				// Check the data
				if (!$rowlog->check()) {
					JError::raiseWarning(0, JText::_('COM_CSVI_CANNOT_CHECK_LOG_DATA'));
				}

				// Store the data
				if (!$rowlog->store()) {
					JError::raiseWarning(0, JText::_('COM_CSVI_CANNOT_STORE_LOG_DATA'));
				}
				else {
					// Clean up any old logs
					$csvilog->cleanUpLogs();
				}
				$csvilog->setLogid($rowlog->id);
				$rowlog->reset();
			}
			else {
				$rowlog->load($csvilog->getLogid());
				if (array_key_exists('action', $logresult) && isset($logcount[$logresult['action']])) $rowlog->records = $logcount[$logresult['action']];
				else $rowlog->records = 0;
				$rowlog->store();
			}

			// Store the log details
			if (is_array($logresult) && !empty($logresult)) {
				$q = 'INSERT INTO `#__csvi_log_details` ( `id`,`log_id`,`line`,`description`,`result`,`status` ) VALUES ';
				$qvalue = '';
				foreach ($logresult as $linenr => $result) {
					if (is_int($linenr)) {
						foreach ($result['status'] as $status => $stat) {
							$qvalue .= "(0, ".$csvilog->getLogid().", ".$linenr.", ".$db->Quote(trim($stat['message'])).", '".$stat['result']."', '".$status."'),\n";
						}
					}
				}
				if (!empty($qvalue)) {
					$q .= substr(trim($qvalue), 0, -1).';';
					$db->setQuery($q);
					if ($db->query()) $csvilog->cleanStats();
				}
				else $csvilog->cleanStats();
				
			}
		}
	}

	/**
	 * Delete 1 or more selected log entries
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of results
	 * @since		3.0
	 */
	public function getDelete() {
		$jinput = JFactory::getApplication()->input;
		jimport('joomla.filesystem.file');
		$db = JFactory::getDBO();
		$cids = $jinput->get('cid', array(), 'array');
		$file_not_found = 0;
		$file_deleted = 0;
		$file_not_deleted = 0;
		$log_del = 0;
		$log_del_error = 0;
		$log_detail_del = 0;
		$log_detail_del_error = 0;

		// Make it an array
		if (!is_array($cids)) (array)$cids;

		$rowlog = $this->getTable('csvi_logs');
		foreach ($cids as $key => $run_id) {
			$filename = CSVIPATH_DEBUG.'/com_csvi.log.'.$run_id.'.php';
			if (JFile::exists($filename)){
				if (JFile::delete($filename)) {
					$file_deleted++;
				}
				else $file_not_deleted++;
			}
			else $file_not_found++;

			// Delete the log entry
			if (empty($run_id)) $q = "SELECT id FROM #__csvi_logs WHERE (run_id = '' OR run_id IS NULL)";
			else $q = "SELECT id FROM #__csvi_logs WHERE run_id = ".$run_id;

			$db->setQuery($q);
			$ids = $db->loadResultArray();
			foreach ($ids as $idkey => $id) {
				if (!$rowlog->delete($id)) $log_del_error++;
				else {
					$log_del++;
				}

				// Delete the log details
				$q = "DELETE FROM #__csvi_log_details
					WHERE log_id = ".$id;
				$db->setQuery($q);
				if (!$db->query()) $log_detail_del_error++;
				else $log_detail_del++;
			}
		}

		// Set the results
		$results = array();
		if ($file_not_found > 0) {
			if ($file_not_found == 1) $results['ok'][] = JText::_('COM_CSVI_DELETE_LOG_FILE_NOT_FOUND');
			else $results['ok'][] = JText::sprintf('COM_CSVI_DELETE_LOGS_FILE_NOT_FOUND', $file_not_found);
		}
		if ($file_deleted > 0) {
			if ($file_deleted == 1) $results['ok'][] = JText::_('COM_CSVI_DELETE_LOG_FILE');
			else $results['ok'][] = JText::sprintf('COM_CSVI_DELETE_LOGS_FILE', $file_deleted);
		}
		if ($file_not_deleted > 0) {
			if ($file_not_deleted == 1) $results['nok'][] = JText::_('COM_CSVI_CANNOT_DELETE_LOG_FILE');
			else $results['nok'][] = JText::sprintf('COM_CSVI_CANNOT_DELETE_LOGS_FILE', $file_not_deleted);
		}
		if ($log_del > 0) {
			if ($log_del == 1) $results['ok'][] = JText::_('COM_CSVI_DELETE_LOG_DATA');
			else $results['ok'][] = JText::sprintf('COM_CSVI_DELETE_LOGS_DATA', $log_del);
		}
		if ($log_del_error > 0) {
			if ($log_del == 1) $results['nok'][] = JText::_('COM_CSVI_CANNOT_DELETE_LOG_DATA');
			else $results['nok'][] = JText::sprintf('COM_CSVI_CANNOT_DELETE_LOGS_DATA', $log_del);
		}
		if ($log_detail_del > 0) {
			if ($log_del == 1) $results['ok'][] = JText::_('COM_CSVI_DELETE_LOG_DETAILS_DATA');
			else $results['ok'][] = JText::sprintf('COM_CSVI_DELETE_LOGS_DETAILS_DATA', $log_detail_del);
		}
		if ($log_detail_del_error > 0) {
			if ($log_del == 1) $results['nok'][] = JText::_('COM_CSVI_CANNOT_DELETE_LOG_DETAILS_DATA');
			else $results['nok'][] = JText::sprintf('COM_CSVI_CANNOT_DELETE_LOGS_DETAILS_DATA', $log_detail_del_error);
		}

		return $results;
	}

	/**
	 * Delete all log entries
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access		public
	 * @param
	 * @return 		array of results
	 * @since 		3.0
	 */
	public function getDeleteAll() {
		$db = JFactory::getDBO();
		$results = array();

		// Empty the log table
		$q = "TRUNCATE ".$db->quoteName('#__csvi_logs');
		$db->setQuery($q);
		if ($db->query()) $results['ok'][] = JText::_('COM_CSVI_DELETE_LOG_DATA_ALL_OK');
		else $results['nok'][] = JText::_('COM_CSVI_DELETE_LOG_DATA_ALL_NOK');

		// Empty the log details table
		$q = "TRUNCATE ".$db->quoteName('#__csvi_log_details');
		$db->setQuery($q);
		if ($db->query()) $results['ok'][] = JText::_('COM_CSVI_DELETE_LOG_DATA_DETAILS_ALL_OK');
		else $results['nok'][] = JText::_('COM_CSVI_DELETE_LOG_DATA_DETAILS_ALL_NOK');

		return $results;
	}

	/**
	 * Load the statistics for displaying
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
	public function getStats() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);
		
		if ($csvilog) $run_id = $csvilog->getId();
		else if ($jinput->get('run_id', '', 'int') > 0) $run_id = $jinput->get('run_id', '', 'int');
		else {
			// Try to get it from the cid
			$cids = $jinput->get('cid', array(), 'array');
			if (is_array($cids) && array_key_exists('0', $cids)) $run_id = $cids[0];
			else return false;
		}
		
		$details = array();
		if ($run_id) {
			jimport('joomla.filesystem.file');

			// Add the run ID
			$details['run_id'] = $run_id;

			// Get the total number of records
			$q = "SELECT SUM(records) AS total_records
				FROM #__csvi_logs
				WHERE run_id = ".$run_id;
			$db->setQuery($q);
			$details['total_records'] = $db->loadResult();

			// Get the general details
			$q = "SELECT MIN(id) AS min_id, MAX(id)+1 AS max_id, file_name, action, action_type, run_cancelled
				FROM #__csvi_logs WHERE run_id = ".$run_id."
				GROUP BY id";
			$db->setQuery($q);
			$min_max = $db->loadObject();

			if (!empty($min_max)) { // Protect against 'record not found'
				// Set the filename
				$details['file_name'] = $min_max->file_name;

				// Set the action
				$details['action'] = $min_max->action;
				
				// Set the action type
				$details['action_type'] = $min_max->action_type;
				
				// Set if the action was cancelled
				$details['run_cancelled'] = $min_max->run_cancelled;

				// Get some status results
				$q = "SELECT COUNT(status) AS total_result, result, status
					FROM #__csvi_log_details WHERE log_id BETWEEN ".$min_max->min_id." AND ".$min_max->max_id."
					GROUP BY status";
				$db->setQuery($q);
				$details['result'] = $db->loadObjectList('status');
			}

			// Check if there is a debug log file
			$logfile = CSVIPATH_DEBUG.'/com_csvi.log.'.$run_id.'.php';
			if (JFile::exists($logfile)) {
				$details['debug'] = JHtml::_('link', JRoute::_('index.php?option=com_csvi&task=log.downloaddebug&run_id='.$run_id), JText::_('COM_CSVI_DOWNLOAD_DEBUG_LOG'));
				$attribs = 'class="modal" onclick="" rel="{handler: \'iframe\', size: {x: 950, y: 500}}"';
				$details['debugview'] = JHtml::_('link', JRoute::_('index.php?option=com_csvi&task=log.logreader&tmpl=component&run_id='.$run_id), JText::_('COM_CSVI_VIEW_DEBUG_LOG'), $attribs);
			}
			else {
				$details['debug'] = JText::_('COM_CSVI_NO_DEBUG_LOG_FOUND');
				$details['debugview'] = '';
			}
		}
		return $details;
	}

	/**
	* Load the statistics
	*/
	public function getStatsMessage() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$run_id = $jinput->get('run_id', false, 'int');
		if (!$run_id) {
			/* Try to get it from the cid */
			$cids = $jinput->get('cid', array(), 'array');
			if (is_array($cids) && array_key_exists('0', $cids)) $run_id = $cids[0];
			else return false;
		}
		$details = array();
		if ($run_id) {
			$q = "SELECT line, description, status, log_id, result
				FROM #__csvi_log_details
				WHERE log_id IN (SELECT id FROM #__csvi_logs WHERE run_id = ".$run_id.")
				ORDER BY line";
			$db->setQuery($q);
			$details =  $db->loadObjectList();
		}
		return $details;
	}

	/**
	* Download a debug report
	*/
	public function downloadDebug() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.archive');
		$jinput = JFactory::getApplication()->input;
		$run_id = $jinput->get('run_id', 0, 'int');
		$filepath = CSVIPATH_DEBUG.'/';
		$filename = 'com_csvi.log.'.$run_id.'.';

		$zip = JArchive::getAdapter('zip');
		$files = array();
		$files[] = array('name' => $filename.'php', 'time' => filemtime($filepath.$filename.'php'), 'data' => JFile::read($filepath.$filename.'php'));
        $zip->create($filepath.$filename.'zip', $files);

		if (preg_match('/Opera[\s|\/]([^\s]+)/i', $_SERVER['HTTP_USER_AGENT'])) {
			$UserBrowser = "Opera";
		}
		elseif (preg_match('/MSIE\s([^\s|;]+)/i', $_SERVER['HTTP_USER_AGENT'])) {
			$UserBrowser = "IE";
		} else {
			$UserBrowser = '';
		}
		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

		/* Clean the buffer */
		while( @ob_end_clean() );
		header('Content-Description: File Transfer');
		header('Content-Type: ' . $mime_type);
		header('Content-Transfer-Encoding: binary');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Length: ' . filesize($filepath.$filename.'zip'));

		if ($UserBrowser == 'IE') {
			header('Content-Disposition: inline; filename="'.$filename.'zip"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Content-Disposition: attachment; filename="'.$filename.'zip"');
			header('Pragma: no-cache');
		}
		/* Send the file */
		readfile($filepath.$filename.'zip');
		JFile::delete($filepath.$filename.'zip');

		/* Close the transmission */
		exit();
	}

	/**
	* Get the action types
	*
	* @author RolandD
	* @access public
	* @return array list of action types
	*/
	public function getActionTypes() {
		$db = JFactory::getDbo();
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_CSVI_LOG_DONT_USE'));
		$q = "SELECT UPPER(action) FROM #__csvi_logs GROUP BY action";
		$db->setQuery($q);
		$actions = $db->loadResultArray();
		if (!empty($actions)) {
      foreach ($actions as $action) {
        $options[] = JHTML::_('select.option', $action, JText::_('COM_CSVI_'.$action));
      }
      }
		return $options;
	}

	/**
	* Reads a log file and displays its results
	*
	* @author RolandD
	* @since 2.3.11
	* @access public
	* @return array of log lines
	*/
	public function getLogfile() {
		$jinput = JFactory::getApplication()->input;
		$run_id = $jinput->get('run_id', 0, 'int');
		$log = array();
		if ($run_id > 0) {
			$logfile = CSVIPATH_DEBUG.'/com_csvi.log.'.$run_id.'.php';
			$loglines = array();
			if (file_exists($logfile)) {
				$loglines = file($logfile);
				foreach ($loglines as $key => $line) {
					switch ($key) {
						case '0':
							// Skip the first line
							break;
						case '1':
							// Get the date
							if (strstr($line, ':')) {
								list($text, $value) = explode(': ', $line);
							}
							else $value = '';
							$log['date'] = $value;
							break;
						case '2':
							// Get the Joomla version
							if (strstr($line, ':')) {
								list($text, $value) = explode(': ', $line);
							}
							else $value = '';
							$log['joomla'] = $value;
							break;
						case '3':
							// This is an empty line
							break;
						case '4':
							// Get the fields
							if (strstr($line, ':')) {
								list($text, $value) = explode(': ', $line);
								$fields = preg_split("/\t/", $value);
								foreach ($fields as $fkey => $field) {
									$log['fields'][] = $field;
								}
							}
							else $log['fields'] = array();
							break;
						default:
							// The actual log lines
							$log['entries'][] = preg_split("/\t/", $line);
							break;
					}
				}
			}
		}
		return $log;
	}
}
?>