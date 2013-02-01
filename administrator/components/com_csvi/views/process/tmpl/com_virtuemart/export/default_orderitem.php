<?php
/**
 * Export order items
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_orderitem.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_EXPORT_ORDER_ITEMS_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label"><?php echo $this->form->getLabel('orderitemnostart', 'orderitem'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('orderitemnostart', 'orderitem'); ?> <?php echo $this->form->getInput('orderitemnoend', 'orderitem'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('orderitemlist', 'orderitem'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('orderitemlist', 'orderitem'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('orderitemdatestart', 'orderitem'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('orderitemdatestart', 'orderitem'); ?> <?php echo $this->form->getInput('orderitemdateend', 'orderitem'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('orderitemmdatestart', 'orderitem'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('orderitemmdatestart', 'orderitem'); ?> <?php echo $this->form->getInput('orderitemmdateend', 'orderitem'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('orderitemstatus', 'orderitem'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('orderitemstatus', 'orderitem'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('orderitemcurrency', 'orderitem'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('orderitemcurrency', 'orderitem'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('orderitempricestart', 'orderitem'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('orderitempricestart', 'orderitem'); ?> <?php echo $this->form->getInput('orderitemspriceend', 'orderitem'); ?></div></li>
	</ul>
	<div>
		<div id="searchitemproduct"><input type="text" name="searchitemproductbox" id="searchitemproductbox" value="" /></div>
		<table id="selectitemproductsku" class="adminlist">
			<thead>
			<tr><th><?php echo JText::_('COM_CSVI_EXPORT_PRODUCT_SKU'); ?></th><th><?php echo JText::_('COM_CSVI_EXPORT_PRODUCT_NAME');?></th></tr>
			</thead>
		</table>
		<?php echo $this->form->getLabel('orderitemproduct', 'orderitem'); ?>
		<?php echo $this->form->getInput('orderitemproduct', 'orderitem'); ?>
	</div>
</fieldset>
<div class="clr"></div>