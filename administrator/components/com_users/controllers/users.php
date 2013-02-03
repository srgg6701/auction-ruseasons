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
		$data = JRequest::getVar('jform', array(), 'post', 'array');

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
			$query="SELECT
    FROM_UNIXTIME(u.date_joined) AS 'registerDate',
	u.username, l.password, 
    u.firstname AS 'name', 
	u.optional_field_1 AS '_middlename', 
	u.lastname AS '_lastname', 
    u.company_name AS '_company_name', 
	'' AS '_country_id', 
	u.zip AS '_zip', 
	u.city AS '_city', 
    u.optional_field_2 AS '_street', 
	u.optional_field_3 AS '_house_number', 
    u.optional_field_4 AS '_corpus_number', 
	u.optional_field_5 AS '_flat_office_number', 
    u.phone AS '_phone_number', 
	u.phone2 AS '_phone2_number', 
	u.email
FROM #__geodesic_users AS u 
LEFT JOIN #__geodesic_logins AS l ON l.username = u.username
WHERE u.id = ";
			$db=JFactory::getDBO();

			$show_data=false;
			if ($show_data){
echo <<<DT
<pre>
'id' => string '124'
'registerDate' => string '2010-09-30 14:20:14'
'username' => string '100130'
'password' => string '74394864'
'name' => string 'Андрей'
'_middlename' => string 'Николаевич'
'_lastname' => string 'Кокарев'
'_company_name' => string ''
'_country_id' => string ''
'_zip' => string '127522'
'_city' => string 'Москва'
'_street' => string 'Ленинградский проспект'
'_house_number' => string '11'
'_corpus_number' => string ''
'_flat_office_number' => string 'офис'
'_phone_number' => string '+7 962 2302121'
'_phone2_number' => string ''
'email' => string 'akvip@mail.ru'
</pre>
DT;
			}
			// перебрать и зарегистрировать полученных аппликантов:
			foreach ($pks as $i => $pk) {	
				$db->setQuery($query.$pk);
				$applicant_data=$db->loadAssocList();
				if (!$applicant_data) {
					JError::raiseWarning(100, JText::_('Не получены данные конвертанта...'));
					die("convert_users(), LINE: ".__LINE__);
				}else{
					
					//********************************************
					// http://stackoverflow.com/a/4212791/1522479
					// "generate" a new JUser Object
					// it's important to set the "0" otherwise your admin user information will be loaded
					$user = JFactory::getUser(0);
					// get the default usertype
					$usertype = $usersParams->get('new_usertype');
					if (!$usertype)
						$usertype = 'Registered';
					$defaultUserGroup = $usersParams->get('new_usertype', 2);
					$data=array(
							'id' => '0', 
							'name' => $applicant_data['name'],
							'_middlename' => $applicant_data['_middlename'],
							'_lastname' => $applicant_data['_lastname'],
							'username' => $applicant_data['username'],
							'email' => $applicant_data['email'],
							'password' => $applicant_data['password'],
							'password2' => $applicant_data['password'],
							'sendEmail' => 1, // should the user receive system mails?
							'registerDate' => $applicant_data['registerDate'],
							'_company_name' => $applicant_data['_company_name'],
							'_country_id' => $applicant_data['_country_id'],
							'_zip' => $applicant_data['_zip'],
							'_city' => $applicant_data['_city'],
							'_street' => $applicant_data['_street'],
							'_house_number' => $applicant_data['_house_number'],
							'_corpus_number' => $applicant_data['_corpus_number'],
							'_flat_office_number' => $applicant_data['_flat_office_number'],
							'_phone_number' => $applicant_data['_phone_number'],
							'_phone2_number' =>$applicant_data['_phone2_number'],
							'groups'=>array($defaultUserGroup),
							'block'=> 0,
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
				}
			}								
		}
		// отправляемся на страницу с текущим списком юзеров:
		$this->setRedirect(JRoute::_('index.php?option=com_users',false));
	}	
}
