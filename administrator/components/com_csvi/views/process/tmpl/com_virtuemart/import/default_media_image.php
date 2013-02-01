<?php
/**
 * Media image options
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_media_image.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset class="float31">
	<legend><?php echo JText::_('COM_CSVI_IMPORT_GENERAL_IMAGES'); ?></legend>
	<ul>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('process_image', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('process_image', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('change_case', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('change_case', 'image'); ?></div></li>
	</ul>
</fieldset>
<fieldset class="float30">
	<legend><?php echo JText::_('COM_CSVI_IMPORT_FULL_IMAGES'); ?></legend>
	<ul>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('keep_original', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('keep_original', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('convert_type', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('convert_type', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('save_images_on_server', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('save_images_on_server', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('full_resize', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('full_resize', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('full_width', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('full_width', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('full_height', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('full_height', 'image'); ?></div></li>
	</ul>
</fieldset>
<fieldset class="float30">
	<legend><?php echo JText::_('COM_CSVI_IMPORT_THUMB_IMAGES'); ?></legend>
	<ul>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('thumb_check_filetype', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('thumb_check_filetype', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('thumb_create', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('thumb_create', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('thumb_extension', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('thumb_extension', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('thumb_width', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('thumb_width', 'image'); ?></div></li>
		<li><div class="option_label_image"><?php echo $this->form->getLabel('thumb_height', 'image'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('thumb_height', 'image'); ?></div></li>
	</ul>
</fieldset>
<div class="clr"/></div>
<script type="text/javascript">
jQuery(document).ready(function() {
	// Hide some fields
	//jQuery('#jform_image_type_generate_image_name, #jform_image_autogenerateext').parent().hide();
	//jQuery('#jform_image_thumb_extension, #jform_image_thumb_width, #jform_image_thumb_height').parent().hide();
	//jQuery('#jform_image_full_width, #jform_image_full_height').parent().hide();
})

// Add some behaviors
jQuery("#jform_image_auto_generate_image_name").live('change', function() {
	jQuery('#jform_image_type_generate_image_name, #jform_image_autogenerateext').parent().toggle();
})

jQuery("#jform_image_thumb_create").live('change', function() {
	jQuery('#jform_image_thumb_extension, #jform_image_thumb_width, #jform_image_thumb_height').parent().toggle();
})

jQuery("#jform_image_full_resize").live('change', function() {
	jQuery('#jform_image_full_width, #jform_image_full_height').parent().toggle();
})
</script>
