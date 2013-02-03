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
<form action="index.php?tmpl=component&amp;option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm"  id="adminForm" autocomplete="off">
	<fieldset class="acyheaderarea">
		<div class="acyheader" style="float: left;"><?php echo JText::_('ACY_FILE').' : '.$this->file->name; ?></div>
		<div class="toolbar" id="toolbar" style="float: right;">
			<table><tr>
			<td><a onclick="javascript:submitbutton('save'); return false;" href="#" ><span class="icon-32-save" title="<?php echo JText::_('ACY_SAVE',true); ?>"></span><?php echo JText::_('ACY_SAVE'); ?></a></td>
			<td><a onclick="javascript:submitbutton('share'); return false;" href="#" ><span class="icon-32-share" title="<?php echo JText::_('SHARE',true); ?>"></span><?php echo JText::_('SHARE'); ?></a></td>
			</tr></table>
		</div>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_( 'ACY_FILE').' : '.$this->file->name; ?>
		<?php if(!empty($this->showLatest)){ ?> <button type="button" class="btn btn-primary" onclick="javascript:submitbutton('latest')"> <?php echo JText::_('LOAD_LATEST_LANGUAGE'); ?> </button> <?php } ?>
		</legend>
		<textarea style="width:700px;" rows="18" name="content" id="translation" ><?php echo @$this->file->content;?></textarea>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_('CUSTOM_TRANS'); ?></legend>
		<?php echo JText::_('CUSTOM_TRANS_DESC'); ?>
		<textarea style="width:700px;" rows="5" name="customcontent" ><?php echo @$this->file->customcontent;?></textarea>
	</fieldset>

	<div class="clr"></div>
	<input type="hidden" name="code" value="<?php echo $this->file->name; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="file" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
