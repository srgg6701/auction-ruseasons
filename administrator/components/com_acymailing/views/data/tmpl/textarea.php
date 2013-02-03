<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><textarea style="width:80%" rows="20" name="textareaentries">
<?php $text = JRequest::getString("textareaentries");
if(empty($text)){ ?>
name,email
Adrien,adrien@example.com
John,john@example.com
<?php }else echo $text?>
</textarea>
<table class="admintable" cellspacing="1">
<?php if($this->config->get('require_confirmation')){ ?>
		<tr id="trtextareaconfirm">
			<td class="key" >
				<?php echo JText::_('IMPORT_CONFIRMED'); ?>
			</td>
			<td>
				<?php echo JHTML::_('acyselect.booleanlist', "import_confirmed_textarea" , '',JRequest::getInt('import_confirmed_textarea',1),JText::_('JOOMEXT_YES'),JTEXT::_('JOOMEXT_NO') ); ?>
			</td>
		</tr>
<?php } ?>
	<tr id="trtextareagenerate" >
		<td class="key" >
			<?php echo JText::_('GENERATE_NAME'); ?>
		</td>
		<td>
			<?php echo JHTML::_('acyselect.booleanlist', "generatename_textarea" , '',JRequest::getInt('generatename_textarea',1),JText::_('JOOMEXT_YES'),JTEXT::_('JOOMEXT_NO')); ?>
		</td>
	</tr>
	<tr id="trtextareaoverwrite">
		<td class="key" >
			<?php echo JText::_('OVERWRITE_EXISTING'); ?>
		</td>
		<td>
			<?php echo JHTML::_('acyselect.booleanlist', "overwriteexisting_textarea" , '',JRequest::getInt('overwriteexisting_textarea',0),JText::_('JOOMEXT_YES'),JTEXT::_('JOOMEXT_NO')); ?>
		</td>
	</tr>
</table>
