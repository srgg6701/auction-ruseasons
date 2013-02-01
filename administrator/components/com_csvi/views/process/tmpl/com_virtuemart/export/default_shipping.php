<?php
/**
 * Export shopper page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_shipping.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_CSVI_EXPORT_SHOPPER_SHIPPING_OPTIONS'); ?></legend>
	<div id="export_fields">
		<table id="newfieldlist_replace" class="adminlist">
			<thead>
				<tr>
					<th class="title"><?php echo JText::_('COM_CSVI_SHOPPER_SHIPPING_PRICE_FROM'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_SHOPPER_SHIPPING_PRICE_TO'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_SHOPPER_SHIPPING_VALUE'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_ADD_FIELD'); ?></th>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<!-- Price from -->
					<td>
						<input type="text" class="replaceinput" name="_price_from" id="_price_from" value="" />
					</td>
					<!-- Price to -->
					<td>
						<input type="text" class="replaceinput" name="_price_to" id="_price_to" value="" />
					</td>
					<!-- Shipping fee -->
					<td>
						<input type="text" class="replaceinput" name="_fee" id="_fee" value="" />
					</td>
					<!-- Add -->
					<td>
						<?php echo JHTML::_('link', '#', JHtml::_('image', JURI::root().'administrator/components/com_csvi/assets/images/csvi_add_16.png', JText::_('COM_CSVI_ADD')), array('id' => 'addRow_shopper_shipping')); ?>
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		<table id="fieldslist_shopper_shipping" class="adminlist">
			<thead>
			<tr class="nodrag">
				<th class="title"><?php echo JText::_('COM_CSVI_SHOPPER_SHIPPING_PRICE_FROM'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_SHOPPER_SHIPPING_PRICE_TO'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_SHOPPER_SHIPPING_VALUE'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_DELETE'); ?></th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5"></td>
				</tr>
			</tfoot>
			<tbody>
				<?php
					$shopper_shipping_fields = $this->template->get('shopper_shipping_export_fields', '', array());
					if (isset($shopper_shipping_fields['_price_from'])) {
						$count = count($shopper_shipping_fields['_price_from']);
						for ($rows = 0; $rows < $count; $rows++) {
							$id = mt_rand();
							?>
							<tr>
								<td><input class="replaceinput" type="text" name="jform[shopper_shipping_export_fields][_price_from][]" value="<?php echo $shopper_shipping_fields['_price_from'][$rows]; ?>" /></td>
								<td><input class="replaceinput" type="text" name="jform[shopper_shipping_export_fields][_price_to][]" value="<?php echo $shopper_shipping_fields['_price_to'][$rows]; ?>" /></td>
								<td><input class="replaceinput" type="text" name="jform[shopper_shipping_export_fields][_fee][]" value="<?php echo $shopper_shipping_fields['_fee'][$rows]; ?>" /></td>
								<td><?php echo JHtml::_('link', 'index.php?option=com_csvi&view=export', JHtml::_('image', JURI::root().'/administrator/components/com_csvi/assets/images/csvi_delete_32.png', 'width="20" height="20" border="0" alt="'.JText::_('COM_CSVI_DELETE').'"'), 'onclick="jQuery(this).parents(\'tr\').remove(); jQuery(\'#fieldslist_shopper_shipping\').tableDnD(); return false;"'); ?></td>
							</tr>
							<?php
						}
					}
				?>
			</tbody>
		</table>
	</div>
</fieldset>

<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery("#fieldslist_shopper_shipping").tableDnD();
});

jQuery("#addRow_shopper_shipping").click(function() {
	var remove_image 	= '<img src="<?php echo JURI::root().'/administrator/components/com_csvi/assets/images/csvi_delete_32.png'; ?>" width="20" height="20" border="0" alt="<?php echo JText::_('COM_CSVI_DELETE'); ?>" />';
	var remove_link 	= '<a href="index.php?option=com_csvi&view=export" onclick="jQuery(this).parents(\'tr\').remove(); jQuery(\'#fieldslist_shopper_shipping\').tableDnD(); return false;">'+remove_image+'</a>';
	var from_value	 	= '<input class="replaceinput" type="text" name="jform[shopper_shipping_export_fields][_price_from][]" value="'+jQuery('#_price_from').val()+'" />';
	var to_value	 	= '<input class="replaceinput" type="text" name="jform[shopper_shipping_export_fields][_price_to][]" value="'+jQuery('#_price_to').val()+'" />';
	var fee_value	 	= '<input class="replaceinput" type="text" name="jform[shopper_shipping_export_fields][_fee][]" value="'+jQuery('#_fee').val()+'" />';
	var rows = jQuery("#fieldslist_shopper_shipping tbody").children().size();
	var tr = '<tr><td>'+from_value+'</td><td>'+to_value+'</td><td>'+fee_value+'</td><td>'+remove_link+'</td></tr>';
	var $table = jQuery("#fieldslist_shopper_shipping");
	var $tableBody = jQuery("tbody", $table);
	newRow = jQuery(tr).appendTo($tableBody);
	jQuery("#fieldslist_shopper_shipping").tableDnD();
	return false;
});
</script>
