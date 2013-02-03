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

class mailClass extends acymailingClass{

	var $tables = array('queue','listmail','stats','userstats','urlclick','mail');
	var $pkey = 'mailid';
	var $namekey = 'alias';
	var $allowedFields = array('subject','published','fromname','fromemail','replyname', 'replyemail', 'type','visible','alias','html','tempid','altbody','filter','metakey','metadesc');

	function get($id,$default = null){

		if(empty($id)) return null;

		$query = 'SELECT a.* FROM '.acymailing_table('mail').' as a WHERE ';
		$query .=  is_numeric($id) ? 'a.mailid' : 'a.alias';
		$query .= ' = '.$this->database->Quote($id);
		$query .= ' LIMIT 1';

		$this->database->setQuery($query);
		$mail =  $this->database->loadObject();

		if(!empty($mail->userid)){
			$this->database->setQuery('SELECT b.username,b.name,b.email FROM #__users as b WHERE b.id = '.intval($mail->userid).' LIMIT 1');
			$author = $this->database->loadObject();
			if(!empty($author)){
				foreach($author as $var => $value){
					$mail->$var = $value;
				}
			}
		}

		if(!empty($mail)){
			$mail->attach = empty($mail->attach) ? array() : unserialize($mail->attach);
			$mail->params = empty($mail->params) ? array() : unserialize($mail->params);
			$mail->filter = empty($mail->filter) ? array() : unserialize($mail->filter);
		}

		return $mail;

	}

	function saveForm(){
		$app = JFactory::getApplication();
		$db= JFactory::getDBO();
		$config =& acymailing_config();

		$mail = new stdClass();
		$mail->mailid = acymailing_getCID('mailid');

		$formData = JRequest::getVar( 'data', array(), '', 'array' );

		foreach($formData['mail'] as $column => $value){
			if($app->isAdmin() OR in_array($column,$this->allowedFields)){
				acymailing_secureField($column);
				if($column == 'params'){
					$mail->$column = $value;
				}else{
					$mail->$column = strip_tags($value);
				}
			}
		}

		$mail->body = JRequest::getVar('editor_body','','','string',JREQUEST_ALLOWRAW);

		$acypluginsHelper = acymailing_get('helper.acyplugins');
		$acypluginsHelper->cleanHtml($mail->body);

		$mail->attach = array();
		$attachments = JRequest::getVar( 'attachments', array(), 'files', 'array' );

		if(!empty($attachments['name'][0]) OR !empty($attachments['name'][1])){

			jimport('joomla.filesystem.file');

			$uploadFolder = JPath::clean(html_entity_decode($config->get('uploadfolder')));
			$uploadFolder = trim($uploadFolder,DS.' ').DS;
			$uploadPath = JPath::clean(ACYMAILING_ROOT.$uploadFolder);

			acymailing_createDir($uploadPath,true);

			if(!is_writable($uploadPath)){
				@chmod($uploadPath,'0755');
				if(!is_writable($uploadPath)){
					$app->enqueueMessage(JText::sprintf( 'WRITABLE_FOLDER',$uploadPath), 'notice');
				}
			}

			foreach($attachments['name'] as $id => $filename){
				if(empty($filename)) continue;
				$attachment = new stdClass();
				$attachment->filename = strtolower(JFile::makeSafe($filename));
				$attachment->size = $attachments['size'][$id];

				if(!preg_match('#\.('.str_replace(array(',','.'),array('|','\.'),$config->get('allowedfiles')).')$#Ui',$attachment->filename,$extension) || preg_match('#\.(php.?|.?htm.?|pl|py|jsp|asp|sh|cgi)#Ui',$attachment->filename)){
					$app->enqueueMessage(JText::sprintf( 'ACCEPTED_TYPE',substr($attachment->filename,strrpos($attachment->filename,'.')+1),$config->get('allowedfiles')), 'notice');
					continue;
				}
				$attachment->filename = str_replace(array('.',' '),'_',substr($attachment->filename,0,strpos($attachment->filename,$extension[0]))).$extension[0];

				if(!JFile::upload($attachments['tmp_name'][$id], $uploadPath . $attachment->filename)){
					if ( !move_uploaded_file($attachments['tmp_name'][$id], $uploadPath . $attachment->filename)) {
						$app->enqueueMessage(JText::sprintf( 'FAIL_UPLOAD','<b><i>'.$attachments['tmp_name'][$id].'</i></b>','<b><i>'.$uploadPath . $attachment->filename.'</i></b>'), 'error');
						continue;
					}
				}

				$mail->attach[] = $attachment;
			}
		}

		if(isset($mail->filter)){
			$mail->filter = array();
			$filterData = JRequest::getVar('filter');
			foreach($filterData['type'] as $num => $oneType){
				if(empty($oneType)) continue;
				$mail->filter['type'][$num] = $oneType;
				$mail->filter[$num][$oneType] = $filterData[$num][$oneType];
			}
		}

		$toggleHelper = acymailing_get('helper.toggle');
		if(!empty($mail->type) && $mail->type == 'followup' && !empty($mail->mailid)){
			$oldMail = $this->get($mail->mailid);
			if(!empty($mail->published) AND !$oldMail->published){
				$this->_publishfollowup($mail->mailid);
			}
			if($oldMail->senddate != $mail->senddate){
				$text = JText::_('FOLLOWUP_CHANGED_DELAY_INFORMED');
				$text .= ' '.$toggleHelper->toggleText('update',$mail->mailid,'followup',JText::_('FOLLOWUP_CHANGED_DELAY'));
				$app->enqueueMessage($text, 'notice');
			}
		}

		if(preg_match('#<a[^>]*subid=[0-9].*</a>#Uis',$mail->body,$pregResult)){
			$app->enqueueMessage('There is a personal link in your Newsletter ( '.$pregResult[0].' ) instead of a tag...<br/>Please make sure to not copy/paste the link you received in your e-mail as it may break your unsubscribe or confirmation links.<br/>Use our tags instead!','notice');
		}

		$mailid = $this->save($mail);
		if(!$mailid) return false;
		JRequest::setVar( 'mailid', $mailid);

		$status = true;

		if(!empty($formData['listmail'])){
			$receivers = array();
			$remove = array();

			foreach($formData['listmail'] as $listid => $receiveme){
				if(!empty($receiveme)){
					$receivers[] = $listid;
				}else{
					$remove[] = $listid;
				}
			}

			$listMailClass = acymailing_get('class.listmail');
			$status = $listMailClass->save($mailid,$receivers,$remove);
		}

		if(!empty($mail->type) && $mail->type == 'followup' && empty($mail->mailid) && !empty($mail->published)){
			$this->_publishfollowup($mailid);
		}

		return $status;

	}

	function _publishfollowup($mailid){
		$listmailClass = acymailing_get('class.listmail');
		$mycampaign = $listmailClass->getCampaign($mailid);
		if(empty($mycampaign->listid)){
			return;
		}

		$db = JFactory::getDBO();
		$toggleHelper = acymailing_get('helper.toggle');
		$db->setQuery('SELECT subid FROM `#__acymailing_listsub` as b WHERE b.`status` = 1 AND b.`listid` = '.$mycampaign->listid.' LIMIT 1');
		$oneuser = $db->loadResult();
		if(empty($oneuser)) return;

		$text = JText::_('FOLLOWUP_PUBLISHED_INFORMED');
		$text .= ' '.$toggleHelper->toggleText('add',$mailid,'followup',JText::_('FOLLOWUP_PUBLISHED'));
		$app = JFactory::getApplication();
		$app->enqueueMessage($text, 'notice');
	}

	function save($mail){

		if(isset($mail->alias) OR empty($mail->mailid)){
			if(empty($mail->alias)) $mail->alias = $mail->subject;
			$mail->alias = JFilterOutput::stringURLSafe(trim($mail->alias));
		}

		if(empty($mail->mailid)){
			if(empty($mail->created)) $mail->created = time();
			if(empty($mail->userid)){
				$user = JFactory::getUser();
				$mail->userid = $user->id;
			}
			if(empty($mail->key)) $mail->key = md5($mail->alias.time());
		}else{
			if(!empty($mail->attach)){
				$oldMailObject = $this->get($mail->mailid);
				if(!empty($oldMailObject)){
					$mail->attach = array_merge($oldMailObject->attach,$mail->attach);
				}
			}
		}

		if(!empty($mail->attach) AND !is_string($mail->attach)) $mail->attach = serialize($mail->attach);
		if(isset($mail->filter) AND !is_string($mail->filter)) $mail->filter = serialize($mail->filter);

		if(!empty($mail->params)){
			if(!empty($mail->params['lastgenerateddate']) && !is_numeric($mail->params['lastgenerateddate'])){
				$mail->params['lastgenerateddate'] = acymailing_getTime($mail->params['lastgenerateddate']);
			}
			$mail->params = serialize($mail->params);
		}

		if(!empty($mail->senddate) && !is_numeric($mail->senddate)){
			$mail->senddate = acymailing_getTime($mail->senddate);
		}

		if(empty($mail->mailid)){
			$status = $this->database->insertObject(acymailing_table('mail'),$mail);
		}else{
			$status = $this->database->updateObject(acymailing_table('mail'),$mail,'mailid');
		}

		if(!$status){
			$this->errors[] = substr(strip_tags($this->database->getErrorMsg()),0,200).'...';
		}

		if(!empty($mail->params) AND is_string($mail->params)) $mail->params = unserialize($mail->params);
		if(!empty($mail->attach) AND is_string($mail->attach)) $mail->attach = unserialize($mail->attach);

		if($status) return empty($mail->mailid) ? $this->database->insertid() : $mail->mailid;
		return false;
	}

}
