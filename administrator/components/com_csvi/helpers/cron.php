<?php
/**
 * Cron handler
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: cron.php 2298 2013-01-29 11:38:39Z RolandD $
 */

/**
 * Cron handler
 */
// Get the Joomla framework
define( '_JEXEC', 1 );
define( 'DS', '/' );
define('JPATH_BASE', substr(str_ireplace('components/com_csvi/helpers/cron.php', '', str_ireplace('\\', '/', __FILE__)), 0, -1));
define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE.'/components/com_csvi');
define('JPATH_COMPONENT', JPATH_COMPONENT_ADMINISTRATOR);

$parts = explode(DS, JPATH_BASE);
array_pop($parts);
define('JPATH_ROOT',			implode(DS, $parts));
define('JPATH_SITE',			JPATH_ROOT);
define('JPATH_CONFIGURATION',	JPATH_ROOT);
define('JPATH_ADMINISTRATOR',	JPATH_ROOT . '/administrator');
define('JPATH_LIBRARIES',		JPATH_ROOT . '/libraries');
define('JPATH_PLUGINS',			JPATH_ROOT . '/plugins');
define('JPATH_INSTALLATION',	JPATH_ROOT . '/installation');
define('JPATH_THEMES',			JPATH_BASE . '/templates');
define('JPATH_CACHE',			JPATH_BASE . '/cache');
define('JPATH_MANIFESTS',		JPATH_ADMINISTRATOR . '/manifests');

// Require files for the framework
require_once (JPATH_BASE.'/includes/framework.php' );
require_once (JPATH_LIBRARIES.'/joomla/environment/request.php' );
require_once (JPATH_BASE.'/includes/toolbar.php' );
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/helpers/csvi.php');

// Load the cron details
$csvicron = new CsviCron();

// Create the Application
$mainframe = JFactory::getApplication('administrator');
$mainframe->initialise();

// Load the language file
$language = JFactory::getLanguage();
$language->load('com_csvi', JPATH_BASE.'/administrator');

// Load the plugin system
JPluginHelper::importPlugin('system');

// trigger the onAfterInitialise events
$mainframe->triggerEvent('onAfterInitialise');

// Run the cron job
$csvicron->runCron();

/**
 * Handles all cron requests
 *
* @package CSVI
 */
class CsviCron {

	/** @var $basepath string the base of the installation */
	var $basepath = '';

	/** @var $_variables array of user set variables to override template settins */
	var $_variables = '';

	/**
	 * Initialise the cron
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.5
	 */
	public function __construct() {
		// Merge the default translation with the current translation
		$jlang = JFactory::getLanguage();
		$jlang->load('com_csvi', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_csvi', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_csvi', JPATH_ADMINISTRATOR, null, true);

		// Get the domain name
		require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/settings.php');
		$settings = new CsviSettings();
		$domainname = $settings->get('site.hostname', 'www.example.com');
		// Check for the trailing slash at the domain name
		if (substr($domainname, -1) == '/') $domainname = substr($domainname, 0, -1);

		// Load the posted variables
		$this->CollectVariables();

		// Fill the server global with necessary information
		$_SERVER['REQUEST_METHOD'] = 'post';
		$_SERVER['HTTP_HOST'] = $domainname;
		$_SERVER['REMOTE_ADDR'] = gethostbyname('localhost');
		$_SERVER['SERVER_PORT'] = '';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0) Gecko/20100101 Firefox/4.0';
		$_SERVER['REQUEST_URI'] = '/administrator/index.php';
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['PHP_SELF'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';
		if (isset($this->_variables['adminpw'])) {
			$_SERVER['QUERY_STRING'] = $this->_variables['adminpw'];
			$_SERVER['REQUEST_URI'] .= '?'.$this->_variables['adminpw'];
		}
	}

	/**
	 * Initialise some settings
	 */
	public function runCron() {
		// Buffer all output to prevent conflicts with external software
		ob_start();
		// Start the clock
		$starttime = time();
		$db = JFactory::getDbo();

		// First check if we deal with a valid user
		if ($this->Login()) {
			// Set some global values
			$jinput = JFactory::getApplication()->input;
			$jfilter = new JFilterInput();
			// Get the parameters
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/settings.php');
			$settings = new CsviSettings();

			// Check if we are running cron mode and set some necessary variables
			$_SERVER['SERVER_ADDR'] = $_SERVER['HTTP_HOST'] = $settings->get('site.hostname');
			$_SERVER['SCRIPT_NAME'] = '/index.php';
			$_SERVER['REQUEST_URI'] = '/';
			$_SERVER['PHP_SELF'] = '/index.php';

			// Get the task to do
			if (isset($this->_variables['task'])) $task = $jfilter->clean($this->_variables['task']);
			else $task = '';

			// Perform the requested task
			switch ($task) {
				case 'maintenance':
					$jinput->set('task', 'maintenance.'.$this->_variables['operation']);
					// Fire CSVI VirtueMart
					$this->ExecuteJob();
					break;
				default:
					// Second check if any template is set to process
					if (array_key_exists('template_id', $this->_variables)) $template_id = $jfilter->clean($this->_variables['template_id'], 'int');
					else $template_id = false;
					if (array_key_exists('template_name', $this->_variables)) $template_name = $jfilter->clean($this->_variables['template_name']);
					else $template_name = false;

					if ($template_id || $template_name) {
						// There is a template_id or template name, get some details to streamline processing
						$where = empty($template_id) ? 'name='.$db->q($template_name) : 'id='.$template_id;

						// There is a template name, get some details to streamline processing
						$q = "SELECT id AS template_id, name AS template_name, settings
							FROM #__csvi_template_settings
							WHERE ".$where;
						$db->setQuery($q);
						$row = $db->loadObject();

						if (is_object($row)) {
							echo JText::sprintf('COM_CSVI_PROCESSING_STARTED', date('jS F Y, g:i a'))."\n";
							echo JText::sprintf('COM_CSVI_TEMPLATE', $row->template_name)."\n";
							// Set the template ID
							$jinput->set('select_template', $row->template_id);
							$jinput->set('template_id', $row->template_id);
							$jinput->set('template_name', $row->template_name);

							// Set the settings
							if (array_key_exists('jform', $this->_variables)) $settings = CsviHelper::arrayExtend(json_decode($row->settings, true), $this->_variables['jform']);
							else $settings = json_decode($row->settings, true);

							// Set some export settings
							if ($settings['options']['action'] == 'export') {
								// Export settings
								$jinput->set('task', 'exportfile.process');
								// Set export to
								if ($settings['general']['exportto'] == 'todownload') $settings['general']['exportto'] = 'tofile';
							}
							// Set some import settings
							else if ($settings['options']['action'] == 'import') {
								// Import settings
								$jinput->set('task', 'importfile.doimport');
								// Turn off preview
								$settings['general']['show_preview'] = 0;
							}
							// Set a view so VirtueMart is happy
							$jinput->set('view', 'products');

							// Post the settings
							$jinput->set('jform', $settings, 'post');

							// Fire CSVI
							$this->ExecuteJob();
						}
						else {
							if ($template_name) echo JText::sprintf('COM_CSVI_NO_TEMPLATE_FOUND', $template_name)."\n";
							else if ($template_id) echo JText::sprintf('COM_CSVI_NO_TEMPLATE_FOUND', $template_id)."\n";
						}
					}
					else echo JText::_('COM_CSVI_NO_TEMPLATE_SPECIFIED')."\n";
					break;
			}
		}
		else {
			$error = JError::getError();
			echo $error->message."\n";
		}
		echo sprintf(JText::_('COM_CSVI_PROCESSING_FINISHED'), date('jS F Y, g:i a'))."\n";
		$duration = time() - $starttime;
		if ($duration < 60) echo sprintf(JText::_('COM_CSVI_PROCESSING_SECONDS'), $duration)."\n";
		else echo sprintf(JText::_('COM_CSVI_PROCESSING_MINUTES'), (number_format($duration/60, 2)))."\n";
		// Done, lets log the user out
		$this->UserLogout();

		// Display any generated messages
		$messages = ob_get_contents();
		@ob_end_clean();
		echo $messages;
	}

	/**
	 * Collect the variables
	 *
	 * Running from the command line, the variables are stored in $argc and $argv.
	 * Here we put them in $_REQUEST so that they are available to the script
	 */
	private function CollectVariables() {
		$arguments = false;
		// Take the argument values and put them in $_REQUEST
		if (isset($_SERVER['argv'])) {
			foreach ($_SERVER['argv'] as $key => $argument) {
				if ($key > 0) {
					list($name, $value) = explode("=", $argument);
					if (strpos($value, '|')) $value = explode('|', $value);
					if (strpos($name, ':')) {
						$names = explode(':', $name);
						if (count($names) == 3 && $names[0] == 'jform') {
							$this->_variables['jform'][$names[1]][$names[2]] = $value;
						}
					}
					else $this->_variables[$name] = $value;
				}
			}
			$arguments = true;
		}

		// Get the _GET
		if (!empty($_GET)) {
			$this->_storeVariables($_GET);
			$arguments = true;
		}

		// Get the _POST
		if (!empty($_POST)) {
			$this->_storeVariables($_POST);
			$arguments = true;
		}
		if (!$arguments) echo JText::_('COM_CSVI_NO_ARGUMENTS')."\n";
	}

	/**
	 * Store the variables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		$vars	array	the variables to store
	 * @return
	 * @since 		3.2
	 */
	private function _storeVariables($vars) {
		foreach ($vars as $name => $value) {
			if (!empty($value)) {
				if (strpos($value, '|')) $value = explode('|', $value);
				if (substr($name, 0, 5) == 'jform') {
					if (strpos($name, ':')) $names = explode(':', $name);
					else $names = explode('_', $name);
					if (count($names) == 3 && $names[0] == 'jform') {
						$this->_variables['jform'][$names[1]][$names[2]] = $value;
					}
				}
				else $this->_variables[$name] = $value;
			}
		}
	}

	/**
	* Check if the user exists
	*/
	private function Login() {
		$mainframe = JFactory::getApplication();
		$jfilter = new JFilterInput();
		$credentials['username'] = $jfilter->clean($this->_variables['username'], 'username');
		$credentials['password'] = $jfilter->clean($this->_variables['passwd']);

		$result = $mainframe->login($credentials, array('entry_url' => ''));

		if (!JError::isError($result)) {
			return true;
		}
		else return false;
	}

	/**
	* Process the requested job
	*/
	function ExecuteJob() {
		$jinput = JFactory::getApplication()->input;
		$jinput->set('cron', true);
		require(JPATH_COMPONENT_ADMINISTRATOR.'/csvi.php');
	}

	/**
	 * Log the user out
	 */
	private function UserLogout() {
		global $mainframe;
		ob_start();
		$error = $mainframe->logout();

		if(JError::isError($error)) {
			ob_end_clean();
			echo JText::_('COM_CSVI_PROBLEM_LOGOUT_USER')."\n";
		}
		else {
			ob_end_clean();
			echo JText::_('COM_CSVI_USER_LOGGED_OUT')."\n";
		}
	}
}
?>