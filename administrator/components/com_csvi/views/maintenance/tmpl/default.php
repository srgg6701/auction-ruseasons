<?php
/**
 * Maintenance page
 *
 * @package 	CSVI
 * @subpackage 	Maintenance
 * @todo
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
JHtml::_('behavior.tooltip');
?>
<form method="post" action="<?php echo JRoute::_('index.php?option=com_csvi'); ?>" name="adminForm" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_CSVI_MAKE_CHOICE_MAINTENANCE'); ?></legend>
			<div class="width-60 fltlft">
				<?php echo JHtml::_('select.genericlist', $this->components, 'component', 'onchange=CsviMaint.loadOperation(this.value)','value', 'text', null, false, true); ?>
				<?php echo JHtml::_('select.genericlist', $this->options, 'operation', 'onchange=CsviMaint.loadOptions(this.value)'); ?>
			</div>
			<div id="optionfield" class="width-100 fltlft"></div>
		</fieldset>
	</div>
	<input type="hidden" name="task" value="" />
	<!-- Used to generate the correct cron line -->
	<input type="hidden" name="from" value="maintenance" />
	
</form>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (jQuery('#operation').val() == '') {
			jAlert('<?php echo JText::_('COM_CSVI_NO_CHOICE'); ?>');
			return false;
		}
		else {
			if (task == 'cron.cron') {
				if (document.adminForm.task.value == 'maintenance.restoretemplates' || document.adminForm.task.value == 'maintenance.backuptemplates') {
					jAlert('<?php echo JText::_('COM_CSVI_OPTION_CRON_NO_SUPPORT'); ?>');
					return false;
				}
				// else document.adminForm.view.value = 'cron';
			}
			else task = document.adminForm.task.value;
			Joomla.submitform(task);
		}
	}
</script>
