<?php
/**
 * Install page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @todo
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<div id="installcsvi">

    <div id="versions">
		<div id="oldversionbox">
			<?php
				if ($this->selectversion == 'current') echo JText::_('COM_CSVI_NONEW_VERSION');
				else echo JText::sprintf('COM_CSVI_FOUND_VERSION', $this->selectversion);
			?>
		</div>
		<div id="newversionbox">
			<?php echo JText::sprintf('COM_CSVI_NEW_VERSION', $this->newversion); ?>
		</div>
	</div>

  <div id="rightbox">

	<div id="options">
		<div>
			<?php
				foreach ($this->installoptions as $installoption) {
					if ($installoption->value == 'availablefields') $checked = 'checked="checked"';
					else $checked = '';
					?>
					<input type="checkbox" name="installoptions[]" value="<?php echo $installoption->value; ?>" id="<?php echo $installoption->value; ?>" <?php echo $checked; ?> /><?php echo $installoption->text; ?>
					<?php
				}
			?>
			<br />
			<input type="checkbox" name="removeoldtables" value="1" id="removeoldtables" /><?php echo JText::_('COM_CSVI_REMOVEOLDTABLES_LABEL'); ?>
		</div>
	</div>



	<div id="progress">
		<div id="steps">
			<?php if ($this->selectversion != 'current') { ?>
					<div id="update">
						<a onclick="updateVersion('<?php echo str_ireplace('+', '', $this->selectversion); ?>'); return false;" href="#"><?php echo JText::_('COM_CSVI_UPGRADE_CSVI'); ?></a>
					</div>
					<div id="updatedesc">
						<?php echo JText::_('COM_CSVI_UPGRADE_CSVI_DESC'); ?>
					</div>
			<?php } ?>
			<div id="install">
				<a onclick="updateVersion('current'); return false;" href="#"><?php echo JText::_('COM_CSVI_INSTALL_CSVI'); ?></a>
			</div>
			<div id="installdesc">
				<?php echo JText::_('COM_CSVI_INSTALL_CSVI_DESC'); ?>
			</div>
			<div id="spinner"></div>
			<div id="installrunning"></div>
		</div>
	</div>

   </div>


</div>




<script type="text/javascript">
function updateVersion(version) {
	jQuery('#update,#updatedesc,#install,#installdesc').hide();
	jQuery('#finished').remove();
	jQuery('#installrunning').html('');
	jQuery('#spinner').html("<img src='<?php echo JURI::root(); ?>/administrator/components/com_csvi/assets/images/csvi_ajax-loading.gif' />");
	var tasks = 'upgrade';
	if (jQuery('#availablefields').is(':checked')) tasks = tasks + '.availablefields';
	if (jQuery('#sampletemplates').is(':checked')) tasks = tasks + '.sampletemplates';
	if (jQuery('#removeoldtables').is(':checked')) removeold = '1';
	else removeold = '0';
	executeTask(version, tasks);
}

function executeTask(version, tasks) {
	jQuery.ajax({
		async: false,
		url: 'index.php',
		dataType: 'json',
		data: 'option=com_csvi&task=install.upgrade&format=json&version='+version+'&tasks='+tasks+'&removeoldtables='+removeold,
		success: function(data) {
			if(typeof(data.results.error) !== 'undefined') {
				for (var i = 0; i < data.results.error.length; i++) {
					jQuery('#installrunning').append(data.results.error[i]+"<br />");
				}
				jQuery('#update,#updatedesc,#install,#installdesc').show();
			}

			for (var i = 0; i < data.results.messages.length; i++) {
				jQuery('#installrunning').append(data.results.messages[i]+"<br />");
			}

			// Check if more tasks need to be performed
			if (data.tasks !== "") {
				// Execute tasks
				executeTask(version, data.tasks);
			}
			else {
				jQuery('#spinner').remove();
				jQuery('#progress').prepend('<div id="finished"><?php echo JText::_('COM_CSVI_INSTALL_FINISHED'); ?></div>');
			}

		},
		error:function (request, status, error) {
			jQuery('#spinner').html('<?php echo JText::_('COM_CSVI_ERROR_UPDATING_VERSION'); ?>');
			jAlert(Joomla.JText._('COM_CSVI_ERROR_DURING_INSTALL')+jQuery.trim(request.responseText).substring(0, 2500));
        }
	});
}
</script>