<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Registration controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UsersControllerRegistration extends UsersController
{
	/**
	 * Method to activate a user.
	 *
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function activate()
	{
		// http://2013.auction-ruseasons.ru/
		// index.php?option=com_users
		// &task=registration.activate
		// &token=bb66f9a638b375b5fe8e3106d3739a6e
		$user		= JFactory::getUser();
		$uParams	= JComponentHelper::getParams('com_users');

		// If the user is logged in, return them back to the homepage.
		if ($user->get('id')) {
			$this->setRedirect('index.php');
			return true;
		}

		// If user registration or account activation is disabled, throw a 403.
		if ($uParams->get('useractivation') == 0 || $uParams->get('allowUserRegistration') == 0) {
			JError::raiseError(403, JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
			return false;
		}

		$model = $this->getModel('Registration', 'UsersModel');
		$token = JRequest::getVar('token', null, 'request', 'alnum');

		// Check that the token is in a valid format.
		if ($token === null || strlen($token) !== 32) {
			JError::raiseError(403, JText::_('JINVALID_TOKEN'));
			return false;
		}

		// Attempt to activate the user.
		$return = $model->activate($token);

		// Check for errors.
		if ($return === false) {
			// Redirect back to the homepage.
			$this->setMessage(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED',$model->getError()), 'warning');
			$this->setRedirect('index.php');
			return false;
		}

		$useractivation = $uParams->get('useractivation');

		// Redirect to the login screen.
		if ($useractivation == 0)
		{
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
		elseif ($useractivation == 1)
		{
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_ACTIVATE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
		elseif ($return->getParam('activate'))
		{
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_VERIFY_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
		}
		else
		{
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_ADMINACTIVATE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
		}
		return true;
	}
/*	MODIFIED START */
/**
 * Создать следующий логин для юзера
 * @package
 * @subpackage
 */
	function getNextUserName(){
		$query="SELECT MAX(username) AS username
		FROM #__users 
		WHERE username REGEXP '[0-9]{1,10}';";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return (int)$db->loadResult()+1; 
	}

/**
 * Альтернативная регистрация - как участника аукциона
 * @package
 * @subpackage
 */
	function registerOnAuction(){
		$test=true;		
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$requestData = JRequest::getVar('jform', array(), 'post', 'array');

		require_once JPATH_SITE.DS.'components'.DS.'com_auction2013'.DS.'third_party'.DS.'recaptchalib.php';

		$privatekey = "6Lest90SAAAAAFbotT7ODJo3LALbvsJMHpfkN7AG";
		$resp = recaptcha_check_answer (
								$privatekey,
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]
							);
		
		if (!$resp->is_valid) {
			$backlink='index.php?option=com_auction2013&layout=register';
			foreach($requestData as $key=>$value)
				$backlink.='&'.$key.'='.$value;
			
			$this->setRedirect($backlink.'&err=wrong_captcha');
			// What happens when the CAPTCHA was entered incorrectly
			//echo ("Неправильно указан контрольный код (captcha).");
			/* . "(reCAPTCHA said: " . $resp->error . ")"*/
				 //echo "<div>Пожалуйста, <a href='index.php?option=com_auction2013&layout=register".$backlink."'>вернитесь</a> и повторите.</div>";
		}else{
			// Your code here to handle a successful verification
			// If registration is disabled - Redirect to login page.
			if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
				$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
				return false;
			}
			// Initialise variables.
			$app	= JFactory::getApplication();
			$model	= $this->getModel('Registration', 'UsersModel');
			// Get the user data.
			$requestData['username']=(string)$this->getNextUserName(); 
			// Validate the posted data.
			$form	= $model->getForm();
			if (!$form) {
				die('!FORM');
				JError::raiseError(500, $model->getError());
				return false;
			}
			$data	= $model->validate($form, $requestData);
			// Check for validation errors.
			if ($data === false) {
				// Get the validation messages.
				$errors	= $model->getErrors();
	
				// Push up to three validation messages out to the user.
				for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
					if ($errors[$i] instanceof Exception) {
						$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
					} else {
						$app->enqueueMessage($errors[$i], 'warning');
					}
				}
	
				// Save the data in the session.
				$app->setUserState('com_users.registration.data', $requestData);
	
				// Redirect back to the registration screen.
				$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration', false));
				return false;
			}
	
			// Attempt to save the data.
			// возвращает, в зависимости от настроек активации юзера:
				// 0 : $user->id 
				// 1 : 'useractivate'
				// 2 : 'adminactivate'
			$return	= $model->register($data);
			$go_return='index.php?option=com_auction2013&layout=thanx';
			// Check for errors.
			if ($return === false) {
				// Save the data in the session.
				$app->setUserState('com_users.registration.data', $data);
	
				// Redirect back to the edit screen.
				$this->setMessage(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError()), 'warning');
				$this->setRedirect(JRoute::_($return_thanx, false));
				return false;
			}
	
			// Flush the data from the session.
			$app->setUserState('com_users.registration.data', null);	
			// Redirect to the profile screen.
			switch($return){
				// активация админомЖ
				case 'adminactivate':
					$tLabel='COMPLETE_VERIFY';
				break;
				// самостоятельная активация юзером:
				case 'useractivate':
					$tLabel='COMPLETE_ACTIVATE';
				break;
				// активация не требуется:
				default:
					$tLabel='SAVE_SUCCESS';
					$go_return='index.php?option=com_users&view=login';
			}
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_'.$tLabel));
			if($test)
				die("<hr><div class=''>setRedirect: <a href='$go_return'>".$var."</a></div>");
			else
				$this->setRedirect(JRoute::_($go_return, false));
			return true;
		}		
	}
/*	MODIFIED END	*/

	/**
	 * Method to register a user.
	 *
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function register()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// If registration is disabled - Redirect to login page.
		if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
			return false;
		}

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Registration', 'UsersModel');

		// Get the user data.
		$requestData = JRequest::getVar('jform', array(), 'post', 'array');

		// Validate the posted data.
		$form	= $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$data	= $model->validate($form, $requestData);

		// Check for validation errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $requestData);

			// Redirect back to the registration screen.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration', false));
			return false;
		}

		// Attempt to save the data.
		$return	= $model->register($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration', false));
			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_users.registration.data', null);

		// Redirect to the profile screen.
		if ($return === 'adminactivate'){
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
		} elseif ($return === 'useractivate') {
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
		} else {
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
		}

		return true;
	}
}
