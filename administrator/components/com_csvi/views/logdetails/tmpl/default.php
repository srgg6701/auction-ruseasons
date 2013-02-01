<?php
/**
 * Details for a log entry
 *
 * @package 	CSVI
 * @subpackage 	Log
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_csvi'); ?>" method="post" name="adminForm">
	<table class="adminlist">
		<thead>
		<tr>
			<th colspan="2"><?php echo JText::_('COM_CSVI_LOG_DETAILS'); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><?php echo JText::_('COM_CSVI_TEMPLATE_TYPE'); ?></td><td><?php echo JText::_('COM_CSVI_'.strtoupper($this->logresult['action_type'])); ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_CSVI_FILE_NAME'); ?></td><td><?php echo $this->logresult['file_name']; ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_CSVI_RECORDS_PROCESSED'); ?></td><td><?php echo $this->logresult['total_records']; ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_CSVI_RUN_CANCELLED'); ?></td><td><?php echo ($this->logresult['run_cancelled']) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO'); ?></td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_CSVI_DEBUG_LOG'); ?></td><td><div><?php echo $this->logresult['debug']; ?></div>
				<?php
					if (!empty($this->logresult['debugview'])) {
						echo '<div>'.$this->logresult['debugview'].'</div>';
					}
				?>
			</td>
		</tr>
		</tbody>
	</table>
	<table id="loglines" class="adminlist">
		<thead>
		<tr>
			<th colspan="3"><?php echo JText::_('COM_CSVI_LOG_STATISTICS'); ?></th>
		</tr>
		</thead>
		<tbody>
			<?php
			if (empty($this->logresult['result'])) { ?>
				<tr><td><?php echo JText::_('COM_CSVI_NO_DETAILS_FOUND'); ?></td></tr>
			<?php }
			else {
				foreach ($this->logresult['result'] as $result => $log) { ?>
					<tr>
						<td align="center">
							<?php echo $log->total_result; ?>
						</td>
						<td>
							<?php echo $log->result; ?>
						</td>
						<td>
							<?php echo JText::_('COM_CSVI_'.strtoupper($log->status)); ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<!--  Add some filters -->
		<div id="filterbox">
			<?php echo JText::_('COM_CSVI_LOGDETAILS_FILTER'); ?>
			<?php echo $this->list['actions']; ?>
			<?php echo $this->list['results']; ?>
			<input type="submit" onclick="this.form.submit();" value="<?php echo JText::_('COM_CSVI_LOGDETAILS_GO'); ?>" />
			<input type="submit" onclick="jQuery('#filter_action, #filter_result').val('');" value="<?php echo JText::_('COM_CSVI_LOGDETAILS_RESET'); ?>" />
			<div class="resultscounter"><?php echo $this->pagination->getResultsCounter(); ?></div>
		</div>
		<table id="loglines" class="adminlist">
			<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_CSVI_LOG_LINE', 'd.line',  $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_CSVI_LOG_ACTION', 'd.status',  $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_CSVI_LOG_RESULT', 'd.result',  $listDirn, $listOrder); ?>
				</th>
				<th class="title">
				<?php echo JText::_('COM_CSVI_LOG_MESSAGE'); ?>
				</th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			if ($this->logmessage) {
				foreach ($this->logmessage as $key => $log) { ?>
					<tr>
						<td>
							<?php echo $log->line; ?>
						</td>
						<td>
							<?php echo JText::_('COM_CSVI_'.strtoupper($log->status)); ?>
						</td>
						<td>
							<?php echo JText::_('COM_CSVI_'.strtoupper($log->result)); ?>
						</td>
						<td>
							<?php echo nl2br($log->description); ?>
						</td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
		<?php } ?>
	</table>
	<input type="hidden" name="task" value="logdetails.display" />
	<input type="hidden" name="run_id" value="<?php echo $this->run_id; ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</form>
<script type="text/javascript">
	Csvi.updateRowClass('loglines');
</script>
