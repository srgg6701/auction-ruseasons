<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Users list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class UsersControllerUsers extends JControllerAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_USERS_USERS';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  UsersControllerUsers
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('block',		'changeBlock');
		$this->registerTask('unblock',		'changeBlock');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since	1.6
	 */
	public function getModel($name = 'User', $prefix = 'UsersModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to change the block status on a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function changeBlock()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('block' => 1, 'unblock' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->block($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_BLOCKED', count($ids)));
				}
				elseif ($value == 0)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_UNBLOCKED', count($ids)));
				}
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	/**
	 * Method to activate a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function activate()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->activate($ids))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				$this->setMessage(JText::plural('COM_USERS_N_USERS_ACTIVATED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}
/**
 * Конвертировать юзеров из старой таблицы в актуальную:
 * @package
 * @subpackage
 */
	public function convert_users($key = null, $urlVar = null)
	{
		// JControllerAdmin!
		// $data = JRequest::getVar('jform', array(), 'post', 'array');
		$query="SELECT u.id, 
    FROM_UNIXTIME(u.date_joined) AS 'registration date',u.username, l.password, 
    u.firstname, u.optional_field_1 AS '*middlename', u.lastname, 
    u.company_name, 'send to admin via email' AS '[COUNTRY]', u.zip, u.city, 
    u.optional_field_2 AS '*street', u.optional_field_3 AS '*house number', 
    u.optional_field_4 AS '*corpus', u.optional_field_5 AS '*flat/office', 
    u.phone, u.phone2, u.email
FROM #__geodesic_users AS u 
LEFT JOIN #__geodesic_logins AS l ON l.username = u.username";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$result=$db->loaAssocList(); 
		var_dump($result); die();
		$go_next=false;
		
		
		if ($go_next){ 
			//********************************************
			/* 	It has been got from here:
					http://stackoverflow.com/a/4212791/1522479
				...and slightly modified by source from here:
					http://stackoverflow.com/a/10173680/1522479
				...for an original source is deprecated for 2.5 version.
			*/
			// get the com_user params */			
			jimport('joomla.application.component.helper'); // include libraries/application/component/helper.php
			$usersParams = JComponentHelper::getParams( 'com_users' ); // load the Params
			//********************************************
			// собственный код:
			$pks=JRequest::getVar('cid'); // массив id id юзеров
			$model=$this->getModel('Item'); // будем получать данные аппликантов
			// перебрать и зарегистрировать полученных аппликантов:
			foreach ($pks as $i => $pk) {	
				$applicant_data=$model->getItem($pk);
				if (!$applicant_data) {
					JError::raiseWarning(100, JText::_('Не получены данные заявки...'));
					die("ApplicationController_chado_app_data::activate(), LINE: ".__LINE__);
				}else{
					
					//********************************************
					// http://stackoverflow.com/a/4212791/1522479
					// "generate" a new JUser Object
					// it's important to set the "0" otherwise your admin user information will be loaded
					$user = JFactory::getUser(0);
					// get the default usertype
					$usertype = $usersParams->get( 'new_usertype' );
					if (!$usertype) {
						 $usertype = 'Registered';
					}
					// set up the "main" user information
					//original logic of name creation
					//$data['name'] = $firstname.' '.$lastname; // add first- and lastname
					//default to defaultUserGroup i.e.,Registered:
					$defaultUserGroup = $usersParams->get('new_usertype', 2);
					$password=$this->generate_password(10);
					$xtra_data=serialize( array(
											'family'=>$applicant_data->family,
											'middle_name'=>$applicant_data->middle_name,
											'child_name'=>$applicant_data->child_name,
											'kindergarten'=>$applicant_data->kindergarten,
											'group'=>$applicant_data->group,
											'mobila'=>$applicant_data->mobila,
											'password'=>$password
										));
					$data=array(
							'id' => '0', // А без этого добавит ТОЛЬКО ОДНУ запись!!!
							'name' => $applicant_data->name,
							'username' => $applicant_data->email,
							'email' => $applicant_data->email,
							'password' => $password, 
							'password2' => $password, 
							'groups'=>array($defaultUserGroup),
							'sendEmail' => 1, // should the user receive system mails?
							'block'=> 0,
							'data'=>$xtra_data
						);
				}
				if (!$user->bind($data)) { // now bind the data to the JUser Object, if it not works....
					JError::raiseWarning('', JText::_( $user->getError())); // ...raise an Warning
					return false;
				}
				if (!$user->save()) {
					JError::raiseWarning('', JText::_( $user->getError())); // ...raise an Warning
					return false; 
					//********************************************
				}else // удалим аппликанта, т.к. теперь он - юзер!
					$this->getModel('Chado_app_data')->delete($pks);
			}								
		}
		
		// отправляемся на страницу с текущим списком юзеров:
		$this->setRedirect(JRoute::_('index.php?option=com_users',false));

	}	
}
