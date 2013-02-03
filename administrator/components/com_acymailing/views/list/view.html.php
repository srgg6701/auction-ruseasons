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


class ListViewList extends acymailingView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function listing(){
		$app = JFactory::getApplication();
		$config = acymailing_config();
		$pageInfo = new stdClass();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->elements = new stdClass();

		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName();

		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'a.ordering','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'asc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );
		$selectedCreator = $app->getUserStateFromRequest( $paramBase."filter_creator",'filter_creator',0,'int');

		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );

		$database = JFactory::getDBO();

		$filters = array();
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.acymailing_getEscaped($pageInfo->search,true).'%\'';
			$filters[] = "a.name LIKE $searchVal OR a.description LIKE $searchVal OR a.listid LIKE $searchVal";
		}
		$filters[] = "a.type = 'list'";
		if(!empty($selectedCreator)) $filters[] = 'a.userid = '.$selectedCreator;

		$query = 'SELECT a.*, d.name as creatorname, d.username, d.email';
		$query .= ' FROM '.acymailing_table('list').' as a';
		$query .=  ' LEFT JOIN '.acymailing_table('users',false).' as d on a.userid = d.id';
		$query .= ' WHERE ('.implode(') AND (',$filters).')';
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}

		$database->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $database->loadObjectList();

		$queryCount = 'SELECT COUNT(a.listid) FROM  '.acymailing_table('list').' as a';
		if(!empty($pageInfo->search)) $queryCount .=  ' LEFT JOIN '.acymailing_table('users',false).' as d on a.userid = d.id';

		$queryCount .= ' WHERE ('.implode(') AND (',$filters).')';

		$database->setQuery($queryCount);
		$pageInfo->elements->total = $database->loadResult();

		$listids = array();
		foreach($rows as $oneRow){
			$listids[] = $oneRow->listid;
		}

		$subscriptionresults = array();
		if(!empty($listids)){
			$querySubscription = 'SELECT count(subid) as total,listid,status FROM '.acymailing_table('listsub').' WHERE listid IN ('.implode(',',$listids).') GROUP BY listid, status';
			$database->setQuery($querySubscription);
			$countresults = $database->loadObjectList();
			foreach($countresults as $oneResult){
				$subscriptionresults[$oneResult->listid][intval($oneResult->status)] = $oneResult->total;
			}
		}

		foreach($rows as $i => $oneRow){
			$rows[$i]->nbsub = intval(@$subscriptionresults[$oneRow->listid][1]);
			$rows[$i]->nbunsub = intval(@$subscriptionresults[$oneRow->listid][-1]);
			$rows[$i]->nbwait = intval(@$subscriptionresults[$oneRow->listid][2]);
		}

		if(!empty($pageInfo->search)){
			$rows = acymailing_search($pageInfo->search,$rows);
		}

		$pageInfo->elements->page = count($rows);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

		acymailing_setTitle(JText::_('LISTS'),'acylist','list');

		$bar = JToolBar::getInstance('toolbar');
		if(acymailing_isAllowed($config->get('acl_lists_filter','all'))){
			$bar->appendButton( 'Link', 'filter', JText::_('ACY_FILTERS'), acymailing_completeLink('filter') );
			JToolBarHelper::divider();
		}
		if(acymailing_isAllowed($config->get('acl_lists_manage','all'))) JToolBarHelper::addNew();
		if(acymailing_isAllowed($config->get('acl_lists_manage','all'))) JToolBarHelper::editList();
		if(acymailing_isAllowed($config->get('acl_lists_delete','all'))) JToolBarHelper::deleteList(JText::_('ACY_VALIDDELETEITEMS'));
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','list-listing');
		if(acymailing_isAllowed($config->get('acl_cpanel_manage','all'))) $bar->appendButton( 'Link', 'acymailing', JText::_('ACY_CPANEL'), acymailing_completeLink('dashboard') );

		$order = new stdClass();
		$order->ordering = false;
		$order->orderUp = 'orderup';
		$order->orderDown = 'orderdown';
		$order->reverse = false;
		if($pageInfo->filter->order->value == 'a.ordering'){
			$order->ordering = true;
			if($pageInfo->filter->order->dir == 'desc'){
				$order->orderUp = 'orderdown';
				$order->orderDown = 'orderup';
				$order->reverse = true;
			}
		}

		$filters = new stdClass();
		$listcreatorType = acymailing_get('type.listcreator');
		$filters->creator = $listcreatorType->display('filter_creator',$selectedCreator);

		$this->assignRef('filters',$filters);
		$this->assignRef('order',$order);
		$toggleClass = acymailing_get('helper.toggle');
		$this->assignRef('toggleClass',$toggleClass);
		$this->assignRef('rows',$rows);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('pagination',$pagination);

	}

	function form(){
		$listid = acymailing_getCID('listid');

		$listClass = acymailing_get('class.list');
		if(!empty($listid)){
			$list = $listClass->get($listid);
		}else{
			$list = new stdClass();
			$list->published = 0;
			$list->visible = 1;
			$list->description = '';
			$list->published = 1;
			$user = JFactory::getUser();
			$list->creatorname = $user->name;
			$list->access_manage = 'none';
			$list->access_sub = 'all';
			$list->languages = 'all';
			$colors = array('#3366ff','#7240A4','#7A157D','#157D69','#ECE649');
			$list->color = $colors[rand(0,count($colors)-1)];
		}

		$editor = acymailing_get('helper.editor');
		$editor->name = 'editor_description';
		$editor->content = $list->description;
		$editor->setDescription();

		if(!ACYMAILING_J16){
			$script = 'function submitbutton(pressbutton){
						if (pressbutton == \'cancel\') {
							submitform( pressbutton );
							return;
						}';
		}else{
			$script = 'Joomla.submitbutton = function(pressbutton) {
						if (pressbutton == \'cancel\') {
							Joomla.submitform(pressbutton,document.adminForm);
							return;
						}';
		}
		$script .= 'if(window.document.getElementById("name").value.length < 2){alert(\''.JText::_('ENTER_TITLE',true).'\'); return false;}';
		$script .= $editor->jsCode();
		if(!ACYMAILING_J16){
			$script .= 'submitform( pressbutton );}';
		}else{
			$script .= 'Joomla.submitform(pressbutton,document.adminForm);}; ';
		}
		$script .= 'function affectUser(idcreator,name,email){
			window.document.getElementById("creatorname").innerHTML = name;
			window.document.getElementById("listcreator").value = idcreator;
		}';


		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $script );

		$colorBox = acymailing_get('type.color');


		acymailing_setTitle(JText::_('LIST'),'acylist','list&task=edit&listid='.$listid);

		$bar = JToolBar::getInstance('toolbar');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','list-form');

		$this->assignRef('colorBox',$colorBox);
		if(acymailing_level(1)){
			$this->assign('welcomeMsg',acymailing_get('type.welcome'));
			$this->assign('languages',acymailing_get('type.listslanguages'));
		}
		$unsubMsg = acymailing_get('type.unsub');
		$this->assignRef('unsubMsg',$unsubMsg);
		$this->assignRef('list',$list);
		$this->assignRef('editor',$editor);

	}
}
