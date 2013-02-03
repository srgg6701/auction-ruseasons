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

class SendController extends acymailingController{

	function sendready(){
		if(!$this->isAllowed('newsletters','send')) return;
		JRequest::setVar( 'layout', 'sendconfirm'  );
		return parent::display();
	}

	function send(){
		if(!$this->isAllowed('newsletters','send')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		JRequest::setVar('tmpl','component');
		$mailid = acymailing_getCID('mailid');
		if(empty($mailid)) exit;

		$user = JFactory::getUser();
		$time = time();
		$queueClass = acymailing_get('class.queue');
		$queueClass->onlynew = JRequest::getInt('onlynew');
		$queueClass->mindelay = JRequest::getInt('mindelay');
		$totalSub = $queueClass->queue($mailid,$time);

		if(empty($totalSub)){
			acymailing_display(JText::_('NO_RECEIVER'),'warning');
			return;
		}

		$mailObject = new stdClass();
		$mailObject->senddate = $time;
		$mailObject->published = 1;
		$mailObject->mailid = $mailid;
		$mailObject->sentby = $user->id;
		$db = JFactory::getDBO();
		$db->updateObject(acymailing_table('mail'),$mailObject,'mailid');

		$config =& acymailing_config();
		$queueType = $config->get('queue_type');
		if($queueType=='onlyauto'){
			$messages = array();
			$messages[] = JText::sprintf('ADDED_QUEUE',$totalSub);
			$messages[] = JText::_('AUTOSEND_CONFIRMATION');
			acymailing_display($messages,'success');
			return;
		}else{
			JRequest::setVar( 'totalsend', $totalSub );
			$app = JFactory::getApplication();
			$app->redirect(acymailing_completeLink('send&task=continuesend&mailid='.$mailid.'&totalsend='.$totalSub,true,true));
			exit;
		}
	}

	function continuesend(){
		$config = acymailing_config();
		$newcrontime = time() + 120;
		if($config->get('cron_next') < $newcrontime){
			$newValue = new stdClass();
			$newValue->cron_next = $newcrontime;
			$config->save($newValue);
		}

		$mailid = acymailing_getCID('mailid');

		$totalSend = JRequest::getVar( 'totalsend',0,'','int');
		$alreadySent = JRequest::getVar( 'alreadysent',0,'','int');

		$helperQueue = acymailing_get('helper.queue');
		$helperQueue->mailid = $mailid;
		$helperQueue->report = true;
		$helperQueue->total = $totalSend;
		$helperQueue->start = $alreadySent;
		$helperQueue->pause = $config->get('queue_pause');
		$helperQueue->process();



	}

}
