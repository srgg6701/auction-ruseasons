<?php
/**
 * Install model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: install.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.model' );

/**
 * Install Model
 *
 * @package CSVI
 */
class CsviModelInstall extends JModel {

	/** */
	private $_templates = array();
	private $_tag = '';
	private $_results = array();
	private $_tables = array();

	/**
	 * Find the version installed
	 *
	 * Version 4 is the first version
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Check version from database
	 * @todo		Convert settings from INI format to JSON format
	 * @see
	 * @access 		private
	 * @param
	 * @return 		string	the version determined by the database
	 * @since 		3.0
	 */
	public function getVersion() {
		// Determine the tables in the database
		$version = $this->_getVersion();
		if (empty($version)) $version = 'current';
		return $version;
	}

	/**
	 * Start performing the upgrade
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		string	the result of the upgrade
	 * @since 		3.0
	 */
	public function getUpgrade() {
		// Get the currently installed version
		$version = $this->_translateVersion();

		// Rename the existing tables
		if ($this->_renameTables($version)) {
			// Create the new tables
			if ($this->_createTables()) {
				// Migrate the data in the tables
				if ($this->_migrateTables($version)) $this->_results['messages'][] = JText::_('COM_CSVI_UPGRADE_OK');

				// Update the version number in the database
				$this->_setVersion();

				// Load the components
				$this->_loadComponents();
			}
			else $this->_results['error'][] = '<span class="error">'.JText::_('COM_CSVI_INSTALL_NOK').'</span>';
		}
		else {
			$this->_results['error'][] = '<span class="error">'.JText::_('COM_CSVI_INSTALL_NOK').'</span>';
			$jinput->set('cancelinstall', true);
		}

		// Send the results back
		return $this->_results;
	}

	/**
	 * Rename the existing tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$version	the currently installed version
	 * @return 		bool	true if tables are renamed | false if tables are not renamed
	 * @since 		3.0
	 */
	private function _renameTables($version) {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$this->_tag = str_ireplace('.', '_', $version);
		$ok = true;
		$removeold = $jinput->get('removeoldtables', false, 'bool');
		$random = time();

		// Load the tables to rename
		$tables = $this->_getTables($version);

		// Start renaming the tables
		foreach ($tables as $table) {
			if ($this->_tableExists($table)) {
				if ($this->_tableExists($table.'_'.$this->_tag)) {
					if ($removeold) {
						$db->setQuery("DROP TABLE ".$db->quoteName($table.'_'.$this->_tag));
						if (!$db->query()) {
							$this->_results['messages'][] = $db->getErrorMsg();
							$ok = false;
						}
					}
					else {
						$db->setQuery("ALTER TABLE ".$db->quoteName($table.'_'.$this->_tag)." RENAME TO ".$db->quoteName($table.'_'.$random));
						if (!$db->query()) {
							$this->_results['messages'][] = $db->getErrorMsg();
							$ok = false;
						}
					}
				}
				$db->setQuery("ALTER TABLE ".$db->quoteName($table)." RENAME TO ".$db->quoteName($table.'_'.$this->_tag));
				if (!$db->query()) {
					$this->_results['messages'][] = $db->getErrorMsg();
					$ok = false;
				}
			}
			$_results['messages'][] = $table;
		}
		return $ok;
	}

	/**
	 * Check if a table exists
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		$table	string	the name of the table to check
	 * @return 		bool	true if table exists | false if table does not exist
	 * @since 		3.0
	 */
	private function _tableExists($table) {
		$db = JFactory::getDbo();
		if (empty($this->_tables)) {
			$this->_tables = $db->getTableList();
		}
		$table = str_ireplace('#__', $db->getPrefix(), $table);
		if (in_array($table, $this->_tables)) return true;
		else return false;
	}

	/**
	 * Create the tables for this version
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Remove return statement
	 * @see
	 * @access 		private
	 * @param
	 * @return 		bool	true on creating all tables | false if table is not created
	 * @since 		3.0
	 */
	private function _createTables() {
		$this->_createTemplateSettings();
		$this->_createTemplateTypes();
		$this->_createTemplateTables();
		$this->_createLogs();
		$this->_createLogDetails();
		$this->_createAvailableFields();
		$this->_createCurrency();
		$this->_createSettings();
		$this->_createIcecatIndex();
		$this->_createIcecatSuppliers();
		$this->_createRelatedProducts();
		$this->_createReplacements();

		return true;
	}

	/**
	 * Migrate the tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version being migrated from
	 * @return 		bool	true if migration is OK | false if errors occured during migration
	 * @since 		3.0
	 */
	private function _migrateTables($version) {
		$db = JFactory::getDbo();
		switch ($version) {
			case '4.0':
				$this->_convertReplacements($version);
				$this->_convertTemplateSettings($version);
				$this->_convertSettings($version);
				$this->_convertIcecatSuppliers($version);
				$this->_convertIcecatIndex($version);
				$this->_convertCurrency($version);
				$this->_convertAvailableFields($version);
				$this->_convertTemplateTables($version);
				$this->_convertLogs($version);
				$this->_convertLogDetails($version);
				break;
			default:
				break;
		}
	}

	/**
	 * Get the tables per version
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the current installed version
	 * @return 		array	list of tables
	 * @since 		3.0
	 */
	private function _getTables($version) {
		$tables = array();
		switch ($version) {
			case 'current':
			case '4.0':
				$tables[] = '#__csvi_available_fields';
				$tables[] = '#__csvi_currency';
				$tables[] = '#__csvi_icecat_index';
				$tables[] = '#__csvi_icecat_suppliers';
				$tables[] = '#__csvi_logs';
				$tables[] = '#__csvi_log_details';
				$tables[] = '#__csvi_replacements';
				$tables[] = '#__csvi_settings';
				$tables[] = '#__csvi_template_settings';
				$tables[] = '#__csvi_template_types';
				$tables[] = '#__csvi_template_tables';
				$tables[] = '#__csvi_related_products';
				break;
		}
		return $tables;
	}

	/**
	 * Create the template settings table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		bool	true if query is succesful | false if query fails
	 * @since 		3.0
	 */
	private function _createTemplateSettings() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_template_settings` (
				`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the saved setting',
				`name` VARCHAR(255) NOT NULL COMMENT 'Name for the saved setting',
				`settings` TEXT NOT NULL COMMENT 'The actual settings',
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Stores the template settings for CSVI';");
		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the template type table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createTemplateTypes() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS  `#__csvi_template_types` (
			  		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
				  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `template_type_name` varchar(55) NOT NULL,
				  `template_type` varchar(55) NOT NULL,
				  `component` varchar(55) NOT NULL COMMENT 'Name of the component',
				  `url` varchar(100) DEFAULT NULL COMMENT 'The URL of the page the import is for',
				  `options` varchar(255) NOT NULL DEFAULT 'fields' COMMENT 'The template pages to show for the template type',
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `type_name` (`template_type_name`,`template_type`,`component`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Template types for CSVI';");
		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the template tables table
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
	private function _createTemplateTables() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS  `#__csvi_template_tables` (
			  	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
				  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `template_type_name` varchar(55) NOT NULL,
				  `template_table` varchar(55) NOT NULL,
				  `component` varchar(55) NOT NULL,
				  `indexed` int(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `type_name` (`template_type_name`,`template_table`,`component`)
				) ENGINE=MyISAM CHARSET=utf8 COMMENT='Template tables used per template type for CSVI'");
		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the log table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createLogs() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_logs` (
			  `id` int(11) NOT NULL auto_increment,
			  `userid` int(11) NOT NULL,
			  `logstamp` datetime NOT NULL,
			  `action` varchar(255) NOT NULL,
			  `action_type` varchar(255) NOT NULL default '',
			  `template_name` varchar(255) NULL default NULL,
			  `records` int(11) NOT NULL,
			  `run_id` INT(11) NULL DEFAULT NULL,
			  `file_name` VARCHAR(255) NULL DEFAULT NULL,
			  `run_cancelled` TINYINT(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Log results for CSVI';");

		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the log details table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createLogDetails() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_log_details` (
			  `id` int(11) NOT NULL auto_increment,
			  `log_id` int(11) NOT NULL,
			  `line` int(11) NOT NULL,
			  `description` text NOT NULL,
			  `result` varchar(45) NOT NULL,
			  `status` varchar(45) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Log details for CSVI';");

		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the available fields table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createAvailableFields() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_available_fields` (
			  `id` int(11) NOT NULL auto_increment,
			  `csvi_name` varchar(255) NOT NULL,
			  `component_name` varchar(55) NOT NULL,
			  `component_table` varchar(55) NOT NULL,
			  `component` varchar(55) NOT NULL,
			  `isprimary` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `component_name_table` (`component_name`,`component_table`,`component`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Available fields for CSVI';");

		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the currency table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createCurrency() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS  `#__csvi_currency` (
			  `currency_id` tinyint(4) NOT NULL auto_increment,
			  `currency_code` varchar(3) NULL DEFAULT NULL,
			  `currency_rate` varchar(55) NULL DEFAULT NULL,
			  PRIMARY KEY  (`currency_id`),
			  UNIQUE INDEX `currency_code` (`currency_code`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Curriencies and exchange rates for CSVI';");

		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the settings table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createSettings() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_settings` (
				`id` INT(11) NOT NULL auto_increment,
				`params` TEXT NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Configuration values for CSVI';");
		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
		else {
			$db->setQuery("INSERT IGNORE INTO `#__csvi_settings` (`id`, `params`) VALUES (1, '');");
			if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
		}

	}

	/**
	 * create ICEcat index table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createIcecatIndex() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_icecat_index` (
			  `path` varchar(100) DEFAULT NULL,
			  `product_id` int(2) DEFAULT NULL,
			  `updated` int(14) DEFAULT NULL,
			  `quality` varchar(6) DEFAULT NULL,
			  `supplier_id` int(1) DEFAULT NULL,
			  `prod_id` varchar(16) DEFAULT NULL,
			  `catid` int(3) DEFAULT NULL,
			  `m_prod_id` varchar(10) DEFAULT NULL,
			  `ean_upc` varchar(10) DEFAULT NULL,
			  `on_market` int(1) DEFAULT NULL,
			  `country_market` varchar(10) DEFAULT NULL,
			  `model_name` varchar(26) DEFAULT NULL,
			  `product_view` int(5) DEFAULT NULL,
			  `high_pic` varchar(51) DEFAULT NULL,
			  `high_pic_size` int(5) DEFAULT NULL,
			  `high_pic_width` int(3) DEFAULT NULL,
			  `high_pic_height` int(3) DEFAULT NULL,
			  `m_supplier_id` int(3) DEFAULT NULL,
			  `m_supplier_name` varchar(51) DEFAULT NULL,
			  INDEX `product_mpn` (`prod_id`),
			  INDEX `manufacturer_name` (`supplier_id`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='ICEcat index data for CSVI';");

		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the ICEcat suppliers table
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since		4.0
	 */
	private function _createIcecatSuppliers() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_icecat_suppliers` (
				`supplier_id` INT(11) UNSIGNED NOT NULL,
				`supplier_name` VARCHAR(255) NOT NULL,
				UNIQUE INDEX `Unique supplier` (`supplier_id`, `supplier_name`),
				INDEX `Supplier name` (`supplier_name`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='ICEcat supplier data for CSVI';");

		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the related products table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.3.1
	 */
	private function _createRelatedProducts() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_related_products` (
				`product_sku` VARCHAR(64) NOT NULL,
				`related_sku` TEXT NOT NULL
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Related products import for CSVI';");

		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}

	/**
	 * Create the replacements table
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
	private function _createReplacements() {
		$db = JFactory::getDbo();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__csvi_replacements` (
			`id` INT(10) NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(100) NOT NULL,
			`findtext` TEXT NOT NULL,
			`replacetext` TEXT NOT NULL,
			`multivalue` ENUM('0','1') NOT NULL,
			`method` ENUM('text','regex') NOT NULL DEFAULT 'text',
			`checked_out` INT(11) UNSIGNED NULL DEFAULT '0',
			`checked_out_time` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM CHARSET=utf8 COMMENT='Replacement rules for CSVI';");
		if (!$db->query()) $this->_results['messages'][] = $db->getErrorMsg();
	}


	/**
	 * Convert the replacements table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertReplacements($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_replacements (SELECT `id`,`name`,`findtext`, `replacetext`, 0, `method`, `checked_out`, `checked_out_time` FROM #__csvi_replacements'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_REPLACEMENTS_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the template settings table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertTemplateSettings($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_template_settings (SELECT * FROM #__csvi_template_settings'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_TEMPLATE_SETTINGS_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the template settings table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertSettings($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_settings (SELECT * FROM #__csvi_settings'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_SETTINGS_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the ICEcat suppliers table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertIcecatSuppliers($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_icecat_suppliers (SELECT * FROM #__csvi_icecat_suppliers'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_ICECAT_SUPPLIERS_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the ICEcat index table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertIcecatIndex($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_icecat_index (SELECT * FROM #__csvi_icecat_index'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_ICECAT_INDEX_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the ICEcat index table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertCurrency($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_currency (SELECT * FROM #__csvi_currency'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_CURRENCY_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the ICEcat index table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertAvailableFields($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_available_fields (SELECT * FROM #__csvi_available_fields'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_AVAILABLE_FIELDS_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the template tables table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertTemplateTables($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_template_tables (SELECT * FROM #__csvi_template_tables'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_TEMPLATE_TABLES_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the logs table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertLogs($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_logs (SELECT * FROM #__csvi_logs'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_LOGS_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Convert the log details table
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$version	the version to convert from
	 * @return
	 * @since 		4.0
	 */
	private function _convertLogDetails($version) {
		$db = JFactory::getDbo();

		switch ($version) {
			case '4.0':
				$db->setQuery('REPLACE INTO #__csvi_log_details (SELECT * FROM #__csvi_log_details'.'_'.$this->_tag.')');
				if ($db->query()) {
					$this->_results['messages'][] = JText::_('COM_CSVI_LOG_DETAILS_CONVERTED');
					return true;
				}
				else $this->_results['messages'][] = $db->getErrorMsg();
				break;
		}
	}

	/**
	 * Proxy function for calling the update the available fields
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
	public function getAvailableFields() {
		// Get the logger class
		$jinput = JFactory::getApplication()->input;
		$csvilog = new CsviLog();
		$jinput->set('csvilog', $csvilog);
		$model = $this->getModel('Availablefields');
		// Prepare to load the available fields
		$model->prepareAvailableFields();

		// Update the available fields
		$model->getFillAvailableFields();
	}

	/**
	 * Proxy function for installing sample templates
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
	public function getSampleTemplates() {
		$db = JFactory::getDbo();
		// Read the example template file
		$fp = fopen(JPATH_COMPONENT_ADMINISTRATOR.'/install/example_templates.csv', "r");
		if ($fp) {
			while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
				$db->setQuery("INSERT IGNORE INTO #__csvi_template_settings (".$db->quoteName('name').", ".$db->quoteName('settings').")
													VALUES (".$db->Quote($data[0]).", ".$db->Quote($data[1]).")");
				if ($db->query()) {
					$this->_results['messages'][] = JText::sprintf('COM_CSVI_RESTORE_TEMPLATE', $data[0]);
				}
				else {
					$this->_results['messages'][] = $db->getErrorMsg();
					$this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_HAS_NOT_BEEN_ADDED', $file);
				}
			}
			fclose($fp);
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
	 * Set the current version in the database
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.1
	 */
	private function _setVersion() {
		$db = JFactory::getDbo();
		$q = "INSERT IGNORE INTO #__csvi_settings (id, params) VALUES (2, '".JText::_('COM_CSVI_CSVI_VERSION')."')
			ON DUPLICATE KEY UPDATE params = '".JText::_('COM_CSVI_CSVI_VERSION')."'";
		$db->setQuery($q);
		$db->query();
	}

	/**
	 * Get the current version in the database
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.2
	 */
	private function _getVersion() {
		$db = JFactory::getDbo();
		$q = "SELECT params
			FROM #__csvi_settings
			WHERE id = 2";
		$db->setQuery($q);
		return $db->loadResult();
	}

	/**
	 * Translate version
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		string with the working version
	 * @since 		3.5
	 */
	private function _translateVersion() {
	$jinput = JFactory::getApplication()->input;
		$version = $jinput->get('version', 'current', 'string');
		switch ($version) {
			case '4.0.1':
			case '4.1':
			case '4.2':
				return '4.0';
				break;
			default:
				return $version;
				break;
		}
	}

	/**
	 * Load supported components
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _loadComponents() {
		$db = JFactory::getDbo();
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$files = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR.'/install', '.sql', false, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'availablefields_extra.sql'));
		if (!empty($files)) {
			foreach ($files as $file) {
				$error = false;
				if (JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.'/install/'.$file)) {
					$q = JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/install/'.$file);
					$queries = $db->splitSql(JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/install/'.$file));
					foreach ($queries as $query) {
						$query = trim($query);
						if (!empty($query)) {
							$db->setQuery($query);
							if (!$db->query()) {
								$this->_results['messages'][] = $db->getErrorMsg();
								$error = true;
							}
						}
					}
					if ($error) $this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_HAS_NOT_BEEN_ADDED', $file);
					else $this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_HAS_BEEN_ADDED', $file);
				}
				else $this->_results['messages'][] = JText::sprintf('COM_CSVI_COMPONENT_NOT_FOUND', $file);
			}
		}
	}
}
?>