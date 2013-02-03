<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><form action="index.php?tmpl=component&amp;option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=send" method="post" name="adminForm"  id="adminForm" autocomplete="off">
	<table class="adminform">
		<tr>
			<td class="key">
				<?php echo JText::_('ACY_USER'); ?>
			</td>
			<td>
				<?php echo JHTML::_('tooltip', 'Name : '.$this->subscriber->name.'<br/>ID : '.$this->subscriber->subid, $this->subscriber->email, 'tooltip.png', $this->subscriber->email); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_('NEWSLETTER'); ?>
			</td>
			<td>
				<?php echo $this->emaildrop; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_('SEND_DATE'); ?>
			</td>
			<td>
				<?php echo JHTML::_('calendar', acymailing_getDate(time(),'%Y-%m-%d %H:%M'), 'senddate','senddate','%Y-%m-%d %H:%M','style="width:200px"'); ?>
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<button class="btn btn-primary" type="submit"><?php echo JText::_('SCHEDULE'); ?></button>
			</td>
		</tr>
	</table>
	<input type="hidden" name="subid" value="<?php echo $this->subscriber->subid; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="scheduleone" />
	<input type="hidden" name="ctrl" value="send" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
