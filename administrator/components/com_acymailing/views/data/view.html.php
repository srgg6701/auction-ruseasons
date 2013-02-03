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


class dataViewdata extends acymailingView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function import(){

		$listClass = acymailing_get('class.list');
		$app = JFactory::getApplication();
		$config = acymailing_config();

		if($app->isAdmin()){
			acymailing_setTitle(JText::_('IMPORT'),'import','data&task=import');

			$bar = JToolBar::getInstance('toolbar');
			JToolBarHelper::custom('doimport', 'import', '',JText::_('IMPORT'), false);
			$bar->appendButton( 'Link', 'cancel', JText::_('ACY_CANCEL'), acymailing_completeLink('subscriber') );
			JToolBarHelper::divider();
			$bar->appendButton( 'Pophelp','data-import');
		}


		$db = JFactory::getDBO();

		$importData = array();
		$importData['file'] = JText::_('ACY_FILE');
		$importData['textarea'] = JText::_('IMPORT_TEXTAREA');
		if($app->isAdmin()){
			$importData['joomla'] = JText::_('IMPORT_JOOMLA');
			$importData['contact'] = 'com_contact';
			$importData['database'] = JText::_('DATABASE');
			$importData['ldap'] = 'LDAP';
			$importData['zohocrm'] = 'ZohoCRM';


			$possibleImport = array();
			$possibleImport[$db->getPrefix().'acajoom_subscribers'] = array('acajoom','Acajoom');
			$possibleImport[$db->getPrefix().'ccnewsletter_subscribers'] = array('ccnewsletter','ccNewsletter');
			$possibleImport[$db->getPrefix().'letterman_subscribers'] = array('letterman','Letterman');
			$possibleImport[$db->getPrefix().'communicator_subscribers'] = array('communicator','Communicator');
			$possibleImport[$db->getPrefix().'yanc_subscribers'] = array('yanc','Yanc');
			$possibleImport[$db->getPrefix().'vemod_news_mailer_users'] = array('vemod','Vemod News Mailer');
			$possibleImport[$db->getPrefix().'jnews_subscribers'] = array('jnews','jNewsletter');
			$possibleImport['civicrm_email'] = array('civi','CiviCRM');
			$possibleImport[$db->getPrefix().'sobipro_field'] = array('sobipro','SobiPro');
			$possibleImport[$db->getPrefix().'nspro_subs'] = array('nspro','NS Pro');

			$tables = $db->getTableList();
			foreach($tables as $mytable){
				if(isset($possibleImport[$mytable])){
					$importData[$possibleImport[$mytable][0]] = $possibleImport[$mytable][1];
				}
			}
		}


		$importvalues = array();
		foreach($importData as $div => $name){
			$importvalues[] = JHTML::_('select.option', $div,$name);
		}
		$js = 'var currentoption = \'file\';
		function updateImport(newoption){document.getElementById(currentoption).style.display = "none";document.getElementById(newoption).style.display = \'block\';currentoption = newoption;}';
		$doc = JFactory::getDocument();

		$function = JRequest::getCmd('importfrom');
		if(!empty($function)){
			$js .='window.addEvent(\'load\', function(){ updateImport(\''.$function.'\'); });';
		}
		if($config->get('ldap_host') && $app->isAdmin()){
			$js .='window.addEvent(\'load\', function(){ updateldap(); });';
		}
		$doc->addScriptDeclaration( $js );

		$this->assignRef('importvalues',$importvalues);
		$this->assignRef('importdata',$importData);

		$lists = $app->isAdmin() ? $listClass->getLists() : $listClass->getFrontendLists();
		$this->assignRef('lists',$lists);
		$this->assignRef('config',$config);

	}

	function export(){

		$listClass = acymailing_get('class.list');
		$db = JFactory::getDBO();
		$fields = acymailing_getColumns('#__acymailing_subscriber');
		$config = acymailing_config();
		$selectedFields = explode(',',$config->get('export_fields','email,name'));
		$selectedLists = explode(',',$config->get('export_lists'));

		acymailing_setTitle(JText::_('ACY_EXPORT'),'acyexport','data&task=export');

		$bar = JToolBar::getInstance('toolbar');
		JToolBarHelper::custom('doexport', 'acyexport', '',JText::_('ACY_EXPORT'), false);
		$bar->appendButton( 'Link', 'cancel', JText::_('ACY_CANCEL'), acymailing_completeLink('subscriber') );
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','data-export');

		$charsetType = acymailing_get('type.charset');
		$this->assignRef('charset',$charsetType);
		$lists = $listClass->getLists();
		$this->assignRef('lists',$lists);
		$this->assignRef('fields',$fields);
		$this->assignRef('selectedfields',$selectedFields);
		$this->assignRef('selectedlists',$selectedLists);
		$this->assignRef('config',$config);

		$whereSubscribers = '';
		if(JRequest::getInt('sessionvalues') AND !empty($_SESSION['acymailing']['exportusers'])){
			$i = 1;
			$subids = array();
			foreach($_SESSION['acymailing']['exportusers'] as $subid){
				$subids[] = (int) $subid;
				$i++;
				if($i>10) break;
			}

			if(!empty($subids)){
				$db->setQuery('SELECT `name`,`email` FROM `#__acymailing_subscriber` WHERE `subid` IN ('.implode(',',$subids).') LIMIT 10');
				$users = $db->loadObjectList();
				$this->assignRef('users',$users);
			}
		}

		if(JRequest::getInt('sessionquery')){
			$currentSession = JFactory::getSession();
			$exportQuery = $currentSession->get('acyexportquery');
			if(!empty($exportQuery)){
				$db->setQuery('SELECT s.`name`,s.`email` '.$exportQuery.' LIMIT 10');
				$users = $db->loadObjectList();
				$this->assignRef('users',$users);

				if(strpos($exportQuery,'userstats')){
					$otherFields = array('userstats.senddate','userstats.open','userstats.opendate','userstats.bounce','userstats.ip','userstats.html','userstats.fail','userstats.sent');
					$this->assignRef('otherfields',$otherFields);
				}
				if(strpos($exportQuery,'urlclick')){
					$otherFields = array('url.name','url.url','urlclick.date','urlclick.ip','urlclick.click');
					$this->assignRef('otherfields',$otherFields);
				}
			}
		}
	}
}
