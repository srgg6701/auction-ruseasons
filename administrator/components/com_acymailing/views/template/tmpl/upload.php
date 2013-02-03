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
<form action="index.php?tmpl=component&amp;option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm"  id="adminForm" autocomplete="off" enctype="multipart/form-data">
	<fieldset>
		<div class="acyheader icon-48-acytemplate" style="float: left;"><?php echo JText::_('ACY_TEMPLATE'); ?></div>
		<div class="toolbar" id="toolbar" style="float: right;">
			<table><tr>
			<td><a onclick="javascript:submitbutton('doupload'); return false;" href="#" ><span class="icon-32-save" title="<?php echo JText::_('IMPORT',true); ?>"></span><?php echo JText::_('IMPORT'); ?></a></td>
			<td><span class="divider"></span></td>
			<td><?php include_once(ACYMAILING_BUTTON.DS.'pophelp.php'); $helpButton = new JButtonPophelp(); echo $helpButton->fetchButton('Pophelp', 'template-upload'); ?></td>
			</tr></table>
		</div>
	</fieldset>
	<div id="iframedoc"></div>
	<div style="text-align:center;padding-top:20px;"><input type="file" style="width:160px" name="uploadedfile" /></div>
	<br/><br/><a class="downloadmore" href="http://www.acyba.com/download/templates.html" target="_blank"><?php echo JText::_('MORE_TEMPLATES'); ?></a>
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="template" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
