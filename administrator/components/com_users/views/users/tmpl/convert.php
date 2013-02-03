<?php
/**
 * @version     2.1.0
 * @package     com_application
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
$loggeduser = JFactory::getUser();
$query="SELECT u.id, 
    FROM_UNIXTIME(u.date_joined) AS 'registerDate',
	u.username, l.password, 
    u.firstname AS 'name', 
	u.optional_field_1 AS '_middlename', 
	u.lastname AS '_lastname', 
    u.company_name AS '_company_name', 
	'' AS '_country_id', 
	u.zip AS '_zip', 
	u.city AS '_city', 
    u.optional_field_2 AS '_street', 
	u.optional_field_3 AS '_house_number', 
    u.optional_field_4 AS '_corpus_number', 
	u.optional_field_5 AS '_flat_office_number', 
    u.phone AS '_phone_number', 
	u.phone2 AS '_phone2_number', 
	u.email
FROM #__geodesic_users AS u 
LEFT JOIN #__geodesic_logins AS l ON l.username = u.username";
$db=JFactory::getDBO();
$db->setQuery($query);
$result=$db->loadAssocList(); 
var_dump($result); // die();
echo <<<DT
<pre>
'id' => string '124'
'registerDate' => string '2010-09-30 14:20:14'
'username' => string '100130'
'password' => string '74394864'
'name' => string 'Андрей'
'_middlename' => string 'Николаевич'
'_lastname' => string 'Кокарев'
'_company_name' => string ''
'_country_id' => string ''
'_zip' => string '127522'
'_city' => string 'Москва'
'_street' => string 'Ленинградский проспект'
'_house_number' => string '11'
'_corpus_number' => string ''
'_flat_office_number' => string 'офис'
'_phone_number' => string '+7 962 2302121'
'_phone2_number' => string ''
'email' => string 'akvip@mail.ru'
</pre>
DT;
$arrFields=array();?>
<form action="<?php echo JRoute::_('index.php?option=com_users'); ?>" method="post" name="adminForm" id="adminForm">
	<? $cnt=0;?>
  <table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /><? ++$cnt;?>
				</th>
				<th>XXX</th>
				<th>XXX</th>
				<th>XXX</th>
				<th>XXX</th>
				<th>XXX</th>
				<th>XXX</th>
				<th>XXX</th>
				<th>XXX</th>
				<th>XXX</th>
			</tr>
		</thead>
     <?	/*if ($items=$this->items){?>
		<tbody>
		<?php 
			foreach ($items as $i => $item) {
				$canCreate	= $user->authorise('core.create',		'com_application');
				$canEdit	= $user->authorise('core.edit',			'com_application');
				$canCheckin	= $user->authorise('core.manage',		'com_application');
				$canChange	= $user->authorise('core.edit.state',	'com_application');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>

				<td>
					<?php echo $item->id; ?>
				</td>
				<td>
				<?php 
				if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_application&task=application.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->family); ?></a>
				<?php else :
					echo $this->escape($item->family);
				endif; ?>
				</td>
				<td>
					<?php echo $item->name; ?>
				</td>
				<td>
					<?php echo $item->middle_name; ?>
				</td>
				<td>
					<?php echo $item->child_name; ?>
				</td>
				<td>
					<?php echo $item->kindergarten; ?>
				</td>
				<td>
					<?php echo $item->group; ?>
				</td>
				<td>
					<?php echo $item->email; ?>
				</td>
				<td>
					<?php echo $item->mobila; ?>
				</td>
			</tr>
	<?php 	} ?>
		</tbody>
	<?	}*/	?>
  </table>
  <div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>