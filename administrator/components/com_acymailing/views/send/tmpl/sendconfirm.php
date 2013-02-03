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
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=send" method="post" name="adminForm"  id="adminForm" autocomplete="off">
	<div>
	<?php $displayWarning = false;
		if(empty($this->values->nbqueue)){
		if(!empty($this->lists)){?>
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'NEWSLETTER_SENT_TO' ); ?></legend>
			<table class="adminlist table table-striped" cellspacing="1" align="center">
				<tbody>
					<?php
					$k = 0;
					$listids = array();
					foreach($this->lists as $row){
						$listids[] = $row->listid;
						if($row->nbsub > 100) $displayWarning = true;
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td>
							<?php
							echo acymailing_tooltip($row->description, $row->name, 'tooltip.png', $row->name);
							echo ' ( '.JText::sprintf('SELECTED_USERS',$row->nbsub).' )';
							 ?>
						</td>
					</tr>
					<?php
						$k = 1 - $k;
					} ?>
				</tbody>
			</table>
			<?php
			$filterClass = acymailing_get('class.filter');

			if(!empty($this->mail->filter)){
				$resultFilters = $filterClass->displayFilters($this->mail->filter);
				if(!empty($resultFilters)){
					echo '<br/>'.JText::_('RECEIVER_LISTS').'<br/>'.JText::_('FILTER_ONLY_IF');
					echo '<ul><li>'.implode('</li><li>',$resultFilters).'</li></ul>';
				}
			}

			$nbTotalReceivers = $nbTotalReceiversAll = $filterClass->countReceivers($listids,$this->mail->filter);
			?>
		</fieldset>
		<?php if(!empty($this->values->alreadySent)){
				$filterClass->onlynew = true;
				$nbTotalReceivers = $nbTotalReceiversAlready = $filterClass->countReceivers($listids,$this->mail->filter,$this->mail->mailid);
				acymailing_display(JText::sprintf('ALREADY_SENT',$this->values->alreadySent).'<br/>'.JText::_('REMOVE_ALREADY_SENT').'<br/>'.JHTML::_('acyselect.booleanlist', "onlynew",'onclick="if(this.value == 1){document.getElementById(\'nbreceivers\').innerHTML = \''.$nbTotalReceiversAlready.'\';}else{document.getElementById(\'nbreceivers\').innerHTML = \''.$nbTotalReceiversAll.'\'}"',1,JText::_('JOOMEXT_YES'),JText::_('SEND_TO_ALL')),'warning');
			}elseif($displayWarning){
				$config = acymailing_config();
				if($config->get('warninglimitation',1)){
					$toggleClass = acymailing_get('helper.toggle');
					$notremind = '<small style="float:right;margin-right:30px;position:relative;">'.$toggleClass->delete('acymailing_messages_warning','warninglimitation_0','config',false,JText::_('DONT_REMIND')).'</small>';
					acymailing_display(JText::_('WARNING_LIMITATION').'<br /><a target="_blank" href="'.ACYMAILING_HELPURL.'send-process">'.JText::_('WARNING_LIMITATION_CONFIG').'</a>'.$notremind,'warning');
				}
			}
		}else{ acymailing_display(JText::_( 'EMAIL_AFFECT' ),'warning');}
	}else{
		acymailing_display(JText::sprintf('NB_PENDING_EMAIL',$this->values->nbqueue,'<b><i>'.$this->mail->subject.'</i></b>').'<br/>'.JText::_('SEND_CONTINUE'),'info');
		?>
		<input type="hidden" name="totalsend" value="<?php echo $this->values->nbqueue; ?>" />
		<?php
	}
	?>
	<?php if(!empty($this->mail->mailid) AND (!empty($this->lists) OR !empty($this->values->nbqueue))){?>
		<div style="text-align:center;font-size:14px;padding:20px;">
			<?php if(empty($this->values->nbqueue)) echo JText::sprintf('SENT_TO_NUMBER','<span style="font-weight:bold;" id="nbreceivers" >'.$nbTotalReceivers.'</span>').'<br/>';?>
			<input class="btn btn-primary" style="padding:10px 30px;margin:5px;font-size:14px;cursor:pointer;" type="submit" value="<?php echo empty($this->values->nbqueue) ? JText::_('SEND') : JText::_('CONTINUE')?>" />
		</div>
	<?php }?>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->mail->mailid; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="<?php echo empty($this->values->nbqueue) ? 'send' : 'continuesend'; ?>" />
	<input type="hidden" name="ctrl" value="send" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
