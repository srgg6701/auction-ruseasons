<?php
/**
 * Import page
 *
 * @package		CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2298 2013-01-29 11:38:39Z RolandD $
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
?>
<form action="<?php JText::_('index.php?option=com_csvi'); ?>" method="post" name="adminForm" enctype="multipart/form-data">
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="template_id" value="" />
	<input type="hidden" name="template_name" value="" />
	<!-- Used to generate cron line -->
	<input type="hidden" name="from" value="process" />

	<!-- Templates -->
	<fieldset id="template_fieldset">
		<legend><?php echo JText::_('COM_CSVI_IMPORT_TEMPLATE_DETAILS'); ?></legend>
		<div id="template_list">
			<?php echo $this->templates; ?>
			<input type="button" onclick="Joomla.submitbutton('process.load')" value="<?php echo JText::_('COM_CSVI_LOAD'); ?>" class="button">
			<input type="button" onclick="Joomla.submitbutton('process.remove')" value="<?php echo JText::_('COM_CSVI_REMOVE'); ?>" class="button">
			<input type="button" onclick="Joomla.submitbutton('process.save')" value="<?php echo JText::_('COM_CSVI_APPLY'); ?>" class="button">
			<input type="button" onclick="Joomla.submitbutton('process.saveasnew')" value="<?php echo JText::_('COM_CSVI_SAVE_AS_NEW'); ?>" class="button">
		</div>
	</fieldset>

	<!-- Using Form -->
	<fieldset id="manual_fieldset">
		<legend><?php echo JText::_('COM_CSVI_PROCESS_OPTIONS'); ?></legend>
			<?php foreach ($this->form->getGroup('options') as $field) : ?>

				<?php echo $field->input; ?>

			<?php endforeach; ?>
		<input type="button" onclick="Joomla.submitform('process.display');" value="<?php echo JText::_('COM_CSVI_GO'); ?>" class="button">
	</fieldset>

	<?php
	$action = $this->form->getValue('action', 'options');
	$component = $this->form->getValue('component', 'options');
	$operation = $this->form->getValue('operation', 'options');

	if ($action && $component & $operation) {
		// Load the source template
		echo $this->loadTemplate('source');

		// Load the specific templates
		switch($action) {
			case 'import':
				?>
				<!-- Load the option template(s) in tabs -->
				<fieldset id="importtabs">
					<legend><?php echo JText::_('COM_CSVI_IMPORT_DETAILS'); ?></legend>
						<div id="process_page">
							<ul>
								<?php foreach ($this->optiontemplates as $template) { ?>
									<li><a href="#<?php echo $template; ?>_tab"><?php echo JText::_('COM_CSVI_IMPORT_'.$template); ?></a></li>
								<?php } ?>
							</ul>
							<?php foreach ($this->optiontemplates as $template) { ?>
								<div id="<?php echo $template; ?>_tab">
									<?php echo $this->loadTemplate($template); ?>
								</div>
							<?php } ?>
						</div>
				</fieldset>
				<?php
				break;
			case 'export':
				?>
				<!-- Load the option template(s) in tabs -->
				<fieldset id="exporttabs">
					<legend><?php echo JText::_('COM_CSVI_EXPORT_DETAILS'); ?></legend>
						<div id="process_page">
							<ul>
								<?php foreach ($this->optiontemplates as $template) { ?>
									<li><a href="#<?php echo $template; ?>_tab"><?php echo JText::_('COM_CSVI_EXPORT_'.$template); ?></a></li>
								<?php } ?>
							</ul>
							<?php foreach ($this->optiontemplates as $template) { ?>
								<div id="<?php echo $template; ?>_tab">
									<?php echo $this->loadTemplate($template); ?>
								</div>
							<?php } ?>
						</div>
				</fieldset>
				<?php
				break;
		}
	}?>
</form>

<script type="text/javascript">
jQuery(document).ready(function () {
	jQuery("#process_page").tabs();

	// Show the export location
	if ('<?php echo $action; ?>' == 'export') Csvi.showSource(document.adminForm.jform_general_exportto.value);
});
</script>

<style type="text/css">
.ui-tabs .ui-tabs-hide {
     display: none;
}
</style>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if (task == 'process.saveasnew') {
		jPrompt('<?php echo JText::_('COM_CSVI_IMPORT_ADD_TEMPLATE_NAME_DESC'); ?>', '', '<?php echo JText::_('COM_CSVI_IMPORT_ADD_TEMPLATE_NAME_LABEL'); ?>', function(template_name) {
			if (template_name) {
				document.adminForm.template_name.value = template_name;
				Joomla.submitform(task);
			}
			else return false;
		});
	}
	else if (task == 'process.save') {
		document.adminForm.template_id.value = jQuery('#select_template :selected').val();
		Joomla.submitform(task);
	}
	else if (task == 'process.remove') {
		jConfirm('<?php echo JText::_('COM_CSVI_REMOVE_TEMPLATE_DESC'); ?>', '<?php echo JText::_('COM_CSVI_REMOVE_TEMPLATE_LABEL'); ?>', function(r) {
			if (r) {
				document.adminForm.template_id.value = jQuery('#select_template :selected').val();
				Joomla.submitform(task);
			}
			else return false;
		})
	}
	else if (task == 'process.load') {
		document.adminForm.template_id.value = jQuery('#select_template :selected').val();
		Joomla.submitform(task);
	}
	else if (task == 'process.imexport') {
		if (document.adminForm.jform_options_action.value == 'import') task = 'importfile.process';
		else if (document.adminForm.jform_options_action.value == 'export') task = 'exportfile.process';
		document.adminForm.template_name.value = jQuery('#select_template :selected').text();
		Joomla.submitform(task);
	}
	else {
		if (task == 'cronline') {
			document.adminForm.template_id.value = jQuery('#select_template :selected').val();
			Joomla.submitform('cron.cron');
		}
		else {
			//checkFields();

		}
		Joomla.submitform(task);
	}
}
</script>