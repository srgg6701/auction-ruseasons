<?php
/**
 * Custom tables page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_custom_tables.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<table class="adminlist">
	<thead>
		<tr><th class="selectcol"></th><th><?php echo JText::_('COM_CSVI_TABLE_NAME'); ?></th></tr>
	</thead>
	<tbody>
	<?php
	$tables = $this->form->getValue('tables');
	if (!is_null($tables)) $selected = $tables->tablelist;
	else $selected = array();

	// Check if the selected value is an array
	if (!is_array($selected)) $selected = array($selected);

	foreach ($this->tablelist as $table) {
		if (in_array($table, $selected)) $sel = 'checked="checked"';
		else $sel = '';
		?><tr><td><input type="checkbox" name="jform[tables][tablelist][]" value="<?php echo $table; ?>" <?php echo $sel; ?> /></td><td><?php echo $table; ?></td></tr><?php
	}
	?>
	</tbody>
</table>
<div class="clr"></div>