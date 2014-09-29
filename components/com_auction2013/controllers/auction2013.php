<?php	
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
header('Content-Type: text/html; charset=utf-8');
include_once JPATH_SITE.DS.'tests.php';
require_once JPATH_COMPONENT.DS.'controller.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'stuff.php';
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
    public function addToFavorites(){
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
                $link='index.php?option=com_users&amp;view=cabinet';
                //commonDebug(__FILE__, __LINE__, JRoute::_($link), true);
                /**
                 * Sorry for such the ugly link appearance... :(
                 * Но иначе придётся роутер дербанить. А это - отдельный геморрой.
                 * В конце концов такие ссылки может видеть только заавторизованный юзер.
                 * Потерпит. */
                $this->setRedirect(JRoute::_($link).'?layout=favorites&Itemid='.$uMenus[0].'&added='.$virtuemart_product_id, false);
            }
        }
    }
    /**
     * Добавить уведомление о появлении предмета на торгах
     * @package
     * @subpackage
     */
    public function  addProductNotify(){
        //...
        $product_name = JRequest::getVar('name');
        $model=$this->getModel();
        // добавить предмет в список наблюдения:
        if($model->add_product_notify($product_name)===1){
            // если нашли предмет, отошлём сообщение
            if($model->getProductwWatchedItems($product_name)){
                // разослать сообщения
                require_once JPATH_COMPONENT.DS.'helpers'.DS.'stuff.php';
                $users=new Users();
                $users->notifyUserAboutProduct($product_name);
            }
            echo HTML::showWatchedItems(true);
        }
        exit;
    }
    /**
     *
     */
    /**
     * Описание
     * @package
     * @subpackage
     */
    public function askQuestion(){
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
     *Проверить активные аукционы и занести в данные в таблицу
     */
    public function checkActiveAuctions(){
        /**
        Проверить предметы, даты/время закрытия торгов по которым не вышло
        за рамки текущего момента и которых нет в таблице #__dev_lots_active;
        Добавить их в таблицу. */
        echo "Добавлено записей:" . $this->getModel()->check_active_auctions();
        return true;
    }
    /**
     * Удалить из избранного
     * @package
     * @subpackage
     */
    public function deleteFromFavorites(){
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
    public function makeBid(){
        $test=false;
        $post = JRequest::get('post');
        $bid_result=$this->getModel()->makeUserBid($post);

        if($test) commonDebug(__FILE__,__LINE__,"bid_result: ".$bid_result);

        if(!$bid_result||is_array($bid_result)) {
            /**
                * array
                * вернуться в профайл предмета и вывести сообщение */
            $link = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' .
                                $post['virtuemart_product_id'] .
                                '&virtuemart_category_id=' .$post['virtuemart_category_id'] .
                                '&Itemid=' . $post['Itemid'] . '&';
            if(is_array($bid_result)) // торги закрыты, вернёт 'expired', auction_date_finish
                $link.= $bid_result[0] . '=' . $bid_result[1];
            elseif($bid_result===false) // пока юзер жевал сопли, кто-то поднял ставку выше того, что он выбрал
                $link.= 'poor_bid=' . $post['bids'];
            if(!$test)
                $this->setRedirect($link);
            else{
                showTestMessage($link,__FILE__,__LINE__);
                commonDebug(__FILE__,__LINE__,$bid_result, true);
            }
        }else{
            $link='index.php?option=com_users&view=cabinet&layout=bids';
            if($test) showTestMessage($link,__FILE__,__LINE__,'blue');
            else //ставка сделана - загрузить раздел ставок в кабинете юзера
                $this->setRedirect($link);
        }
    }
    /**
 * Оформить заказ предмета. Таблица: #__dev_shop_orders
 */
    public function purchase(){
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
     * Комментарий
     * @package
     * @subpackage
     */
    public function  removeProductNotify(){
        //...
        if($model=$this->getModel()->remove_product_notify(JRequest::getVar('id')))
            echo HTML::showWatchedItems(true);
        exit;
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
    /**
     * Отправить извещение победителю торгов и админу по их окончании
     */
    public function sendNotifyOnAuctionClose(){
        /**
        *проверить все онлайн-торги, зарегистрированные в таблице
        активных аукционов, дата закрытия которых истекла
        *по каждому предмету, ставки по которым превысили резервную
        цену, разослать сообщения - победителю и админу.*/
        $this->getModel()->check_closed_lots();
    }
    /**
     * Комментарий
     * @package
     * @subpackage
     */
    public function testCron(){
        //...
        $this->getModel()->testCron();
        return true;
    }

    /**
     * Комментарий
     * @package
     * @subpackage
     */
    public function getInfo(){
        HTML::buildForm();
        return true;
    }
}