<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content" >
<div id="iframedoc"></div>
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=stats" method="post" name="adminForm" id="adminForm" >
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->escape($this->pageInfo->search);?>" class="text_area" />
				<button class="btn" onclick="document.adminForm.limitstart.value=0;this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
				<button class="btn" onclick="document.adminForm.limitstart.value=0;document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
			</td>
		</tr>
	</table>

	<table class="adminlist table table-striped table-hover" cellpadding="1">
		<thead>
			<tr>
				<th class="title titlenum">
					<?php echo JText::_( 'ACY_NUM' );?>
				</th>
				<th class="title titlebox">
					<input type="checkbox" name="toggle" value="" onclick="acymailing_js.checkAll(this);" />
				</th>
				<th class="title titledate">
					<?php echo JHTML::_('grid.sort',   JText::_( 'SEND_DATE' ), 'a.senddate', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_( 'JOOMEXT_SUBJECT'), 'b.subject', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',   JText::_( 'OPEN' ), 'a.openunique', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<?php if(acymailing_level(1)){?>
				<th class="title">
					<?php echo JHTML::_('grid.sort',   JText::_( 'CLICKED_LINK' ), 'a.clickunique', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<?php } ?>
				<th class="title">
						<?php echo JHTML::_('grid.sort',   JText::_( 'UNSUBSCRIBE' ), 'a.unsub', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
					</th>
				<?php if(acymailing_level(1)){?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort',   JText::_( 'FORWARDED' ), 'a.forward', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
					</th>
				<?php } ?>
				<th class="title titletoggle">
					<?php echo JHTML::_('grid.sort',   JText::_( 'SENT_HTML' ), 'a.senthtml', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titletoggle">
					<?php echo JHTML::_('grid.sort',   JText::_( 'SENT_TEXT' ), 'a.senttext', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<?php if(acymailing_level(3)){?>
				<th class="title">
					<?php echo JHTML::_('grid.sort',   JText::_( 'BOUNCES' ), 'a.bounceunique', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<?php } ?>
				<th class="title titletoggle">
					<?php echo JHTML::_('grid.sort',   JText::_( 'FAILED' ), 'a.fail', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titleid">
					<?php echo JHTML::_('grid.sort',   JText::_( 'ACY_ID' ), 'a.mailid', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
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
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
					<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->mailid ); ?>
					</td>
					<td align="center">
					<?php echo acymailing_getDate($row->senddate); ?>
					</td>
					<td>
					<?php if(acymailing_level(2)){ ?><a class="modal" href="<?php echo acymailing_completeLink('diagram&task=mailing&mailid='.$row->mailid,true)?>" rel="{handler: 'iframe', size: {x: 800, y: 590}}"><img src="<?php echo ACYMAILING_IMAGES?>icons/icon-16-stats.png" /><?php } ?>
					<?php echo acymailing_tooltip('<b>'.JText::_('JOOMEXT_ALIAS').' : </b>'.$row->alias, ' ', '', $row->subject); ?>
					<?php if(acymailing_level(2)){ ?></a><?php }?>
					</td>
					<td align="center">
						<?php
						if(!empty($row->senthtml)){
							$text = '<b>'.JText::_('OPEN_UNIQUE').' : </b>'.$row->openunique;
							$text .= '<br/><b>'.JText::_('OPEN_TOTAL').' : </b>'.$row->opentotal;
							$pourcent = (substr($row->openunique/$row->senthtml*100,0,5)).'%';
							$title = JText::sprintf('PERCENT_OPEN',$pourcent);
							echo acymailing_tooltip( $text, $title, '', $row->openunique.' ('.$pourcent.')',acymailing_completeLink('stats&task=detaillisting&filter_status=open&filter_mail='.$row->mailid));
						}
						?>
					</td>
					<?php if(acymailing_level(1)){?>
					<td align="center">
						<?php
						if(!empty($row->senthtml)){
							$text = '<b>'.JText::_('UNIQUE_HITS').' : </b>'.$row->clickunique;
							$text .= '<br/><b>'.JText::_('TOTAL_HITS').' : </b>'.$row->clicktotal;
							$pourcent = (substr($row->clickunique/$row->senthtml*100,0,5)).'%';
							$title = JText::sprintf('PERCENT_CLICK',$pourcent);
							echo acymailing_tooltip( $text, $title, '',$row->clickunique.' ('.$pourcent.')',acymailing_completeLink('statsurl&filter_mail='.$row->mailid));
						}
						?>
					</td>
					<?php } ?>
					<td align="center">
						<?php echo '<a class="modal" href="'.acymailing_completeLink('stats&task=unsubchart&mailid='.$row->mailid,true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}"><img src="'.ACYMAILING_IMAGES.'icons/icon-16-stats.png" /></a>'; ?>


						<?php $pourcent = (empty($row->senthtml) AND empty($row->senttext)) ? '0%' : (substr($row->unsub/($row->senthtml+$row->senttext)*100,0,5)).'%';
						echo '<a class="modal" href="'.acymailing_completeLink('stats&task=unsubscribed&filter_mail='.$row->mailid,true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}">'.$row->unsub.' ('.$pourcent.')</a>'; ?>
					</td>
					<?php if(acymailing_level(1)){?>
						<td align="center">
							<?php echo '<a class="modal" href="'.acymailing_completeLink('stats&task=forward&filter_mail='.$row->mailid,true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}">'.$row->forward.'</a>'; ?>
						</td>
					<?php } ?>
					<td align="center">
						<a href="<?php echo acymailing_completeLink('stats&task=detaillisting&filter_status=0&filter_mail='.$row->mailid); ?>"><?php echo $row->senthtml; ?></a>
					</td>
					<td align="center">
						<?php echo $row->senttext; ?>
					</td>
					<?php if(acymailing_level(3)){?>
					<td align="center">
						<?php echo '<a class="modal" href="'.acymailing_completeLink('bounces&task=chart&mailid='.$row->mailid,true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}"><img src="'.ACYMAILING_IMAGES.'icons/icon-16-stats.png" /></a>'; ?>

						<?php $pourcent = (empty($row->senthtml) AND empty($row->senttext)) ? '0%' : (substr($row->bounceunique/($row->senthtml+$row->senttext)*100,0,5)).'%';
						echo '<a href="'.acymailing_completeLink('stats&task=detaillisting&filter_status=bounce&filter_mail='.$row->mailid).'">'.$row->bounceunique.' ('.$pourcent.')';?>
					</td>
					<?php } ?>
					<td align="center">
						<a href="<?php echo acymailing_completeLink('stats&task=detaillisting&filter_status=failed&filter_mail='.$row->mailid); ?>">
						<?php echo $row->fail; ?>
						</a>
					</td>
					<td align="center">
						<?php echo $row->mailid; ?>
					</td>
				</tr>
			<?php
					$k = 1-$k;
				}
			?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
