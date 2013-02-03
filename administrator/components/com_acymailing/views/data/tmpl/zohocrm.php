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
	$listClass = acymailing_get('class.list');
	$this->data = $listClass->getLists('listid');
	$this->values = array();
	$this->values[] = JHTML::_('select.option', '0', '- - -' );
	foreach($this->data as $onelist){
		$this->values[] = JHTML::_('select.option', $onelist->listid, $onelist->name );
	}
	$namekey = array ('zoho_fields','zoho_lists');
	foreach($namekey as $oneNamekey){
		$oneValue = $this->config->get($oneNamekey);
		$value[$oneNamekey] = empty($oneValue) ? array() : unserialize($oneValue);
	}

	if(empty($value['zoho_fields'])) $value['zoho_fields'] = array('First Name' => 'name');
?>
<fieldset class="adminform">
	<legend><?php echo JText::_('Options'); ?></legend>
<table class="admintable table" cellspacing="1">
<?php if($this->config->get('require_confirmation')){ ?>
	<tr id="trfileconfirm">
		<td class="key" >
			<?php echo JText::_('IMPORT_CONFIRMED'); ?>
		</td>
		<td>
			<?php
				echo JHTML::_('acyselect.booleanlist', "zoho_confirmed" , '',$this->config->get('zoho_confirmed'),JText::_('JOOMEXT_YES'),JTEXT::_('JOOMEXT_NO') );
			?>
		</td>
	</tr>
	<?php } ?>
	<tr id="trfileoverwrite">
		<td class="key" >
			<?php echo JText::_('OVERWRITE_EXISTING'); ?>
		</td>
		<td>
			<?php
			echo JHTML::_('acyselect.booleanlist', "zoho_overwrite" , '',$this->config->get('zoho_overwrite'),JText::_('JOOMEXT_YES'),JTEXT::_('JOOMEXT_NO') ); ?>
		</td>
	</tr>
	<tr id="trfileoverwrite">
		<td class="key" >
			<?php echo JText::_('DELETE_USERS'); ?>
		</td>
		<td>
			<?php
			echo JHTML::_('acyselect.booleanlist', "zoho_delete" , '',$this->config->get('zoho_delete'),JText::_('JOOMEXT_YES'),JTEXT::_('JOOMEXT_NO') ); ?>
		</td>
	</tr>
	<tr id="trfileoverwrite">
		<td class="key" >
			<?php echo 'Auth Token'; ?>
		</td>
		<td>
			<input class="inputbox" type="text" name="zoho_apikey" size="35" value="<?php echo $this->escape($this->config->get('zoho_apikey')); ?>">
		</td>
	</tr>
</table>
</fieldset>

<fieldset class="adminform">
	<legend><?php echo JText::_('FIELD'); ?></legend>
<?php
	$db = JFactory::getDBO();
	$subfields = acymailing_getColumns('#__acymailing_subscriber');
	$acyfields = array();
	$acyfields[] = JHTML::_('select.option', '',' - - - ');
	if(!empty($subfields)) {
		foreach($subfields as $oneField => $typefield){
			if(in_array($oneField,array('subid','confirmed','enabled','key','userid','accept','html','created','zohoid','zoholist', 'email'))) continue;
			$acyfields[] = JHTML::_('select.option', $oneField,$oneField);
		}
	}
?>
	<table class="admintable table" cellspacing="1">
<?php
	$fields = array('First Name','Last Name','Date of Birth');
	foreach($fields as $oneField){
		$fieldValue = '';
		if(!empty($value['zoho_fields'][$oneField])) $fieldValue = $value['zoho_fields'][$oneField];
		echo '<tr><td class="key">'.$oneField.'</td><td><div id="zoho_fields">'.JHTML::_('select.genericlist',  $acyfields, "zoho_fields[".$oneField."]", 'class="inputbox" size="1"','value', 'text',$fieldValue).'</div></td></tr>';
	}
?>
	</table>
</fieldset>

<fieldset class="adminform">
	<legend><?php echo JText::_('LISTS'); ?></legend>
	<table class="admintable table" cellspacing="1">
<?php
	$lists = array('Leads','Contacts','Vendors');
	$listValue = '';

	foreach($lists as $oneList){
		$listValue='';
		if(!empty($value['zoho_lists'][$oneList])) $listValue = $value['zoho_lists'][$oneList];
		echo '<tr><td class="key" >'.$oneList.'</td><td><div id="zoho_lists">'.JHTML::_('select.genericlist',  $this->values, "zoho_lists[".$oneList."]", 'class="inputbox" size="1"','value', 'text',$listValue).'</div></td></tr>';
	}
?>
	</table>
</fieldset>


