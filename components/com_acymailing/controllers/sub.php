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

class SubController extends acymailingController{

	function notask(){
		$redirectUrl = urldecode(JRequest::getVar('redirect','','','string'));
		$this->_checkRedirectUrl($redirectUrl);
		$this->setRedirect($redirectUrl,'Please enable the Javascript to be able to subscribe','notice');
		return false;
	}

	function display($dummy1 = false, $dummy2 = false){
		$moduleId = JRequest::getInt('formid');
		if(empty($moduleId)) return;

		if(JRequest::getInt('interval') > 0) setcookie('acymailingSubscriptionState', true, time() + JRequest::getInt('interval'), '/');

		$db = JFactory::getDBO();
	 	$db->setQuery('SELECT * FROM #__modules WHERE id = '.$moduleId.' AND `module` LIKE \'%acymailing%\' LIMIT 1');
	 	$module = $db->loadObject();
	 	if(empty($module)){ echo 'No module found'; exit; }

		$module->user  	= substr( $module->module, 0, 4 ) == 'mod_' ?  0 : 1;
		$module->name = $module->user ? $module->title : substr( $module->module, 4 );
		$module->style = null;
		$module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);

		$params = array();

		echo JModuleHelper::renderModule($module, $params);
	}

	function optin(){
		acymailing_checkRobots();
		$my = JFactory::getUser();
		$config = acymailing_config();


		$userClass = acymailing_get('class.subscriber');

		$redirectUrl = urldecode(JRequest::getVar('redirect','','','string'));

		$user = new stdClass();
		$formData = JRequest::getVar( 'user', array(), '', 'array' );

		if(!empty($formData)){
			$userClass->checkFields($formData,$user);
		}

		$allowUserModifications = (bool) ($config->get('allow_modif','data') == 'all');
		$allowSubscriptionModifications = (bool) ($config->get('allow_modif','data') != 'none');

		if(empty($user->email)){
			$connectedUser = $userClass->identify(true);
			if(!empty($connectedUser->email)){
				$user->email = $connectedUser->email;
				$allowUserModifications = true;
				$allowSubscriptionModifications = true;
			}
		}

		$user->email =  trim($user->email);

		$ajax = JRequest::getInt('ajax',0);
		if($ajax) header("Content-type:text/html; charset=utf-8");

		$userHelper = acymailing_get('helper.user');
		if(empty($user->email) || !$userHelper->validEmail($user->email,true)){
			if ($ajax) echo '{"message":"'.JText::_('VALID_EMAIL').'","type":"error","code":"0"}';
			else echo "<script>alert('".JText::_('VALID_EMAIL',true)."'); window.history.go(-1);</script>";
			exit;
		}

		$alreadyExists = $userClass->get($user->email);

		if(!empty($alreadyExists->subid)){
			if(!empty($alreadyExists->userid)) unset($user->name);
			$user->subid = $alreadyExists->subid;
			$currentSubscription = $userClass->getSubscriptionStatus($alreadyExists->subid);
		}else{
			$allowSubscriptionModifications = true;
			$allowUserModifications = true;
			$currentSubscription = array();
		}

		$user->accept = 1;

		if($allowUserModifications){
			$userClass->recordHistory = true;
			$user->subid = $userClass->save($user);
		}

		$myuser = $userClass->get($user->subid);
		if(empty($myuser->subid)){
			if ($ajax) echo '{"message":"Could not save the user","type":"error","code":"1"}';
			else echo "<script>alert('Could not save the user'); window.history.go(-1);</script>";
			exit;
		}

		if(empty($myuser->accept)){
			$myuser->accept = 1;
			$userClass->save($myuser);
		}

		if(!$allowUserModifications && !empty($myuser->subid) && empty($myuser->confirmed)){
			$userClass->sendConf($myuser->subid);
		}

		$statusAdd = (empty($myuser->confirmed) AND $config->get('require_confirmation',false)) ? 2 : 1;

		$addlists = array();
		$updatelists = array();

		$hiddenlistsstring = JRequest::getVar('hiddenlists','','','string');
		if(!empty($hiddenlistsstring)){

			$hiddenlists = explode(',',$hiddenlistsstring);

			JArrayHelper::toInteger($hiddenlists);

			foreach($hiddenlists as $id => $idOneList){
				if(!isset($currentSubscription[$idOneList])){
					$addlists[$statusAdd][] = $idOneList;
					continue;
				}

				if($currentSubscription[$idOneList]->status == $statusAdd || $currentSubscription[$idOneList]->status == 1) continue;

				$updatelists[$statusAdd][] = $idOneList;
			}
		}

		$visibleSubscription = JRequest::getVar('subscription','','','array');

		if(!empty($visibleSubscription)){
			foreach($visibleSubscription as $idOneList){
				if(empty($idOneList)) continue;

				if(!isset($currentSubscription[$idOneList])){
					$addlists[$statusAdd][] = $idOneList;
					continue;
				}

				if($currentSubscription[$idOneList]->status == $statusAdd || $currentSubscription[$idOneList]->status == 1) continue;

				$updatelists[$statusAdd][] = $idOneList;
			}
		}

		$visiblelistsstring = JRequest::getVar('visiblelists','','','string');

		if(!empty($visiblelistsstring)){

			$visiblelist = explode(',',$visiblelistsstring);
			JArrayHelper::toInteger($visiblelist);

			foreach($visiblelist as $idList){
				if(!in_array($idList,$visibleSubscription) AND !empty($currentSubscription[$idList]) AND $currentSubscription[$idList]->status != '-1'){
					$updatelists['-1'][] = $idList;
				}
			}
		}

		$listsubClass = acymailing_get('class.listsub');
		$status = true;
		$updateMessage = false;
		$insertMessage = false;
		if($allowSubscriptionModifications){
			if(!empty($updatelists)){
				$status = $listsubClass->updateSubscription($myuser->subid,$updatelists) && $status;
				$updateMessage = true;
			}
			if(!empty($addlists)){
				$status = $listsubClass->addSubscription($myuser->subid,$addlists) && $status;
				$insertMessage = true;
			}
		}else{
			$mailClass = acymailing_get('helper.mailer');
			$mailClass->checkConfirmField = false;
			$mailClass->checkEnabled = false;
			$mailClass->report = false;
			$modifySubscriptionSuccess = $mailClass->sendOne('modif',$myuser->subid);
			$modifySubscriptionError = $mailClass->reportMessage;
		}

		$userClass->sendNotification();

		if($config->get('subscription_message',1) || $ajax){

			$app = JFactory::getApplication();

			if($allowSubscriptionModifications){
				if($statusAdd == 2){
					if($userClass->confirmationSentSuccess){
						if ($ajax) echo '{"message":"'.JText::_('CONFIRMATION_SENT').'","type":"success","code":"2"}';
						elseif(empty($redirectUrl)) acymailing_display(JText::_('CONFIRMATION_SENT'),'info');
						else $app->enqueueMessage(JText::_('CONFIRMATION_SENT'));
					}else{
						if ($ajax) echo '{"message":"'.JText::sprintf('SEND_ERROR','',$myuser->email).'","type":"error","code":"7"}';
						elseif(empty($redirectUrl)) acymailing_display($userClass->confirmationSentError,'error');
						else $app->enqueueMessage($userClass->confirmationSentError,'error');
					}
				}else{
					if($insertMessage){
						if ($ajax) echo '{"message":"'.JText::_('SUBSCRIPTION_OK').'","type":"success","code":"3"}';
						elseif(empty($redirectUrl)) acymailing_display(JText::_('SUBSCRIPTION_OK'),'info');
						else $app->enqueueMessage(JText::_('SUBSCRIPTION_OK'));
					}elseif($updateMessage){
						if ($ajax) echo '{"message":"'.JText::_('SUBSCRIPTION_UPDATED_OK').'","type":"success","code":"4"}';
						elseif(empty($redirectUrl)) acymailing_display(JText::_('SUBSCRIPTION_UPDATED_OK'),'info');
						else $app->enqueueMessage(JText::_('SUBSCRIPTION_UPDATED_OK'));
					}else{
						if ($ajax) echo '{"message":"'.JText::_('ALREADY_SUBSCRIBED').'","type":"success","code":"5"}';
						elseif(empty($redirectUrl)) acymailing_display(JText::_('ALREADY_SUBSCRIBED'),'info');
						else $app->enqueueMessage(JText::_('ALREADY_SUBSCRIBED'));
					}
				}
			}else{
				if($modifySubscriptionSuccess){
					if ($ajax) echo '{"message":"'.JText::_('IDENTIFICATION_SENT').'","type":"success","code":"6"}';
					elseif(empty($redirectUrl)) acymailing_display(JText::_('IDENTIFICATION_SENT'),'warning');
					else $app->enqueueMessage(JText::_( 'IDENTIFICATION_SENT' ), 'notice');
				}else{
					if ($ajax) echo '{"message":"'.JText::sprintf('SEND_ERROR','',$myuser->email).'","type":"error","code":"8"}';
					elseif(empty($redirectUrl)) acymailing_display($modifySubscriptionError,'error');
					else $app->enqueueMessage($modifySubscriptionError, 'error');
				}
			}
		}

		if ($ajax) exit;

		$this->_closepop($redirectUrl);

		$this->setRedirect($redirectUrl);
		return true;
	}

	function _closepop($redirectUrl){
		$this->_checkRedirectUrl($redirectUrl);

		if(empty($redirectUrl) OR !JRequest::getInt('closepop')) return;

		echo '<script type="text/javascript" language="javascript">
					window.parent.document.location.href=\''.str_replace('&amp;','&',$redirectUrl).'\';
					d = window.parent.document;
					w = window.parent;
					var e = d.getElementById(\'sbox-window\');
					if(e && typeof(e.close) != "undefined") {
						e.close();
					}else if(typeof(w.jQuery) != "undefined" && w.jQuery(\'div.modal.in\') && w.jQuery(\'div.modal.in\').hasClass(\'in\')){
						w.jQuery(\'div.modal.in\').modal(\'hide\');
					}else if(w.SqueezeBox !== undefined) {
						w.SqueezeBox.close();
					}
				</script>';

		$app = JFactory::getApplication();
		$messages = $app->getMessageQueue();
		if(!empty($messages)){
			$session = JFactory::getSession();
			$session->set('application.queue', $messages);
		}

		exit;
	}

	function optout(){
		acymailing_checkRobots();
		$config = acymailing_config();
		$app = JFactory::getApplication();
		$userClass = acymailing_get('class.subscriber');
		$my = JFactory::getUser();


		$redirectUrl = urldecode(JRequest::getString('redirectunsub'));
		if(!empty($redirectUrl)) $this->setRedirect($redirectUrl);

		$formData = JRequest::getVar( 'user', array(), '', 'array' );

		$email = trim(strip_tags(@$formData['email']));

		if(empty($email) && !empty($my->email)){
			$email = $my->email;
		}

		$ajax = JRequest::getInt('ajax',0);
		if($ajax) header("Content-type:text/html; charset=utf-8");

		$userHelper = acymailing_get('helper.user');
		if(empty($email) || !$userHelper->validEmail($email)){
			if ($ajax) echo '{"message":"'.JText::_('VALID_EMAIL').'","type":"error","code":"7"}';
			else echo "<script>alert('".JText::_('VALID_EMAIL',true)."'); window.history.go(-1);</script>";
			exit;
		}

		$alreadyExists = $userClass->get($email);

		if(empty($alreadyExists->subid)){
			if ($ajax){
				echo '{"message":"'.JText::sprintf('NOT_IN_LIST','<b><i>'.$email.'</i></b>').'","type":"error","code":"8"}';
				exit;
			}
			if(empty($redirectUrl)) acymailing_display(JText::sprintf('NOT_IN_LIST','<b><i>'.$email.'</i></b>'),'warning');
			else $app->enqueueMessage(JText::sprintf('NOT_IN_LIST','<b><i>'.$email.'</i></b>'),'notice');
			return $this->_closepop($redirectUrl);
		}

		if($config->get('allow_modif','data') == 'none' AND (empty($my->email) || $my->email != $email)){
			$mailClass = acymailing_get('helper.mailer');
			$mailClass->checkConfirmField = false;
			$mailClass->checkEnabled = false;
			$mailClass->report = false;
			$mailClass->sendOne('modif',$alreadyExists->subid);
			if ($ajax){
				echo '{"message":"'.JText::_('IDENTIFICATION_SENT').'","type":"success","code":"9"}';
				exit;
			}
			if(empty($redirectUrl)) acymailing_display(JText::_( 'IDENTIFICATION_SENT' ),'warning');
			else $app->enqueueMessage(JText::_( 'IDENTIFICATION_SENT' ), 'notice');
			return $this->_closepop($redirectUrl);
		}

		$visibleSubscription = JRequest::getVar('subscription','','','array');
		$currentSubscription = $userClass->getSubscriptionStatus($alreadyExists->subid);
		$hiddenSubscription = explode(',',JRequest::getVar('hiddenlists','','','string'));

		$updatelists = array();
		$removeSubscription = array_merge($visibleSubscription,$hiddenSubscription);
		foreach($removeSubscription as $idList){
			if(!empty($currentSubscription[$idList]) AND $currentSubscription[$idList]->status != '-1'){
				$updatelists[-1][] = $idList;
			}
		}

		if(!empty($updatelists)){
			$listsubClass = acymailing_get('class.listsub');
			$listsubClass->updateSubscription($alreadyExists->subid,$updatelists);
			if($config->get('unsubscription_message',1)){
				if ($ajax){
					echo '{"message":"'.JText::_('UNSUBSCRIPTION_OK').'","type":"success","code":"10"}';
					exit;
				}
				if(empty($redirectUrl)) acymailing_display(JText::_('UNSUBSCRIPTION_OK'),'info');
				else $app->enqueueMessage(JText::_('UNSUBSCRIPTION_OK'));
			}
		}elseif($config->get('unsubscription_message',1) || $ajax){
			if ($ajax){
				echo '{"message":"'.JText::_('UNSUBSCRIPTION_NOT_IN_LIST').'","type":"success","code":"11"}';
				exit;
			}
			if(empty($redirectUrl)) acymailing_display(JText::_('UNSUBSCRIPTION_NOT_IN_LIST'),'info');
			else $app->enqueueMessage(JText::_('UNSUBSCRIPTION_NOT_IN_LIST'));
		}

		if ($ajax) exit;

		return $this->_closepop($redirectUrl);

	}

	function _checkRedirectUrl($redirectUrl){
		$config = acymailing_config();
		$regex = trim(preg_replace('#[^a-z0-9\|\.]#i','',$config->get('module_redirect')),'|');
		if($regex != 'all' && !empty($redirectUrl)){
			preg_match('#^(https?://)?(www.)?([^/]*)#i',$redirectUrl,$resultsurl);
			$domainredirect = preg_replace('#[^a-z0-9\.]#i','',@$resultsurl[3]);
			if(!preg_match('#^'.$regex.'$#i',$domainredirect)){
				$regex .= '|'.$domainredirect;
				echo "<script>alert('This redirect url is not allowed, you should change the \"".JText::_('REDIRECTION_MODULE',true)."\" parameter from the AcyMailing configuration page to \"".$regex."\" to allow it or set it to \"all\" to allow all urls'); window.history.go(-1);</script>";
				exit;
			}
		}
	}

}
