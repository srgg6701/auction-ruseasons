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


class QueueViewQueue extends acymailingView
{
	var $searchFields = array('b.name','b.email','c.subject','a.mailid','a.subid');
	var $selectFields = array('b.name','b.email','c.subject','c.type','c.published','a.mailid','a.subid','a.senddate','a.priority','a.try');

	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function preview(){
		$mailid = JRequest::getInt('mailid');
		$subid = JRequest::getInt('subid');

		$mailerHelper = acymailing_get('helper.mailer');
		$mailerHelper->loadedToSend = false;
		$mail = $mailerHelper->load($mailid);

		$user = JFactory::getUser();
		$userClass = acymailing_get('class.subscriber');
		$receiver = $userClass->get($subid);
		$mail->sendHTML = $mail->html && $receiver->html;

		$db = JFactory::getDBO();
		$db->setQuery('SELECT paramqueue FROM #__acymailing_queue WHERE mailid = '.intval($mailid).' AND subid = '.intval($subid));
		$receiver->paramqueue = $db->loadResult();

		$mailerHelper->dispatcher->trigger('acymailing_replaceusertags',array(&$mail,&$receiver,false));
		if(!empty($mail->altbody)) $mail->altbody = $mailerHelper->textVersion($mail->altbody,false);

		$this->assignRef('mail',$mail);
	}

	function listing(){
		JHTML::_('behavior.modal','a.modal');

		$app = JFactory::getApplication();
		$pageInfo = new stdClass();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->elements = new stdClass();

		$config = acymailing_config();

		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName();
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'a.senddate','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'asc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );
		$selectedMail = $app->getUserStateFromRequest( $paramBase."filter_mail",'filter_mail',0,'int');

		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );

		$database	= JFactory::getDBO();

		$filters = array();
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.acymailing_getEscaped($pageInfo->search,true).'%\'';
			$filters[] = implode(" LIKE $searchVal OR ",$this->searchFields)." LIKE $searchVal";
		}

		if(!empty($selectedMail)) $filters[] = 'a.mailid = '.intval($selectedMail);

		$query = 'SELECT '.implode(' , ',$this->selectFields);
		$query .= ' FROM '.acymailing_table('queue').' as a';
		$query .= ' JOIN '.acymailing_table('subscriber').' as b on a.subid = b.subid';
		$query .= ' JOIN '.acymailing_table('mail').' as c on a.mailid = c.mailid';
		if(!empty($filters)) $query .= ' WHERE ('.implode(') AND (',$filters).')';
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir.', a.`subid` ASC';
		}

		if(empty($pageInfo->limit->value)) $pageInfo->limit->value = 100;
		$database->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $database->loadObjectList();

		$pageInfo->elements->page = count($rows);

		if($pageInfo->limit->value > $pageInfo->elements->page){
			$pageInfo->elements->total = $pageInfo->limit->start + $pageInfo->elements->page;
		}else{
			$queryCount = 'SELECT COUNT(a.mailid) FROM '.acymailing_table('queue').' as a';
			if(!empty($pageInfo->search)){
				$queryCount .= ' JOIN '.acymailing_table('subscriber').' as b on a.subid = b.subid';
				$queryCount .= ' JOIN '.acymailing_table('mail').' as c on a.mailid = c.mailid';
			}
			if(!empty($filters)) $queryCount .= ' WHERE ('.implode(') AND (',$filters).')';

			$database->setQuery($queryCount);
			$pageInfo->elements->total = $database->loadResult();
		}

		if(!empty($pageInfo->search)){
			$rows = acymailing_search($pageInfo->search,$rows);
		}

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

		$mailqueuetype =  acymailing_get('type.queuemail');
		$filtersType = new stdClass();
		$filtersType->mail = $mailqueuetype->display('filter_mail',$selectedMail);

		acymailing_setTitle(JText::_('QUEUE'),'process','queue');

		$bar = JToolBar::getInstance('toolbar');

		if(acymailing_isAllowed($config->get('acl_queue_process','all'))){
			$bar->appendButton( 'Acypopup', 'process', JText::_('PROCESS'), "index.php?option=com_acymailing&ctrl=queue&task=process&tmpl=component&mailid=".$selectedMail);
		}

		if(!empty($pageInfo->elements->total) AND acymailing_isAllowed($config->get('acl_queue_delete','all'))){
			JToolBarHelper::spacer();
			JToolBarHelper::spacer();
			$bar->appendButton( 'Confirm', JText::sprintf('CONFIRM_DELETE_QUEUE',$pageInfo->elements->total), 'delete', JText::_('ACY_DELETE'), 'remove', false, false );
		}
		JToolBarHelper::divider();

		$bar->appendButton( 'Pophelp','queue-listing');
		if(acymailing_isAllowed($config->get('acl_cpanel_manage','all'))) $bar->appendButton( 'Link', 'acymailing', JText::_('ACY_CPANEL'), acymailing_completeLink('dashboard') );


		$toggleClass = acymailing_get('helper.toggle');

		$this->assignRef('toggleClass',$toggleClass);
		$this->assignRef('filters',$filtersType);
		$this->assignRef('rows',$rows);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('pagination',$pagination);

	}

	function process(){

		$mailid = acymailing_getCID('mailid');
		$queueClass = acymailing_get('class.queue');
		$queueStatus = $queueClass->queueStatus($mailid);
		$nextqueue = $queueClass->queueStatus($mailid,true);
		if(acymailing_level(1)){
			$scheduleClass = acymailing_get('helper.schedule');
			$scheduleNewsletter = $scheduleClass->getScheduled();
			$this->assignRef('schedNews',$scheduleNewsletter);
		}

		if(empty($queueStatus) AND empty($scheduleNewsletter)) acymailing_display(JText::_('NO_PROCESS'),'info');

		$infos = new stdClass();
		$infos->mailid = $mailid;
		$this->assignRef('queue',$queueStatus);
		$this->assignRef('nextqueue',$nextqueue);
		$this->assignRef('infos',$infos);

	}

}
