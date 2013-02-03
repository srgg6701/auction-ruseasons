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
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm"  id="adminForm" autocomplete="off">
	<fieldset class="acyheaderarea">
		<div class="acyheader" style="float: left;"><?php echo $this->type.'_'.$this->fileName.'.css'; ?></div>
		<div class="toolbar" id="toolbar" style="float: right;">
			<table><tr>
			<td><a onclick="javascript:submitbutton('savecss'); return false;" href="#" ><span class="icon-32-save" title="<?php echo JText::_('ACY_SAVE',true); ?>"></span><?php echo JText::_('ACY_SAVE'); ?></a></td>
			</tr></table>
		</div>
	</fieldset>

	<textarea style="width:98%" rows="20" name="csscontent" ><?php echo $this->content; ?></textarea>

	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="savecss" />
	<input type="hidden" name="ctrl" value="file" />
	<input type="hidden" name="file" value="<?php echo $this->type.'_'.$this->fileName; ?>" />
	<input type="hidden" name="var" value="<?php echo JRequest::getCmd('var'); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
