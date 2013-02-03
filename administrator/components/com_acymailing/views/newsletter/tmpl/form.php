<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content" >
<div id="iframedoc"></div>
<form action="index.php" method="post" name="adminForm"  id="adminForm" enctype="multipart/form-data">
	<table width="100%">
		<tr>
			<td valign="top">
				<?php include(dirname(__FILE__).DS.'info.'.basename(__FILE__)); ?>
				<fieldset class="adminform" width="100%" id="htmlfieldset">
					<legend><?php echo JText::_( 'HTML_VERSION' ); ?></legend>
					<?php echo $this->editor->display(); ?>
				</fieldset>
				<fieldset class="adminform" width="100%" id="textfieldset">
					<legend><?php echo JText::_( 'TEXT_VERSION' ); ?></legend>
					<textarea style="width:98%" rows="20" name="data[mail][altbody]" id="altbody" ><?php echo @$this->mail->altbody; ?></textarea>
				</fieldset>
			</td>
			<td width="400" valign="top">
				<?php include(dirname(__FILE__).DS.'param.'.basename(__FILE__)); ?>
			</td>
		</tr>
		</table>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo @$this->mail->mailid; ?>" />
	<input type="hidden" id="tempid" name="data[mail][tempid]" value="<?php echo @$this->mail->tempid; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="data[mail][type]" value="news" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="newsletter" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
