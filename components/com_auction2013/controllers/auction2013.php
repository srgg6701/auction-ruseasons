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
class Auction2013ControllerAuction2013 extends JControllerLegacy
{
	public function sendApplication(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$requestData = JRequest::getVar('jform', array(), 'post', 'array');		
		/*
		'name' => string 'dfdsfsd' (length=7)
	  	'city' => string 'sdfsdf' (length=6)
  		'email1' => string 'sdfsdf' (length=6)
  		'phone_number' => string '' (length=0)
  		'short_description' => string 'sdfsdf' (length=6)
  		'price_wiches' => string 'ssdfsdf' (length=7)
		*/
		$from = $replyto = $requestData['email1'];
		//'admin@somewhere.com';
		$fromname = $replytoname = $requestData['name'];
		//'BIGSHOT Blog';
		$adminEmail=JFactory::getConfig()->getValue('mailfrom');
		$recipient[] = $adminEmail;
		//'john@somewhere.com';
		//$recipient[] = $requestData[''];
		//'jane@somewhere.com';
		$subject = "Предложение предмета для аукциона";
		//'Want to learn about BIGSHOT Blog';
		$body = 'Имя клиента: 
<p>'.$requestData['name'].'</p>
Город проживания: 
<p>'.$requestData['city'].'</p>
Емэйл: 
<p>'.$requestData['email1'].'</p>
Контактный телефон: 
<p>'.$requestData['phone_number'].'</p>
Описание предмета: 
<p>'.$requestData['short_description'].'</p>
Пожелания по цене: 
<p>'.$requestData['price_wiches'].'</p>';

		$mode = 1;
		$cc = false;
		//'bob@somewhereelse.com';
		$bcc[] = false;
		//'simon@somewhereelse.com';
		//$attachment[] = 
		//'/home/my_site/public_html/images/stories/food/coffee.jpg';
		//$attachment[] = 
		//'/home/my_site/public_html/images/stories/food/milk.jpg';
		
		var_dump($_FILES);
		
		foreach($_FILES as $file)
			if($file['name']!='')
				$attachment[]=$file['tmp_name'];
		
		var_dump($attachment);
		
		$mail = JFactory::getMailer();
		
		$mail->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
		
		var_dump($requestData);
		die($body);
		$this->setRedirect(JRoute::_('index.php?option=com_auction2013&layout=thanx_for_lot', false));

	}
}