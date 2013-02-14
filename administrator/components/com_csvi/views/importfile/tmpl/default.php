<?php
/**
 * Import file
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @todo
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2298 2013-01-29 11:38:39Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$jinput = JFactory::getApplication()->input;
?>
<form method="post" action="index.php" name="adminForm">
	<table class="adminlist" id="progresstable" style="width: 45%;">
		<thead>
		<tr><th colspan="2" style="white-space:nowrap;"><?php echo JText::sprintf('COM_CSVI_TEMPLATE_NAME', $this->template_name); ?></th></tr>
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
	<div id="preview">
		<table id="tablepreview" class="adminlist" style="empty-cells: show;">
		<thead></thead>
		<tfoot></tfoot>
		<tbody></tbody>
		</table>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_csvi" />
</form>
<script type="text/javascript">
jQuery(function() {
	<?php if ($jinput->get('do_preview', false, 'bool')) { ?>
		jQuery('#toolbar-csvi_import_32').hide();
	<?php } ?>
	startTime();
	doImport();
});

// Build the timer
function startTime() {
	jQuery(".uncontrolled-interval span").everyTime(1000, 'importcounter', function(i) {
		if (<?php echo ini_get('max_execution_time'); ?> > 0 && i > <?php echo ini_get('max_execution_time'); ?>) {
			jQuery("#spinner").remove();
			jQuery("#progress").remove();
			jQuery(this).html('<?php echo addslashes(JText::_('COM_CSVI_MAX_IMPORT_TIME_PASSED')); ?>');
		}
		else {
			jQuery(this).html(i);
			var ptime = (100 / <?php echo ini_get('max_execution_time'); ?>) * i;
			jQuery("#progressbar").progressbar({ value: ptime });
		}
	});
}

// Catch the submitbutton
function submitbutton(task) {
	if (task == 'doimport') {
		jQuery('#toolbar-csvi_import_32').hide();
		jQuery('#preview').remove();
		jQuery('#progresstable').show();
		doImport();
		return true;
	}
	else {
		submitform(task);
	}
}

// Start the import
function doImport() {
	jQuery.ajax({
		async: true,
		url: 'index.php',
		dataType: 'json',
		data: 'option=com_csvi&task=importfile.doimport&format=json',
		success: function(data) {
			if (data) {
				if (data.process == true) {
					if (data.view == 'preview') {
						jQuery(".uncontrolled-interval span").stopTime('importcounter');
						jQuery('#progresstable').hide();
						// Output the headers
						var trdata = '';
						jQuery.each(data.headers, function(index, val) {
							trdata = trdata + '<th>' + val + '</th>';
						});

						// Add the headers to the table
						jQuery('#tablepreview > thead:last').append('<tr>'+trdata+'</tr>');

						// Output the data
						jQuery.each(data.output, function(tindex, tval) {
							var trdata = '';
							jQuery.each(data.output[tindex], function(sindex, sval) {
								trdata = trdata + '<td>' + data.output[tindex][sindex] + '</td>';
							});
							// Add the row to the table
							jQuery('#tablepreview > tbody:last').append('<tr>'+trdata+'</tr>');
						});
						jQuery('#toolbar-csvi_import_32').show();
					}
					else {
						jQuery('#status').html(data.records);
						jQuery(".uncontrolled-interval span").stopTime('importcounter');
						startTime();
						doImport();
					}
				}
				else {
					window.location = data.url;
				}
			}
		},
		error:function (request, status, error) {
			jAlert(Joomla.JText._('COM_CSVI_ERROR_DURING_PROCESS')+jQuery.trim(request.responseText).substring(0, 2500));
        }
	});
}
</script>
