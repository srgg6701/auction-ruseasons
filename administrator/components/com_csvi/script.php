<?php
/**
 * Installation file for CSVI
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: script.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

/**
 * Load the CSVI installer 
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
class com_csviInstallerScript {
	
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) {
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		echo JText::_('COM_CSVI_UNINSTALL_TEXT');
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		
		// Check if the PHP version is correct
		if (version_compare(phpversion(), '5.2', '<') == '-1') {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::sprintf('COM_CSVI_PHP_VERSION_ERROR', phpversion()), 'error');
			return false;
		}
		
		// Check if the Joomla version is correct
		$version = new JVersion();
		if (version_compare($version->getShortVersion(), '2.5', '<') == '-1') {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::sprintf('COM_CSVI_JOOMLA_VERSION_ERROR', $version->getShortVersion()), 'error');
			return false;
		}
		
		// Check if there is an entry in the schemas table
		if ($type == 'update') {
			$db = JFactory::getDbo();

			// Get the extension id first
			$query = $db->getQuery(true);
			$query->select('extension_id')->from('#__extensions')->where($db->qn('type').'='.$db->q('component'))->where($db->qn('element').'='.$db->q('com_csvi'));
			$db->setQuery($query);
			$eid = $db->loadResult();

			if ($eid) {
				// Check if there is a version in the schemas table
				$query->clear();
				$query->select('version_id')
					->from('#__schemas')
					->where('extension_id = ' . $eid);
				$db->setQuery($query);
				$version = $db->loadResult();

				if (empty($version)) {
					// Get the current CSVI version
					$query->clear();
					$query->select('params')
						->from('#__csvi_settings')
						->where('id = 2');
					$db->setQuery($query);
					$version = $db->loadResult();

					// Add the version number
					$query->clear();
					$query->insert('#__schemas')->values($eid.','.$db->q($version));
					$db->setQuery($query);
					$db->query();
				}
			}
		}
		
		return true;
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		// Load the CSS
		?>
		<style type="text/css">
			#install a, #install a:visited {

			display: block;
			padding: 5px;

			border: 1px solid #fba534;
			-webkit-border-radius: 6px 6px 6px 6px;
			-moz-border-radius: 6px 6px 6px 6px;
			border-radius: 6px 6px 6px 6px;

			background-color: #fba534;
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffad40', endColorstr='#f58c04'); /* for IE */
			background: -webkit-gradient(linear, left top, left bottom, from(#ffad40), to(#f58c04)); /* for webkit browsers */
					background: -moz-linear-gradient(top,  #ffad40,  #f58c04); /* for firefox 3.6+ */

							-webkit-box-shadow:  1px 1px 4px #666;
							-moz-box-shadow:  1px 1px 4px #666;
							box-shadow:  1px 1px 4px #666;

							color: #FFF;
							text-decoration:none;
			font-weight: bold;
			font-size: 25px;

			margin-bottom: 10px;
			margin-top: 12px;
			text-align:center;

			}
		</style>
		<?php
		// Show the message to show users to click to continue to the second step
		echo '<div id="install">';
			echo JHtml::_('link', JRoute::_('index.php?option=com_csvi&view=install'), JText::_('COM_CSVI_CONTINUE_SETUP'));
		echo '</div>';
	}
}