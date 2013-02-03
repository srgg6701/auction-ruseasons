<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php if($this->values->filter) { ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
				<input type="text" name="search" id="acymailingsearch" value="<?php echo $this->escape($this->pageInfo->search);?>" class="inputbox" />
				<button class="btn button buttongo" onclick="this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
				<button class="btn button buttonreset" onclick="document.getElementById('acymailingsearch').value='';this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
			</td>
		</tr>
	</table>
<?php } ?>

	<table class="table table-striped table-hover" width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
		$nbcols = 4;
		if(!$this->values->show_colnumber) $nbcols--;
		if(!$this->values->show_senddate) $nbcols--;
		if(!$this->values->show_receiveemail) $nbcols--;

		if($this->values->show_headings) { ?>
		<thead>
			<tr>
				<?php if($this->values->show_colnumber) { ?>
				<td class="sectiontableheader<?php echo $this->values->suffix; ?>" align="center">
					<?php echo JText::_( 'ACY_NUM' );?>
				</td>
				<?php } ?>
				<td class="sectiontableheader<?php echo $this->values->suffix; ?>" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('JOOMEXT_SUBJECT').' ', 'a.subject', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</td>
				<?php if($this->values->show_senddate) { ?>
				<td class="sectiontableheader<?php echo $this->values->suffix; ?>" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('SEND_DATE').' ', 'a.senddate', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</td>
				<?php } ?>
				<?php if($this->values->show_receiveemail) { ?>
				<td class="sectiontableheader<?php echo $this->values->suffix; ?>" align="center">
				</td>
				<?php } ?>
			</tr>
		</thead>
	<?php } ?>
		<tfoot class="pagination">
			<tr>
				<td colspan="<?php echo $nbcols ?>" class="sectiontablefooter<?php echo $this->values->suffix; ?>" align="center">
					<?php echo $this->pagination->getPagesLinks(); ?>
				</td>
			</tr>
			<tr>
				<td colspan="<?php echo $nbcols ?>" class="sectiontablefooter<?php echo $this->values->suffix; ?>" align="right">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				$k = 1;
				for($i = 0,$a = count($this->rows);$i<$a;$i++){
					$row =& $this->rows[$i];
			?>
				<tr class="<?php echo "sectiontableentry$k".$this->values->suffix; ?>">
				<?php if($this->values->show_colnumber) { ?>
					<td align="center" class="numcolumn">
					<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
				<?php } ?>
					<td>
						<a <?php if($this->config->get('open_popup',1)){ echo 'class="modal" rel="{handler: \'iframe\', size: {x: '.intval($this->config->get('popup_width',750)).', y: '.intval($this->config->get('popup_height',550)).'}}"'; } ?> href="<?php echo acymailing_completeLink('archive&task=view&listid='.$this->list->listid.'-'.$this->list->alias.'&mailid='.$row->mailid.'-'.strip_tags($row->alias).$this->item,(bool)$this->config->get('open_popup',1)); ?>">
							<?php echo $row->subject; ?>
						</a>
						<?php if($this->access->frontEndManament){
							if(($this->config->get('frontend_modif',1) || ($row->userid == $this->my->id)) AND ($this->config->get('frontend_modif_sent',1) || empty($row->senddate))){	?>
							<span class="acyeditbutton"><a href="<?php echo acymailing_completeLink('newsletter&task=edit&mailid='.$row->mailid.'&listid='.$this->list->listid); ?>" title="<?php echo JText::_('ACY_EDIT',true) ?>" ><img class="icon16" src="<?php echo ACYMAILING_IMAGES ?>icons/icon-16-edit.png" alt="<?php echo JText::_('ACY_EDIT',true) ?>" /></a></span>
							<?php } if(!empty($row->senddate) && acymailing_isAllowed($this->config->get('acl_statistics_manage','all'))){ ?>
							<span class="acystatsbutton"><a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 590}}" href="<?php echo acymailing_completeLink('newsletter&task=stats&mailid='.$row->mailid.'&listid='.$this->list->listid,true); ?>"><img src="<?php echo ACYMAILING_IMAGES; ?>icons/icon-16-stats.png" alt="<?php echo JText::_('STATISTICS',true) ?>" /></a></span>
							<?php } ?>
						<?php }?>
					</td>
					<?php if($this->values->show_senddate) { ?>
					<td align="center" nowrap="nowrap">
						<?php echo acymailing_getDate($row->senddate,JText::_('DATE_FORMAT_LC3')); ?>
					</td>
					<?php } ?>
					<?php if($this->values->show_receiveemail) { ?>
					<td align="center" nowrap="nowrap" class="receiveviaemail">
						<input onclick="changeReceiveEmail(this.checked)" type="checkbox" name="receivemail[]" value="<?php echo $row->mailid ?>" id="receive_<?php echo $row->mailid ?>" /> <label for="receive_<?php echo $row->mailid ?>"><?php echo JText::_('RECEIVE_VIA_EMAIL'); ?></label>
					</td>
					<?php } ?>
				</tr>
			<?php
					$k = 3-$k;
				}
			?>
		</tbody>
	</table>
