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
	$db->setQuery('SELECT count(id) FROM '.acymailing_table('users',false));
	$resultUsers = $db->loadResult();

	$db->setQuery('SELECT count(subid) FROM '.acymailing_table('subscriber').' WHERE userid > 0');
	$resultAcymailing = $db->loadResult();
?>

There are <?php echo $resultUsers; ?> Users in your Joomla User Manager.
<br/>
There are <?php echo $resultAcymailing; ?> Registered Users in AcyMailing.
<br/>
<br/>
If you click on the "import" button, the system will :
<ol>
	<li>Update the AcyMailing Users from your Joomla Users</li>
	<li>Delete the AcyMailing Users if they were linked to a Joomla User but this Joomla User does not exist any more</li>
	<li>Add all your Joomla Users into AcyMailing if they are not already there</li>
	<li>Subscribe all your Joomla Users to the selected lists if they are not already subscribed or unsubscribed from it</li>
</ol>
