<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
	$db = JFactory::getDBO();
	$db->setQuery('SELECT count(id) FROM '.acymailing_table('jnews_subscribers',false));
	$resultAcaUsers = $db->loadResult();
	$db->setQuery('SELECT count(id) FROM '.acymailing_table('jnews_lists',false));
	$resultAcaLists = $db->loadResult();
?>

<table class="admintable" cellspacing="1">
	<tr>
		<td colspan="2">
			<?php echo JText::sprintf('USERS_IN_COMP',$resultAcaUsers,'jNewsletter'); ?>
			<br/>
			There are <?php echo $resultAcaLists ?> lists in jNewsletter.
			<br/>
			You can import those <?php echo $resultAcaLists ?> Lists and so keep the subscription of each subscriber.
		</td>
	</tr>
	<tr>
		<td class="key" >
			<?php echo JText::_('Import the jNewsletter Lists too?'); ?>
		</td>
		<td>
			<?php echo JHTML::_('acyselect.booleanlist', "jnews_lists"); ?>
		</td>
	</tr>
</table>
