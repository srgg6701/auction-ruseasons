<?php
/**
 * Import file cron view
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.json.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

/**
 * Import file View
 *
* @package CSVI
 */
class CsviViewImportFile extends JView {

	/**
	 * Import the files
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	JSON encoded text
	 * @since 		3.0
	 */
	public function display($tpl = null) {
		$jinput = JFactory::getApplication()->input;
		if ($jinput->get('importsession', true, 'bool')) {
			// Process the data
			$this->get('ProcessData');

			// Empty the message stack
			$app = JFactory::getApplication();
			$app->set('_messageQueue', array());

			// Collect the results
			$result = array();

			// Set the view mode
			if ($jinput->get('csvipreview', false, 'bool')) {
				$result['view'] = 'preview';
				$result['headers'] = $jinput->get('headers_preview', null, null);
				$result['output'] = $jinput->get('data_preview', null, null);

				if (empty($results['headers']) && empty($result['output'])) {
					$result['process'] = false;
					$csvilog = $jinput->get('csvilog', null, null);
					$result['url'] = JURI::root().'administrator/index.php?option=com_csvi&task=process.finished&run_id='.$csvilog->getId();

					// Clean the session, nothing to import
					$this->get('CleanSession');
				}
				else $result['process'] = true;
			}
			else {
				$result['view'] = '';
				// Get the number of records processed
				$result['records'] = $jinput->get('recordsprocessed', 0, 'int');
				if ($result['records'] == 0) {
					$result['process'] = false;
					$result['url'] = JURI::root().'administrator/index.php?option=com_csvi&task=process.finished&run_id='.$jinput->get('run_id', 0, 'int');
				}
				else {
					$result['process'] = true;
				}
			}
		}
		else {
			$csvilog = $jinput->get('csvilog', null, null);

			// Collect the results
			$result = array();
			$result['process'] = false;
			$result['url'] = JURI::root().'administrator/index.php?option=com_csvi&task=process.finished&run_id='.$jinput->get('run_id', 0, 'int');

			// Clean the session, nothing to import
			$this->get('CleanSession');
		}
		
		if ($result['process']) {
			// Import is not finished, lets sleep
			$settings = new CsviSettings();
			sleep($settings->get('import.import_wait', 0));
		}

		// Output the results
		echo json_encode($result);
	}
}
?>