<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="page-queue">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'QUEUE_PROCESS' ); ?></legend>
		<table class="admintable" cellspacing="1" >
		<?php if(acymailing_level(1)){?>
			<tr>
				<td class="key">
				<?php echo acymailing_tooltip(JText::_('QUEUE_PROCESSING_DESC'), JText::_('QUEUE_PROCESSING'), '', JText::_('QUEUE_PROCESSING')); ?>
				</td>
				<td>
					<?php echo $this->elements->queue_type; ?>
				</td>
			</tr>
			<tr id="method_auto">
				<td class="key">
				<?php echo JText::_('AUTO_SEND_PROCESS'); ?>
				</td>
				<td>
					<?php echo JText::sprintf('SEND_X_EVERY_Y','<input class="inputbox" type="text" name="config[queue_nbmail_auto]" style="width:50px" value="'.intval($this->config->get('queue_nbmail_auto')).'" />',$this->elements->cron_frequency); ?>
				</td>
			</tr>
			<?php } ?>
			<tr id="method_manual">
				<td class="key">
				<?php echo JText::_('MANUAL_SEND_PROCESS'); ?>
				</td>
				<td>
					<?php echo JText::sprintf('SEND_X_WAIT_Y','<input class="inputbox" type="text" name="config[queue_nbmail]" style="width:50px" value="'.intval($this->config->get('queue_nbmail')).'" />',$this->elements->queue_pause); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
				<?php echo acymailing_tooltip(JText::_('MAX_NB_TRY_DESC'), JText::_('MAX_NB_TRY'), '', JText::_('MAX_NB_TRY')); ?>
				</td>
				<td>
					<?php echo JText::sprintf('CONFIG_TRY','<input class="inputbox" type="text" name="config[queue_try]" style="width:50px" value="'.intval($this->config->get('queue_try')).'">');
					echo ' '.JText::sprintf('CONFIG_TRY_ACTION',$this->bounceaction->display('maxtry',$this->config->get('bounce_action_maxtry'))); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<?php if(acymailing_level(1)){
			include(dirname(__FILE__).DS.'cron.php');
		}
	if(acymailing_level(3)){ ?>
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'PRIORITY' ); ?></legend>
		<table class="admintable" cellspacing="1">
			<tr>
				<td class="key">
				<?php echo acymailing_tooltip(JText::_('NEWS_PRIORITY_DESC'), JText::_('NEWS_PRIORITY'), '', JText::_('NEWS_PRIORITY')); ?>
				</td>
				<td>
					<input class="inputbox" type="text" name="config[priority_newsletter]" style="width:50px" value="<?php echo intval($this->config->get('priority_newsletter',3)); ?>">
				</td>
			</tr>
			<tr>
				<td class="key">
				<?php echo acymailing_tooltip(JText::_('FOLLOW_PRIORITY_DESC'), JText::_('FOLLOW_PRIORITY'), '', JText::_('FOLLOW_PRIORITY')); ?>
				</td>
				<td>
					<input class="inputbox" type="text" name="config[priority_followup]" style="width:50px" value="<?php echo intval($this->config->get('priority_followup',2)); ?>">
				</td>
			</tr>
		</table>
	</fieldset>
	<?php } ?>
</div>
