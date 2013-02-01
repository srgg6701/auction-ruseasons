<?php
/**
 * Export front-end
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: export.php 2275 2013-01-03 21:08:43Z RolandD $
 */
 
defined( '_JEXEC' ) or die;

/**
 * Main processor for front-end export
 */
class CsviModelExport extends JModel {
	
	/**
	 * Prepare for export 
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
	public function getPrepareExport() {
		// Load the basics
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$exportfile_model = $this->_getModel('exportfile');
		
		// Load the backend language file
		$lang = JFactory::getLanguage();
		$lang->load('com_csvi', JPATH_ADMINISTRATOR);
		
		// Load the template
		$template = new CsviTemplate();
		$template->load($jinput->get('template_id', 0, 'int'));
		$template->set('exportto', 'general', $jinput->get('exportto', 'tofront', 'cmd'));
		$jinput->set('template', $template);
		
		// Set the export type
		$jinput->set('export_type', $template->get('operation', 'options'));
		
		// Initiate the log
		$csvilog = new CsviLog();
		
		// Create a new Import ID in the logger
		$csvilog->setId();
		
		// Set to collect debug info
		$csvilog->setDebug($template->get('collect_debug_info', 'general'));
		
		// Set some log info
		$csvilog->SetAction('export');
		$csvilog->SetActionType($template->get('export_type'), $template->getName());
		
		// Add the logger to the registry
		$jinput->set('csvilog', $csvilog);
		
		// Load the fields to export
		$exportfields = $exportfile_model->getExportFields();
		if (!empty($exportfields)) {
			$jinput->set('export.fields', $exportfields);
			
			// Allow big SQL selects
			$db->setQuery("SET OPTION SQL_BIG_SELECTS=1");
			$db->query();
			
			// Get the filename for the export file
			$jinput->set('export.filename', $exportfile_model->exportFilename());
			
			// See if we need to get an XML/HTML class
			$export_format = $template->get('export_file', 'general');
			if ($export_format == 'xml' || $export_format == 'html') {
				$exportclass = $exportfile_model->getExportClass();
				if ($exportclass) $jinput->set('export.class', $exportclass);
				else {
					$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_EXPORT_CLASS'));
					$jinput->set('logcount', 0);
					return false;
				}
			}
			
			// Return all is good
			return true;
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_EXPORT_FIELDS'));
			$jinput->set('logcount', 0);
			return false;
		}
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
}