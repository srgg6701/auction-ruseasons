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


class EmailViewEmail extends acymailingView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();


		parent::display($tpl);

	}

	function form(){

		acymailing_loadMootools();

		$mailid = acymailing_getCID('mailid');
		if(empty($mailid)) $mailid = JRequest::getString('mailid');

		$mailClass = acymailing_get('class.mail');
		$mail = $mailClass->get($mailid);

		if(empty($mail)){
			$config =& acymailing_config();

			$mail->created = time();
			$mail->fromname = $config->get('from_name');
			$mail->fromemail = $config->get('from_email');
			$mail->replyname = $config->get('reply_name');
			$mail->replyemail = $config->get('reply_email');
			$mail->subject = '';
			$mail->type = JRequest::getString('type');
			$mail->published = 1;
			$mail->visible = 0;
			$mail->html = 1;
			$mail->body = '';
			$mail->altbody = '';
			$mail->tempid = 0;
		};


		$values = new stdClass();
		$values->maxupload = (acymailing_bytes(ini_get('upload_max_filesize')) > acymailing_bytes(ini_get('post_max_size'))) ? ini_get('post_max_size') : ini_get('upload_max_filesize');


		$toggleClass = acymailing_get('helper.toggle');


		$editor = acymailing_get('helper.editor');
		$editor->name = 'editor_body';
		$editor->content = $mail->body;

		$js = "function updateAcyEditor(htmlvalue){";
			$js .= 'if(htmlvalue == \'0\'){window.document.getElementById("htmlfieldset").style.display = \'none\'}else{window.document.getElementById("htmlfieldset").style.display = \'block\'}';
		$js .= '}';
		$js .='window.addEvent(\'load\', function(){ updateAcyEditor('.$mail->html.'); });';

		$script = 'function addFileLoader(){
		var divfile=window.document.getElementById("loadfile");
		var input = document.createElement(\'input\');
		input.type = \'file\';
		input.size = \'30\';
		input.name = \'attachments[]\';
		divfile.appendChild(document.createElement(\'br\'));
		divfile.appendChild(input);}
		';

		if(!ACYMAILING_J16){
			$script .= 'function submitbutton(pressbutton){
						if (pressbutton == \'cancel\') {
							submitform( pressbutton );
							return;
						}';
		}else{
			$script .= 'Joomla.submitbutton = function(pressbutton) {
						if (pressbutton == \'cancel\') {
							Joomla.submitform(pressbutton,document.adminForm);
							return;
						}';
		}
		$script .= 'if(window.document.getElementById("subject").value.length < 2){alert(\''.JText::_('ENTER_SUBJECT',true).'\'); return false;}';
		$script .= $editor->jsCode();
		if(!ACYMAILING_J16){
			$script .= 'submitform( pressbutton );} ';
		}else{ $script .= 'Joomla.submitform(pressbutton,document.adminForm);}; '; }

		$script .= "function insertTag(tag){ try{jInsertEditorText(tag,'editor_body'); document.getElementById('iframetag').style.display = 'none'; displayTags(); return true;} catch(err){alert('Your editor does not enable AcyMailing to automatically insert the tag, please copy/paste it manually in your Newsletter'); return false;}}";

		$iFrame = "'<iframe src=\'index.php?option=com_acymailing&ctrl=tag&task=tag&type=news&tmpl=component\' width=\'100%\' height=\'100%\' scrolling=\'auto\'></iframe>'";
		$script .= "var openTag = true;
					function displayTags(){var box=$('iframetag'); if(openTag){box.innerHTML = ".$iFrame."; box.setStyle('display','block');}
					try{
						var fx = box.effects({duration: 1500, transition: Fx.Transitions.Quart.easeOut});
						if(openTag){fx.start({'height': 300});}else{fx.start({'height': 0}).chain(function() {box.innerHTML = '';box.setStyle('display','none');})};
					}catch(err){
						box.style.height = '300px';
						var myVerticalSlide = new Fx.Slide('iframetag');
						if(openTag){
							myVerticalSlide.slideIn();
						}else{
							myVerticalSlide.slideOut().chain(function() {
								box.innerHTML='';
								box.setStyle('display','none');
							});
						}
					}
					openTag = !openTag;}";

		$iFrame = "'<iframe src=\'index.php?option=com_acymailing&ctrl=template&task=theme&tmpl=component\' width=\'100%\' height=\'100%\' scrolling=\'auto\'></iframe>'";
		$script .= "var openTemplate = true;
					function displayTemplates(){var box=$('iframetemplate'); if(openTemplate){box.innerHTML = ".$iFrame."; box.setStyle('display','block');}
					try{
						var fx = box.effects({duration: 1500, transition: Fx.Transitions.Quart.easeOut});
						if(openTemplate){fx.start({'height': 300});}else{fx.start({'height': 0}).chain(function() {box.innerHTML = '';box.setStyle('display','none');})};
					}catch(err){
						box.style.height = '300px';
						var myVerticalSlide = new Fx.Slide('iframetemplate');
						if(openTemplate){
							myVerticalSlide.slideIn();
						}else{
							myVerticalSlide.slideOut().chain(function() {
								box.innerHTML='';
								box.setStyle('display','none');
							});
						}
					}
					openTemplate = !openTemplate;}";

		$script .= "function changeTemplate(newhtml,newtext,newsubject,stylesheet,fromname,fromemail,replyname,replyemail,tempid){
			if(newhtml.length>2){".$editor->setContent('newhtml')."}
			var vartextarea =$('altbody'); if(newtext.length>2) vartextarea.innerHTML = newtext;
			document.getElementById('tempid').value = tempid;
			if(fromname.length>1){document.getElementById('fromname').value = fromname;}
			if(fromemail.length>1){document.getElementById('fromemail').value = fromemail;}
			if(replyname.length>1){document.getElementById('replyname').value = replyname;}
			if(replyemail.length>1){document.getElementById('replyemail').value = replyemail;}
			if(newsubject.length>1){document.getElementById('subject').value = newsubject;}
			document.getElementById('iframetemplate').style.display = 'none'; displayTemplates();
		}
		";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js.$script );

		$this->assignRef('toggleClass',$toggleClass);
		$this->assignRef('editor',$editor);
		$this->assignRef('values',$values);
		$this->assignRef('mail',$mail);
		$tabs = acymailing_get('helper.acytabs');
		$tabs->setOptions(array('useCookie' => true));
		$this->assignRef('tabs',$tabs);

	}


}
