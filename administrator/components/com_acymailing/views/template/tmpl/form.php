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
<form action="index.php" method="post" name="adminForm"  id="adminForm" >
<fieldset class="adminform" id="sendtest" <?php if(JRequest::getCmd('task') != 'test') echo 'style="display:none"'; ?> >
	<legend><?php echo JText::_( 'SEND_TEST' ); ?></legend>
	<table>
		<tr>
			<td valign="top">
				<?php echo JText::_( 'SEND_TEST_TO' ); ?>
			</td>
			<td>
				<?php echo $this->receiverClass->display('receiver_type',$this->infos->receiver_type); ?>
				<div id="emailfield" style="display:none" >
					<?php echo JText::_('EMAIL_ADDRESS')?> <input class="inputbox" type="text" id="test_email" name="test_email" style="width:200px" value="<?php echo $this->infos->test_email;?>" />
					<?php echo ' <a class="modal" title="'.JText::_('ACY_SELECTUSER',true).'"  href="index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=subscriber&amp;task=choose" rel="{handler: \'iframe\', size: {x: 800, y: 500}}"><img class="icon16" src="'.ACYMAILING_IMAGES.'icons/icon-16-edit.png" alt="'.JText::_('ACY_SELECTUSER',true).'"/></a>'; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>

			</td>
			<td>
				<button type="submit" class="btn btn-primary" onclick="submitbutton('test');return false;"><?php echo JText::_('SEND_TEST')?></button>
			</td>
		</tr>
	</table>
</fieldset>

	<table class="adminform" style="width:100%">
		<tr>
			<td width="50%" valign="top">
				<table class="adminform">
					<tr>
						<td>
							<label for="name">
								<?php echo JText::_( 'TEMPLATE_NAME' ); ?>
							</label>
						</td>
						<td>
							<input type="text" name="data[template][name]" id="name" class="inputbox" style="width:200px" value="<?php echo $this->escape(@$this->template->name); ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="published">
								<?php echo JText::_( 'ACY_PUBLISHED' ); ?>
							</label>
						</td>
						<td>
							<?php echo JHTML::_('acyselect.booleanlist', "data[template][published]" , '',@$this->template->published); ?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="default">
								<?php echo JText::_( 'ACY_DEFAULT' ); ?>
							</label>
						</td>
						<td>
							<?php echo JHTML::_('acyselect.booleanlist', "data[template][premium]" , '',@$this->template->premium); ?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="bgcolor">
								<?php echo JText::_( 'BACKGROUND_COLOUR' ); ?>
							</label>
						</td>
						<td>
							<?php echo $this->colorBox->displayAll('','styles[color_bg]',@$this->template->styles['color_bg']); ?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="description">
								<?php echo JText::_( 'ACY_DESCRIPTION' ); ?>
							</label>
						</td>
						<td>
							<textarea id="description" name="editor_description" style="width:80%" cols="60" rows="10"><?php echo @$this->template->description; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label for="subject">
								<?php echo JText::_( 'JOOMEXT_SUBJECT' ); ?>
							</label>
						</td>
						<td>
							<input type="text" id="subject" name="data[template][subject]" class="inputbox" style="width:80%" value="<?php echo $this->escape(@$this->template->subject); ?>" />
						</td>
					</tr>
					<tr>
							<td class="paramlist_key">
								<label for="fromname"><?php echo JText::_( 'FROM_NAME' ); ?></label>
							</td>
							<td class="paramlist_value">
								<input class="inputbox" id="fromname" type="text" name="data[template][fromname]" style="width:200px" value="<?php echo $this->escape(@$this->template->fromname); ?>" />
							</td>
						</tr>
					<tr>
							<td class="paramlist_key">
								<label for="fromemail"><?php echo JText::_( 'FROM_ADDRESS' ); ?></label>
							</td>
							<td class="paramlist_value">
								<input class="inputbox" id="fromemail" type="text" name="data[template][fromemail]" style="width:200px" value="<?php echo $this->escape(@$this->template->fromemail); ?>" />
							</td>
						</tr>
						<tr>
						<td class="paramlist_key">
							<label for="replyname"><?php echo JText::_( 'REPLYTO_NAME' ); ?></label>
							</td>
							<td class="paramlist_value">
								<input class="inputbox" id="replyname" type="text" name="data[template][replyname]" style="width:200px" value="<?php echo $this->escape(@$this->template->replyname); ?>" />
							</td>
						</tr>
						<tr>
						<td class="paramlist_key">
							<label for="replyemail"><?php echo JText::_( 'REPLYTO_ADDRESS' ); ?></label>
							</td>
							<td class="paramlist_value">
								<input class="inputbox" id="replyemail" type="text" name="data[template][replyemail]" style="width:200px" value="<?php echo $this->escape(@$this->template->replyemail); ?>" />
							</td>
					</tr>
				</table>
			</td>
			<td valign="top">
			<?php
				echo $this->tabs->startPane( 'template_css');
				echo $this->tabs->startPanel( JText::_( 'STYLE_IND' ), 'template_css_classes'); ?>
					<br style="font-size:1px"/>

					<table width="100%" >
						<tbody id="classtable">
						<?php $tagList = array('tag_h1' => 'Title h1',
												 'tag_h2' => 'Title h2',
												 'tag_h3' => 'Title h3',
												 'tag_h4' => 'Title h4',
												 'tag_h5' => 'Title h5',
												 'tag_h6' => 'Title h6',
												 'tag_a' => JText::_('ACY_LINK_STYLE'),
												 'acymailing_unsub' => JText::_('STYLE_UNSUB'),
												 'acymailing_content' => JText::_('CONTENT_AREA'),
												 'acymailing_title' => JText::_('CONTENT_HEADER'),
												 'acymailing_readmore' => JText::_('CONTENT_READMORE'),
												 'acymailing_online'=> JText::_('STYLE_VIEW'));
							foreach($tagList as $value => $text){ ?>
								<tr>
									<td><span id="name_<?php echo $value;?>" style="<?php echo str_replace('!important','',$this->escape(@$this->template->styles[$value]));?>"><?php echo $text; ?></span></td>
									<td><input id="style_<?php echo $value;?>" type="text" style="width:200px" onclick="showthediv('<?php echo $value;?>',event);" name="styles[<?php echo $value; ?>]" value="<?php echo $this->escape(@$this->template->styles[$value]); ?>"/></td>
								</tr>
							<?php
							}
							?>
								<tr>
									<td><ul id="name_tag_ul" style="<?php echo $this->escape(@$this->template->styles['tag_ul']);?>">
											<li id="name_tag_li2" style="<?php echo $this->escape(@$this->template->styles['tag_li']);?>">ul</li>
											<li id="name_tag_li" style="<?php echo $this->escape(@$this->template->styles['tag_li']);?>">li</li>
										</ul>
									</td>
									<td><input type="text" id="style_tag_ul" onclick="showthediv('tag_ul',event);" style="width:200px" name="styles[tag_ul]" value="<?php echo $this->escape(@$this->template->styles['tag_ul']); ?>"/>
									<br/><input type="text" id="style_tag_li" onclick="showthediv('tag_li',event);" style="width:200px" name="styles[tag_li]" value="<?php echo $this->escape(@$this->template->styles['tag_li']); ?>"/></td>
								</tr>
							<?php
							unset($this->template->styles['color_bg']);unset($this->template->styles['tag_ul']);unset($this->template->styles['tag_li']);
							if(!empty($this->template->styles)){
								foreach($this->template->styles as $className => $style){
								if(isset($tagList[$className])) continue;
								?>
									<tr>
										<td><span id="name_<?php echo $className ?>" style="<?php echo $this->escape($style);?>"><?php echo $className ?></span></td>
										<td><input id="style_<?php echo $className ?>" type="text" style="width:200px" onclick="showthediv('<?php echo $className;?>',event);" name="styles[<?php echo $className; ?>]" value="<?php echo $this->escape($style); ?>"/></td>
									</tr>
								<?php
								}?>

							<?php  }
							?>
						</tbody>
					</table>

					<a onclick="addStyle();return false;" href="#" ><?php echo JText::_('ADD_STYLE'); ?></a>
				<?php echo $this->tabs->startPanel( JText::_( 'TEMPLATE_STYLESHEET' ), 'template_css_stylesheet');?>
				<br style="font-size:1px"/>
				<?php
						$messages = array();
						if(version_compare(PHP_VERSION, '5.0.0', '<')) $messages[] = 'Please make sure you use at least PHP 5.0.0';
						if(!class_exists('DOMDocument')) $messages[] = 'DOMDocument class not found';
						else{
							$xmldoc =@ new DOMDocument;
							if(!is_object($xmldoc) || !method_exists($xmldoc,'loadHTML')){
								$messages[] = 'Please make sure that php_domxml.dll on windows is removed before using the domdocument class as they cannot coexist.';
							}
						}
						if(!function_exists('mb_convert_encoding')) $messages[] = 'The php extension mbstring is not installed';
						if(!empty($messages)){
							$messages[] = 'The stylesheet can not be used';
							acymailing_display($messages,'warning');
						}else{ ?>
							<textarea onmouseover="document.getElementById('wysija').style.display = 'none'" name="data[template][stylesheet]" style="width:98%" rows="25"><?php echo @$this->template->stylesheet; ?></textarea>
						<?php }
					echo $this->tabs->endPanel();
					echo $this->tabs->endPane(); ?>
			</td>
		</tr>
	</table>
	<fieldset class="adminform" width="100%">
		<legend><?php echo JText::_( 'HTML_VERSION' ); ?></legend>
		<?php echo $this->editor->display(); ?>
	</fieldset>
	<fieldset class="adminform" >
		<legend><?php echo JText::_( 'TEXT_VERSION' ); ?></legend>
		<textarea style="width:98%" rows="20" name="data[template][altbody]" id="altbody" ><?php echo @$this->template->altbody; ?></textarea>
	</fieldset>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo @$this->template->tempid; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="template" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<div style="display:none;position:absolute;background-color=#FFFFFF;" id="wysija">
	<?php echo $this->colorBox->displayOne('wysijacolor',"",""); ?>
	<select style="width:75px;height:17px;margin:0px;font-size:11px;" class="chzn-done" id="style_select_wysija" onchange="getValueSelect()">
		<?php $nbs = array('8','10','11','12','14','16','18','20','22','24','26','36');
		echo "<option value=''>Font Size</option>";
		foreach($nbs as $nb){
			echo "<option value='".$nb."px'>$nb px.</option>";
		}?>
	</select>

	<span id="B" onclick="spanChange('B')" class="belement"></span><span id="I" class="ielement" onclick="spanChange('I')"></span><span class="uelement" id="U" onclick="spanChange('U')"></span>
 </div>
</div>
