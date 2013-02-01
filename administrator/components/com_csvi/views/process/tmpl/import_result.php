<?php
/**
 * Import result file
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: import_result.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
if ($this->logresult) {
	$jinput = JFactory::getApplication()->input;
	?>
	<table id="importlog" class="adminlist">
		<thead>
			<tr>
				<th colspan="4" class="message"><?php echo JText::sprintf('COM_CSVI_RESULTS_FOR', $this->logresult['file_name']).' <br />'.$this->runtime; ?></th>
			</tr>
			<tr>
				<th class="title" width="5%">
				<?php echo JText::_('COM_CSVI_TOTAL'); ?>
				</th>
				<th class="title">
				<?php echo JText::_('COM_CSVI_RESULT'); ?>
				</th>
				<th class="title">
				<?php echo JText::_('COM_CSVI_STATUS'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<?php
					// Show debug log
					echo JHtml::_('link', JRoute::_('index.php?option=com_csvi&view=logdetails&run_id='.$jinput->get('run_id', 0, 'int')), JText::_('COM_CSVI_SHOW_FULL_LOG'));
					echo ' | ';
					// Show view debug log
					if (!empty($this->logresult['debugview'])) {
						echo $this->logresult['debugview'];
						echo ' | ';
					}
					// Show download debug log
					echo $this->logresult['debug'];
					?>
				</td>
			</tr>
		</tfoot>
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
			<tr><td colspan="3"><?php echo JText::_('COM_CSVI_NO_RESULTS_FOUND'); ?></td></tr>
		<?php } ?>
		</tbody>
	</table>
	<script type="text/javascript">
		Csvi.updateRowClass('importlog');
	</script>
<?php }
else {
	echo JText::_('COM_CSVI_IMPORT_FINISHED_NO_LOG_STORE');
	echo '<br />';
	echo $this->runtime;
	echo '<br />';
	echo '<br />';
	echo JText::_('COM_CSVI_NO_LOG_EXPLAIN');
}?>
