<?php
/**
 * ICEcat loading page
 *
 * @package 	CSVI
 * @subpackage 	Cron
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: icecat.php 2275 2013-01-03 21:08:43Z RolandD $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<form method="post" action="index.php" name="adminForm">
	<table class="adminlist" id="progresstable" style="width: 45%;">
		<thead>
		<tr><th colspan="2" style="white-space:nowrap;"><?php echo JText::_('COM_CSVI_MAINTENANCE_ICECAT'); ?></th></tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">
					<div id="progressbar"></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr><td><?php echo JText::_('COM_CSVI_RECORDS_PROCESSED'); ?></td><td><div id="status"></div></td></tr>
			<tr><td><?php echo JText::_('COM_CSVI_TIME_RUNNING'); ?></td><td><div class="uncontrolled-interval"><span></span></div></td></tr> 
			<tr><td colspan="2"><img id="spinner" src='<?php echo JURI::root(); ?>/administrator/components/com_csvi/assets/images/csvi_ajax-loading.gif' /></td></tr>
		</tbody>
	</table> 
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_csvi" />
	<input type="hidden" name="view" value="maintenance" />
</form>
<script type="text/javascript">
jQuery(function() {
	startTime();
	loadIndex();
});

// Build the timer
function startTime() {
	jQuery(".uncontrolled-interval span").everyTime(1000, 'importcounter', function(i) {
		if (<?php echo ini_get('max_execution_time'); ?> > 0 && i > <?php echo ini_get('max_execution_time'); ?>) {
			jQuery("#spinner").remove();
			jQuery("#progress").remove();
			jQuery(this).html('<?php echo JText::_('COM_CSVI_MAX_IMPORT_TIME_PASSED'); ?>');
		}
		else {
			jQuery(this).html(i);
			var ptime = (100 / <?php echo ini_get('max_execution_time'); ?>) * i;
			jQuery("#progressbar").progressbar({ value: ptime });
		}
	}); 
}

// Start the import
function loadIndex() {
	jQuery.ajax({
		async: true,
		url: 'index.php',
		dataType: 'json',
		data: 'option=com_csvi&view=maintenance&task=icecatsingle&format=json',
		success: function(data) {
			if (data) {
				if (data.process == true) {
					jQuery('#status').html(data.records);
					jQuery(".uncontrolled-interval span").stopTime('importcounter');
					startTime();
					loadIndex();
				}
				else {
					window.location = data.url;
				}
			}
		},
		failure: function(data) {
			jQuery(".uncontrolled-interval span").stopTime('importcounter');
			jQuery('#spinner').remove();
			jQuery('#status').html('<?php echo JText::_('COM_CSVI_ERROR_PROCESSING_RECORDS'); ?>'+data.responseText);
		},
		error: function(data) {
			jQuery(".uncontrolled-interval span").stopTime('importcounter');
			jQuery('#spinner').remove();
			jQuery('#status').html('<?php echo JText::_('COM_CSVI_ERROR_PROCESSING_RECORDS'); ?>'+data.responseText);
		}
	});
}
</script>