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


class UserViewUser extends acymailingView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function modify(){
		global $Itemid;

		$app = JFactory::getApplication();
		$pathway	= $app->getPathway();
		$document	= JFactory::getDocument();
		$values = new stdClass();
		$values->show_page_heading = 0;

		$listsClass = acymailing_get('class.list');
		$subscriberClass = acymailing_get('class.subscriber');

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

			if(!empty($menuparams)){
				$this->assign('introtext',$menuparams->get('introtext'));
				$this->assign('finaltext',$menuparams->get('finaltext'));

				$document = JFactory::getDocument();
				if ($menuparams->get('menu-meta_description')) $document->setDescription($menuparams->get('menu-meta_description'));
				if ($menuparams->get('menu-meta_keywords')) $document->setMetadata('keywords',$menuparams->get('menu-meta_keywords'));
				if ($menuparams->get('robots')) $document->setMetadata('robots',$menuparams->get('robots'));
				if ($menuparams->get('page_title')) $document->setTitle($menuparams->get('page_title'));

				$values->suffix = $menuparams->get('pageclass_sfx','');
				$values->page_heading = ACYMAILING_J16 ? $menuparams->get('page_heading') : $menuparams->get('page_title');
				$values->show_page_heading = ACYMAILING_J16 ? $menuparams->get('show_page_heading',0) : $menuparams->get('show_page_title',0);
			}
		}

		$subscriber = $subscriberClass->identify(true);
		if(empty($subscriber)){
			$subscription = $listsClass->getLists('listid');
			$subscriber = new stdClass();
			$subscriber->html = 1;
			$subscriber->subid = 0;
			$subscriber->key = 0;

			if(!empty($subscription)){
				foreach($subscription as $id => $onesub){
					$subscription[$id]->status = 1;
					if(!empty($menuparams) AND strtolower($menuparams->get('listschecked','all')) != 'all' AND !in_array($id,explode(',',$menuparams->get('listschecked','all')))){
						$subscription[$id]->status = 0;
					}

				}
			}

			$pathway->addItem(JText::_('SUBSCRIPTION'));
			if(empty($menu)) $document->setTitle( JText::_('SUBSCRIPTION'));
		}else{
			$subscription = $subscriberClass->getSubscription($subscriber->subid,'listid');

			$pathway->addItem(JText::_('MODIFY_SUBSCRIPTION'));
			if(empty($menu)) $document->setTitle( JText::_('MODIFY_SUBSCRIPTION'));
		}

		acymailing_initJSStrings();

		if(!empty($menuparams) AND strtolower($menuparams->get('lists','all')) != 'all'){
			$visibleLists = strtolower($menuparams->get('lists','all'));
			if($visibleLists == 'none') $subscription = array();
			else{
				$newSubscription = array();
				$visiblesListsArray = explode(',',$visibleLists);
				foreach($subscription as $id => $onesub){
					if(in_array($id,$visiblesListsArray)) $newSubscription[$id] = $onesub;
				}
				$subscription = $newSubscription;
			}
		}


		$displayLists = false;
		foreach($subscription as $oneSub){
			if(!empty( $oneSub->published) AND $oneSub->visible){
				$displayLists = true;
				break;
			}
		}

		$this->assignRef('values',$values);
		$this->assign('status',acymailing_get('type.festatus'));
		$this->assignRef('subscription',$subscription);
		$this->assignRef('subscriber',$subscriber);
		$this->assignRef('displayLists',$displayLists);
		$this->assign('config',acymailing_config());
	}

	function saveunsub(){
		$subscriberClass = acymailing_get('class.subscriber');
		$subscriber = $subscriberClass->identify();
		$this->assignRef('subscriber',$subscriber);

		$listid = JRequest::getInt('listid');
		if(!empty($listid)){
			$listClass = acymailing_get('class.list');
			$mylist = $listClass->get($listid);
			$this->assignRef('list',$mylist);
		}
	}


	function unsub(){

		$subscriberClass = acymailing_get('class.subscriber');
		$config = acymailing_config();
		$this->assignRef('config',$config);

		$subscriber = $subscriberClass->identify();
		$this->assignRef('subscriber',$subscriber);

		$mailid = JRequest::getInt('mailid');
		$this->assignRef('mailid',$mailid);

		$replace = array();
		$replace['{list:name}'] = '';
		foreach($subscriber as $oneProp => $oneVal){
			$replace['{user:'.$oneProp.'}'] = $oneVal;
		}

		if(!empty($mailid)){
			$classListmail = acymailing_get('class.listmail');
			$lists = $classListmail->getLists($mailid);
			$this->assignRef('lists',$lists);
			if(!empty($lists)){
				$oneList = reset($lists);
				foreach($oneList as $oneProp => $oneVal){
					$replace['{list:'.$oneProp.'}'] = $oneVal;
				}
			}
		}

		$intro = str_replace('UNSUB_INTRO',JText::_('UNSUB_INTRO'),$config->get('unsub_intro','UNSUB_INTRO'));
		$intro = '<div class="unsubintro">'.nl2br(str_replace(array_keys($replace),$replace,$intro)).'</div>';
		$this->assignRef('intro',$intro);

		$this->assignRef('replace',$replace);


		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
		$pathway->addItem(JText::_('UNSUBSCRIBE'));

		$document = JFactory::getDocument();
		$document->setTitle( JText::_('UNSUBSCRIBE'));
	}
}
