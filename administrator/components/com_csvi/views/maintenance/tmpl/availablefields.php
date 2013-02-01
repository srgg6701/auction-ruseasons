<?php
/**
 * Availablefields loading page
 *
 * @package 	CSVI
 * @subpackage 	Maintenance
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: availablefields.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;
?>
<form method="post" action="index.php" name="adminForm">
	<table class="adminlist" id="progresstable" style="width: 45%;">
		<thead>
		<tr><th colspan="2" style="white-space:nowrap;"><?php echo JText::_('COM_CSVI_MAINTENANCE_AVAILABLEFIELDS'); ?></th></tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">
					<div id="progressbar"></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr><td><?php echo JText::_('COM_CSVI_TABLES_PROCESSED'); ?></td><td><div id="status"></div></td></tr>
			<tr><td colspan="2"><img id="spinner" src='<?php echo JURI::root(); ?>/administrator/components/com_csvi/assets/images/csvi_ajax-loading.gif' /></td></tr>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_csvi" />
</form>
<script type="text/javascript">
jQuery(function() {
	loadIndex();
});

// Start the import
function loadIndex() {
	jQuery.ajax({
		async: true,
		url: 'index.php',
		dataType: 'json',
		data: 'option=com_csvi&task=maintenance.updateavailablefieldssingle&format=json',
		success: function(data) {
			if (data) {
				if (data.process == true) {
					jQuery('#status').prepend(data.table+'<br />');
					loadIndex();
				}
				else {
					window.location = data.url;
				}
			}
		},
		failure: function(data) {
			jQuery('#spinner').remove();
			jQuery('#status').html(Joomla.JText._('COM_CSVI_ERROR_PROCESSING_RECORDS')+data.responseText);
		},
		error: function(data) {
			jQuery('#spinner').remove();
			jQuery('#status').html(Joomla.JText._('COM_CSVI_ERROR_PROCESSING_RECORDS')+data.responseText);
		}
	});
}
</script>