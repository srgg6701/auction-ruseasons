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
	$subfields = acymailing_getColumns('#__acymailing_subscriber');

	$config = acymailing_config();
	$postFields = (array) @unserialize($config->get('import_db_fields',''));
?>
<table class="admintable table" cellspacing="1">
	<tr>
		<td class="key"><?php echo JText::_('TABLENAME'); ?></td>
		<td><input type="text" name="tablename" size="80" value="<?php echo $this->escape($config->get('import_db_table','')); ?>" /></td>
	</tr>
<?php
	if(!empty($subfields)) {
		foreach($subfields as $oneField => $type){
			if(in_array($oneField,array('subid','confirmed','enabled','key','userid','accept','html','created'))) continue;
			echo '<tr><td class="key">'.$oneField.'</td><td><input style="width:200px" type="text" name="fields['.$oneField.']" value="'.@$postFields[$oneField].'" /></td></tr>';
		}
	}
?>
</table>
