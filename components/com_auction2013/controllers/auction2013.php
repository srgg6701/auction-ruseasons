<?php	
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
include_once JPATH_SITE.DS.'tests.php';
require_once JPATH_COMPONENT.DS.'controller.php';

/**
 * Registration controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class Auction2013ControllerAuction2013 extends JControllerLegacy
{
    /**
     * Добавить предмет в избранное
     * @package
     * @subpackage
     */
    function addToFavorites(){
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        // подключить тестовые функции:
        require_once JPATH_SITE.'/tests.php';
        $data=JRequest::get('post');
        $virtuemart_product_id=$data['virtuemart_product_id'];
        $user = JFactory::getUser();
        if($user->guest){
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login&virtuemart_product_id='.$virtuemart_product_id), false);
        }else{ /*	'btn_favor' => string 'добавить в избранное' (length=38)
				'option' => string 'com_auction2013' (length=15)
				'task' => string 'addToFavorites' (length=14)
				'virtuemart_product_id' => string '516' (length=3)
				'79513d0a835927c68c03b271ac965de9' => string '1' (length=1)
			  */
            require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
            if(!AuctionStuff::addToFavorites($virtuemart_product_id,$user->id))
                echo "<div class=''>Ошибка. Данные не добавлены...</div>";
            else{
                $uMenus=AuctionStuff::getTopCatsMenuItemIds(
                    'usermenu',
                    'profile',
                    'favorites'
                );
                $link='index.php?option=com_users&amp;view=profile';
                //commonDebug(__FILE__, __LINE__, JRoute::_($link), true);
                /**
                 * Sorry for such the ugly link appearance... :(
                 * Но иначе придётся роутер дербанить. А это - отдельный геморрой.
                 * В конце концов такие ссылки может видеть только заавторизованный юзер.
                 * Потерпит. */
                $this->setRedirect(JRoute::_($link).'&layout=favorites&Itemid='.$uMenus[0].'&added='.$virtuemart_product_id, false);
            }
        }
    }
    /**
     * Описание
     * @package
     * @subpackage
     */
    function askQuestion(){
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $requestData = JRequest::getVar('jform', array(), 'post', 'array');
        /*
        */
        $subject = "Вопрос по предмету id ".$_POST['lot_id']." (".$_POST['lot_name'].")";
        $body = '<p>
Имя клиента: '.$requestData['name'].'</p>
<p>Емэйл: '.$requestData['email'].'</p>
<p>Контактный телефон: '.$requestData['phone_number'].'</p>
<hr>
<p><b>Комментарий:</b> '.$requestData['comments'].'</p>';

        // $cc = false;
        // $bcc[] = false;

        $mail = JFactory::getMailer();
        $mail->setSender(array($requestData['email'],$requestData['name']));
        $config =& JFactory::getConfig();
        $recipient = array(
            $config->getValue( 'config.mailfrom' ),
            $config->getValue( 'config.fromname' )
        );

        $mail->addRecipient($recipient);
        $mail->setSubject($subject);
        $mail->isHTML(true);
        $mail->Encoding = 'base64';
        $mail->setBody($body);
        //echo $subject.'<hr>'.$body;
        //die();
        $send =& $mail->Send();
        if ($send !== true)
            die("Сообщение не было отправлено из-за возникшей ошибки.<hr>".$send->message);
        else
            $this->setRedirect(JRoute::_('index.php?option=com_auction2013&layout=askaboutlot&result=thanx', false));
    }
    /**
     * Удалить из избранного
     * @package
     * @subpackage
     */
    function deleteFromFavorites(){
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //var_dump(JRequest::get('post'));
        //die('deleteFromFavorites');
        $virtuemart_product_id=JRequest::getVar('virtuemart_product_id');
        $user = JFactory::getUser();
        $db	= JFactory::getDBO();
        $query	= $db->getQuery(true);
        $query->delete();
        $query->from("#__product_favorites");
        $query->where(" virtuemart_product_id = ".$virtuemart_product_id.' AND user_id = '.$user->id);
        //echo "<div class=''>query= ".$query."</div>";die();
        $db->setQuery((string) $query);
        if (!$db->query()) {
            //sendErrorMess включён
            JError::raiseError(500, $db->getErrorMsg());
        }else{
            require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
            $uMenus=AuctionStuff::getTopCatsMenuItemIds(
                'usermenu',
                'profile',
                'favorites'
            );
            $link='index.php?option=com_users&view=profile&layout=favorites&Itemid='.$uMenus[0].'&deleted='.$virtuemart_product_id;
            $this->setRedirect($link,false);
        }
        /*	'virtuemart_product_id' => string '584' (length=3)
              'option' => string 'com_auction2013' (length=15)
              'task' => string 'deleteFromFavorites' (length=19)
              'df11e545edba1f3486e07aa892c90cb1' => string '1' (length=1)

        */
    }
    /**
     * Сделать ставку
     */
    function makeBid(){
        $test=false;
        $post = JRequest::get('post');
        $result = $this->getModel()->makeBid($post); die('file: '.__FILE__);
        // ставка больше минимальной резервной и максимальной текущей ставки
        if($result&&!is_array($result)) {
            /**
             * ставка сделана - загрузить раздел ставок в кабинете юзера */
            $this->setRedirect('index.php?option=com_users&view=cabinet&layout=bids');
        }else{ /**
                * array
                * вернуться в профайл предмета и вывести сообщение */
            $link = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' .
                                $post['virtuemart_product_id'] .
                                '&virtuemart_category_id=' .$post['virtuemart_category_id'] .
                                '&Itemid=' . $post['Itemid'] . '&poor_bid=' . $post['bids'];
            if(is_array($result)) // торги закрыты, вернёт 'expired', auction_date_finish
                $link.= '&' . $result[0] . '=' . $result[1];
                //commonDebug(__FILE__,__LINE__,$link, true);
            if(!$test)
                $this->setRedirect($link);
        }
    }
    /**
 * Оформить заказ предмета. Таблица: auc13_dev_shop_orders
 */    
    function purchase(){
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model=$this->getModel(); // Auction2013ModelAuction2013
        $post = JRequest::get('post');
        //commonDebug(__FILE__,__LINE__,$post['link'], true);
        /* см. состав $post в модели */
        if($result=$model->makePurchase($post)){
            $this->setRedirect($post['link'].'?result='.$result['type'],$result['msg'],$result['type']);
        }
    }
    /**
 *
 */
	public function sendApplication(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$requestData = JRequest::getVar('jform', array(), 'post', 'array');		
		
		/*	'name' => string 'dfdsfsd' (length=7)
			'city' => string 'sdfsdf' (length=6)
			'email1' => string 'sdfsdf' (length=6)
			'phone_number' => string '' (length=0)
			'short_description' => string 'sdfsdf' (length=6)
			'price_wiches' => string 'ssdfsdf' (length=7)
		*/
		$subject = "Предложение предмета для аукциона";
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

		// $cc = false;
		// $bcc[] = false;

		$mail = JFactory::getMailer();
		$mail->setSender(array($requestData['email1'],$requestData['name']));
		$config =& JFactory::getConfig();
		$recipient = array( 
				$config->getValue( 'config.mailfrom' ),
				$config->getValue( 'config.fromname' ) 
			);
		
		$mail->addRecipient($recipient);		
		$mail->setSubject($subject);
		$mail->isHTML(true);
		$mail->Encoding = 'base64';
		$mail->setBody($body);
		$i=1;
		foreach($_FILES as $file){
			if($file['name']!='')
				$mail->AddEmbeddedImage($file['tmp_name'], 'image_'.$i, $file['name']);
			
			$i++;
		}
		$send =& $mail->Send();
		if ($send !== true) 
			die("Сообщение не было отправлено из-за возникшей ошибки.<hr>".$send->message);
		else
			$this->setRedirect(JRoute::_('index.php?option=com_auction2013&layout=thanx_for_lot', false));
		//http://docs.joomla.org/Sending_email_from_extensions			//http://api.joomla.org/__filesource/fsource_Joomla-Platform_Mail_librariesjoomlamailmail.php.html#a290
	}
}