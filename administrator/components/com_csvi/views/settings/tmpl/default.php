<?php
/**
 * Log settings page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// Load some behaviour
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<?php
	echo JHtml::_('tabs.start','settings', array('useCookie'=>1));
		echo JHtml::_('tabs.panel', JText::_('COM_CSVI_SETTINGS_SITE_SETTINGS'), 'site_settings');
			echo $this->loadTemplate('site');
		echo JHtml::_('tabs.panel', JText::_('COM_CSVI_SETTINGS_IMPORT_SETTINGS'), 'import_settings');
			echo $this->loadTemplate('import');
		echo JHtml::_('tabs.panel', JText::_('COM_CSVI_SETTINGS_GOOGLE_BASE_SETTINGS'), 'google_base_settings');
			echo $this->loadTemplate('google_base');
		echo JHtml::_('tabs.panel', JText::_('COM_CSVI_SETTINGS_ICECAT_SETTINGS'), 'icecat_settings');
			echo $this->loadTemplate('icecat');
		echo JHtml::_('tabs.panel', JText::_('COM_CSVI_SETTINGS_LOG_SETTINGS'), 'log_settings');
			echo $this->loadTemplate('log');
		echo JHtml::_('tabs.panel', JText::_('COM_CSVI_SETTINGS_CUSTOM_TABLES'), 'custom_tables_settings');
			echo $this->loadTemplate('custom_tables');
	echo JHtml::_('tabs.end');
	?>
	<input type="hidden" name="option" value="com_csvi" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<div id="dialog-confirm" class="dialog-hide" title="<?php echo JText::_('COM_CSVI_CONFIRM_RESET_SETTINGS_TITLE'); ?>">
	<div class="dialog-info"></div>
	<div class="dialog-text"><?php echo JText::_('COM_CSVI_CONFIRM_RESET_SETTINGS_TEXT'); ?></div>
</div>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'reset') {
			jQuery("#dialog-confirm").dialog({
				resizable: false,
				height:140,
				modal: true,
				buttons: {
					"<?php echo JText::_('COM_CSVI_RESET_SETTINGS'); ?>": function() {
						Joomla.submitform(task);
					},
					Cancel: function() {
						jQuery( this ).dialog( "close" );
					}
				}
			});

		}
		else if (document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task);
		}
		else {
			jAlert('<?php echo JText::_('COM_CSVI_INCOMPLETE_FORM'); ?>');
		}
	}
</script>