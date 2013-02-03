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


class SubscriberViewSubscriber extends acymailingView
{

	var $searchFields = array('a.name','a.email','a.subid','a.userid','b.username');
	var $selectedFields = array('a.*','b.username');
	var $ctrl = 'subscriber';

	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function listing(){

		$pageInfo = new stdClass();
		$pageInfo->elements = new stdClass();

		$app = JFactory::getApplication();
		$config = acymailing_config();

		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order = new stdClass();
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'a.subid','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'desc',	'word' );
		$selectedList = $app->getUserStateFromRequest( $paramBase."filter_lists",'filter_lists',0,'int');
		$selectedStatus = $app->getUserStateFromRequest( $paramBase."filter_status",'filter_status',0,'int');
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );

		$pageInfo->limit = new stdClass();
		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );


		$database	= JFactory::getDBO();

		$customFields = acymailing_get('class.fields');
		$displayFields = array();
		$displayFields['name'] = new stdClass();
		$displayFields['name']->fieldname = 'JOOMEXT_NAME';
		$displayFields['name']->type = 'text';
		$displayFields['email'] = new stdClass();
		$displayFields['email']->fieldname = 'JOOMEXT_EMAIL';
		$displayFields['email']->type = 'text';
		$displayFields['html'] = new stdClass();
		$displayFields['html']->fieldname = 'RECEIVE_HTML';
		$displayFields['html']->type = 'radio';


		$filters = array();
		if(!empty($pageInfo->search)){
			foreach($displayFields as $fieldname => $onefield){
				if($fieldname == 'html' OR in_array('a.'.$fieldname,$this->searchFields) OR $onefield->type == 'customtext') continue;
				$this->searchFields[] = 'a.`'.$fieldname.'`';
			}
			$searchVal = '\'%'.acymailing_getEscaped($pageInfo->search,true).'%\'';
			$filters[] = implode(" LIKE $searchVal OR ",$this->searchFields)." LIKE $searchVal";
		}

		$leftJoinQuery = array();
		$joinQuery = array();

		if(empty($selectedList) || ($selectedStatus == -2 && $app->isAdmin())){
			if(empty($selectedList) && $selectedStatus == -2) $selectedStatus = 0;
			$fromQuery = ' FROM '.acymailing_table('subscriber').' as a ';
			$countField = "a.subid";
			$leftJoinQuery[] = acymailing_table('users',false).' as b ON a.userid = b.id';
			if($selectedStatus == 1){
				$filters[] = 'a.accept > 0';
			}elseif($selectedStatus == -1){
				$filters[] = 'a.accept < 1';
			}elseif($selectedStatus == -2){
				$leftJoinQuery[] = acymailing_table('listsub').' as c on a.subid = c.subid AND listid = '.$selectedList;
				$filters[] = 'c.listid IS NULL';
			}elseif($selectedStatus == 2){
				$filters[] = 'a.confirmed < 1';
			}elseif($selectedStatus == 3){
				$filters[] = 'a.enabled > 0';
			}elseif($selectedStatus == -3){
				$filters[] = 'a.enabled < 1';
			}
		}else{
			$fromQuery = ' FROM '.acymailing_table('listsub').' as c';
			$countField = "c.subid";
			$joinQuery[] = acymailing_table('subscriber').' as a ON a.subid = c.subid';
			$leftJoinQuery[] = acymailing_table('users',false).' as b ON a.userid = b.id';
			$filters[] = 'c.listid = '.intval($selectedList);

			if(!in_array($selectedStatus,array(-1,1,2))) $selectedStatus = 1;
			$filters[] = 'c.status = '.$selectedStatus;
		}

		$query = 'SELECT '.implode(',',$this->selectedFields).$fromQuery;
		if(!empty($joinQuery)) $query .= ' JOIN '.implode(' JOIN ',$joinQuery);
		if(!empty($leftJoinQuery)) $query .= ' LEFT JOIN '.implode(' LEFT JOIN ',$leftJoinQuery);

		if(!empty($filters)){
			$query .= ' WHERE ('.implode(') AND (',$filters).')';
		}
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}

		$database->setQuery($query,$pageInfo->limit->start,empty($pageInfo->limit->value) ? 500 : $pageInfo->limit->value);
		$rows = $database->loadObjectList('subid');

		$pageInfo->elements->page = count($rows);

		if($pageInfo->limit->value > $pageInfo->elements->page){
			$pageInfo->elements->total = $pageInfo->limit->start + $pageInfo->elements->page;
		}else{
			$queryCount = 'SELECT COUNT('.$countField.') '.$fromQuery;
			if(!empty($pageInfo->search) || $selectedStatus == -2){
				if(!empty($joinQuery)) $queryCount .= ' JOIN '.implode(' JOIN ',$joinQuery);
				if(!empty($leftJoinQuery)) $queryCount .= ' LEFT JOIN '.implode(' LEFT JOIN ',$leftJoinQuery);
			}
			if(!empty($filters)) $queryCount .= ' WHERE ('.implode(') AND (',$filters).')';
			$database->setQuery($queryCount);
			$pageInfo->elements->total = $database->loadResult();
		}



		if(!empty($rows)){
			$database->setQuery('SELECT * FROM `#__acymailing_listsub` WHERE `subid` IN (\''.implode('\',\'',array_keys($rows)).'\')');
			$subscriptions = $database->loadObjectList();
			if(!empty($subscriptions)){
				foreach($subscriptions as $onesub){
					$sublistid = $onesub->listid;
					if(empty($rows[$onesub->subid]->subscription)) $rows[$onesub->subid]->subscription = new stdClass();
					$rows[$onesub->subid]->subscription->$sublistid = $onesub;
				}
			}
		}

		if(!empty($pageInfo->search)){
			$rows = acymailing_search($pageInfo->search,$rows);
		}


		if(empty($pageInfo->limit->value)){
			if($pageInfo->elements->total > 500){
				acymailing_display('We do not want you to crash your server so we displayed only the first 500 users','warning');
			}
			$pageInfo->limit->value = 100;
		}

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

		$filters = new stdClass();
		if(empty($selectedList)){
			$statusType = acymailing_get('type.statusfilter');
		}else{
			$statusType = acymailing_get('type.statusfilterlist');
		}

		$listsType = acymailing_get('type.lists');
		if($app->isAdmin()){
			$filters->lists = $listsType->display('filter_lists',$selectedList);
		}else{
			$listClass = acymailing_get('class.list');
			$allLists = $listClass->getFrontendLists();
			if(count($allLists)>1){
				$filters->lists = JHTML::_('select.genericlist',   $allLists, "filter_lists", 'class="inputbox" size="1" onchange="document.adminForm.limitstart.value=0;document.adminForm.submit( );"', 'listid', 'name', (int) $selectedList,"filter_lists" );
			}else{
				$filters->lists = '<input type="hidden" name="filter_lists" value="'.$selectedList.'"/>';
			}

			array_shift($statusType->values);
		}

		$filters->status = $statusType->display('filter_status',$selectedStatus);



		if($app->isAdmin()){
			acymailing_setTitle(JText::_('USERS'),'acyusers','subscriber');


			$bar = JToolBar::getInstance('toolbar');
			if(acymailing_isAllowed($config->get('acl_lists_filter','all'))){
				$bar->appendButton( 'Acyactions');
				JToolBarHelper::divider();
			}
			if(acymailing_isAllowed($config->get('acl_subscriber_import','all'))) $bar->appendButton( 'Link', 'import', JText::_('IMPORT'), acymailing_completeLink('data&task=import') );
			if(acymailing_isAllowed($config->get('acl_subscriber_export','all'))) JToolBarHelper::custom('export', 'acyexport', '',JText::_('ACY_EXPORT'), false);
			JToolBarHelper::divider();
			if(acymailing_isAllowed($config->get('acl_subscriber_manage','all'))) JToolBarHelper::addNew();
			if(acymailing_isAllowed($config->get('acl_subscriber_manage','all'))) JToolBarHelper::editList();
			if(acymailing_isAllowed($config->get('acl_subscriber_delete','all'))) JToolBarHelper::deleteList(JText::_('ACY_VALIDDELETEITEMS',true));
			JToolBarHelper::divider();

			$bar->appendButton( 'Pophelp','subscriber-listing');
			if(acymailing_isAllowed($config->get('acl_cpanel_manage','all'))) $bar->appendButton( 'Link', 'acymailing', JText::_('ACY_CPANEL'), acymailing_completeLink('dashboard') );
		}
		$lists = $listsType->getData();
		$this->assignRef('lists',$lists);
		$toggleClass = acymailing_get('helper.toggle');
		$this->assignRef('toggleClass',$toggleClass);
		$this->assignRef('rows',$rows);
		$this->assignRef('filters',$filters);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('pagination',$pagination);
		$config = acymailing_config();
		$this->assignRef('config',$config);
		$this->assignRef('displayFields',$displayFields);
		$this->assignRef('customFields',$customFields);

	}

	function choose(){

		$pageInfo = new stdClass();
		$app = JFactory::getApplication();

		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName().'_'.$this->getLayout();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->elements = new stdClass();
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'a.name','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'asc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );

		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );

		if(empty($pageInfo->limit->value)) $pageInfo->limit->value = 100;

		$db	= JFactory::getDBO();

		$filters = array();
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.acymailing_getEscaped($pageInfo->search,true).'%\'';
			$filters[] = implode(" LIKE $searchVal OR ",$this->searchFields)." LIKE $searchVal";
		}

		if(JRequest::getString('onlyreg')){
			$filters[] = 'a.userid > 0';
		}

		$query = 'SELECT '.implode(',',$this->selectedFields).' FROM #__acymailing_subscriber as a';
		$query .= ' LEFT JOIN #__users as b on a.userid = b.id';
		if(!empty($filters)){
			$query .= ' WHERE ('.implode(') AND (',$filters).')';
		}
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}
		$db->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $db->loadObjectList();

		$queryWhere = 'SELECT COUNT(a.subid) FROM #__acymailing_subscriber as a';
		if(!empty($filters)){
			$queryWhere .= ' LEFT JOIN #__users as b on a.userid = b.id';
			$queryWhere .= ' WHERE ('.implode(') AND (',$filters).')';
		}
		$db->setQuery($queryWhere);
		$pageInfo->elements->total = $db->loadResult();


		if(!empty($pageInfo->search)){
			$rows = acymailing_search($pageInfo->search,$rows);
		}

		$pageInfo->elements->page = count($rows);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

		$this->assignRef('rows',$rows);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('pagination',$pagination);

	}

	function form(){
		$subid = acymailing_getCID('subid');
		$db = JFactory::getDBO();
		$app= JFactory::getApplication();
		$config = acymailing_config();

		if(!empty($subid)){
			$subscriberClass = acymailing_get('class.subscriber');
			$subscriber = $subscriberClass->getFull($subid);
			$subscription = $app->isAdmin() ? $subscriberClass->getSubscription($subid) : $subscriberClass->getFrontendSubscription($subid);
		}else{
			$listType = acymailing_get('class.list');
			$subscription = $app->isAdmin() ? $listType->getLists() : $listType->getFrontendLists();

			$subscriber = new stdClass();
			$subscriber->created = time();
			$subscriber->html = 1;
			$subscriber->confirmed = 1;
			$subscriber->blocked = 0;
			$subscriber->accept = 1;
			$subscriber->enabled = 1;
			$iphelper = acymailing_get('helper.user');
			$subscriber->ip = $iphelper->getIP();
		}

		if($app->isAdmin()){
			acymailing_setTitle(JText::_('ACY_USER'),'acyusers','subscriber&task=edit&subid='.$subid);
			$bar = JToolBar::getInstance('toolbar');
		}



		if(!empty($subid)){
			$query = 'SELECT a.`mailid`, a.`html`, a.`sent`, a.`senddate`,a.`open`, a.`opendate`, a.`bounce`, a.`fail`,b.`subject`,b.`alias`';
			$query .= ' FROM `#__acymailing_userstats` as a';
			$query .= ' JOIN '.acymailing_table('mail').' as b on a.mailid = b.mailid';
			$query .= ' WHERE a.subid = '.intval($subid).' ORDER BY a.senddate DESC LIMIT 30';
			$db->setQuery($query);
			$open = $db->loadObjectList();
			$this->assignRef('open',$open);

			$query = 'SELECT a.*,b.`subject`,b.`alias`';
			$query .= ' FROM `#__acymailing_queue` as a';
			$query .= ' JOIN '.acymailing_table('mail').' as b on a.mailid = b.mailid';
			$query .= ' WHERE a.subid = '.intval($subid).' ORDER BY a.senddate ASC LIMIT 60';
			$db->setQuery($query);
			$queue = $db->loadObjectList();
			$this->assignRef('queue',$queue);

			$query = 'SELECT h.*,m.subject FROM #__acymailing_history as h LEFT JOIN #__acymailing_mail as m ON h.mailid = m.mailid WHERE h.subid = '.intval($subid).' ORDER BY h.`date` DESC LIMIT 30';
			$db->setQuery($query);
			$history = $db->loadObjectList();
			$this->assignRef('history',$history);
		}


	if($app->isAdmin()){
		if(!empty($subscriber->userid)){
			if(file_exists(ACYMAILING_ROOT.'components'.DS.'com_comprofiler'.DS.'comprofiler.php')){
				$editLink = 'index.php?option=com_comprofiler&task=edit&cid[]=';
			}elseif(!ACYMAILING_J16){
				$editLink = 'index.php?option=com_users&task=edit&cid[]=';
			}else{
				$editLink = 'index.php?option=com_users&task=user.edit&id=';
			}
			$bar->appendButton( 'Link', 'edit', JText::_('EDIT_JOOMLA_USER'), $editLink.$subscriber->userid );
			JToolBarHelper::spacer();
		}
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','subscriber-form');
	}


		$filters = new stdClass();
		$quickstatusType = acymailing_get('type.statusquick');
		$filters->statusquick = $quickstatusType->display('statusquick');

		$this->assignRef('subscriber',$subscriber);
		$toggleClass = acymailing_get('helper.toggle');
		$this->assignRef('toggleClass',$toggleClass);
		$this->assignRef('subscription',$subscription);
		$this->assignRef('filters',$filters);
		$statusType = acymailing_get('type.status');
		$this->assignRef('statusType',$statusType);


	}

}
