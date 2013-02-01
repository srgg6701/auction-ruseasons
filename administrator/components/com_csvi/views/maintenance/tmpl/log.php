<?php
/**
 * Maintenance log
 *
 * @package 	CSVI
 * @subpackage 	Maintenance
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: log.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
if ($this->logresult) {
	?>
	<table class="adminlist">
	<thead>
	<tr>
		<th class="title" colspan="3"><?php echo JText::_('COM_CSVI_'.$this->logresult['action_type']); ?></th>
	</tr>
	<tr>
		<th class="title">
		<?php echo JText::_('COM_CSVI_LOG_ACTION'); ?>
		</th>
		<th class="title">
		<?php echo JText::_('COM_CSVI_LOG_RESULT'); ?>
		</th>
		<th class="title">
		<?php echo JText::_('COM_CSVI_LOG_MESSAGE'); ?>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if (count($this->logresult['result']) > 0) {
		foreach ($this->logresult['result'] as $result => $log) { ?>
			<tr>
				<td align="center">
					<?php echo $log->total_result; ?>
				</td>
				<td>
					<?php echo $log->result; ?>
				</td>
				<td>
					<?php echo JText::_('COM_CSVI_'.$log->status); ?>
				</td>
			</tr>
		<?php }
	}
	else { ?>
		<tr><td colspan="3"><?php echo JText::_('COM_CSVI_NO_DETAILS_FOUND'); ?></td></tr>
	<?php }
	?>
	</tbody>
	</table>
	<form action="index.php" method="post" name="adminForm">
		<input type="hidden" name="option" value="com_csvi" />
		<input type="hidden" name="task" value="logdetails.logdetails" />
		<input type="hidden" name="run_id" value="<?php echo $this->logresult['run_id']; ?>" />
	</form>
<?php }
else {
	echo JText::sprintf('COM_CSVI_MAINTENANCE_RESULT_NO_LOG', JText::_('COM_CSVI_'.$this->operation[0].'_LABEL'));
	echo '<br />';
	echo '<br />';
	echo JText::_('COM_CSVI_NO_LOG_EXPLAIN');
}
?>
