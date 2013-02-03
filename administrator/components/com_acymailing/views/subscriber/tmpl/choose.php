<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="iframedoc"></div>

<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=subscriber" method="post" name="adminForm" id="adminForm" >
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->escape($this->pageInfo->search);?>" class="text_area" onchange="document.adminForm.submit();" />
				<button class="btn" onclick="this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
				<button class="btn" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
			</td>
		</tr>
	</table>

	<table class="adminlist table table-striped table-hover" cellpadding="1">
		<thead>
			<tr>
				<th class="title titlenum">
					<?php echo JText::_( 'ACY_NUM' ); ?>
				</th>
				<th class="title">
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',   JText::_('JOOMEXT_NAME'), 'a.name', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',   JText::_('JOOMEXT_EMAIL'), 'a.email',$this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titleid">
					<?php echo JHTML::_('grid.sort',   JText::_('USER_ID'), 'a.userid',$this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titleid">
					<?php echo JHTML::_('grid.sort',   JText::_('ACY_ID'), 'a.subid', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->pagination->getListFooter(); ?>
					<?php echo $this->pagination->getResultsCounter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				$k = 0;

				for($i = 0,$a = count($this->rows);$i<$a;$i++){
					$row =& $this->rows[$i];

			?>
				<tr class="<?php echo "row$k"; ?>" style="cursor:pointer" onclick="window.top.affectUser(<?php echo strip_tags(intval($row->userid));?>,'<?php echo addslashes(strip_tags($row->name)); ?>','<?php echo addslashes(strip_tags($row->email)); ?>'); acymailing_js.closeBox(true);">
					<td align="center">
					<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td class="acytdcheckbox"></td>
					<td>
						<?php echo $row->name; ?>
					</td>
					<td>
						<?php echo $row->email; ?>
					</td>
					<td align="center">
						<?php if(!empty($row->userid)){
							$text = JText::_('ACY_USERNAME').' : <b>'.$row->username;
							$text .= '</b><br/>'.JText::_('USER_ID').' : <b>'.$row->userid.'</b>';
							echo acymailing_tooltip($text,$row->username,'',$row->userid);} ?>
					</td>
					<td align="center">
						<?php echo $row->subid; ?>
					</td>
				</tr>
			<?php
					$k = 1-$k;
				}
			?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="choose" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
	<?php if(JRequest::getString('onlyreg')){ ?><input type="hidden" name="onlyreg" value="1" /><?php } ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
