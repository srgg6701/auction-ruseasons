<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content">
<div id="iframedoc"></div>
<form action="<?php echo JRoute::_('index.php?option=com_acymailing&ctrl='.JRequest::getCmd('ctrl')); ?>" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td width="100%" id="subscriberfilter">
				<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
				<input type="text" name="search" id="acymailingsearch" value="<?php echo $this->escape($this->pageInfo->search);?>" class="text_area" />
				<button class="btn" onclick="document.adminForm.limitstart.value=0;this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
				<button class="btn" onclick="document.getElementById('acymailingsearch').value='';document.adminForm.limitstart.value=0;this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<span id="subscriberfilterlists"><?php echo $this->filters->lists; ?></span>
				<span id="subscriberfilterstatus"><?php echo $this->filters->status; ?></span>
			</td>
		</tr>
	</table>
	<table class="adminlist table table-striped table-hover" cellpadding="1">
		<thead>
			<tr>
				<th class="title titlenum">
					<?php echo JText::_( 'ACY_NUM' ); ?>
				</th>
				<th class="title titlebox">
					<input type="checkbox" name="toggle" value="" onclick="acymailing_js.checkAll(this);" />
				</th>
				<?php
				foreach($this->displayFields as $map => $oneField){
					if($map == 'html') continue; ?>
					<th class="title">
					<?php echo JHTML::_('grid.sort',   $this->customFields->trans($oneField->fieldname), 'a.'.$map, $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
					</th>
				<?php } ?>
				<?php $app = JFactory::getApplication();
				if($app->isAdmin()){ ?>
				<th class="title">
					<?php echo JText::_('SUBSCRIPTION'); ?>
				</th>
				<?php } ?>
				<th class="title titledate">
					<?php echo JHTML::_('grid.sort',   JText::_('CREATED_DATE'), 'a.created', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<?php
				if($app->isAdmin()){
				if(!empty($this->displayFields['html'])){ ?>
				<th class="title titletoggle">
					<?php echo JHTML::_('grid.sort',   JText::_('RECEIVE_HTML'), 'a.html', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<?php } ?>
				<?php if($this->config->get('require_confirmation',1)){ ?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort',   JText::_('CONFIRMED'), 'a.confirmed', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
					</th>
				<?php } ?>
				<th class="title titletoggle">
					<?php echo JHTML::_('grid.sort',   JText::_('ENABLED'), 'a.enabled', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titleid">
					<?php echo JHTML::_('grid.sort',   JText::_('USER_ID'), 'a.userid', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title titleid">
					<?php echo JHTML::_('grid.sort',   JText::_('ACY_ID'), 'a.subid', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo $app->isAdmin() ? count($this->displayFields)+9 : count($this->displayFields)+2; ?>">
					<?php echo $this->pagination->getListFooter(); ?>
					<?php echo $this->pagination->getResultsCounter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				$k = 0;
				$i = 0;
				foreach($this->rows as $row){
					$confirmedid = 'confirmed_'.$row->subid;
					$htmlid = 'html_'.$row->subid;
					$enabledid = 'enabled_'.$row->subid;
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
					<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
									 <?php echo JHTML::_('grid.id', $i, $row->subid); ?>
					 </td>
					<?php foreach($this->displayFields as $map => $oneField){
						if($map == 'html') continue; ?>
						<td>
						<?php
						if($map =='email') echo '<a href="'.acymailing_completeLink(JRequest::getCmd('ctrl').'&task=edit&subid='.$row->subid).'">';
						echo $this->customFields->listing($oneField,@$row->$map);
						if($map =='email') echo '</a>';
						?>
						</td>
					<?php }
					if($app->isAdmin()){
					?>
					<td align="right">

						<?php
						if(empty($row->accept)){
							echo '<div class="icon-16-refuse" >'.JHTML::_('tooltip',JText::_('USER_REFUSE',true), '','','&nbsp;&nbsp;&nbsp;&nbsp;').'</div>';
						}

						foreach($this->lists as $listid => $list){
							if(empty($row->subscription->$listid)) continue;
								$statuslistid = 'status_'.$listid.'_'.$row->subid;
								echo '<div id="'.$statuslistid.'" class="loading">';
								$extra = array();
								$extra['color'] = $this->lists[$listid]->color;
								$extra['tooltiptitle'] = $this->lists[$listid]->name;
								$extra['tooltip'] = '<b>'.JText::_('LIST_NAME').' : </b>'.$this->lists[$listid]->name.'<br/>';
								if($row->subscription->$listid->status > 0){
									$extra['tooltip'] .= '<b>'.JText::_('STATUS').' : </b>';
									$extra['tooltip'] .= ($row->subscription->$listid->status == '1') ? JText::_('SUBSCRIBED') : JText::_('PENDING_SUBSCRIPTION');
									$extra['tooltip'] .= '<br/><b>'.JText::_('SUBSCRIPTION_DATE').' : </b>'.acymailing_getDate($row->subscription->$listid->subdate);
								}else{
									$extra['tooltip'] .= '<b>'.JText::_('STATUS').' : </b>'.JText::_('UNSUBSCRIBED').'<br/>';
									$extra['tooltip'] .= '<b>'.JText::_('UNSUBSCRIPTION_DATE').' : </b>'.acymailing_getDate($row->subscription->$listid->unsubdate);
								}

								echo $this->toggleClass->toggle($statuslistid,$row->subscription->$listid->status,'listsub',$extra);
								echo '</div>';
							}

						?>
						</td>
					<?php } ?>
					<td align="center">
						<?php echo acymailing_getDate($row->created); ?>
					</td>

					<?php if($app->isAdmin()){
						if(!empty($this->displayFields['html'])){ ?>
					<td align="center">
						<span id="<?php echo $htmlid ?>" class="loading"><?php echo $this->toggleClass->toggle($htmlid,$row->html,'subscriber') ?></span>
					</td>
					<?php } ?>
					<?php if($this->config->get('require_confirmation',1)){ ?>
					<td align="center">
						<span id="<?php echo $confirmedid ?>" class="loading"><?php echo $this->toggleClass->toggle($confirmedid,$row->confirmed,'subscriber') ?></span>
					</td>
					<?php } ?>
					<td align="center">
						<span id="<?php echo $enabledid ?>" class="loading"><?php echo $this->toggleClass->toggle($enabledid,$row->enabled,'subscriber') ?></span>
					</td>
					<td align="center">
						<?php if(!empty($row->userid)){
							if(file_exists(ACYMAILING_ROOT.'components'.DS.'com_comprofiler'.DS.'comprofiler.php')){
								$editLink = 'index.php?option=com_comprofiler&task=edit&cid[]=';
							}elseif(!ACYMAILING_J16){
								$editLink = 'index.php?option=com_users&task=edit&cid[]=';
							}else{
								$editLink = 'index.php?option=com_users&task=user.edit&id=';
							}
							$text = JText::_('ACY_USERNAME').' : <b>'.$row->username;
							$text .= '</b><br/>'.JText::_('USER_ID').' : <b>'.$row->userid.'</b>';
							echo acymailing_tooltip($text,$row->username,'',$row->userid,$editLink.$row->userid);} ?>
					</td>
					<td align="center">
						<?php echo $row->subid; ?>
					</td>
					<?php } ?>
				</tr>
			<?php
					$k = 1-$k; $i++;
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
	<?php if(!empty($this->Itemid)) echo '<input type="hidden" name="Itemid" value="'.$this->Itemid.'" />';
	echo JHTML::_( 'form.token' ); ?>
</form>
</div>
