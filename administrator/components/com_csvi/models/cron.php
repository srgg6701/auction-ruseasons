<?php
/**
 * Cron model
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: cron.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Cron Model
 *
* @package CSVI
 */
class CsviModelCron extends JModel {

	/**
	 * Build the command to use for the cron command to do an import/export
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	the parameters for the cron line
	 * @since 		3.0
	 */
	public function getCronLine() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$settings = $jinput->get('com_csvi.data', array(), 'array');
		$cronline = '';
		$notemplate = false;
		$details = new StdClass;

		// Get the template used
		$template_id = $jinput->get('template_id', 0, 'int');
		if ($template_id) {
			$cronline .= ' template_id="'.$template_id.'"';

			// Load the template settings to compare against selection
			$query = $db->getQuery(true);
			$query->select('settings');
			$query->from('#__csvi_template_settings');
			$query->where('id = '.$template_id);
			$db->setQuery($query);
			$template = new CsviTemplate(json_decode($db->loadResult(), true));
			$details->type = $template->get('action', 'options');
		}
		else {
			$notemplate = true;
			// Initialise the details
			$details->type = $settings['options']['action'];
		}
		
		// Check if this is an import or export cron
		if ($details->type == 'export') {
			foreach ($settings as $group => $values) {
				switch($group) {
					case 'options':
						break;
					case 'general':
						if ($notemplate) $general = $settings['general'];
						else $general = CsviHelper::recurseArrayDiff($settings['general'], $template->get('general'));
						foreach ($general as $name => $setting) {
							switch ($name) {
								case 'exportto':
									if (!empty($setting)) {
										if ($setting == 'todownload') $setting = 'tofile';
										$cronline .= ' jform:general:'.$name.'="'.$setting.'" ';
									}
									break;
								case 'localpath':
									if (!empty($setting)) {
										if ($template->get('exportto', 'general') == 'todownload' || $template->get('exportto', 'general') == 'tofile') {
											$cronline .= ' jform:general:'.$name.'="'.$setting.'" ';
										}
									}
									break;
								default:
									if (!empty($setting)) $cronline .= ' jform:general:'.$name.'="'.$setting.'" ';
								break;
							}
						}
						break;
					case 'export_fields':
						if ($notemplate) {
							if (array_key_exists('export_fields', $settings)) $fields = $settings['export_fields'];
							else $fields = array();
						}
						else $fields = $template->get('export_fields', '', array());
						if (!empty($fields)) {
							$fields['_selected_name'] = CsviHelper::recurseArrayDiff($settings['export_fields']['_selected_name'], $fields['_selected_name']);
							if (!empty($fields['_selected_name'])) {
								$cronline .= ' jform:export_fields:_selected_name = "'.implode('|', $settings['export_fields']['_selected_name']).'|"';
								$cronline .= ' jform:export_fields:_column_header = "'.implode('|', $settings['export_fields']['_column_header']).'|"';
								$cronline .= ' jform:export_fields:_default_value = "'.implode('|', $settings['export_fields']['_default_value']).'|"';
								$cronline .= ' jform:export_fields:_process_field = "'.implode('|', $settings['export_fields']['_process_field']).'|"';
							}
						}
						break;
					default:
						if ($notemplate) $values = $settings[$group];
						else $values = CsviHelper::recurseArrayDiff($settings[$group], $template->get($group));
						$cronline .= $this->_getCronSetting($values, $group);
						break;
				}
			}	
		}
		else if ($details->type == 'import') {
			foreach ($settings as $group => $values) {
				switch($group) {
					case 'options':
						break;
					case 'import_fields':
						if ($notemplate) {
							if (array_key_exists('import_fields', $settings)) $fields = $settings['import_fields'];
							else $fields = array();
						}
						else {
							// Create a default value
							$default = array();
							$default['_selected_name'][] = '';
							$fields = $template->get('import_fields', '', $default);
							$fields['_selected_name'] = CsviHelper::recurseArrayDiff($settings['import_fields']['_selected_name'], $fields['_selected_name']);
						}
						if (!empty($fields)) {
							if (!empty($fields['_selected_name'])) {
								$cronline .= ' jform:import_fields:_selected_name = "'.implode('|', $settings['import_fields']['_selected_name']).'|"';
								$cronline .= ' jform:import_fields:_default_value = "'.implode('|', $settings['import_fields']['_default_value']).'|"';
								$cronline .= ' jform:import_fields:_process_field = "'.implode('|', $settings['import_fields']['_process_field']).'|"';
							}
						}
						break;
					default:
						if ($notemplate) {
							$values = $settings[$group];
							if (!is_array($values)) {
								$values = array();
								$values[$group] = $settings[$group];
							}
						}
						else $values = CsviHelper::recurseArrayDiff($settings[$group], $template->get($group));
						
						$cronline .= $this->_getCronSetting($values, $group);
						break;
				}
			}
		}

		return $cronline;
	}

	/**
	 * Build the command to use for the cron command to do a maintenance task
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string the parameters for the cron line
	 * @since 		3.0
	 */
	public function getCronLineMaintenance() {
		$jinput = JFactory::getApplication()->input;
		$operation = $jinput->get('operation');

		$cronline = 'task="maintenance" operation="'.strtolower($operation).'"';

		// Handle the ICEcat settings
		switch ($operation) {
			case 'icecatindex':
				$cronline .= ' icecatlocation="'.$jinput->get('icecatlocation', '', 'var').'"';
				$cronline .= ' icecat_gzip="'.$jinput->get('icecat_gzip', 1, 'int').'"';
				$icecat_options = $jinput->get('icecat', array(), 'var');
				if (!empty($icecat_options)) {
					$cronline .= ' icecat="'.implode('|', $icecat_options).'"';
				}
				break;
			case 'restoretemplates':
				$cronline = ' restore_file=""';
				break;
		}

		return $cronline;
	}

	/**
	 * Create the cron parameter
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$values	array of values to add to the cronline
	 * @param		string	$type	the name of the export type
	 * @return 		string	the command line
	 * @since		3.0
	 */
	private function _getCronSetting($values, $type) {
		$cronline = '';
		if (is_array($values)) {
			foreach ($values as $name => $setting) {
				switch ($name) {
					case 'custom_table':
						$cronline .= ' jform:'.$type.'="'.$setting.'"';
						break;
					default:
						if (!empty($setting)) {
							if (is_array($setting)) {
								if (!empty($setting[0])) $cronline .= ' jform:'.$type.':'.$name.'="'.implode('|', $setting).'|"';
							}
							else $cronline .= ' jform:'.$type.':'.$name.'="'.$setting.'"';
						}
						break;
				}
			}
		}
		return $cronline;
	}
}
?>