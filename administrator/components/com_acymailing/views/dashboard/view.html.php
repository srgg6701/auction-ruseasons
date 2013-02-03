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

class dashboardViewDashboard extends acymailingView
{
	function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$config = acymailing_config();

		$buttons = array();
		$desc = array();
		$desc['subscriber'] = '<ul><li>'.JText::_('USERS_DESC_CREATE').'</li><li>'.JText::_('USERS_DESC_MANAGE').'</li><li>'.JText::_('USERS_DESC_IMPORT').'</li></ul>';
		$desc['list'] = '<ul><li>'.JText::_('LISTS_DESC_CREATE').'</li><li>'.JText::_('LISTS_DESC_SUBSCRIPTION').'</li></ul>';
		$desc['newsletter'] = '<ul><li>'.JText::_('NEWSLETTERS_DESC_CREATE').'</li><li>'.JText::_('NEWSLETTERS_DESC_TEST').'</li><li>'.JText::_('NEWSLETTERS_DESC_SEND').'</li></ul>';
		$desc['template'] = '<ul><li>'.JText::_('TEMPLATES_DESC_CREATE').'</li></ul>';
		$desc['queue'] = '<ul><li>'.JText::_('QUEUE_DESC_CONTROL').'</li></ul>';
		$desc['cpanel'] = '<ul><li>'.JText::_('CONFIG_DESC_CONFIG').'</li><li>'.JText::_('CONFIG_DESC_MODIFY').'</li><li>'.JText::_('CONFIG_DESC_PLUGIN').'</li><li>'.JText::_('QUEUE_DESC_BOUNCE');
		if(!acymailing_level(3)){ $desc['cpanel'] .= acymailing_getUpgradeLink('enterprise'); }
		$desc['cpanel'] .= '</li></ul>';
		$desc['stats'] = '<ul><li>'.JText::_('STATS_DESC_VIEW').'</li><li>'.JText::_('STATS_DESC_CLICK');
		if(!acymailing_level(1)){ $desc['stats'] .= acymailing_getUpgradeLink('essential'); }
		$desc['stats'] .= '</li><li>'.JText::_('STATS_DESC_CHARTS');
		if(!acymailing_level(1)){ $desc['stats'] .= acymailing_getUpgradeLink('essential'); }
		$desc['stats'] .= '</li></ul>';
		$desc['autonews'] = '<ul><li>'.JText::_('AUTONEWS_DESC');
		if(!acymailing_level(2)){ $desc['autonews'] .= acymailing_getUpgradeLink('business'); }
		$desc['autonews'] .='</li></ul>';
		$desc['campaign'] = '<ul><li>'.JText::_('CAMPAIGN_DESC_CREATE');
		if(!acymailing_level(3)){ $desc['campaign'] .= acymailing_getUpgradeLink('enterprise'); }
		$desc['campaign'] .= '</li><li>'.JText::_('CAMPAIGN_DESC_AFFECT');
		if(!acymailing_level(3)){ $desc['campaign'] .= acymailing_getUpgradeLink('enterprise'); }
		$desc['campaign'] .='</li></ul>';
		$desc['update'] = '<ul><li>'.JText::_('UPDATE_DESC').'</li><li>'.JText::_('CHANGELOG_DESC').'</li><li>'.JText::_('ABOUT_DESC').'</li></ul>';

		$buttons[] = array('link'=>'subscriber','level'=>0,'image'=>'acyusers','text'=>JText::_('USERS'),'acl' => 'acl_subscriber_manage');
		$buttons[] = array('link'=>'list','level'=>0,'image'=>'acylist','text'=>JText::_('LISTS'),'acl' => 'acl_lists_manage');
		$buttons[] = array('link'=>'newsletter','level'=>0,'image'=>'newsletter','text'=>JText::_('NEWSLETTERS'),'acl' => 'acl_newsletters_manage');
		$buttons[] = array('link'=>'autonews','level'=>2,'image'=>'autonewsletter','text'=>JText::_('AUTONEWSLETTERS'),'acl' => 'acl_autonewsletters_manage');
		$buttons[] = array('link'=>'campaign','level'=>3,'image'=>'campaign','text'=>JText::_('CAMPAIGN'), 'acl' => 'acl_campaign_manage');
		$buttons[] = array('link'=>'template','level'=>0,'image'=>'acytemplate','text'=>JText::_('ACY_TEMPLATES'), 'acl' => 'acl_templates_manage');
		$buttons[] = array('link'=>'queue','level'=>0,'image'=>'process','text'=>JText::_('QUEUE'), 'acl' => 'acl_queue_manage');
		$buttons[] = array('link'=>'stats','level'=>0,'image'=>'stats','text'=>JText::_('STATISTICS'), 'acl' => 'acl_statistics_manage');
		if(!ACYMAILING_J16 || JFactory::getUser()->authorise('core.admin', 'com_acymailing')) $buttons[] = array('link'=>'cpanel','level'=>0,'image'=>'acyconfig','text'=>JText::_('CONFIGURATION'), 'acl' => 'acl_configuration_manage');
		$buttons[] = array('link'=>'update','level'=>0,'image'=>'acyupdate','text'=>JText::_('UPDATE_ABOUT'), 'acl' => 'acl_configuration_manage');

		$htmlbuttons = array();
		foreach($buttons as $oneButton){
			if(acymailing_isAllowed($config->get($oneButton['acl'],'all'))){
				$htmlbuttons[] = $this->_quickiconButton($oneButton['link'],$oneButton['image'],$oneButton['text'],$desc[$oneButton['link']],$oneButton['level']);
			}
		}

		acymailing_setTitle( ACYMAILING_NAME , 'acymailing' ,'dashboard' );

		$bar = JToolBar::getInstance('toolbar');
		if(ACYMAILING_J16 && JFactory::getUser()->authorise('core.admin', 'com_acymailing')) {
			JToolBarHelper::preferences('com_acymailing');
		}
		$bar->appendButton( 'Pophelp','dashboard');

		$this->assignRef('buttons',$htmlbuttons);
		$toggleClass = acymailing_get('helper.toggle');
		$this->assignRef('toggleClass',$toggleClass);

		$db = JFactory::getDBO();
		$db->setQuery('SELECT name,email,html,confirmed,subid,created FROM '.acymailing_table('subscriber').' ORDER BY subid DESC LIMIT 15');
		$users10 = $db->loadObjectList();
		$this->assignRef('users',$users10);

		$db->setQuery('SELECT a.*, b.subject FROM '.acymailing_table('stats').' as a JOIN '.acymailing_table('mail').' as b on a.mailid = b.mailid ORDER BY a.senddate DESC LIMIT 15');
		$newsletters10 = $db->loadObjectList();
		$this->assignRef('stats',$newsletters10);

		$doc->addScript(((empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) != "on" ) ? 'http://' : 'https://')."www.google.com/jsapi");
		$today = acymailing_getTime(date('Y-m-d'));
		$joomConfig = JFactory::getConfig();
		$offset = ACYMAILING_J30 ? $joomConfig->get('offset') : $joomConfig->getValue('config.offset');
		$diff = date('Z') + intval($offset*60*60);
		$db->setQuery("SELECT count(`subid`) as total, DATE_FORMAT(FROM_UNIXTIME(`created` - $diff),'%Y-%m-%d') as subday FROM ".acymailing_table('subscriber')." GROUP BY subday ORDER BY subday DESC LIMIT 15");
		$statsusers = $db->loadObjectList();
		$this->assignRef('statsusers',$statsusers);

		$tabs = acymailing_get('helper.acytabs');
		$tabs->setOptions(array('useCookie' => true));

		$this->assignRef('tabs',$tabs);

		$this->assignRef('config',$config);

		parent::display($tpl);
	}

	function _quickiconButton( $link, $image, $text,$description,$level)
	{
		$url = acymailing_level($level) ? 'onclick="document.location.href=\''.acymailing_completeLink($link).'\';"' : '';
		$html = '<div style="float:left;width: 100%;" '.$url.' class="icon"><table width="100%"><tr><td style="text-align: center;" width="100px">';
		$html .= '<span class="icon-48-'.$image.'" style="background-repeat:no-repeat;background-position:center;width:auto;height:48px" title="'.$text.'"> </span>';
		$html .= '<span>'.$text.'</span></td><td style="text-align:left;">'.$description.'</td></tr></table>';
		$html .= '</div>';
		return $html;
	}
}
