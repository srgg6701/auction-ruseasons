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

class TemplateController extends acymailingController{

	var $pkey = 'tempid';
	var $table = 'template';
	var $aclCat = 'templates';

	function load(){
		$class = acymailing_get('class.template');
		$tempid = JRequest::getInt('tempid');
		if(empty($tempid)) exit;
		$template = $class->get($tempid);


		echo $class->buildCSS($template->styles,$template->stylesheet);
		exit;
	}


	function remove(){
		if(!$this->isAllowed($this->aclCat,'delete')) return;
		JRequest::checkToken() or die( 'Invalid Token' );
		$app = JFactory::getApplication();
		$app->isAdmin() or die('Only from the back-end');

		$cids = JRequest::getVar( 'cid', array(), '', 'array' );

		$class = acymailing_get('class.template');
		$num = $class->delete($cids);

		$app->enqueueMessage(JText::sprintf('SUCC_DELETE_ELEMENTS',$num), 'message');

		return $this->listing();
	}

	function copy(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		$cids = JRequest::getVar( 'cid', array(), '', 'array' );
		$db = JFactory::getDBO();
		$time = time();

		$query = 'INSERT IGNORE INTO `#__acymailing_template` (`name`, `description`, `body`, `altbody`, `created`, `published`, `premium`, `ordering`, `namekey`, `styles`, `subject`,`stylesheet`,`fromname`,`fromemail`,`replyname`,`replyemail`)';
		$query .= " SELECT CONCAT('copy_',`name`), `description`, `body`, `altbody`, $time, `published`, 0, `ordering`, CONCAT('$time',`tempid`,`namekey`), `styles`, `subject`,`stylesheet`,`fromname`,`fromemail`,`replyname`,`replyemail` FROM `#__acymailing_template` WHERE `tempid` IN (".implode(',',$cids).')';
		$db->setQuery($query);
		$db->query();

		return $this->listing();
	}

	function store(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		$app = JFactory::getApplication();
		$app->isAdmin() or die('Only from the back-end');

		$templateClass = acymailing_get('class.template');
		$status = $templateClass->saveForm();
		if($status){
			$app->enqueueMessage(JText::_( 'JOOMEXT_SUCC_SAVED' ), 'message');
		}else{
			$app->enqueueMessage(JText::_( 'ERROR_SAVING' ), 'error');
			if(!empty($mailClass->errors)){
				foreach($mailClass->errors as $oneError){
					$app->enqueueMessage($oneError, 'error');
				}
			}
		}
	}

	function theme(){
		if(!$this->isAllowed($this->aclCat,'view')) return;
		JRequest::setVar( 'layout', 'theme'  );
		return parent::display();
	}

	function upload(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::setVar( 'layout', 'upload'  );
		return parent::display();
	}

	function doupload(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$importFile =  JRequest::getVar( 'uploadedfile', '', 'files');

		if(empty($importFile['name'])){
			acymailing_display(JText::_('BROWSE_FILE'),'error');
			return $this->upload();
		}

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.path');

		$config =& acymailing_config();

		$uploadPath = JPath::clean(ACYMAILING_ROOT.'media'.DS.ACYMAILING_COMPONENT.DS.'templates');

		if(!is_writable($uploadPath)){
			@chmod($uploadPath,'0755');
			if(!is_writable($uploadPath)){
				acymailing_display(JText::sprintf( 'WRITABLE_FOLDER',$uploadPath), 'warning');
			}
		}

		if (!(bool) ini_get('file_uploads')) {
			acymailing_display('Can not upload the file, please make sure file_uploads is enabled on your php.ini file','error');
			return $this->upload();
		}

		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return $this->upload();
		}

		$filename = strtolower(JFile::makeSafe($importFile['name']));
		$extension = strtolower(substr($filename,strrpos($filename,'.')+1));

		if(!in_array($extension,array('zip','tar.gz'))){
			acymailing_display(JText::sprintf( 'ACCEPTED_TYPE',$extension,'zip,tar.gz'),'error');
			return $this->upload();
		}

		$joomConfig = JFactory::getConfig();
		$jpath = ACYMAILING_J30 ? $joomConfig->get('tmp_path') : $joomConfig->getValue('config.tmp_path');
		$tmp_dest 	= JPath::clean($jpath.DS.$filename);
		$tmp_src	= $importFile['tmp_name'];

		$uploaded = JFile::upload($tmp_src, $tmp_dest);
		if(!$uploaded){
			acymailing_display('Error uploading the file from '.$tmp_src.' to '.$tmp_dest,'error');
			return $this->upload();
		}

		$tmpdir = uniqid().'_template';

		$extractdir = JPath::clean(dirname($tmp_dest).DS.$tmpdir);

		$result = JArchive::extract( $tmp_dest, $extractdir);
		JFile::delete($tmp_dest);

		if(!$result){
			acymailing_display('Error extracting the file '.$tmp_dest.' to '.$extractdir,'error');
			return $this->upload();
		}

		$templateClass = acymailing_get('class.template');
		if($templateClass->detecttemplates($extractdir)){
			$messages = $templateClass->templateNames;
			array_unshift($messages,JText::sprintf('TEMPLATES_INSTALL',count($templateClass->templateNames)));
			acymailing_display($messages,'success');
			if(is_dir($extractdir)) JFolder::delete($extractdir);
			$js = "setTimeout('redirect()',2000); function redirect(){window.top.location.href = 'index.php?option=com_acymailing&ctrl=template'; }";
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration( $js );
			return;
		}

		acymailing_display('Error installing template','error');
		if(is_dir($extractdir)) JFolder::delete($extractdir);
		return $this->upload();
	}

	function test(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		$this->store();

		$tempid = acymailing_getCID('tempid');
		$receiver_type = JRequest::getVar('receiver_type','','','string');
		if(empty($tempid) OR empty($receiver_type)) return false;

		$mailer = acymailing_get('helper.mailer');
		$mailer->report = true;
		$config = acymailing_config();
		$subscriberClass = acymailing_get('class.subscriber');
		$userHelper = acymailing_get('helper.user');
		JPluginHelper::importPlugin('acymailing');
		$dispatcher = JDispatcher::getInstance();
		$app = JFactory::getApplication();

		$receivers = array();
		if($receiver_type == 'user'){
			$user = JFactory::getUser();
			$receivers[] = $user->email;
		}elseif($receiver_type == 'other'){
			$receiverEntry = JRequest::getVar('test_email','','','string');
			if(substr_count($receiverEntry,'@')>1){
				$receivers = explode(' ',trim(preg_replace('# +#',' ',str_replace(array(';',','),' ',$receiverEntry))));
			}else{
				$receivers[] = trim($receiverEntry);
			}
		}else{
			$gid = substr($receiver_type,strpos($receiver_type,'_')+1);
			if(empty($gid)) return false;
			$db = JFactory::getDBO();
			$db->setQuery('SELECT email from '.acymailing_table('users',false).' WHERE gid = '.intval($gid));
			$receivers = acymailing_loadResultArray($db);
		}

		if(empty($receivers)){
			$app->enqueueMessage(JText::_('NO_SUBSCRIBER'), 'notice');
			return $this->edit();
		}

		$classTemplate = acymailing_get('class.template');
		$myTemplate = $classTemplate->get($tempid);
		$myTemplate->sendHTML = 1;
		$myTemplate->mailid = 0;
		if(empty($myTemplate->subject))  $myTemplate->subject = $myTemplate->name;
		if(empty($myTemplate->altBody)) $myTemplate->altbody = $mailer->textVersion($myTemplate->body);
		$dispatcher->trigger('acymailing_replacetags',array(&$myTemplate,true));

		$myTemplate->body = acymailing_absoluteURL($myTemplate->body);

		$result = true;
		foreach($receivers as $receiveremail){
			$copy = $myTemplate;
			$mailer->clearAll();
			$mailer->setFrom($copy->fromemail,$copy->fromname);
			if(!empty($copy->replyemail)){
				$replyToName = $config->get('add_names',true) ? $mailer->cleanText($copy->replyname) : '';
				$mailer->AddReplyTo($mailer->cleanText($copy->replyemail),$replyToName);
			}

			$receiver = $subscriberClass->get($receiveremail);
			if(empty($receiver->subid)){
				if($userHelper->validEmail($receiveremail)){
					$newUser = new stdClass();
					$newUser->email = $receiveremail;
					$subscriberClass->sendConf = false;
					$subid = $subscriberClass->save($newUser);
					$receiver = $subscriberClass->get($subid);
				}
				if(empty($receiver->subid)) continue;
			}

			$addedName = $config->get('add_names',true) ? $mailer->cleanText($receiver->name) : '';
			$mailer->AddAddress($mailer->cleanText($receiver->email),$addedName);

			$dispatcher->trigger('acymailing_replaceusertags',array(&$copy,&$receiver,true));
			$mailer->IsHTML(true);
			$mailer->sendHTML = true;
			$mailer->Body = $copy->body;
			$mailer->Subject = $copy->subject;
			if($config->get('multiple_part',false)){
				$mailer->AltBody = $copy->altbody;
			}

			$mailer->send();
		}

		return $this->edit();
	}
}
