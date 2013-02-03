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


class archiveViewArchive extends acymailingView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function forward(){
		$my = JFactory::getUser();
		$subkeys = JRequest::getString('subid',JRequest::getString('sub'));
		if(!empty($subkeys)){
			$db = JFactory::getDBO();
			$subid = intval(substr($subkeys,0,strpos($subkeys,'-')));
			$subkey = substr($subkeys,strpos($subkeys,'-')+1);
			$db->setQuery('SELECT * FROM '.acymailing_table('subscriber').' WHERE `subid` = '.$db->Quote($subid).' AND `key` = '.$db->Quote($subkey).' LIMIT 1');
			$receiver = $db->loadObject();
		}
		if(empty($receiver) AND !empty($my->email)){
			$userClass = acymailing_get('class.subscriber');
			$receiver = $userClass->get($my->email);
		}
		if(empty($receiver)){
			$receiver = new stdClass();
			$receiver->name = '' ;
			$receiver->email = '' ;
		}
		$this->assignRef('senderName',$receiver->name);
		$this->assignRef('senderMail',$receiver->email);

		$js = 'var numForwarders = 1;function addLine(){
							var myTable = window.document.getElementById("friend_table");
							var line1 = document.createElement("tr");
							var tdname = document.createElement("td");
							var itdname = document.createElement("td");
							var line2 = document.createElement("tr");
							var tdemail = document.createElement("td");
							var itdemail = document.createElement("td");

							var inputName = document.createElement("input");
							inputName.type = \'text\';
							inputName.name = \'forwardusers[\'+numForwarders+\'][name]\';

							var inputEmail = document.createElement("input");
							inputEmail.type = \'text\';
							inputEmail.name = \'forwardusers[\'+numForwarders+\'][email]\';

							var nameLabel = document.createElement("label");
							nameLabel.innerHTML="'.JText::_('FRIEND_NAME',true).'";

							var emailLabel = document.createElement("label");
							emailLabel.innerHTML="'.JText::_('FRIEND_EMAIL',true).'";

							tdname.appendChild(nameLabel);
							itdname.appendChild(inputName);
							line1.appendChild(tdname);
							line1.appendChild(itdname);
							myTable.appendChild(line1);

							tdemail.appendChild(emailLabel);
							itdemail.appendChild(inputEmail);
							line2.appendChild(tdemail);
							line2.appendChild(itdemail);
							myTable.appendChild(line2);
							numForwarders++;
			}
';
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );
		return $this->view();
	}


	function listing(){
		global $Itemid;

		$app = JFactory::getApplication();

		$my = JFactory::getUser();
		$pathway = $app->getPathway();

		$values = new stdClass();
		$jsite = JFactory::getApplication('site');
		$menus = $jsite->getMenu();
		$menu	= $menus->getActive();
		$config = acymailing_config();

		if(empty($menu) AND !empty($Itemid)){
			$menus->setActive($Itemid);
			$menu	= $menus->getItem($Itemid);
		}

		$myItem = empty($Itemid) ? '' : '&Itemid='.$Itemid;
		$this->assignRef('item',$myItem);

		if (is_object( $menu )) {
			jimport('joomla.html.parameter');
			$menuparams = new acyParameter( $menu->params );
		}

		$pageInfo = new stdClass();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order  = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->elements = new stdClass();

		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName();
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'desc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );

		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = JRequest::getInt('limitstart',0);

		$listClass = acymailing_get('class.list');
		$listid = acymailing_getCID('listid');

		if(empty($listid) AND !empty($menuparams)){
			$listid = $menuparams->get('listid');
		}

		if(empty($listid) AND acymailing_level(3)){
			$listClass = acymailing_get('class.list');
			$allAllowedLists = $listClass->getFrontendLists();
			if(!empty($allAllowedLists)){
				$firstList = reset($allAllowedLists);
				$listid = $firstList->listid;
				JRequest::setVar('listid',$listid);
			}
		}

		if(empty($listid)){
			return JError::raiseError( 404, 'Mailing List not found' );
		}
		$oneList = $listClass->get($listid);

		if(empty($oneList->listid)){
			return JError::raiseError( 404, 'Mailing List not found : '.$listid );
		}

		$access = new stdClass();
		$access->frontEndManament = false;
		$access->frontEndAccess = true;

		if(!$access->frontEndManament AND (!$oneList->published OR !$oneList->visible OR !$access->frontEndAccess)){
			if(empty($my->id)){
				$usercomp = !ACYMAILING_J16 ? 'com_user' : 'com_users';
				$uri = JFactory::getURI();
				$url = 'index.php?option='.$usercomp.'&view=login&return='.base64_encode($uri->toString());
				$app->redirect($url, JText::_('ACY_NOTALLOWED') );
				return false;
			}else{
				$app->enqueueMessage(JText::_('ACY_NOTALLOWED'),'error');
				$app->redirect(acymailing_completeLink('lists',false,true));
				return false;
			}
		}

		if($config->get('open_popup',1)) JHTML::_('behavior.modal','a.modal');

		if(!empty($menuparams)){
			$values->suffix = $menuparams->get('pageclass_sfx','');
			$values->page_title = $menuparams->get('page_title');
			$values->page_heading = ACYMAILING_J16 ? $menuparams->get('page_heading') : $menuparams->get('page_title');
			$values->show_page_heading = ACYMAILING_J16 ? $menuparams->get('show_page_heading',1) : $menuparams->get('show_page_title',1);
		}else{
			$values->suffix = '';
			$values->show_page_heading = 1;
		}

		$values->show_description = $config->get('show_description',1);
		$values->show_headings = $config->get('show_headings',1);
		$values->show_senddate = $config->get('show_senddate',1);
		$values->show_colnumber = $config->get('show_colnumber',1);
		$values->show_receiveemail = $config->get('show_receiveemail',0) && acymailing_level(1);
		$values->filter = $config->get('show_filter',1);

		if(empty($values->page_title)) $values->page_title = $oneList->name;
		if(empty($values->page_heading)) $values->page_heading = $oneList->name;

		if(empty($menuparams)){
			$pathway->addItem(JText::_('MAILING_LISTS'),acymailing_completeLink('lists'));
			$pathway->addItem($values->page_title);
		}elseif(!$menuparams->get('listid')){
			$pathway->addItem($values->page_title);
		}


		$document = JFactory::getDocument();
		$document->setTitle( $values->page_title );

		$link	= '&format=feed&limitstart=';
		if($config->get('acyrss_format') == 'rss'  || $config->get('acyrss_format') == 'both'){
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
		}
		if($config->get('acyrss_format') == 'atom' || $config->get('acyrss_format') == 'both'){
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}

		$db = JFactory::getDBO();

		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order', $access->frontEndManament ? 'a.mailid' : 'a.senddate','cmd' );

		$searchMap = array('a.mailid','a.subject','a.alias');
		$filters = array();
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.acymailing_getEscaped($pageInfo->search,true).'%\'';
			$filters[] = implode(" LIKE $searchVal OR ",$searchMap)." LIKE $searchVal";
		}

		$filters[] = 'a.type = \'news\'';
		if(!$access->frontEndManament){
			$filters[] = 'a.published = 1';
			$filters[] = 'a.visible = 1';
		}
		$filters[] = 'c.listid = '.$oneList->listid;

		$selection = array_merge($searchMap,array('a.senddate','a.created','a.visible','a.published','a.fromname','a.fromemail','a.replyname','a.replyemail','a.userid'));

		$query = 'SELECT '.implode(',',$selection);
		$query .= ' FROM '.acymailing_table('listmail').' as c';
		$query .= ' JOIN '.acymailing_table('mail').' as a on a.mailid = c.mailid ';
		$query .= ' WHERE ('.implode(') AND (',$filters).')';
		$query .= ' ORDER BY '.acymailing_secureField($pageInfo->filter->order->value).' '.acymailing_secureField($pageInfo->filter->order->dir).', c.mailid DESC';

		$db->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $db->loadObjectList();
		$pageInfo->elements->page = count($rows);

		if(!empty($pageInfo->search)){
			$rows = acymailing_search($pageInfo->search,$rows);
		}

		if($pageInfo->limit->value > $pageInfo->elements->page){
			$pageInfo->elements->total = $pageInfo->limit->start + $pageInfo->elements->page;
		}else{
			$queryCount = 'SELECT COUNT(c.mailid) FROM '.acymailing_table('listmail').' as c';
			$queryCount .= ' JOIN '.acymailing_table('mail').' as a on a.mailid = c.mailid ';
			$queryCount .= ' WHERE ('.implode(') AND (',$filters).')';
			$db->setQuery($queryCount);
			$pageInfo->elements->total = $db->loadResult();
		}


		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

	$js = 'function tableOrdering( order, dir, task ){
			var form = document.adminForm;

			form.filter_order.value 	= order;
			form.filter_order_Dir.value	= dir;
			document.adminForm.submit( task );
		}

		function changeReceiveEmail(checkedbox){
			var form = document.adminForm;
			if(checkedbox){
				form.nbreceiveemail.value++;
			}else{
				form.nbreceiveemail.value--;
			}

			if(form.nbreceiveemail.value > 0 ){
				document.getElementById(\'receiveemailbox\').className = \'receiveemailbox receiveemailbox_visible\';
			}else{
				document.getElementById(\'receiveemailbox\').className = \'receiveemailbox receiveemailbox_hidden\';
			}
		}
		';


		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js);


		$this->assignRef('access',$access);
		$this->assignRef('rows',$rows);
		$this->assignRef('values',$values);
		$this->assignRef('list',$oneList);
		$this->assignRef('pagination',$pagination);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('config',$config);
		$this->assignRef('my',$my);

	}

	function view(){

		global $Itemid;

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$currentBase = $document->getBase();
		if(!empty($currentBase)){
			if(isset($_SERVER["REQUEST_URI"])){
				$requestUri = $_SERVER["REQUEST_URI"];
			}else{
				$requestUri = $_SERVER['PHP_SELF'];
				if (!empty($_SERVER['QUERY_STRING'])) $requestUri = rtrim($requestUri,'/').'?'.$_SERVER['QUERY_STRING'];
			}
			$currenturl = ((empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) != "on" ) ? 'http://' : 'https://').$_SERVER["HTTP_HOST"].$requestUri;
			$document->setBase($currenturl);
		}

		$pathway = $app->getPathway();
		$my = JFactory::getUser();

		$frontEndManagement = false;
		$listid = acymailing_getCID('listid');

		if(empty($listid)){
			$jsite = JFactory::getApplication('site');
			$menus = $jsite->getMenu();
			$menu	= $menus->getActive();

			if(empty($menu) AND !empty($Itemid)){
				$menus->setActive($Itemid);
				$menu	= $menus->getItem($Itemid);
			}

			if (is_object( $menu )) {
				jimport('joomla.html.parameter');
				$menuparams = new acyParameter( $menu->params );
			}

			if(!empty($menuparams)){
				$listid = $menuparams->get('listid');
				$document	= JFactory::getDocument();
				if ($menuparams->get('menu-meta_description')) $document->setDescription($menuparams->get('menu-meta_description'));
				if ($menuparams->get('menu-meta_keywords')) $document->setMetadata('keywords',$menuparams->get('menu-meta_keywords'));
				if ($menuparams->get('robots')) $document->setMetadata('robots',$menuparams->get('robots'));
				if ($menuparams->get('page_title')) $document->setTitle($menuparams->get('page_title'));
			}
		}

		if(!empty($listid)){
			 $listClass = acymailing_get('class.list');
			 $oneList = $listClass->get($listid);
			 if(!empty($oneList->visible) AND $oneList->published AND (empty($menuparams) || !$menuparams->get('listid'))){
				 $pathway->addItem($oneList->name,acymailing_completeLink('archive&listid='.$oneList->listid.':'.$oneList->alias));
			 }

			 if(!empty($oneList->listid) AND acymailing_level(3)){
				if(!empty($my->id) AND (int)$my->id == (int)$oneList->userid){
					$frontEndManagement = true;
				}
				if(!empty($my->id)){
					if($oneList->access_manage == 'all' OR acymailing_isAllowed($oneList->access_manage)){
						 $frontEndManagement = true;
					}
				}
			}
		}

		$mailid = JRequest::getString('mailid','nomailid');
		if(empty($mailid)) return JError::raiseError( 404, 'You can not preview a Newsletter-template, please create a Newsletter from your template');

		if($mailid == 'nomailid'){
			$db = JFactory::getDBO();
			$query = 'SELECT m.`mailid` FROM `#__acymailing_list` as l JOIN `#__acymailing_listmail` as lm ON l.listid=lm.listid JOIN `#__acymailing_mail` as m on lm.mailid = m.mailid';
			$query .= ' WHERE l.`visible` = 1 AND l.`published` = 1 AND m.`visible`= 1 AND m.`published` = 1 AND l.`type` = "list"';
			if(!empty($listid)) $query .= ' AND l.`listid` = '.(int) $listid;
			$query .= ' ORDER BY m.`senddate` DESC, m.`mailid` DESC LIMIT 1';
			$db->setQuery($query);
			$mailid = $db->loadResult();
		}
		$mailid = intval($mailid);
		if(empty($mailid)) return JError::raiseError( 404, 'Newsletter not found');

		$access_sub = true;

		$mailClass = acymailing_get('helper.mailer');
		$mailClass->loadedToSend = false;
		$oneMail = $mailClass->load($mailid);

		if(empty($oneMail->mailid)){
			return JError::raiseError( 404, 'Newsletter not found : '.$mailid );
		}

		if(!$frontEndManagement AND (!$access_sub OR !$oneMail->published OR !$oneMail->visible)){
			$key = JRequest::getString('key');
			if(empty($key) OR $key !== $oneMail->key){
				$reason = (!$oneMail->published) ? 'Newsletter not published' : (!$oneMail->visible ? 'Newsletter not visible' : (!$access_sub ? 'Access not allowed' : ''));
				$app->enqueueMessage('You can not have access to this e-mail : '.$reason,'error');
				$app->redirect(acymailing_completeLink('lists',false,true));
				return false;
			}
		}

		$subkeys = JRequest::getString('subid',JRequest::getString('sub'));
		if(!empty($subkeys)){
			$db = JFactory::getDBO();
			$subid = intval(substr($subkeys,0,strpos($subkeys,'-')));
			$subkey = substr($subkeys,strpos($subkeys,'-')+1);
			$db->setQuery('SELECT * FROM '.acymailing_table('subscriber').' WHERE `subid` = '.$db->Quote($subid).' AND `key` = '.$db->Quote($subkey).' LIMIT 1');
			$receiver = $db->loadObject();
		}

		if(empty($receiver) AND !empty($my->email)){
			$userClass = acymailing_get('class.subscriber');
			$receiver = $userClass->get($my->email);
		}

		if(empty($receiver)){
			$receiver = new stdClass();
			$receiver->name = JText::_('VISITOR');
		}

		$oneMail->sendHTML = true;
		$mailClass->dispatcher->trigger('acymailing_replaceusertags',array(&$oneMail,&$receiver,false));

		$pathway->addItem($oneMail->subject);

		$document	= JFactory::getDocument();
		$document->setTitle( $oneMail->subject );

		if (!empty($oneMail->metadesc)) {
			$document->setDescription( $oneMail->metadesc );
		}
		if (!empty($oneMail->metakey)) {
			$document->setMetadata('keywords', $oneMail->metakey);
		}

		$this->assignRef('mail',$oneMail);
		$this->assignRef('frontEndManagement',$frontEndManagement);
		$this->assignRef('list',$oneList);
		$config =& acymailing_config();
		$this->assignRef('config',$config);
		$this->assignRef('my',$my);
		$this->assignRef('receiver',$receiver);

	}

}
