<?php
/**
 * Log reader to read a log file and show it in a popup screen
 *
 * @package 	CSVI
 * @subpackage 	Log
 * @author	 	Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: logreader.php 2275 2013-01-03 21:08:43Z RolandD $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
if (empty($this->logdetails)) echo '<span class="error">'.sprintf(JText::_('COM_CSVI_NO_LOG_FOUND'), $this->logfile).'</span>';
else {
?>
<table class="adminlist">
	<thead>
		<tr>
			<th colspan="2"><?php echo JText::_('COM_CSVI_DETAILS'); ?></th>
		</tr>
	</thead>
	<tfoot>
	</tfoot>
	<tbody>
		<tr>
			<td><?php echo JText::_('COM_CSVI_DATE'); ?></td>
			<td><?php echo $this->logdetails['date']; ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_CSVI_SOFTWARE'); ?></td>
			<td><?php echo $this->logdetails['joomla']; ?></td>
		</tr>
	</tbody>
</table>
<table class="adminlist">
	<thead>
		<tr>
			<?php foreach ($this->logdetails['fields'] as $title) { ?>
				<th><?php echo JText::_('COM_CSVI_'.strtoupper(trim($title))); ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tfoot>
		<tr><td colspan="<?php echo count($this->logdetails['fields']); ?>"><?php echo sprintf(JText::_('COM_CSVI_LOG_LINES'), count($this->logdetails['entries'])); ?></td></tr>
	</tfoot>
	<tbody>
		<?php foreach ($this->logdetails['entries'] as $entry) { ?>
			<tr>
				<?php foreach ($entry as $value) { ?>
					<td><?php echo $value; ?></td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php } ?>
