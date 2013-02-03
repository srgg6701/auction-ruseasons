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

class acymenuHelper{
	function display($selected = ''){

		if(!ACYMAILING_J16){
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration(" #submenu-box{display:none !important;} ");
		}

		$selected = substr($selected,0,5);
		if($selected == 'field' || $selected == 'bounc' || $selected == 'updat') $selected = 'cpane';
		if($selected == 'data' || $selected == 'data&') $selected = 'subsc';
		if($selected == 'campa' || $selected == 'templ' || $selected == 'auton') $selected = 'newsl';
		if($selected == 'diagr') $selected = 'stats';
		if($selected == 'filte') $selected = 'list';

		$config = acymailing_config();
		$mainmenu = array();
		$submenu = array();

		if(acymailing_isAllowed($config->get('acl_subscriber_manage','all'))){
			$mainmenu['subscriber'] = array(JText::_('USERS'), 'index.php?option=com_acymailing&ctrl=subscriber','acyicon-16-users');
			$submenu['subscriber'] = array();
			$submenu['subscriber'][] = array(JText::_('USERS'), 'index.php?option=com_acymailing&ctrl=subscriber','acyicon-16-users');
			if(acymailing_isAllowed($config->get('acl_subscriber_import','all'))) $submenu['subscriber'][] = array(JText::_('IMPORT'), 'index.php?option=com_acymailing&ctrl=data&task=import','acyicon-16-import');
			if(acymailing_isAllowed($config->get('acl_subscriber_export','all'))) $submenu['subscriber'][] = array(JText::_('ACY_EXPORT'), 'index.php?option=com_acymailing&ctrl=data&task=export','acyicon-16-export');
		}

		if(acymailing_isAllowed($config->get('acl_lists_manage','all'))){
			$mainmenu['list'] = array(JText::_('LISTS'), 'index.php?option=com_acymailing&ctrl=list','acyicon-16-acylist');
			$submenu['list'] = array();
			$submenu['list'][] = array(JText::_('LISTS'), 'index.php?option=com_acymailing&ctrl=list','acyicon-16-acylist');
			if(acymailing_isAllowed($config->get('acl_lists_filter','all'))) $submenu['list'][] = array(JText::_('ACY_FILTERS'), 'index.php?option=com_acymailing&ctrl=filter','acyicon-16-filter' );
		}

		if(acymailing_isAllowed($config->get('acl_newsletters_manage','all'))){
			$mainmenu['newsletter'] = array(JText::_('NEWSLETTERS'), 'index.php?option=com_acymailing&ctrl=newsletter','acyicon-16-newsletter');
			$submenu['newsletter'] = array();
			$submenu['newsletter'][] = array(JText::_('NEWSLETTERS'), 'index.php?option=com_acymailing&ctrl=newsletter','acyicon-16-newsletter');
			if(acymailing_level(2) && acymailing_isAllowed($config->get('acl_autonewsletters_manage','all'))){
				$submenu['newsletter'][] = array(JText::_('AUTONEWSLETTERS'), 'index.php?option=com_acymailing&ctrl=autonews','acyicon-16-autonewsletter');
			}
			if(acymailing_level(3) && acymailing_isAllowed($config->get('acl_campaign_manage','all'))){
				$submenu['newsletter'][] = array(JText::_('CAMPAIGN'), 'index.php?option=com_acymailing&ctrl=campaign','acyicon-16-campaign');
			}

			if(acymailing_isAllowed($config->get('acl_templates_manage','all'))) $submenu['newsletter'][] = array(JText::_('ACY_TEMPLATES'), 'index.php?option=com_acymailing&ctrl=template','acyicon-16-template');
		}

		if(acymailing_isAllowed($config->get('acl_queue_manage','all'))) $mainmenu['queue'] = array(JText::_('QUEUE'), 'index.php?option=com_acymailing&ctrl=queue','acyicon-16-queue');

		if(acymailing_isAllowed($config->get('acl_statistics_manage','all'))){
			$mainmenu['stats'] = array(JText::_('STATISTICS'), 'index.php?option=com_acymailing&ctrl=stats','acyicon-16-stats');
			$submenu['stats'] = array();
			$submenu['stats'][] = array(JText::_('STATISTICS'), 'index.php?option=com_acymailing&ctrl=stats','acyicon-16-stats');
			$submenu['stats'][]= array(JText::_('DETAILED_STATISTICS'), 'index.php?option=com_acymailing&ctrl=stats&task=detaillisting','acyicon-16-stats');
			if(acymailing_level(1)) $submenu['stats'][]= array(JText::_('CLICK_STATISTICS'), 'index.php?option=com_acymailing&ctrl=statsurl','acyicon-16-stats');
			if(acymailing_level(1)) $submenu['stats'][]= array(JText::_('CHARTS'), 'index.php?option=com_acymailing&ctrl=diagram','acyicon-16-stats');
		}
		if(acymailing_isAllowed($config->get('acl_configuration_manage','all')) && (!ACYMAILING_J16 || JFactory::getUser()->authorise('core.admin', 'com_acymailing'))){
			$mainmenu['cpanel'] = array(JText::_('CONFIGURATION'), 'index.php?option=com_acymailing&ctrl=cpanel','acyicon-16-config');
			$submenu['cpanel'] = array();
			$submenu['cpanel'][] = array(JText::_('CONFIGURATION'), 'index.php?option=com_acymailing&ctrl=cpanel','acyicon-16-config');
			if(acymailing_level(3)){
				$submenu['cpanel'][] = array(JText::_('EXTRA_FIELDS'), 'index.php?option=com_acymailing&ctrl=fields','acyicon-16-fields');
				$submenu['cpanel'][] = array(JText::_('BOUNCE_HANDLING'), 'index.php?option=com_acymailing&ctrl=bounces','acyicon-16-bounces');
			}
			$submenu['cpanel'][] = array(JText::_('UPDATE_ABOUT'), 'index.php?option=com_acymailing&ctrl=update','acyicon-16-update');
		}

		$doc = JFactory::getDocument();
		$doc->addStyleSheet( ACYMAILING_CSS.'acymenu.css?v='.str_replace('.','',$config->get('version')) );

		if(!ACYMAILING_J30) {
			$menu = '<div id="acymenutop" class="donotprint"><ul>';
			foreach($mainmenu as $id => $oneMenu){
				$menu .= '<li class="acymainmenu'.(!empty($submenu[$id]) ? ' parentmenu' : ' singlemenu').'"';
				if($selected == substr($id,0,5)) $menu .= ' id="acyselectedmenu"';
				$menu .= ' >';
				$menu .= '<a class="acymainmenulink '.$oneMenu[2].'" href="'.$oneMenu[1].'" >'.$oneMenu[0].'</a>';
				if(!empty($submenu[$id])){
					$menu .= '<ul>';
					foreach($submenu[$id] as $subelement){
						$menu .= '<li class="acysubmenu "><a class="acysubmenulink '.$subelement[2].'" href="'.$subelement[1].'" title="'.$subelement[0].'">'.$subelement[0].'</a></li>';
					}
					$menu .= '</ul>';
				}
				$menu .= '</li>';
			}
			$menu .= '</ul></div><div style="clear:left"></div>';
		} else {
			$menu = '<div class="navbar"><div class="navbar-inner"><div class="container"><div class="nav"><ul id="acymenutop_j3" class="nav">';
			foreach($mainmenu as $id => $oneMenu) {
				$sel = '';
				if($selected == substr($id,0,5)) $sel = ' sel';
				$menu .= '<li class="dropdown'.$sel.'"><a class="dropdown-toggle'.$sel.'" '.(!empty($submenu[$id]) ? 'data-toggle="dropdown"' : '').' href="'.(!empty($submenu[$id]) ? '#' : $oneMenu[1]).'"><i class="'.$oneMenu[2].'"></i> '.$oneMenu[0]. (!empty($submenu[$id]) ? '<span class="caret"></span>' : '') . '</a>';
				if(!empty($submenu[$id])){
					$menu .= '<ul class="dropdown-menu">';
					foreach($submenu[$id] as $subelement){
						$menu .= '<li class="acysubmenu "><a class="acysubmenulink" href="'.$subelement[1].'" title="'.$subelement[0].'"><i class="'.$subelement[2].'"></i> '.$subelement[0].'</a></li>';
					}
					$menu .= '</ul>';
				}
				$menu .= '</li>';
			}
			$menu .= '</ul></div></div></div></div>';
		}
		return $menu;
	}
}
