<?php
/**
 * Maintenance model
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: maintenance.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.model' );

/**
 * Maintenance Model
 */
class CsviModelMaintenance extends JModel {

	/** @var string Model context string */
	private $context = 'com_csvi.maintenance';

	/** @var array contains the categories in the system */
	var $_categories = array();

	/** @var array contains a list of levels deep per category */
	var $_catlevels = array();

	/** @var array contains a list of subcategories per category */
	var $_catpaths = array();

	/**
	 * Prepare maintenance
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function getPrepareMaintenance() {
		$jinput = JFactory::getApplication()->input;
		// Start the log
		$csvilog = new CsviLog();
		$import_id = $csvilog->setId();
		$csvilog->SetAction('Maintenance');
		$csvilog->SetActionType($jinput->get('task').'_LABEL');
		$jinput->set('import_id', $import_id);
		$jinput->set('csvilog', $csvilog);
	}

	/**
	 * Finish up maintenance
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function getFinishProcess() {
		// Load the session data
		$jinput = JFactory::getApplication()->input;
		// See if we have the csvilog
		$csvilog = $jinput->get('csvilog', null, null);

		// If not, then check the session
		if (empty($csvilog)) {
			$session = JFactory::getSession();
			$option = $jinput->get('option');
			$csvilog = unserialize($session->get($option.'.csvilog'));
			$jinput->set('csvilog', $csvilog);
		}

		// Store the log
		$model_log = $this->getModel('log');
		$model_log->getStoreLogResults();
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
	 * Empty VirtueMart tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Write out product type tables that get deleted
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getEmptyDatabase() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$linenumber = 1;
		jimport('joomla.language.helper');
		$languages = array_keys(JLanguageHelper::getLanguages('lang_code'));
		$tables = $db->getTableList();

		// Empty all the necessary tables
		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_products`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_TABLE_HAS_NOT_BEEN_EMPTIED'));

		foreach ($languages as $language) {
			$table = $db->getPrefix().'virtuemart_products_'.strtolower(str_replace('-', '_', $language));
			if (in_array($table, $tables)) {
				$q = "TRUNCATE TABLE ".$db->quoteName($table).";";
				$db->setQuery($q);
				$csvilog->addDebug('Empty product language table', true);
				if ($db->query()) $csvilog->AddStats('empty', JText::sprintf('COM_CSVI_PRODUCT_LANGUAGE_TABLE_HAS_BEEN_EMPTIED', $language));
				else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_PRODUCT_LANGUAGE_TABLE_HAS_NOT_BEEN_EMPTIED', $language));
			}
		}

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_categories`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product category link table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_CATEGORY_LINK_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_CATEGORY_LINK_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_customfields`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product custom fields table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_CUSTOMFIELDS_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_CUSTOMFIELDS_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_downloads`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product downloads table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_DOWNLOADS_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_DOWNLOADS_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_manufacturers`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product manufacturer link table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_MANUFACTURER_LINK_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_MANUFACTURER_LINK_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_medias`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product medias table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_MEDIAS_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_MEDIAS_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_prices`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product price table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_PRICE_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_PRICE_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_relations`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product relations table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_RELATIONS_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_RELATIONS_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_product_shoppergroups`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty product shoppergroups table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_PRODUCT_SHOPPERGROUPS_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_SHOPPERGROUPS_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_categories`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty category table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_CATEGORY_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_CATEGORY_TABLE_HAS_NOT_BEEN_EMPTIED'));

		foreach ($languages as $language) {
			$table = $db->getPrefix().'virtuemart_categories_'.strtolower(str_replace('-', '_', $language));
			if (in_array($table, $tables)) {
				$q = "TRUNCATE TABLE ".$db->quoteName($table).";";
				$db->setQuery($q);
				$csvilog->addDebug('Empty category language table', true);
				if ($db->query()) $csvilog->AddStats('empty', JText::sprintf('COM_CSVI_CATEGORY_LANGUAGE_TABLE_HAS_BEEN_EMPTIED', $language));
				else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CATEGORY_LANGUAGE_TABLE_HAS_NOT_BEEN_EMPTIED', $language));
			}
		}

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_category_categories`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty category link table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_CATEGORY_LINK_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_CATEGORY_LINK_TABLE_HAS_NOT_BEEN_EMPTIED'));

		$csvilog->setLinenumber($linenumber++);
		$q = "TRUNCATE TABLE `#__virtuemart_category_medias`;";
		$db->setQuery($q);
		$csvilog->addDebug('Empty category medias table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_CATEGORY_MEDIAS_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_CATEGORY_MEDIAS_TABLE_HAS_NOT_BEEN_EMPTIED'));

		// Get the lowest manufacturer ID
		$q = "SELECT MIN(virtuemart_manufacturer_id) AS mf_id FROM ".$db->quoteName('#__virtuemart_manufacturers');
		$db->setQuery($q);
		$mf_id = $db->loadResult();

		// Delete anything higher than the lowest ID
		$csvilog->setLinenumber($linenumber++);
		$q = "DELETE FROM `#__virtuemart_manufacturers` WHERE virtuemart_manufacturer_id > ".$mf_id;
		$db->setQuery($q);
		$csvilog->addDebug('Empty manufacturer table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('COM_CSVI_MANUFACTURER_TABLE_HAS_BEEN_EMPTIED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_MANUFACTURER_TABLE_HAS_NOT_BEEN_EMPTIED'));

		// Optimize the table
		$csvilog->setLinenumber($linenumber++);
		$q =  "OPTIMIZE TABLE ".$db->quoteName('#__virtuemart_manufacturers');
		$db->setQuery($q);
		if ($db->query()) $csvilog->AddStats('information', JText::_('COM_CSVI_MANUFACTURER_TABLE_HAS_BEEN_OPTIMIZED'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_MANUFACTURER_TABLE_HAS_NOT_BEEN_OPTIMIZED'));
		// Reset the auto increment
		$csvilog->setLinenumber($linenumber++);
		$q = "ALTER TABLE ".$db->quoteName('#__virtuemart_manufacturers')." AUTO_INCREMENT = ".($mf_id+1);
		if ($db->query()) $csvilog->AddStats('information', JText::_('COM_CSVI_MANUFACTURER_AUTO_INCREMENT_RESET'));
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_MANUFACTURER_AUTO_INCREMENT_NOT_RESET'));

		// Store the log count
		$linenumber--;
		$jinput->set('logcount', $linenumber);
		return true;
	}

	/**
	 * Optimize CSVI VirtueMart and VirtueMart tables
	 *
	 * @todo clean up messages
	 */
	public function getOptimizeTables() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);
		$linenumber = 1;
		$tables = $db->getTableList();

		foreach ($tables as $id => $tablename) {
			$csvilog->setLinenumber($linenumber++);
			$q =  "OPTIMIZE TABLE ".$tablename;
			$db->setQuery($q);
			if ($db->query()) $csvilog->AddStats('information', JText::sprintf('COM_CSVI_TABLE_HAS_BEEN_OPTIMIZED', $tablename));
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_TABLE_HAS_NOT_BEEN_OPTIMIZED', $tablename));
		}
		// Store the log count
		$linenumber--;
		$jinput->set('logcount', $linenumber);
		return true;
	}

	/**
	 * Add exchange rates
	 * The eurofxref-daily.xml file is updated daily between 14:15 and 15:00 CET
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
	public function getExchangeRates() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$linenumber = 1;
		// Read eurofxref-daily.xml file in memory
		$XMLContent= file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");

		// Process the file
		if ($XMLContent) {
			// Empty table
			$q = "TRUNCATE TABLE `#__csvi_currency`;";
			$db->setQuery($q);
			$db->query();

			// Add the Euro
			$q = "INSERT INTO #__csvi_currency (currency_code, currency_rate)
				VALUES ('EUR', 1)";
			$db->setQuery($q);
			$db->query();

			$currencyCode = array();
			$rate = array();
			foreach ($XMLContent as $line) {
				if (preg_match("/currency='([[:alpha:]]+)'/",$line,$currencyCode)) {
					if (preg_match("/rate='([[:graph:]]+)'/",$line,$rate)) {
						$csvilog->setLinenumber($linenumber++);
						$q = "INSERT INTO #__csvi_currency (currency_code, currency_rate)
							VALUES (".$db->Quote($currencyCode[1]).", ".$rate[1].")";
						$db->setQuery($q);
						if ($db->query()) {
							$rate_name = 'COM_CSVI_EXCHANGE_RATE_'.$currencyCode[1].'_ADDED';
							$csvilog->AddStats('added', JText::_($rate_name));
						}
						else $csvilog->AddStats('incorrect', JText::_($rate_name));
					}
				}
			}
		}
		else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_CANNOT_LOAD_EXCHANGERATE_FILE'));

		// Store the log count
		$linenumber--;
		$jinput->set('logcount', $linenumber);
	}

	/**
	 * Remove all categories that have no products
	 * Parent categories are only deleted if there are no more children left
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
	public function getRemoveEmptyCategories() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$this->getCategoryTreeModule();
		$catpaths = $jinput->get('categorypaths', array(), 'array');
		arsort($this->_catlevels);
		foreach ($this->_catlevels as $catid => $nrlevels) {
			// Check if there are any products in the category
			$db->setQuery($this->getCatQuery($catid));
			if ($db->loadResult() > 0 && array_key_exists($catid, $catpaths)) {
				foreach ($catpaths[$catid] as $key => $level) {
					unset($catpaths[$level]);
					unset($this->_catlevels[$level]);
				}
				unset($catpaths[$catid]);
				unset($this->_catlevels[$catid]);
			}
			else {
				if (array_key_exists($catid, $catpaths)) {
					foreach ($catpaths[$catid] as $key => $level) {
						$db->setQuery($this->getCatQuery($level));
						if ($db->loadResult() > 0) {
							unset($catpaths[$level]);
							unset($this->_catlevels[$level]);
						}
					}
				}
			}
		}
		$delcats = array_keys($catpaths);
		if (!empty($delcats)) {
			// Remove all categories except the ones we have
			$q = "DELETE FROM #__vm_category
				WHERE category_id IN (".implode(', ', $delcats).")";
			$db->setQuery($q);
			if ($db->query()) $csvilog->AddStats('deleted', JText::_('COM_CSVI_MAINTENANCE_CATEGORIES_DELETED'));
			else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_MAINTENANCE_CATEGORIES_NOT_DELETED'));

			// Remove all category parent-child relations except the ones we have
			$q = "DELETE FROM #__vm_category_xref
				WHERE category_child_id IN (".implode(', ', $delcats).")";
			$db->setQuery($q);
			if ($db->query()) $csvilog->AddStats('deleted', JText::_('COM_CSVI_MAINTENANCE_CATEGORIES_XREF_DELETED'));
			else $csvilog->AddStats('incorrect', JText::_('COM_CSVI_MAINTENANCE_CATEGORIES_XREF_NOT_DELETED'));
		}
		else $csvilog->AddStats('information', JText::_('COM_CSVI_NO_CATEGORIES_FOUND'));
	}

	/**
	 * Construct a query to count the number of references to a category
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		string	the query to count entries in a category
	 * @since 		3.0
	 */
	private function getCatQuery($catid) {
		return "SELECT COUNT(*)
			FROM #__vm_product_category_xref
			WHERE category_id = ".$catid;

	}

	/**
	 * Clean the CSVI cache
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
	public function getCleanTemp() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$folder = CSVIPATH_TMP;

		if (JFolder::exists($folder)) {
			// Delete all import files left behind in the folder
			JFile::delete(JFolder::files($folder, '.', false, true));

			// Delete all import folders left behind in the folder
			$folders = array();
			$folders = JFolder::folders($folder, '.', true, true, array('debug'));
			if (!empty($folders)) {
				foreach ($folders as $path) {
					JFolder::delete($path);
				}
			}

			// Load the files
			if (JFolder::exists(CSVIPATH_DEBUG)) {
				$files = JFolder::files(CSVIPATH_DEBUG, '.', false, true);
				if ($files) {
					// Remove any debug logs that are still there but not in the database
					$q = "SELECT CONCAT(".$db->Quote(CSVIPATH_DEBUG.'/com_csvi.log.').", import_id, '.php') AS filename
						FROM #__csvi_logs
						WHERE import_id > 0
						GROUP BY import_id";
					$db->setQuery($q);
					$ids = $db->loadResultArray();
					if (!is_array($ids)) $ids = (array)$ids;

					// Delete all obsolete files
					JFile::delete(array_diff($files, $ids));
				}
			}

			$csvilog->AddStats('deleted', JText::_('COM_CSVI_TEMP_CLEANED'));
		}
		else $csvilog->AddStats('information', JText::_('COM_CSVI_TEMP_PATH_NOT_FOUND'));
	}

	/**
	 * Export all VirtueMart tables
	 *
	 * @copyright
	 * @author  	RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getBackupVirtueMart() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$filepath = JPATH_SITE.'/tmp/com_csvi';
		$filename = 'virtuemart_'.time().'.sql';
		$file = $filepath.'/'.$filename;
		$sqlstring = '';
		$fp = fopen($file, "w+");
		if ($fp) {
			// Load a list of VirtueMart tables
			$q = "SHOW TABLES LIKE '".$db->getPrefix()."virtuemart\_%'";
			$db->setQuery($q);
			$tables = $db->loadResultArray();
			$linenumber = 1;
			foreach ($tables as $table) {
				$csvilog->setLinenumber($linenumber);
				// Get the create table statement
				$q = "SHOW CREATE TABLE ".$table;
				$db->setQuery($q);
				$tcreate = $db->loadAssocList();
				$sqlstring .= "-- Table structure for table ".$db->quoteName($table)."\n\n";
				$sqlstring .= $tcreate[0]['Create Table'].";\n\n";

				// Check if there is any data in the table
				$q = "SELECT COUNT(*) FROM ".$db->quoteName($table);
				$db->setQuery($q);
				$count = $db->loadResult();

				if ($count > 0) {
					$sqlstring .= "-- Data for table ".$db->quoteName($table)."\n\n";
					// Get the field names
					$q = "SHOW COLUMNS FROM ".$db->quoteName($table);
					$db->setQuery($q);
					$fields = $db->loadObjectList();
					$sqlstring .= 'INSERT INTO '.$db->quoteName($table).' (';
					foreach ($fields as $field) {
						$sqlstring .= $db->quoteName($field->Field).',';
					}

					$sqlstring = substr(trim($sqlstring), 0, -1).") VALUES \n";
					$start = 0;
					while ($count > 0) {
						$q = "SELECT * FROM ".$table." LIMIT ".$start.", 50";
						$db->setQuery($q);
						$records = $db->loadAssocList();

						// Add the values
						foreach ($records as $record) {
							foreach ($record as $rkey => $value) {
								if (!is_numeric($value)) $record[$rkey] = $db->Quote($value);
								else $record[$rkey] = $value;
							}
							$sqlstring .= '('.implode(',', $record)."),\n";
						}
						$start += 50;
						$count -= 50;

						// Fix the end of the query
						if ($count < 1) $sqlstring = substr(trim($sqlstring), 0, -1).";\n";

						// Add a linebreak
						$sqlstring .= "\n\n";

						// Write the data to the file
						fwrite($fp, $sqlstring);

						// Empty the string
						$sqlstring = '';
					}
					// Update the log
					$csvilog->AddStats('added', JText::sprintf('COM_CSVI_BACKUP_COMPLETE_FOR', $table));
					$linenumber++;
				}
			}

			// Store the log count
			$linenumber--;
			$jinput->set('logcount', $linenumber);

			// Zip up the file
			jimport('joomla.filesystem.archive');
			$zip = JArchive::getAdapter('zip');
			$files = array();
			$files[] = array('name' => $filename, 'time' => filemtime($file), 'data' => JFile::read($file));
			if ($zip->create($filepath.'/'.$filename.'.zip', $files)) {

				// Close the file
				fclose($fp);

				// Remove the SQL file
				JFile::delete($file);

				// Add a download link for the backup
				$csvilog->setFilename(JHTML::link(JURI::root().'tmp/com_csvi/'.$filename.'.zip', JText::_('COM_CSVI_BACKUP_DOWNLOAD_LINK')));
			}
			else {
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_BACKUP_NO_ZIP_CREATE'));
				$csvilog->setFilename($filepath.'/'.$filename);
			}
		}
		else {
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_COULD_NOT_OPEN_FILE', $file));
		}
	}

	/**
	 * This function is repsonsible for returning an array containing category information
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		2.3.6
	 */
	private function getCategoryTreeModule() {
		$database = JFactory::getDBO();

		// Get all categories
		$query  = "SELECT category_child_id AS cid, category_parent_id AS pid
			FROM #__vm_category, #__vm_category_xref
			WHERE #__vm_category.category_id=#__vm_category_xref.category_child_id";

		// Execute the query
		$database->setQuery( $query );
		$records = $database->loadObjectList();

		// Check if there are any records
		if (count($records[0]) == 0) {
			$this->_categories = false;
			return false;
		}
		else {
			$this->_categories = array();
			// Group all categories together according to their level
			foreach( $records as $id => $record ) {
				$this->_categories[$record->pid][$record->cid]["category_id"] = $record->pid;
				$this->_categories[$record->pid][$record->cid]["category_child_id"] = $record->cid;
			}
		}
		$this->CategoryPaths();
	}

	/**
	 * Create an array of subcategories per category
	 *
	 * @author RolandD
	 * @since 2.3.6
	 * @access private
	 */
	private function CategoryPaths() {
		$catpath = array();
		krsort($this->_categories);
		foreach ($this->_categories as $pid => $categories) {
			foreach ($categories as $cid => $category) {
				$catpath[$cid] = $pid;
			}
		}
		foreach ($catpath as $cid => $value) {
			$catlevel = $value;
			$this->_catpaths[$cid][] = $catlevel;
			while ($catlevel > 0) {
				$this->_catpaths[$cid][] = $catpath[$catlevel];
				$catlevel = $catpath[$catlevel];
			}
		}

		foreach ($this->_catpaths as $cid => $paths) {
			$this->_catlevels[$cid] = count($paths);
		}
		asort($this->_catlevels);
		$jinput->set('categorypaths', $this->_catpaths);
	}

	/**
	 * Load the ICEcat index file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool	true if index file is loaded | false if index file is not loaded
	 * @since 		3.0
	 */
	public function getIcecatIndex() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);
		$settings = $jinput->get('settings', null, null);
		$username = $settings->get('icecat.ice_username', false);
		$password = $settings->get('icecat.ice_password', false);
		$loadremote_index = false;
		$loadremote_supplier = false;
		$icecat_options = $jinput->get('icecat', array(), null);

		if (in_array('icecat_index', $icecat_options)) $load_index = true;
		else $load_index = false;
		if (in_array('icecat_supplier', $icecat_options)) $load_supplier = true;
		else $load_supplier = false;
		// Should we load the index file in 1 go
		$loadtype = JRequest::getBool('loadtype');
		// What to do next?
		$result = 'full';

		// Check if we have a username and password
		if ($username && $password) {

			// Joomla includes
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.archive');

			// Check if the files are stored on the server
			$location = JRequest::getVar('icecatlocation');
			if ($load_index) {
				if (JFile::exists($location.'/icecat_index')) $icecat_index_file = $location.'/icecat_index';
				else if (JFile::exists($location.'/icecat_index.gzip')) $icecat_index_file = $location.'/icecat_index.gzip';
				else if (JFile::exists($location.'/icecat_index.zip')) $icecat_index_file = $location.'/icecat_index.zip';
				else $loadremote_index = true;
			}

			if ($load_supplier) {
				if (JFile::exists($location.'/icecat_supplier')) $icecat_supplier_file = $location.'/icecat_supplier';
				else if (JFile::exists($location.'/icecat_supplier.gzip')) $icecat_supplier_file = $location.'/icecat_supplier.gzip';
				else if (JFile::exists($location.'/icecat_supplier.zip')) $icecat_supplier_file = $location.'/icecat_supplier.zip';
				else $loadremote_supplier = true;
			}

			// Load the remote files if needed
			if ($loadremote_index || $loadremote_supplier) {
				// Context for retrieving files
				if (JRequest::getBool('icecat_gzip')) $gzip = "Accept-Encoding: gzip\r\n";
				else $gzip = '';
				$context = stream_context_create(array(
					'http' => array(
						'header'  => "Authorization: Basic " . base64_encode($username.':'.$password)."\r\n".
				$gzip
				)
				));

				if ($load_index && $loadremote_index) {
					// ICEcat index file
					$icecat_url = $settings->get('icecat.ice_index', 'http://data.icecat.biz/export/freexml.int/INT/files.index.csv');

					// Load the index file from the ICEcat server to a local file
					$icecat_index_file = CSVIPATH_TMP.'/icecat_index';
					if (JRequest::getBool('icecat_gzip')) $icecat_index_file .= '.gzip';
					$fp_url = fopen($icecat_url, 'r', false, $context);
					$fp_local = fopen($icecat_index_file, 'w+');
					while($content = fread($fp_url,1024536)){
						fwrite($fp_local, $content);
					}
					fclose($fp_url);
					fclose($fp_local);
				}

				if ($load_supplier && $loadremote_supplier) {
					// Load the manufacturer data
					$icecat_mf = $settings->get('icecat.ice_supplier', 'http://data.icecat.biz/export/freexml.int/INT/supplier_mapping.xml');

					// Load the index file from the ICEcat server to a local file
					$icecat_supplier_file = CSVIPATH_TMP.'/icecat_supplier';
					if (JRequest::getBool('icecat_gzip')) $icecat_supplier_file .= '.gzip';
					$fp_url = fopen($icecat_mf, 'r', false, $context);
					$fp_local = fopen($icecat_supplier_file, 'w+');
					while($content = fread($fp_url,1024536)){
						fwrite($fp_local, $content);
					}
					fclose($fp_url);
					fclose($fp_local);
				}
			}

			// Check if we need to unpack the files
			if ($load_index) {
				if (substr($icecat_index_file, -3) == 'zip') {
					if (!$this->_unpack($icecat_index_file, CSVIPATH_TMP)) {
						$csvilog->AddStats('incorrect', JText::_('COM_CSVI_ICECAT_INDEX_NOT_UNPACKED'));
						return 'cancel';
					}
					else $icecat_index_file = CSVIPATH_TMP.'/icecat_index';
				}
			}
			if ($load_supplier) {
				if (substr($icecat_supplier_file, -3) == 'zip') {
					if (!$this->_unpack($icecat_supplier_file, CSVIPATH_TMP)) {
						$csvilog->AddStats('incorrect', JText::_('COM_CSVI_ICECAT_SUPPLIER_NOT_UNPACKED'));
						return 'cancel';
					}
					else $icecat_supplier_file = CSVIPATH_TMP.'/icecat_supplier';
				}
			}

			if ($load_index) {
				// Empty the index table
				$q = "TRUNCATE TABLE ".$db->quoteName('#__csvi_icecat_index');
				$db->setQuery($q);
				$db->query();

				// Load the local file into the database
				if (!$loadtype) {
					$q = "LOAD DATA LOCAL INFILE ".$db->Quote($icecat_index_file)."
						INTO TABLE ".$db->quoteName('#__csvi_icecat_index')."
						IGNORE 1 LINES";
					$db->setQuery($q);
					if ($db->query()) $csvilog->AddStats('added', JText::_('COM_CSVI_ICECAT_INDEX_LOADED'));
					else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_ICECAT_INDEX_NOT_LOADED', $db->getErrorMsg()));
				}
				else {
					// Need to redirect for the batch import
					$result = 'single';
				}
			}

			if ($load_supplier) {
				// Empty the supplier table
				$q = "TRUNCATE TABLE ".$db->quoteName('#__csvi_icecat_suppliers');
				$db->setQuery($q);
				$db->query();

				// Reset the supplier file
				$xmlstr = file_get_contents($icecat_supplier_file);
				$xml = new SimpleXMLElement($xmlstr);
				$supplier_data = array();
				foreach ($xml->SupplierMappings->children() as $key => $mapping) {
					foreach ($mapping->attributes() as $attr_name => $attr_value) {
						switch($attr_name) {
							case 'supplier_id':
								$supplier_id = $attr_value;
								break;
							case 'name':
								$supplier_data[] = '('.$db->Quote($supplier_id).','.$db->Quote($attr_value).')';
						}
					}
					foreach ($mapping->children() as $symbol) {
						$supplier_data[] = '('.$db->Quote($supplier_id).','.$db->Quote($symbol).')';
					}
				}

				$q = "INSERT IGNORE INTO ".$db->quoteName('#__csvi_icecat_suppliers')."
					VALUES ".implode(',', $supplier_data);
				$db->setQuery($q);
				if ($db->query()) $csvilog->AddStats('added', JText::_('COM_CSVI_ICECAT_SUPPLIERS_LOADED'));
				else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_ICECAT_SUPPLIERS_NOT_LOADED', $db->getErrorMsg()));
			}
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_ICECAT_NO_USER_PASS'));
		}

		// See if we need to store some info
		if ($loadtype) {
			// Session init
			$session = JFactory::getSession();
			$option = JRequest::getVar('option');
			$session->set($option.'.csvilog', serialize($csvilog));
			$session->set($option.'.icecat_index_file', serialize($icecat_index_file));
			$session->set($option.'.icecat_records', serialize(JRequest::getInt('icecat_records')));
			$session->set($option.'.icecat_wait', serialize(JRequest::getInt('icecat_wait')));
		}
		return $result;
	}

	/**
	 * Load the ICEcat index in batches
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	public function getIcecatSingle() {
		$jinput = JFactory::getApplication()->input;
		// Session init
		$session = JFactory::getSession();
		$option = $jinput->get('option');
		$csvilog = unserialize($session->get($option.'.csvilog'));
		$icecat_index_file = unserialize($session->get($option.'.icecat_index_file'));
		$totalrow = unserialize($session->get($option.'.icecat_rows'));
		$records = unserialize($session->get($option.'.icecat_records', 1000));
		$finished = false;
		$continue = true;

		// Sleep to please the server
		sleep(unserialize($session->get($option.'.icecat_wait', 5)));

		// Load the records line by line
		$db = JFactory::getDBO();

		$q = "INSERT INTO `#__csvi_icecat_index` (`path`, `product_id`, `updated`, `quality`, `supplier_id`, `prod_id`, `catid`, `m_prod_id`, `ean_upc`, `on_market`, `country_market`, `model_name`, `product_view`, `high_pic`, `high_pic_size`, `high_pic_width`, `high_pic_height`, `m_supplier_id`, `m_supplier_name`) VALUES ";
		$lines = '';
		if (($handle = fopen($icecat_index_file, "r")) !== FALSE) {
			// Position pointers
			$row = 0;

			// Position file pointer
			$pointer = unserialize($session->get($option.'.icecat_position'));
			fseek($handle, $pointer);

			// Start processing
			while ($continue) {
				if ($row < $records) {
					$data = fgetcsv($handle, 1024, "\t");
					if ($data) {
						$row++;
						$lines .= '(';
						foreach ($data as $item) {
							$lines .= $db->Quote($item).',';
						}
						$lines = substr($lines, 0, -1);
						$lines .= '),';
					}
					else {
						$finished = true;
						$continue = false;
					}
				}
				else $continue = false;
			}
			// Store the data
			$lines = substr($lines, 0, -1);
			$db->setQuery($q.$lines);
			$db->query();

			// Information for reload
			$jinput->set('finished', $finished);
			$sumrows = $totalrow+$row;
			$jinput->set('linesprocessed', $sumrows);

			// Store for future use
			if (!$finished) {
				$session->set($option.'.csvilog', serialize($csvilog));
				$session->set($option.'.icecat_rows', serialize($sumrows));
				$session->set($option.'.icecat_position', serialize(ftell($handle)));
			}
			else {
				$csvilog->AddStats('added', JText::_('COM_CSVI_ICECAT_INDEX_LOADED'));

				// Store the log results
				$jinput->set('csvilog', $csvilog);
				$this->getFinishProcess();

				// Clear the session
				$session->set($option.'.icecat_index_file', serialize('0'));
				$session->set($option.'.icecat_rows', serialize('0'));
				$session->set($option.'.icecat_position', serialize('0'));
				$session->set($option.'.icecat_records', serialize('0'));
				$session->set($option.'.icecat_wait', serialize('0'));
				$session->set($option.'.csvilog', serialize('0'));

				// Set the run ID
				$jinput->set('run_id', $csvilog->getId());
			}

			fclose($handle);
		}
	}

	/**
	 * Unpack the ICEcat index files
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$archivename	the full path and name of the file to extract
	 * @param		string	$extractdir		the folder to copy the extracted file to
	 * @return 		bool	true on success | false on failure
	 * @since 		3.0
	 */
	private function _unpack($archivename, $extractdir) {
		$adapter = JArchive::getAdapter('gzip');
		if ($adapter)
		{
			$config = JFactory::getConfig();
			$tmpfname = $config->getValue('config.tmp_path').'/'.uniqid('gzip');
			$gzresult = $adapter->extract($archivename, $tmpfname);
			if (JError::isError($gzresult))
			{
				@unlink($tmpfname);
				return false;
			}

			$path = JPath::clean($extractdir);
			JFolder::create($path);
			$result = JFile::copy($tmpfname,$path.'/'.JFile::stripExt(basename(strtolower($archivename))));
			@unlink($tmpfname);
		}
		return true;
	}

	/**
	 * Backup templates
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
	public function getBackupTemplates() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$linenumber = 1;
		// Create the backup file
		$filepath = JPATH_SITE.$jinput->get('backup_location', '/tmp/com_csvi', 'string');
		$filename = 'csvi_templates_'.date('Ymd', time()).'.csv';
		$file = JPath::clean($filepath.'/'.$filename, '/');

		$fp = fopen($file, "w+");
		if ($fp) {
			$db->setQuery("SELECT ".$db->quoteName('name').", ".$db->quoteName('settings')." FROM #__csvi_template_settings");
			$templates = $db->loadAssocList();
			foreach ($templates as $template) {
				$csvilog->setLinenumber($linenumber++);
				if (fputcsv($fp, $template)) $csvilog->AddStats('information', JText::sprintf('COM_CSVI_BACKUP_TEMPLATE', $template['name']));
				else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_BACKUP_NO_TEMPLATE', $template['name']));
			}
			fclose($fp);
			$csvilog->AddStats('information', JText::sprintf('COM_CSVI_BACKUP_TEMPLATE_PATH', $file));
		}
		else {
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_COULD_NOT_OPEN_FILE', $file));
		}

		// Store the log count
		$linenumber--;
		$jinput->set('logcount', $linenumber);
	}

	/**
	 * Restore templates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Remove JRequest once jinput can handle files
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getRestoreTemplates() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);
		$linenumber = 1;
		jimport('joomla.filesystem.file');
		// Load the restore file
		$upload = JRequest::getVar('restore_file', '', 'files');
		if (empty($upload) || $upload['error'] > 0) $upload = JRequest::getVar('file', '', 'files');

		// Check if the file upload has an error
		if (empty($upload)) {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_UPLOADED_FILE_PROVIDED'));
			return false;
		}
		else if ($upload['error'] == 0) {
			if (is_uploaded_file($upload['tmp_name'])) {
				// Get some basic info
				$folder = CSVIPATH_TMP.'/'.time();
				$upload_parts = pathinfo($upload['name']);

				// Create the temp folder
				if (JFolder::create($folder)) {
					$this->folder = $folder;
					// Move the uploaded file to its temp location
					if (JFile::upload($upload['tmp_name'], $folder.'/'.$upload['name'])) {
						// Read the uploaded file
						$fp = fopen($folder.'/'.$upload['name'], "r");
						if ($fp) {
							while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
								$csvilog->setLinenumber($linenumber++);
								$db->setQuery("INSERT IGNORE INTO #__csvi_template_settings (".$db->quoteName('name').", ".$db->quoteName('settings').")
											VALUES (".$db->Quote($data[0]).", ".$db->Quote($data[1]).")");
								if ($db->query()) {
									$csvilog->AddStats('added', JText::sprintf('COM_CSVI_RESTORE_TEMPLATE', $data[0]));
								}
								else {
									$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_NO_RESTORE_TEMPLATE', $data[0]));
									$csvilog->AddStats('incorrect', $db->getQuery());
								}
							}

							fclose($fp);
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

		// Store the log count
		$linenumber--;
		$jinput->set('logcount', $linenumber);
	}

	/**
	 * Unpublish products in unpublished categories
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
	public function getUnpublishProductByCategory() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('p').'.'.$db->quoteName('virtuemart_product_id'));
		$query->from($db->quoteName('#__virtuemart_products').' AS p');
		$query->innerJoin($db->quoteName('#__virtuemart_product_categories').' AS pc ON '.$db->quoteName('p').'.'.$db->quoteName('virtuemart_product_id').' = '.$db->quoteName('pc').'.'.$db->quoteName('virtuemart_product_id'));
		$query->innerJoin($db->quoteName('#__virtuemart_categories').' AS c ON '.$db->quoteName('pc').'.'.$db->quoteName('virtuemart_category_id').' = '.$db->quoteName('c').'.'.$db->quoteName('virtuemart_category_id'));
		$query->where($db->quoteName('p').'.'.$db->quoteName('published').' = '.$db->quote('1'));
		$query->where($db->quoteName('c').'.'.$db->quoteName('published').' = '.$db->quote('0'));
		// Get the IDs to unpublish
		$q = "SELECT #__vm_product.product_id
			FROM #__vm_product
			INNER JOIN #__vm_product_category_xref
			ON #__vm_product.product_id = #__vm_product_category_xref.product_id
			INNER JOIN #__vm_category
			ON #__vm_product_category_xref.category_id = #__vm_category.category_id
			WHERE #__vm_product.product_publish = 'Y'
			AND #__vm_category.category_publish = 'N'";
		$db->setQuery($query);
		$ids = $db->loadResultArray();

		if (!empty($ids)) {
			// Unpublish the IDs
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__virtuemart_products'));
			$query->set($db->quoteName('published').' = '.$db->quote('0'));
			$query->where($db->quoteName('virtuemart_product_id').' IN ('.implode(',', $ids).')');
			$q = "UPDATE #__vm_product SET product_publish = 'N' WHERE product_id IN (".implode(',', $ids).")";
			$db->setQuery($query);
			if ($db->query()) {
				$csvilog->AddStats('updated', JText::sprintf('COM_CSVI_PRODUCTS_UNPUBLISHED', $db->getAffectedRows()));
			}
			else {
				$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_PRODUCTS_NOT_UNPUBLISHED', $db->getErrorMsg()));
			}
		}
		else $csvilog->AddStats('information', JText::_('COM_CSVI_PRODUCTS_NOT_FOUND'));
	}

	/**
	 * Get a list of available components
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
	public function getComponents() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('component AS value, component AS text');
		$query->from($db->quoteName('#__csvi_template_types'));
		$query->leftJoin('#__extensions ON #__csvi_template_types.component = #__extensions.element');
		$query->where('#__extensions.type = '.$db->Quote('component'));
		$query->group('component');
		$db->setQuery($query);
		$components = $db->loadObjectList();
		$options = JHtml::_('select.option', '', JText::_('COM_CSVI_MAKE_CHOICE'), 'value', 'text', true);
		array_unshift($components, $options);
		return $components;
	}

	/**
	* Sorts all VirtueMart categories in alphabetical order
	*
	* @copyright
	* @author 		RolandD
	* @todo
	* @see
	* @access 		public
	* @param
	* @return 		bool	true if categories are sorted | false if an error occured
	* @since 		3.0
	*/
	public function getSortCategories() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);
		$language = $jinput->get('language');
		$linenumber = 1;
		// Check if the table exists
		$tables = $db->getTableList();
		if (!in_array($db->getPrefix().'virtuemart_categories_'.$language, $tables)) {
			$csvilog->AddStats('information', JText::sprintf('COM_CSVI_LANG_TABLE_NOT_EXIST', $language));
		}
		else {
			// Get all categories
			$query = $db->getQuery(true);
			$query->select('LOWER('.$db->quoteName('category_name').') AS '.$db->quoteName('category_name'));
			$query->select($db->quoteName('category_child_id').' AS '.$db->quoteName('cid'));
			$query->select($db->quoteName('category_parent_id').' AS '.$db->quoteName('pid'));
			$query->from($db->quoteName('#__virtuemart_categories').' AS '.$db->quoteName('c'));
			$query->leftJoin($db->quoteName('#__virtuemart_category_categories').' AS '.$db->quoteName('cc').' ON '.$db->quoteName('c').'.'.$db->quoteName('virtuemart_category_id').' = '.$db->quoteName('cc').'.'.$db->quoteName('category_child_id'));
			$query->leftJoin($db->quoteName('#__virtuemart_categories_'.$language).' AS '.$db->quoteName('cl').' ON '.$db->quoteName('cc').'.'.$db->quoteName('category_child_id').' = '.$db->quoteName('cl').'.'.$db->quoteName('virtuemart_category_id'));

			// Execute the query
			$db->setQuery($query);
			$records = $db->loadObjectList();
			if (count($records) > 0) {
				$categories = array();

				// Group all categories together according to their level
				foreach ($records as $key => $record) {
					$categories[$record->pid][$record->cid] = $record->category_name;
				}

				// Sort the categories and store the item list
				foreach ($categories as $id => $category) {
					asort($category);
					$listorder = 1;
					foreach ($category as $category_id => $category_name) {
						// Store the new sort order
						$query = $db->getQuery(true);
						$query->update($db->quoteName('#__virtuemart_categories'));
						$query->set($db->quoteName('ordering').' = '.$db->quote($listorder));
						$query->where($db->quoteName('virtuemart_category_id').' = '.$db->quote($category_id));
						$db->setQuery($query);
						$db->query();

						// Set the line number
						$csvilog->setLinenumber($linenumber++);
						$csvilog->AddStats('information', JText::sprintf('COM_CSVI_SAVED_CATEGORY', $category_name ,$listorder));
						$listorder++;
					}
				}
				// Store the log count
				$linenumber--;
				$jinput->set('logcount', $linenumber);
			}
			else $csvilog->AddStats('information', 'COM_CSVI_NO_CATEGORIES_FOUND');
		}
		return true;
	}

	/**
	 * Create a list of maintenance options
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of available options
	 * @since 		4.0
	 */
	public function getMaintenanceOptions() {
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_CSVI_MAKE_CHOICE'), 'value', 'text', true);
		return $options;
	}

	/**
	 * Load the languages in the system
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array of available languages
	 * @since 		4.0
	 */
	public function getLanguages() {
		$language = JFactory::getLanguage();
		$known = $language->getKnownLanguages();
		$options = array();
		foreach ($known as $tag => $lang) {
			$options[] = JHtml::_('select.option', str_replace('-', '_', strtolower($lang['tag'])), $lang['name']);
		}

		return $options;
	}

	/**
	 * Get operations for a selected component
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
	public function getOperations() {
		$jinput = JFactory::getApplication()->input;
		$component = $jinput->get('component');
		$options = '';
		switch ($component) {
			case 'com_csvi':
				$options .= '<option value="">'.JText::_('COM_CSVI_MAKE_CHOICE').'</option>';
				$options .= '<option value="updateavailablefields">'.JText::_('COM_CSVI_UPDATEAVAILABLEFIELDS_LABEL').'</option>';
				$options .= '<option value="exchangerates">'.JText::_('COM_CSVI_EXCHANGERATES_LABEL').'</option>';
				$options .= '<option value="cleantemp">'.JText::_('COM_CSVI_CLEANTEMP_LABEL').'</option>';
				$options .= '<option value="backuptemplates">'.JText::_('COM_CSVI_BACKUPTEMPLATES_LABEL').'</option>';
				$options .= '<option value="restoretemplates">'.JText::_('COM_CSVI_RESTORETEMPLATES_LABEL').'</option>';
				$options .= '<option value="optimizetables">'.JText::_('COM_CSVI_OPTIMIZETABLES_LABEL').'</option>';
				break;
			case 'com_virtuemart':
				$options .= '<option value="">'.JText::_('COM_CSVI_MAKE_CHOICE').'</option>';
				$options .= '<option value="sortcategories">'.JText::_('COM_CSVI_SORTCATEGORIES_LABEL').'</option>';
				$options .= '<option value="unpublishproductbycategory">'.JText::_('COM_CSVI_UNPUBLISHPRODUCTBYCATEGORY_LABEL').'</option>';
				$options .= '<option value="backupvm">'.JText::_('COM_CSVI_BACKUPVM_LABEL').'</option>';
				$options .= '<option value="emptydatabase">'.JText::_('COM_CSVI_EMPTYDATABASE_LABEL').'</option>';
				$options .= '<option value="icecatindex">'.JText::_('COM_CSVI_ICECATINDEX_LABEL').'</option>';
				break;
			default:
				$options .= '<option value="">'.JText::_('COM_CSVI_NO_OPTIONS_FOUND').'</option>';
			break;
		}

		// Return the output
		return $options;
	}
}
?>