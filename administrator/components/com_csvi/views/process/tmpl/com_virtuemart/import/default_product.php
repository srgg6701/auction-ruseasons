<?php
/**
 * Import product options
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_product.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label"><?php echo $this->form->getLabel('language', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('language', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('category_separator', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('category_separator', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('append_categories', 'product'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('append_categories', 'product'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('update_based_on', 'product'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('update_based_on', 'product'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('mpn_column_name', 'product'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('mpn_column_name', 'product'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('unpublish_before_import', 'product'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('unpublish_before_import', 'product'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('use_icecat', 'product'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('use_icecat', 'product'); ?></div></li>
	</ul>
</fieldset>
<div class="clr"></div>
<script type="text/javascript">
jQuery(document).ready(function() {
	if ('<?php echo $this->template->get('update_based_on', 'product', 'product_sku'); ?>' != 'product_mpn') {
		jQuery('#jform_product_mpn_column_name').parent().parent().hide();
	}
});
jQuery('#jform_product_update_based_on').live('change', function() {
	if (jQuery(this).val() == 'product_mpn') {
		jQuery('#jform_product_mpn_column_name').parent().parent().show();
	}
	else jQuery('#jform_product_mpn_column_name').parent().parent().hide();
});
</script>
