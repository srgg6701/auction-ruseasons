/**
* CSVI JavaScript
*
* CSVI
* Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
*
* @package CSVI
* @version $Id: csvi.js 2275 2013-01-03 21:08:43Z RolandD $
 */

var Csvi = {
	// Retrieve the template types for the given component
	loadTemplateTypes: function() {
		var action = jQuery("#jform_options_action").val();
		var component = jQuery("#jform_options_component").val();
		jQuery.ajax({
			async: false,
			url: 'index.php',
			dataType: 'json',
			data: 'option=com_csvi&task=templatetypes.loadtemplatetypes&format=json&action='+action+'&component='+component,
			success: function(data) {
				jQuery('#jform_options_operation > option').remove();
				jQuery.each(data, function(value, name) {
					jQuery('#jform_options_operation').append(jQuery('<option></option>').val(value).html(name));
				})
			},
			error: function(data, status, statusText) {
				jAlert(statusText+'\r\n'+data.responseText);
			}
		});
	},

	getData: function(task) {
		var template_type = jQuery('#jformimport_type').val();
		var table_name = jQuery('#jformcustom_table_import').val();
		jQuery.ajax({
				async: false,
				url: 'index.php',
				dataType: 'json',
				data: 'option=com_csvi&view=export&task='+task+'&format=json&template_type='+template_type+'&table_name='+table_name,
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
	},

	/**
	 * Set the maintenance task
	 */
	setTask: function(task) {
		 document.adminForm.task.value = task;
	},

	createFolder: function(folder, element) {
		var spinner = jQuery('#'+element).html("<img src='/administrator/components/com_csvi/assets/images/csvi_ajax-loading.gif' />");
		jQuery.ajax({
			async: false,
			url: 'index.php',
			dataType: 'html',
			data: 'option=com_csvi&task=about.createfolder&format=raw&folder='+folder,
			success: function(data) {
				location.reload();
			},
			error: function(data, status, statusText) {
				jQuery('#'+element).html(Joomla.JText._('COM_CSVI_ERROR_CREATING_FOLDER'));
				jAlert(statusText+'\r\n'+data.responseText);
			}
		});
	},

	updateRowClass: function(table) {
		jQuery("table#"+table+" tr:even").addClass("row0");
		jQuery("table#"+table+" tr:odd").addClass("row1");
	},

	showSource: function(source) {
		switch (source) {
			case 'fromserver':
				jQuery('.importupload, .importftp, .importurl').parent().parent().hide();
				jQuery('.importserver').parent().parent().show();
				break;
			case 'fromurl':
				jQuery('.importupload, .importftp, .importserver').parent().parent().hide();
				jQuery('.importurl').parent().parent().show();
				break;
			case 'fromftp':
				jQuery('.importupload, .importserver, .importurl').parent().parent().hide();
				jQuery('.importftp').parent().parent().show();
				break;
			case 'fromupload':
				jQuery('.importserver, .importftp, .importurl').parent().parent().hide();
				jQuery('.importupload').parent().parent().show();
				break;
			case 'todownload':
			case 'toemail':
				jQuery('.exportftp').parent().parent().hide();
				jQuery('.exportlocalpath').parent().parent().hide();
				break;
			case 'tofile':
				jQuery('.exportftp').parent().parent().hide();
				jQuery('.exportlocalpath').parent().parent().show();
				break;
			case 'toftp':
				jQuery('.exportlocalpath').parent().parent().hide();
				jQuery('.exportftp').parent().parent().show();
				break;
		}
		return;
	},

	searchUser: function() {
		_timeout = null;
		jQuery("#selectuserid tbody").remove();
		jQuery("#selectuserid").append('<tbody><tr><td colspan="2"><div id="ajaxuserloading"><img src="/administrator/components/com_csvi/assets/images/csvi_ajax-loading.gif" /></div></td></tr></tbody>');
		var searchfilter = jQuery("input[name='searchuserbox']").val();
		var component = jQuery("#jform_options_component").val();
		jQuery.ajax({
			async: false,
			url: 'index.php',
			datatype: 'json',
			data: 'option=com_csvi&task=process.getuser&format=json&filter='+searchfilter+'&component='+component,
			success: function(data) {
				jQuery("#ajaxuserloading").remove();
				jQuery("#selectuserid tbody").remove();
				var options = [];
				var r = 0;
				options[++r] = '<tbody>';
				if (data.length > 0) {
					for (var i = 0; i < data.length; i++) {
						options[++r] = '<tr><td class="user_id">';
						options[++r] = data[i].user_id;
						options[++r] = '</td><td class="user_name">';
						options[++r] = data[i].user_name;
						options[++r] = '</td></tr>';
					}
				}
				options[++r] = '</tbody>';
				jQuery("#selectuserid").append(options.join(''));
				jQuery("table#selectuserid tr:even").addClass("row0");
				jQuery("table#selectuserid tr:odd").addClass("row1");
				jQuery('table#selectuserid tbody tr').click(function() {
					var user_id = jQuery(this).find('td.user_id').html();
					/* Check if the user ID is already in the select box */
					var existingvals = [];
					jQuery('select#jform_order_orderuser option').each(function() {
					    var optionval = jQuery(this).val();
					    if (optionval !== "") existingvals.push(optionval);
					});
					if (jQuery.inArray(user_id, existingvals) >= 0) {
						return;
					}
					else {
						var options = '<option value="'+user_id+'" selected="selected">'+jQuery(this).find('td.user_name').html()+'</option>';
						jQuery("select#jform_order_orderuser").append(options);
						jQuery("select#jform_order_orderuser option:eq(0)").attr("selected", false);
					}
				});
			},
			error: function(data, status, statusText) {
				jQuery("#ajaxproductloading").remove();
				jAlert(statusText+'\r\n'+data.responseText);
			}
		})
	},

	searchProduct: function() {
		_timeout = null;
		jQuery("#selectproductsku tbody").remove();
		jQuery("#selectproductsku").append('<tbody><tr><td colspan="2"><div id="ajaxproductloading"><img src="/administrator/components/com_csvi/assets/images/csvi_ajax-loading.gif" /></div></td></tr></tbody>');
		var searchfilter = jQuery("input[name='searchproductbox']").val();
		var component = jQuery("#jform_options_component").val();
		jQuery.ajax({
			async: false,
			url: 'index.php',
			datatype: 'json',
			data: 'option=com_csvi&task=process.getproduct&format=json&filter='+searchfilter+'&component='+component,
			success: function(data) {
				jQuery("#ajaxproductloading").remove();
				jQuery("#selectproductsku tbody").remove();
				var options = [];
				var r = 0;
				options[++r] = '<tbody>';
				if (data.length > 0) {
					for (var i = 0; i < data.length; i++) {
						options[++r] = '<tr><td class="product_sku">';
						options[++r] = data[i].product_sku;
						options[++r] = '</td><td class="product_name">';
						options[++r] = data[i].product_name;
						options[++r] = '</td></tr>';
					}
				}
				options[++r] = '</tbody>';
				jQuery("#selectproductsku").append(options.join(''));
				jQuery("table#selectproductsku tr:even").addClass("row0");
				jQuery("table#selectproductsku tr:odd").addClass("row1");
				jQuery('table#selectproductsku tbody tr').click(function() {
					var product_sku = jQuery(this).find('td.product_sku').html();
					/* Check if the product ID is already in the select box */
					var existingvals = [];
					jQuery('select#jform_order_orderproduct option').each(function() {
					    var optionval = jQuery(this).val();
					    if (optionval !== "") existingvals.push(optionval);
					});
					if (jQuery.inArray(product_sku, existingvals) >= 0) {
						return;
					}
					else {
						var options = '<option value="'+product_sku+'" selected="selected">'+jQuery(this).find('td.product_name').html()+'</option>';
						jQuery("select#jform_order_orderproduct").append(options);
						jQuery("select#jform_order_orderproduct option:eq(0)").attr("selected", false);
					}
				});
			},
			error: function(data, status, statusText) {
				jQuery("#ajaxproductloading").remove();
				jAlert(statusText+'\r\n'+data.responseText);
			}
		})
	},

	searchItemProduct: function() {
		_timeout = null;
		jQuery("#selectitemproductsku tbody").remove();
		jQuery("#selectitemproductsku").append('<tbody><tr><td colspan="2"><div id="ajaxproductloading"><img src="/administrator/components/com_csvi/assets/images/csvi_ajax-loading.gif" /></div></td></tr></tbody>');
		var searchfilter = jQuery("input[name='searchitemproductbox']").val();
		jQuery.ajax({
			async: false,
			url: 'index.php',
			datatype: 'json',
			data: 'option=com_csvi&task=process.getitemproduct&format=json&filter='+searchfilter,
			success: function(data) {
				jQuery("#ajaxproductloading").remove();
				jQuery("#selectitemproductsku tbody").remove();
				var options = [];
				var r = 0;
				options[++r] = '<tbody>';
				if (data.length > 0) {
					for (var i = 0; i < data.length; i++) {
						options[++r] = '<tr><td class="product_sku">';
						options[++r] = data[i].product_sku;
						options[++r] = '</td><td class="product_name">';
						options[++r] = data[i].product_name;
						options[++r] = '</td></tr>';
					}
				}
				options[++r] = '</tbody>';
				jQuery("#selectitemproductsku").append(options.join(''));
				jQuery("table#selectitemproductsku tr:even").addClass("row0");
				jQuery("table#selectitemproductsku tr:odd").addClass("row1");
				jQuery('table#selectitemproductsku tbody tr').click(function() {
					var product_sku = jQuery(this).find('td.product_sku').html();
					// Check if the product ID is already in the select box
					var existingvals = [];
					jQuery('select#jform_orderitem_orderitemproduct option').each(function() {
					    var optionval = jQuery(this).val();
					    if (optionval !== "") existingvals.push(optionval);
					});
					if (jQuery.inArray(product_sku, existingvals) >= 0) {
						return;
					}
					else {
						var options = '<option value="'+product_sku+'" selected="selected">'+jQuery(this).find('td.product_name').html()+'</option>';
						jQuery("select#jform_orderitem_orderitemproduct").append(options);
						jQuery("select#jform_orderitem_orderitemproduct option:eq(0)").attr("selected", false);
					}
				});
			},
			error: function(data, status, statusText) {
				jQuery("#ajaxproductloading").remove();
				jAlert(statusText+'\r\n'+data.responseText);
			}
		})
	},

	loadExportSites: function(site, selected) {
		switch (site) {
			case 'xml':
			case 'html':
				jQuery.ajax({
					async: false,
					url: 'index.php',
					dataType: 'json',
					data: 'option=com_csvi&task=process.loadsites&format=json&exportsite='+site+'&selected='+selected,
					success: function(data) {
						if (data) {
							jQuery('#jform_general_export_site').parent().html(data);
						}
					},
					error: function(data, status, statusText) {
						jAlert(statusText+'\r\n'+data.responseText);
					}
				});
				jQuery('#jform_general_export_site').parent().parent().show();
				break;
			default:
				jQuery('#jform_general_export_site').parent().parent().hide();
				break;
		}
	},
	
	loadCategoryTree: function (lang, component) {
		jQuery.ajax({
			async: false,
			url: 'index.php',
			dataType: 'json',
			data: 'option=com_csvi&task=process.loadcategorytree&format=json&language='+lang+'&component='+component,
			success: function(data) {
				if (data) {
					jQuery('#jform_product_product_categories > option').remove();
					jQuery.each(data, function(key, item) {
						jQuery('#jform_product_product_categories').append(jQuery('<option></option>').val(item.value).html(item.text));
					})
					jQuery("#jform_product_product_categories > option:first").attr("selected", "true");
				}
			},
			error: function(data, status, statusText) {
				jAlert(statusText+'\r\n'+data.responseText);
			}
		});
	} 
}

var CsviMaint = {
	loadOptions: function(option) {
		jQuery('#optionfield').empty();
		switch (option) {
			case 'emptydatabase':
				message = Joomla.JText._('COM_CSVI_CONFIRM_DB_DELETE');
				jConfirm(message, Joomla.JText._('COM_CSVI_'+option+'_LABEL'), function(r) {
					if (r) {
						Csvi.setTask('maintenance.'+option);
					}
					else {
						Csvi.setTask('maintenance.maintenance');
					}
				})
				break;
			case 'removecsvitables':
				message = Joomla.JText._('COM_CSVI_CONFIRM_CSVITABLES_DELETE');
				jConfirm(message, option, function(r) {
					if (r) {
						Csvi.setTask('maintenance.'+option);
					}
					else {
						Csvi.setTask('maintenance.maintenance');
					}
				})
				break;
			case 'backuptemplates':
				jQuery('#optionfield').empty().append('<label>'+Joomla.JText._('COM_CSVI_CHOOSE_BACKUP_LOCATION_LABEL')+'</label><input type="text" name="backup_location" id="backup_location" value="" size="120" />');
				Csvi.setTask('maintenance.'+option);
				break;
			case 'restoretemplates':
				jQuery('#optionfield').empty().append('<label>'+Joomla.JText._('COM_CSVI_CHOOSE_RESTORE_FILE_LABEL')+'</label><input type="file" name="restore_file" id="file" size="120" />');
				Csvi.setTask('maintenance.'+option);
				break;
			case 'icecatindex':
				jQuery.ajax({
					async: false,
					url: 'index.php',
					dataType: 'html',
					data: 'option=com_csvi&task=maintenance.icecatsettings&format=raw',
					success: function(data) {
						jQuery('#optionfield').empty().append(data);
					},
					error: function(data, status, statusText) {
						jQuery('#optionfield').empty();
						jAlert(statusText+'\r\n'+data.responseText);
					}
				});
				Csvi.setTask('maintenance.'+option);
				break;
			case 'sortcategories':
				jQuery.ajax({
					async: false,
					url: 'index.php',
					dataType: 'html',
					data: 'option=com_csvi&task=maintenance.sortcategories&format=raw',
					success: function(data) {
						jQuery('#optionfield').empty().append(data);
					},
					error: function(data, status, statusText) {
						jQuery('#optionfield').empty();
						jAlert(statusText+'\r\n'+data.responseText);
					}
				});
				Csvi.setTask('maintenance.'+option);
				break;
			default:
				Csvi.setTask('maintenance.'+option);
				break;
		}
	},
	
	loadOperation: function(component) {
		jQuery('#optionfield').empty();
		jQuery.ajax({
			async: false,
			url: 'index.php',
			dataType: 'html',
			data: 'option=com_csvi&task=maintenance.operations&component='+component+'&format=raw',
			success: function(data) {
				jQuery('#operation').html(data);
			},
			error: function(data, status, statusText) {
				jQuery('#operation').empty();
				jAlert(statusText+'\r\n'+data.responseText);
			}
		});
	}

}

// Set the live events
var _timeout = null;
var notallowedkeys = [9, 10, 16, 17, 18, 20, 27, 37, 38, 39, 40, 92, 93];
jQuery("#searchuser, #searchproduct, #searchitemproduct").live('keyup', function(e) {
	if (jQuery.inArray(e.keyCode, notallowedkeys) >= 0) {
		return;
	}
	else {
		if(_timeout != null) {
			clearTimeout(_timeout); _timeout = null;
		}
		var callfunc = jQuery(this)[0].id;
		switch (callfunc) {
			case 'searchuser':
				_timeout = setTimeout('Csvi.searchUser()', 1000);
				break;
			case 'searchproduct':
				_timeout = setTimeout('Csvi.searchProduct()', 1000);
				break;
			case 'searchitemproduct':
				_timeout = setTimeout('Csvi.searchItemProduct()', 1000);
				break;
		}

	}
})