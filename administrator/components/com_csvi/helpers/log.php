<?php
/**
 * The logger class
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: log.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die;

/**
 * Helper class for logging
 */
class CsviLog {

	// Private variables
	/** @var int contains the current line number */
	private $_linenumber = 0;
	/** @var int contains a unique id for the import */
	private $_id = 0;
	/** @var string The name of the file being imported/exported */
	private $_filename = '';
	/** @var bool The status if debug info is to be collected */
	private $_debug = false;
	/** @var int database ID for log results */
	private $_logid = false;
	/** @var int start line for logging */
	private $_log_line_start = 1;
	/** @var int end line for logging */
	private $_log_line_end = 5;

	// Public variables
	/** @var string contains the log messages */
	public $logmessage = '';
	/** @var string contains the debug messages */
	public $debug_message = '';
	/** @var array contains the import statistics */
	public $stats = array();

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
	 * @since 		3.4
	 */
	public function __construct() {
		$jinput = JFactory::getApplication()->input;
		$settings = $jinput->get('settings', null, null);
		$this->_log_line_start = $settings->get('debuglog.log_line_start', 1);
		$this->_log_line_end = $settings->get('debuglog.log_line_end', 5);
	}

	/**
	 * Clean up old log entries
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
	public function cleanUpLogs() {
		// Load the settings
		$jinput = JFactory::getApplication()->input;
		$settings = $jinput->get('settings', null, null);
		$max = $settings->get('log.log_max', 25);
		$cid = array();

		// Check if there are any logs to remove
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('run_id');
		$query->from('#__csvi_logs');
		$query->order('id');
		$db->setQuery($query);
		$dblogs = $db->loadResultArray();
		$this->addDebug(JText::sprintf('COM_CSVI_CLEAN_OLD_LOGS', count($dblogs), $max), false, 'DEBUG', true);
		if (count($dblogs) > $max) {
			$jinput->set('cid', array_slice($dblogs, 0, (count($dblogs)-$max)));

			// Load the log model
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/models/log.php');
			$log_model = new CsviModelLog();
			$log_model->getDelete();
		}
	}

    /**
     * Invoke the Joomla logger
     *
     * @copyright
     * @author 		RolandD
     * @todo
     * @see
     * @access 		public
     * @param 		string	$comment	The comment to log
     * @param		int		$linenr		The linenumber concerned
     * @param		string	$action		The type of action
     * @return 		void
     * @since 		3.0
     */
    public function simpleLog($comment, $linenr, $action) {
        // Include the library dependancies
        jimport('joomla.log.log');

        // Set the logfile
        $this->getLogName();

        // Create the instance of the log file in case we use it later
        $options = array('text_entry_format' => "{DATE}\t{TIME}\t{LINE_NR}\t{ACTION}\t{COMMENT}", 'text_file' => $this->logfile, 'text_file_path' => $this->logpath);
        JLog::addLogger($options);

		$entry = new JLogEntry($comment);
		$entry->comment = $comment;
		$entry->line_nr = $linenr;
		$entry->action = $action;
        JLog::add($entry);
    }

    /**
     * Return the name of the logfile
     *
     * @copyright
     * @author 		RolandD
     * @todo
     * @see
     * @access 		public
     * @param
     * @return 		string	The name of the logfile
     * @since 		3.0
     */
    public function getLogName() {
        $this->logfile = 'com_csvi.log.'.$this->getId().'.php';
        $this->logpath = CSVIPATH_DEBUG;

    	return $this->logpath.'/'.$this->logfile;
    }

	/**
	 * Set the current line number
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$linenumber	The current linenumber
	 * @return 		bool	true
	 * @since 		3.0
	 */
	public function setLinenumber($linenumber) {
		$this->_linenumber = $linenumber;
		return true;
	}

	/**
	 * Set the import/export ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$id	The ID to set
	 * @return 		int	the ID
	 * @since 		3.0
	 */
	public function setId($id=false) {
		if ($id) $this->_id = $id;
		else $this->_id = time();
		return $this->_id;
	}

	/**
	 * Get the import/export ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		int	the ID
	 * @since 		3.0
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * Set the filename used for import/export
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$filename	the full path and filename of the import/export file
	 * @return 		void
	 * @since
	 */
	public function setFilename($filename) {
		$this->_filename = $filename;
	}

	/**
	 * Get the import filename
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	the full path and filename of the logfile
	 * @since
	 */
	public function getFilename() {
		return $this->_filename;
	}

	/**
	 * Set the log ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$id	The log ID to set
	 * @return 		void
	 * @since 		3.0
	 */
	public function setLogid($id) {
		$this->_logid = $id;
	}

	/**
	 * Get the log ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		int	The current log ID
	 * @since 		3.0
	 */
	public function getLogid() {
		return $this->_logid;
	}

	/**
	 * Enable the debugger
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		bool	$val	Set the logging on or off
	 * @return 		void
	 * @since 		3.0
	 */
	public function setDebug($val) {
		$this->_debug = $val;
	}

	/**
	 * Adds a message to the log file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string 	$message 	message to add to the debug log
	 * @param 		string 	$sql 		if true adds the sql statement
	 * @param 		string 	$action 	the kind of action to qualify the message for
	 * @param 		boolean	$override	sets if the line check should be overridden
	 * @return
	 * @since 		3.0
	 */
	public function addDebug($message, $sql=false, $action='DEBUG', $override=false) {
		// Check if we should add the log line
		if ($this->_debug) {
			if ($override || $this->_linenumber == 0 || ($this->_linenumber >= $this->_log_line_start && $this->_linenumber <= $this->_log_line_end)) {
				$this->simpleLog(JText::_($message), $this->_linenumber, '['.$action.']');
				if ($sql) {
					$db = JFactory::getDbo();
					$qmsg = '';
					if ($db->getErrorNum() > 0) {
						$qmsg .= $db->getErrorMsg();
						$qaction = 'SQL ERROR';
					}
					else {
						$qmsg .= $db->getQuery();
						$qaction = 'QUERY';
					}
					$qmsg = str_replace(array("\r\n", "\n", "\r", "\t"), ' ', $qmsg);
					$this->simpleLog($qmsg, $this->_linenumber, '['.$qaction.']');
				}
			}
		}
	}

	/**
	* Adds a message to the statistics stack
	*
	* <p>
	* Types:
	* --> Products
	* updated
	* deleted
	* added
	* skipped
	* incorrect
	* --> DB tables
	* empty
	* --> Fields
	* nosupport
	* --> No files found multiple images
	* nofiles
	* --> General information
	* information
	* </p>
	*
	* @param string $type type of message
	* @param string $message message to add to the stack
	 */
	function AddStats($type, $message) {
		// Load the settings
		$jinput = JFactory::getApplication()->input;
		$settings = $jinput->get('settings', null, null);
		$message = JText::_($message);

		switch ($type) {
			case 'updated':
			case 'deleted':
			case 'added':
			case 'empty':
				if ($settings->get('log.log_type', 'all') == 'all') $this->_addMessage($type, $message);
				break;
			case 'incorrect':
			case 'nosupport':
				if ($settings->get('log.log_type', 'all') == 'all'
					|| $settings->get('log.log_type') == 'failure'
					|| $settings->get('log.log_type') == 'failure_notice') $this->_addMessage($type, $message);
				break;
			case 'information':
			case 'nofiles':
			case 'skipped':
				if ($settings->get('log.log_type', 'all') == 'all'
					|| $settings->get('log.log_type') == 'notice'
					|| $settings->get('log.log_type') == 'failure_notice') $this->_addMessage($type, $message);
				break;
			case 'nosupport':
				$this->stats['nosupport'] = true;
				break;
		}
	}

	/**
	 * Add a message to the statistics stack
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$type		The type to add
	 * @param		string	$message	The message to add
	 * @return 		void
	 * @since 		3.0
	 */
	private function _addMessage($type, $message) {
		// Set the result
		$success = array('updated', 'deleted', 'added', 'empty');
		$failure = array('incorrect', 'nosupport');
		$notice = array('information', 'nofiles','skipped');
		if (in_array($type, $success)) $result = JText::_('COM_CSVI_SUCCESS');
		else if (in_array($type, $failure)) $result = JText::_('COM_CSVI_FAILURE');
		else if (in_array($type, $notice)) $result = JText::_('COM_CSVI_NOTICE');
		else $result = '';

		if (!isset($this->stats[$this->_linenumber]['status'][$type])) {
			$this->stats[$this->_linenumber]['status'][$type]['message'] = $message."<br />\n";
		}
		else {
			$this->stats[$this->_linenumber]['status'][$type]['message'] .= $message."<br />\n";
		}
		$this->stats[$this->_linenumber]['status'][$type]['result'] = $result;
	}

	/**
	* Retrieves the log message
	* @return string returns the log message
	 */
	function GetLogMessage() {
		return $this->logmessage;
	}

	/**
	* Retrieves the debug message
	* @return string returns the debug message
	 */
	function GetDebugMessage() {
		return $this->debug_message;
	}

	/**
	* Retrieves the line number
	* @return string returns the debug message
	 */
	function GetLineNumber() {
		return $this->_linenumber;
	}

	/**
	* Retrieves the statistics array
	* @return array returns the statistics array
	*/
	public function getStats() {
		return $this->stats;
	}

	/**
	* Retrieves the statistics array
	* @return array returns the statistics array
	*/
	public function cleanStats() {
		$runstats['action'] = $this->stats['action'];
		$runstats['action_type'] = $this->stats['action_type'];
		$runstats['action_template'] = $this->stats['action_template'];
		$this->stats = array();
		$this->stats = $runstats;
	}

	/**
	* Set the type of action the log is for
	 */
	public function SetAction($action) {
		$this->stats['action'] = strtolower($action);
	}

	/**
	* Set the type of action the log is for
	 */
	public function SetActionType($action, $template_name='') {
		$this->stats['action_type'] = strtolower($action);
		$this->stats['action_template'] = $template_name;
	}
}
?>
