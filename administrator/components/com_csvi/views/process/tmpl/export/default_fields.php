<?php
/**
 * Export page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_fields.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>

<!-- See if we need to show the custom tables for export -->
<?php if ($this->form->getValue('operation', 'options') == 'customexport') { ?>
	<fieldset class="adminform" id="field_customtables">
		<legend><?php echo JText::_('COM_CSVI_CUSTOM_TABLE_EXPORT'); ?></legend>
		<?php echo $this->form->getInput('custom_table'); ?>
	</fieldset>
<?php } ?>

<fieldset>
	<div id="quickadd-buttons">
		<input class="quickadd-button" type="button" id="quickadd-button" value="<?php echo JText::_('COM_CSVI_QUICK_ADD_FIELDS'); ?>">
	</div>
	<br class="clear" />
	<legend><?php echo JText::_('COM_CSVI_SELECT_EXPORT_FIELDS'); ?></legend>
	<div id="export_fields">
		<table id="newfieldlist" class="adminlist">
			<thead>
				<tr>
					<th class="title"><?php echo JText::_('COM_CSVI_ADD_FIELD'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_FIELD_NAME'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_COLUMN_HEADER'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_DEFAULT_VALUE'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_PROCESS_FIELD'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_COMBINE_FIELD'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_SORT_FIELD'); ?></th>
					<th class="title"><?php echo JText::_('COM_CSVI_REPLACEMENT_FIELD'); ?></th>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8" />
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<!-- Add field -->
					<td class="center">
						<?php echo JHtml::_('link', '#', JHtml::_('image', JURI::root().'administrator/components/com_csvi/assets/images/csvi_add_16.png', JText::_('COM_CSVI_ADD')), array('id' => 'addRow')); ?>
					</td>
					<!-- Field name -->
					<td>
						<?php echo JHtml::_('select.genericlist', $this->templatefields, '_field_name', null, 'value', 'text', null, '_field_name'); ?>
					</td>
					<!-- Column header -->
					<td>
						<input type="text" name="_column_header" id="_column_header" value="" />
					</td>
					<!-- Default value -->
					<td id="newfield_defaultvalue">
						<input type="text" name="_default_value" id="_default_value" value="" size="55" />
					</td>
					<!-- Process field -->
					<td id="newfield_processfield">
						<?php echo CsviHelper::getYesNo('_process_field', '1', '', '_process_field_default'); ?>
					</td>
					<!-- Combine field -->
					<td id="newfield_combinefield">
						<?php echo CsviHelper::getYesNo('_combine_field', '0', '', '_combine_field_default'); ?>
					</td>
					<!-- Sort field -->
					<td id="newfield_sortfield">
						<?php echo CsviHelper::getYesNo('_sort_field', '0', '', '_sort_field_default'); ?>
					</td>
					<!-- Replacement field -->
					<td id="newfield_replacementfield">
						<?php echo JHtml::_('select.genericlist', $this->replacements, '_replace_field', '', 'value', 'text', '', '_replace_field_default'); ?>
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		<table id="fieldslist" class="adminlist">
			<thead>
			<tr class="nodrag">
				<th class="title"><?php echo JText::_('COM_CSVI_FIELD_NAME'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_COLUMN_HEADER'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_DEFAULT_VALUE'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_PROCESS_FIELD') ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_COMBINE_FIELD') ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_SORT_FIELD') ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_REPLACEMENT_FIELD'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_DELETE'); ?></th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8"></td>
				</tr>
			</tfoot>
			<tbody>
				<?php
					$export_fields = $this->template->get('export_fields');
					if (isset($export_fields['_selected_name'])) {
						for ($rows = 0; $rows < count($export_fields['_selected_name']); $rows++) {
							$id = mt_rand();
							?>
							<tr>
								<td><input type="text" name="jform[export_fields][_selected_name][]" value="<?php echo $export_fields['_selected_name'][$rows]; ?>" readonly="readonly" size="35" /></td>
								<td><input class="column_header_input" type="text" name="jform[export_fields][_column_header][]" value="<?php echo $export_fields['_column_header'][$rows]; ?>" /></td>
								<td><input type="text" name="jform[export_fields][_default_value][]" value="<?php echo $export_fields['_default_value'][$rows]; ?>" size="75"/></td>
								<td><?php echo CsviHelper::getYesNo('jform[export_fields][_process_field]['.$rows.']', $export_fields['_process_field'][$rows], '', '_process_field'.$id); ?>
								<td><?php if (!array_key_exists('_combine_field', $export_fields)) $export_fields['_combine_field'] = array(); echo CsviHelper::getYesNo('jform[export_fields][_combine_field]['.$rows.']', $export_fields['_combine_field'][$rows], '', '_combine_field'.$id); ?></td>
								<td><?php if (!array_key_exists('_sort_field', $export_fields)) $export_fields['_sort_field'] = array(); echo CsviHelper::getYesNo('jform[export_fields][_sort_field]['.$rows.']', $export_fields['_sort_field'][$rows], '', '_sort_field'.$id); ?></td>
								<td><?php if (!array_key_exists('_replace_field', $export_fields)) $export_fields['_replace_field'] = array(); if (!isset($export_fields['_replace_field'][$rows])) $export_fields['_replace_field'][$rows] = ''; echo JHtml::_('select.genericlist', $this->replacements, 'jform[export_fields][_replace_field]['.$rows.']', '', 'value', 'text', $export_fields['_replace_field'][$rows], '_replace_field_'.$id); ?></td>
								<td class="center"><?php echo JHtml::_('link', 'index.php?option=com_csvi&view=export', JHtml::_('image', JURI::root().'/administrator/components/com_csvi/assets/images/csvi_delete_32.png', 'width="20" height="20" border="0" alt="'.JText::_('COM_CSVI_DELETE').'"'), 'onclick="jQuery(this).parents(\'tr\').remove(); jQuery(\'#fieldslist\').tableDnD(); return false;"'); ?></td>
							</tr>
							<?php
						}
					}
				?>
			</tbody>
		</table>
	</div>
</fieldset>

<!-- The Quick Add form -->
<div id="quickadd-form" title="<?php echo JText::_('COM_CSVI_QUICK_ADD_FIELDS'); ?>">
	<fieldset>
	<table class="adminlist" id="quickadd-table">
		<tbody>
			<?php
			foreach ($this->templatefields as $fieldname) {
				?><tr><td><input type="checkbox" name="quickfields" value="<?php echo $fieldname->value; ?>" /></td><td class="addfield"><?php echo $fieldname->text; ?></td></tr><?php
			}
			?>
		</tbody>
	</table>
	</fieldset>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	Csvi.showSource('todownload');
    jQuery('#fieldslist').tableDnD();
});

// Add new field to fields list
jQuery("#addRow").click(function() {
	var remove_image 	= '<img src="<?php echo JURI::root().'/administrator/components/com_csvi/assets/images/csvi_delete_32.png'; ?>" width="20" height="20" border="0" alt="<?php echo JText::_('COM_CSVI_DELETE'); ?>" />';
	var remove_link 	= '<a href="index.php?option=com_csvi&view=export" onclick="jQuery(this).parents(\'tr\').remove(); jQuery(\'#fieldslist\').tableDnD(); return false;">'+remove_image+'</a>';
	var field_name	 	= '<input type="text" name="jform[export_fields][_selected_name][]" value="'+jQuery('#_field_name').val()+'" readonly="readonly" size="35" />';
	var column_header 	= '<input class="column_header_input" type="text" name="jform[export_fields][_column_header][]" value="'+jQuery('#_column_header').val()+'" />';
	var default_value	= '<input type="text" name="jform[export_fields][_default_value][]" value="'+jQuery('#_default_value').val()+'" size="75"/>';
	// Get the numer of exiting rows
	var rows = jQuery("#fieldslist tbody").children().size();
	// Build the replacement dropdown
	var replace_value	= '<select id="_replace_field'+rows+'" name="jform[export_fields][_replace_field]['+rows+']"></select>';
	
	if (jQuery("#_process_field_default").val() == 1) {
		var process_link		= '<select id="_process_field'+rows+'" name="jform[export_fields][_process_field]['+rows+']"><option value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option selected="selected" value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
	}
	else {
		var process_link		= '<select id="_process_field'+rows+'" name="jform[export_fields][_process_field]['+rows+']"><option selected="selected" value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
	}
	if (jQuery("#_combine_field_default").val() == "1") {
		var combine_link		= '<select id="_combine_field'+rows+'" name="jform[export_fields][_combine_field]['+rows+']"><option value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option selected="selected" value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
	}
	else {
		var combine_link		= '<select id="_combine_field'+rows+'" name="jform[export_fields][_combine_field]['+rows+']"><option selected="selected" value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
	}
	if (jQuery("_sort_field_default").val() == "1") {
		var sort_link		= '<select id="_sort_field'+rows+'" name="jform[export_fields][_sort_field]['+rows+']"><option value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option selected="selected" value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
	}
	else {
		var sort_link		= '<select id="_sort_field'+rows+'" name="jform[export_fields][_sort_field]['+rows+']"><option selected="selected" value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
	}
	// Add the table row
	addTableRow(field_name, column_header, default_value, process_link, combine_link, sort_link, replace_value, remove_link);

	// Fill the new replacement dropdown with the options
	var optionValues = '';
	jQuery('#_replace_field_default').children('option').map(function(i, elem){
		optionValues += '<option value='+elem.value+'>'+elem.text+'</option>';
	});
	// Add the options
	jQuery('#_replace_field'+rows).append(optionValues);
	// Set the selected option
	jQuery('#_replace_field'+rows).val(jQuery('#_replace_field_default option:selected').val());
	return false;
});

function getData(task) {
	var component = jQuery('#jform_options_component').val();
	var template_type = jQuery('#jform_options_operation').val();
	var table_name = jQuery('#jform_custom_table').val();
	jQuery.ajax({
			async: false,
			url: 'index.php',
			dataType: 'json',
			data: 'option=com_csvi&task=process.'+task+'&format=json&template_type='+template_type+'&table_name='+table_name+'&component='+component,
			success: function(data) {
				switch (task) {
					case 'loadtables':
						loadTables(data);
						break;
					case 'loadfields':
						loadFields(data);
						break;
				}
			},
			error:function (xhr, ajaxOptions, thrownError){
				jAlert(thrownError);
            }
	});
}

function loadTables(data) {
	if (data) {
		var optionsValues = '<select id="jformcustom_table_export" name="jform[custom_table_export]">';
		for (var i = 0; i < data.length; i++) {
				optionsValues += '<option value="' + data[i] + '">' + data[i] + '</option>';
		};
		optionsValues += '</select>';
		jQuery('#jformcustom_table_export').replaceWith(optionsValues);
	}
}
function loadFields(data) {
	if (data) {
		if (data.length > 0) {
			var optionsValues = '';
			var trValues = '';
			for (var i = 0; i < data.length; i++) {
				optionsValues += '<option value="' + data[i] + '">' + data[i] + '</option>';
				trValues += '<tr><td><input type="checkbox" name="quickfields" value="' + data[i] + '" /></td><td class="addfield">' + data[i] + '</td></tr>';
			};
			jQuery('#_field_name').replaceWith('<select id="_field_name" name="field[_field_name]">'+optionsValues+'</select>');
			jQuery('#fieldslist tbody').children().remove();
			jQuery('#_field_name_replace').replaceWith('<select id="_field_name_replace" name="_field_name_replace">'+optionsValues+'</select>');
			jQuery('#fieldslist_replace tbody').children().remove();
			jQuery('#quickadd-table tbody').replaceWith('<tbody>'+trValues+'</tbody>');
		}
	};
}

/**
 * Method Description
 *
 * @copyright
 * @author
 * @todo 		Check jQuery when maxHeight function works !!
 * @see
 * @access
 * @param
 * @return
 * @since
 */
 jQuery(function() {
		jQuery("#quickadd-form").dialog({
			autoOpen: false,
			height: 600,
			width: 350,
			modal: true,
			buttons: {
				"<?php echo JText::_('COM_CSVI_CHECK_ALL_FIELDS'); ?>": function() {
					jQuery("input[@name='quickfields'][type='checkbox']").each(function() {
						jQuery(this).attr('checked', 'true');
					});
				},
				"<?php echo JText::_('COM_CSVI_UNCHECK_ALL_FIELDS'); ?>": function() {
					jQuery("input[@name='quickfields'][type='checkbox']").each(function() {
						jQuery(this).removeAttr('checked');
					});
				},
				"<?php echo JText::_('COM_CSVI_ADD_FIELDS'); ?>": function() {
					var column_header 	= '<input class="column_header_input" type="text" name="jform[export_fields][_column_header][]" value="" />';
					var default_value	= '<input type="text" name="jform[export_fields][_default_value][]" value="" size="75"/>';
					var remove_image 	= '<img src="<?php echo JURI::root().'/administrator/components/com_csvi/assets/images/csvi_delete_32.png'; ?>" width="20" height="20" border="0" alt="<?php echo JText::_('COM_CSVI_DELETE'); ?>" />';
					var remove_link 	= '<a href="index.php?option=com_csvi&view=export" onclick="jQuery(this).parents(\'tr\').remove(); jQuery(\'#fieldslist\').tableDnD(); return false;">'+remove_image+'</a>';
					var optionValues = '';
					jQuery('#_replace_field_default').children('option').map(function(i, elem){
						optionValues += '<option value='+elem.value+'>'+elem.text+'</option>';
					});
					
					jQuery("input[@name='quickfields'][type='checkbox']").each(function() {
						if (jQuery(this).is(':checked')) {
							var field_name = '<input type="text" name="jform[export_fields][_selected_name][]" value="'+jQuery(this).val()+'" readonly="readonly" size="35" />';
							var rows = jQuery("#fieldslist tbody").children().size();
							var process_link = '<select id="_process_field'+rows+'" name="jform[export_fields][_process_field]['+rows+']"><option value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option selected="selected" value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
							var combine_link = '<select id="_combine_field'+rows+'" name="jform[export_fields][_combine_field]['+rows+']"><option selected="selected" value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
							var sort_link = '<select id="_sort_field'+rows+'" name="jform[export_fields][_sort_field]['+rows+']"><option selected="selected" value="0"><?php echo JText::_('COM_CSVI_NO'); ?></option><option value="1"><?php echo JText::_('COM_CSVI_YES'); ?></option></select>';
							var replace_value = '<select id="_replace_field'+rows+'" name="jform[export_fields][_replace_field]['+rows+']"></select>';
							addTableRow(field_name, column_header, default_value, process_link, combine_link, sort_link, replace_value,remove_link);
							// Add the options
							jQuery('#_replace_field'+rows).append(optionValues);
							jQuery(this).attr('checked', false);
						}
					});
					jQuery(this).dialog("close");
				},
				Cancel: function() {
					jQuery(this).dialog("close");
				}
			},
			close: function() {

			}
		});

		jQuery( "#quickadd-button" )
			.click(function() {
				jQuery( "#quickadd-form" ).dialog( "open" );
			});

});

// Selects a field in the quick add list when user clicks on the name only
jQuery("addfield").click(function() {
	var selectbox = jQuery(this).parent().children('td').children('input');
	if (jQuery(selectbox).attr('checked')) {
		jQuery(selectbox).attr('checked', false);
	}
	else {
		jQuery(selectbox).attr('checked', true);
	}
});

function addTableRow(field_name, column_header, default_value, process_link, combine_link, sort_link, replace_value, remove_link) {
	var tr = '<tr><td>'+field_name+'</td><td>'+column_header+'</td><td>'+default_value+'</td><td>'+process_link+'</td><td>'+combine_link+'</td><td>'+sort_link+'</td><td>'+replace_value+'</td><td class="center">'+remove_link+'</td></tr>';
	var table = jQuery("#fieldslist");
	var tableBody = jQuery("tbody", table);
	newRow = jQuery(tr).appendTo(tableBody);
	jQuery("#fieldslist").tableDnD();
}
</script>
