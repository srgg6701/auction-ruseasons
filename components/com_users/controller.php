<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Base controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.5
 */
class UsersController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		/*	MODIFIED START */ 
		/*
			'view' => string 'registration' (length=12)
		  	'layout' => string 'complete' (length=8)
		  	'Itemid' => string '155' (length=3)
		  	'option' => string 'com_users' (length=9)
		*/
		if(JRequest::getVar('view')=='registration'&&JRequest::getVar('layout')=='complete') $this->setRedirect('index.php?option=com_users&view=login');
			
		/*	MODIFIED END	*/
		
		// Get the document object.
		$document	= JFactory::getDocument();

		$user = JFactory::getUser();
		// Set the default view name and format from the Request.
		$vName=($user->get('guest') == 1)? JRequest::getCmd('view', 'login'):'profile';
		$vFormat = $document->getType();
		$lName	 = JRequest::getCmd('layout', 'default');
		/** 
			если юзер заавторизован и view = login: 
			lName = default
			vName = profile
		*/
		if ($view = $this->getView($vName, $vFormat)) {
			// Do any specific processing by view.
			switch ($vName) {
				case 'registration':
					// If the user is already logged in, redirect to the profile page.
					if ($user->get('guest') != 1) {
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));
						return;
					}

					// Check if user registration is enabled
            		if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
            			// Registration is disabled - Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
						return;
            		}

					// The user is a guest, load the registration model and show the registration page.
					$model = $this->getModel('Registration');
					break;

				// Handle view specific models.
				case 'profile':

					// If the user is a guest, redirect to the login page.
					$user = JFactory::getUser();
					if ($user->get('guest') == 1) {
						// Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
						return;
					}
					$model = $this->getModel($vName);
					break;

				// Handle the default views.
				case 'login':
					
					$model = $this->getModel($vName);
					break;

				case 'reset':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();
					if ($user->get('guest') != 1) {
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));
						return;
					}

					$model = $this->getModel($vName);
					break;

				case 'remind':
					// If the user is already logged in, redirect to the profile page.
					$user = JFactory::getUser();
					if ($user->get('guest') != 1) {
						// Redirect to profile page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile', false));
						return;
					}

					$model = $this->getModel($vName);
					break;

				default:
					$model = $this->getModel('Login');
					break;
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			/** 
				по умолчанию для заавторизованного юзера:
				layout:  default
				view:	 profile
			*/
			$view->setLayout($lName);
			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}
}
