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

class CpanelViewCpanel extends acymailingView
{
	function display($tpl = null)
	{
		JHTML::_('behavior.modal','a.modal');

		$toggleClass = acymailing_get('helper.toggle');
		$config = acymailing_config();
		$db = JFactory::getDBO();
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$lg = JFactory::getLanguage();
		$language = $lg->getTag();
		$styleRemind= 'float:right;margin-right:30px;position:relative;';
		$loadLink = '<a onclick="window.document.getElementById(\'acymailing_messages_warning\').style.display = \'none\';return true;" class="modal" rel="{handler: \'iframe\', size:{x:800, y:500}}" href="index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=file&amp;task=latest&amp;code='.$language.'">'.JText::_('LOAD_LATEST_LANGUAGE').'</a>';
		if(!file_exists(ACYMAILING_ROOT.'language'.DS.$language.DS.$language.'.com_acymailing.ini')){
			if($config->get('errorlanguagemissing',1)){
				$notremind = '<small style="'.$styleRemind.'">'.$toggleClass->delete('acymailing_messages_warning','errorlanguagemissing_0','config',false,JText::_('DONT_REMIND')).'</small>';
				acymailing_display(JText::_('MISSING_LANGUAGE').' '.$loadLink.' '.$notremind,'warning');
			}
		}elseif(version_compare(JText::_('ACY_LANG_VERSION'),$config->get('version'),'<')){
			if($config->get('errorlanguageupdate',1)){
				$notremind = '<small style="'.$styleRemind.'">'.$toggleClass->delete('acymailing_messages_warning','errorlanguageupdate_0','config',false,JText::_('DONT_REMIND')).'</small>';
				acymailing_display(JText::_('UPDATE_LANGUAGE').' '.$loadLink.' '.$notremind,'warning');
			}
		}

		$indexes = array('listsub','stats','list','mail','userstats','urlclick','history','template','queue','subscriber');
		$addIndexes = array('We recenty optimized our database...');
		foreach($indexes as $oneTable){
			if($config->get('optimize_'.$oneTable,1)) continue;
			$addIndexes[] = 'Please '.$toggleClass->toggleText('addindex',$oneTable,'config','click here').' to add indexes on the '.$oneTable.' table';
		}
		if(count($addIndexes) > 1) acymailing_display($addIndexes,'warning');


		acymailing_setTitle(JText::_('CONFIGURATION'),'acyconfig','cpanel');

		$bar = JToolBar::getInstance('toolbar');
		JToolBarHelper::custom('test', 'acysend', '',JText::_('SEND_TEST'), false);
		JToolBarHelper::divider();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel('cancel',JText::_('ACY_CLOSE'));
		JToolBarHelper::divider();

		$bar->appendButton( 'Pophelp','config');
		if(acymailing_isAllowed($config->get('acl_cpanel_manage','all'))) $bar->appendButton( 'Link', 'acymailing', JText::_('ACY_CPANEL'), acymailing_completeLink('dashboard') );

		$elements = new stdClass();
		$elements->add_names = JHTML::_('acyselect.booleanlist', "config[add_names]" , '',$config->get('add_names',true) );
		$elements->embed_images = JHTML::_('acyselect.booleanlist', "config[embed_images]" , '',$config->get('embed_images',0) );
		$elements->embed_files = JHTML::_('acyselect.booleanlist', "config[embed_files]" , '',$config->get('embed_files',1) );
		$elements->multiple_part = JHTML::_('acyselect.booleanlist', "config[multiple_part]" , '',$config->get('multiple_part',0) );

		$mailerMethods = array('smtp_com','elasticemail','smtp','sendmail');
		$js = "function updateMailer(mailermethod){"."\n";
		foreach($mailerMethods as $oneMethod){
			$js .= " window.document.getElementById('".$oneMethod."_config').style.display = 'none'; "."\n";
		}
		$js .= "if(window.document.getElementById(mailermethod+'_config')) {window.document.getElementById(mailermethod+'_config').style.display = 'block';} }";
		$js .='window.addEvent(\'domready\', function(){ updateMailer(\''.$config->get('mailer_method','phpmail').'\'); });';
		$doc->addScriptDeclaration( $js );

		$encodingval = array();
		$encodingval[] = JHTML::_('select.option', 'binary', 'Binary' );
		$encodingval[] = JHTML::_('select.option', 'quoted-printable', 'Quoted-printable' );
		$encodingval[] = JHTML::_('select.option', '7bit', '7 Bit');
		$encodingval[] = JHTML::_('select.option', '8bit', '8 Bit' );
		$encodingval[] = JHTML::_('select.option', 'base64', 'Base 64' );
		$elements->encoding_format =  JHTML::_('select.genericlist', $encodingval, "config[encoding_format]" , 'size="1"', 'value', 'text', $config->get('encoding_format','base64'));

		$charset = acymailing_get('type.charset');
		$elements->charset = $charset->display("config[charset]",$config->get('charset','UTF-8'));

		$securedVals = array();
		$securedVals[] = JHTML::_('select.option', '','- - -');
		$securedVals[] = JHTML::_('select.option', 'ssl','SSL');
		$securedVals[] = JHTML::_('select.option', 'tls','TLS');
		$elements->smtp_secured = JHTML::_('select.genericlist',$securedVals, "config[smtp_secured]" , 'size="1" style="width:100px;"', 'value', 'text', $config->get('smtp_secured'));

		$elements->smtp_auth = JHTML::_('acyselect.booleanlist', "config[smtp_auth]" , '',$config->get('smtp_auth',0) );
		$elements->smtp_keepalive = JHTML::_('acyselect.booleanlist', "config[smtp_keepalive]" , '',$config->get('smtp_keepalive',1) );

		$elements->allow_visitor = JHTML::_('acyselect.booleanlist', "config[allow_visitor]" , '',$config->get('allow_visitor',1) );

		$editorType = acymailing_get('type.editor');
		$elements->editor = $editorType->display('config[editor]',$config->get('editor'));

		$elements->subscription_message = JHTML::_('acyselect.booleanlist', "config[subscription_message]" , '',$config->get('subscription_message',1) );
		$elements->confirmation_message = JHTML::_('acyselect.booleanlist', "config[confirmation_message]" , '',$config->get('confirmation_message',1) );
		$elements->unsubscription_message = JHTML::_('acyselect.booleanlist', "config[unsubscription_message]" , '',$config->get('unsubscription_message',1) );
		$elements->welcome_message = JHTML::_('acyselect.booleanlist', "config[welcome_message]" , '',$config->get('welcome_message',1) );
		$elements->unsub_message = JHTML::_('acyselect.booleanlist', "config[unsub_message]" , '',$config->get('unsub_message',1) );
		$elements->confirm_message = JHTML::_('acyselect.booleanlist', "config[confirm_message]" , '',$config->get('confirm_message',0) );

		$elements->show_footer = JHTML::_('acyselect.booleanlist', "config[show_footer]" , '',$config->get('show_footer',1) );
		if(acymailing_level(1)){
			$elements->forward = JHTML::_('acyselect.booleanlist', "config[forward]" , '',$config->get('forward',false) );
		}else{
			$elements->forward = acymailing_getUpgradeLink('essential');
		}


		if(acymailing_level(1)){
			$js = "function updateDKIM(dkimval){if(dkimval == 1){document.getElementById('dkim_config').style.display = 'block';}else{document.getElementById('dkim_config').style.display = 'none';}}
					window.addEvent('load', function(){ updateDKIM(".$config->get('dkim',0).");});";
			$doc->addScriptDeclaration( $js );
			if(function_exists('openssl_sign')){
				$elements->dkim = JHTML::_('acyselect.booleanlist', "config[dkim]" , 'onclick="updateDKIM(this.value)"',$config->get('dkim',0) );
			}else{
				$elements->dkim = '<input type="hidden" name="config[dkim]" value="0" />PHP Extension openssl not enabled';
			}

			$js = "function updateQueueProcess(newvalue){";
			$js .= "if(newvalue == 'onlyauto') {window.document.getElementById('method_auto').style.display = ''; window.document.getElementById('method_manual').style.display = 'none';}";
			$js .= "if(newvalue == 'auto') {window.document.getElementById('method_auto').style.display = ''; window.document.getElementById('method_manual').style.display = '';}";
			$js .= "if(newvalue == 'manual') {window.document.getElementById('method_auto').style.display = 'none'; window.document.getElementById('method_manual').style.display = '';}";
			$js .= '}';
			$js .='window.addEvent(\'domready\', function(){ updateQueueProcess(\''.$config->get('queue_type','auto').'\'); });';
			$doc->addScriptDeclaration( $js );

			$queueType = array();
			$queueType[] = JHTML::_('select.option', 'onlyauto',JText::_('AUTO_ONLY'));
			$queueType[] = JHTML::_('select.option', 'auto',JText::_('AUTO_MAN'));
			$queueType[] = JHTML::_('select.option', 'manual',JText::_('MANUAL_ONLY'));
			$elements->queue_type = JHTML::_('acyselect.radiolist', $queueType, "config[queue_type]" , 'onclick="updateQueueProcess(this.value);"', 'value', 'text', $config->get('queue_type','auto'));

		}else{
			$elements->dkim = acymailing_getUpgradeLink('essential');
		}

		$delayTypeManual = acymailing_get('type.delay');
		$elements->queue_pause = $delayTypeManual->display('config[queue_pause]',$config->get('queue_pause'),0);
		$delayTypeAuto = acymailing_get('type.delay');
		$elements->cron_frequency = $delayTypeAuto->display('config[cron_frequency]',$config->get('cron_frequency'),2);


		$cssval = array('css_frontend' => 'component', 'css_module' => 'module', 'css_backend' => 'component');
		foreach($cssval as $configval => $type){
			$myvals = array();
			$myvals[] = JHTML::_('select.option', '',JText::_('ACY_NONE'));

			$regex = '^'.$type.'_([-_a-z0-9]*)\.css$';
			$allCSSFiles = JFolder::files( ACYMAILING_MEDIA.'css', $regex );

			$family = '';
			foreach($allCSSFiles as $oneFile){
				preg_match('#'.$regex.'#i',$oneFile,$results);
				$fileName = str_replace('default_','',$results[1]);
				$fileNameArray = explode('_',$fileName);
				if(count($fileNameArray) == 2){
					if($fileNameArray[0] != $family){
						if(!empty($family)) $myvals[] = JHTML::_('select.option',  '</OPTGROUP>');
						$family = $fileNameArray[0];
						$myvals[] = JHTML::_('select.option',  '<OPTGROUP>', ucfirst($family));
					}
					unset($fileNameArray[0]);
					$fileName = implode('_',$fileNameArray);
				}

				$fileName = ucwords(str_replace('_',' ',$fileName));
				$myvals[] = JHTML::_('select.option', $results[1],$fileName);
			}
			if(!empty($family)) $myvals[] = JHTML::_('select.option',  '</OPTGROUP>');
			$js = 'onchange="updateCSSLink(\''.$configval.'\',\''.$type.'\',this.value);"';
			$currentVal = $config->get($configval,'default');
			$aStyle = empty($currentVal) ? 'style="display:none"' : '';
			$elements->$configval = JHTML::_('select.genericlist',   $myvals, 'config['.$configval.']', 'class="inputbox" size="1" '.$js, 'value', 'text', $config->get($configval,'default'),$configval.'_choice' );
			$linkEdit = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=file&amp;task=css&amp;file='.$type.'_'.$config->get($configval,'default').'&amp;var='.$configval;
			$elements->$configval .= ' <a id="'.$configval.'_link" '.$aStyle.' class="modal" title="'.JText::_('ACY_EDIT',true).'"  href="'.$linkEdit.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><img class="icon16" src="'.ACYMAILING_IMAGES.'icons/icon-16-edit.png" alt="'.JText::_('ACY_EDIT',true).'"/></a>';
		}
		$js = "function updateCSSLink(myid,type,newval){
			if(newval){document.getElementById(myid+'_link').style.display = '';}else{document.getElementById(myid+'_link').style.display = 'none'}
			document.getElementById(myid+'_link').href = 'index.php?option=com_acymailing&tmpl=component&ctrl=file&task=css&file='+type+'_'+newval+'&var='+myid;
		}";
		$doc->addScriptDeclaration( $js );


		$elements->colortype = acymailing_get('type.color');

		$elements->use_sef = JHTML::_('acyselect.booleanlist', "config[use_sef]" , '',$config->get('use_sef',0) );

		if(!ACYMAILING_J16){
			$query = 'SELECT a.name, a.id as itemid, b.title  FROM `#__menu` as a JOIN `#__menu_types` as b on a.menutype = b.menutype WHERE a.access = 0 ORDER BY b.title ASC,a.ordering ASC';
		}else{
			$orderby = ACYMAILING_J30 ? 'a.lft' : 'a.ordering';
			$query = 'SELECT a.alias as name, a.id as itemid, b.title  FROM `#__menu` as a JOIN `#__menu_types` as b on a.menutype = b.menutype WHERE a.client_id=0 AND a.parent_id != 0 ORDER BY b.title ASC,'.$orderby.' ASC';
		}

		$db->setQuery($query);
		$joomMenus = $db->loadObjectList();

		$menuvalues = array();
		$menuvalues[] = JHTML::_('select.option', '0',JText::_('ACY_NONE'));
		$lastGroup = '';
		foreach($joomMenus as $oneMenu){
			if($oneMenu->title != $lastGroup){
				if(!empty($lastGroup)) $menuvalues[] = JHTML::_('select.option', '</OPTGROUP>');
				$menuvalues[] = JHTML::_('select.option', '<OPTGROUP>',$oneMenu->title);
				$lastGroup = $oneMenu->title;
			}
			$menuvalues[] = JHTML::_('select.option', $oneMenu->itemid,$oneMenu->name);
		}

		$elements->acymailing_menu = JHTML::_('select.genericlist', $menuvalues, 'config[itemid]' , 'size="1"', 'value', 'text', $config->get('itemid'));

		$menupositions = array();
		$menupositions[] = JHTML::_('select.option', 'under',JText::_('UNDER_TITLE'));
		$menupositions[] = JHTML::_('select.option', 'above',JText::_('ABOVE_MAIN_AREA'));
		$elements->menu_position = JHTML::_('acyselect.radiolist', $menupositions, 'config[menu_position]' , 'size="1"', 'value', 'text', $config->get('menu_position','under'));

		if(ACYMAILING_J30){
			$elements->menu_position = '<input type="hidden" name="config[menu_position]" value="above" />'.JText::_('ABOVE_MAIN_AREA');
		}

		$acyrss_format = array();
		$acyrss_format[] = JHTML::_('select.option', '', JText::_('ACY_NONE') );
		$acyrss_format[] = JHTML::_('select.option', 'rss', 'RSS feed' );
		$acyrss_format[] = JHTML::_('select.option', 'atom', 'Atom feed');
		$acyrss_format[] = JHTML::_('select.option', 'both', JText::_('ACY_ALL') );
		$elements->acyrss_format =  JHTML::_('select.genericlist', $acyrss_format, "config[acyrss_format]" , 'size="1"', 'value', 'text', $config->get('acyrss_format',''));

		$acyrss_order = array();
		$acyrss_order[] = JHTML::_('select.option', 'senddate', JText::_('SEND_DATE') );
		$acyrss_order[] = JHTML::_('select.option', 'mailid', JText::_('ACY_ID') );
		$acyrss_order[] = JHTML::_('select.option', 'subject', JText::_('ACY_TITLE'));
		$elements->acyrss_order =  JHTML::_('select.genericlist', $acyrss_order, "config[acyrss_order]" , 'size="1"', 'value', 'text', $config->get('acyrss_order','senddate'));




		$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=email&amp;task=edit&amp;mailid=confirmation';
		$elements->editConfEmail = '<a class="modal" id="confirmemail"  href="'.$link.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><button class="btn" onclick="return false">'.JText::_('EDIT_CONF_MAIL').'</button></a>';

		$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=email&amp;task=edit&amp;mailid=notification_created';
		$elements->edit_notification_created = '<a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><button class="btn" onclick="return false">'.JText::_('EDIT_NOTIFICATION_MAIL').'</button></a>';
		$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=email&amp;task=edit&amp;mailid=notification_refuse';
		$elements->edit_notification_refuse = '<a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><button class="btn" onclick="return false">'.JText::_('EDIT_NOTIFICATION_MAIL').'</button></a>';
		$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=email&amp;task=edit&amp;mailid=notification_unsuball';
		$elements->edit_notification_unsuball = '<a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><button class="btn" onclick="return false">'.JText::_('EDIT_NOTIFICATION_MAIL').'</button></a>';
		$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=email&amp;task=edit&amp;mailid=notification_unsub';
		$elements->edit_notification_unsub = '<a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><button class="btn" onclick="return false">'.JText::_('EDIT_NOTIFICATION_MAIL').'</button></a>';

		$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=email&amp;task=edit&amp;mailid=modif';
		$elements->editModifEmail = '<a class="modal" id="modifemail"  href="'.$link.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><button class="btn" onclick="return false">'.JText::_('EDIT_NOTIFICATION_MAIL').'</button></a>';

		$js = "function addUnsubReason(){
			var input = document.createElement('input');
			input.name = 'unsub_reasons[]';
			input.style.width = '300px';
			input.type = 'text';
			document.getElementById('unsub_reasons').appendChild(input);
			var br = document.createElement('br');
			document.getElementById('unsub_reasons').appendChild(br);
		}
		function displaySurvey(surveyval){
			if(surveyval == 1){
				document.getElementById('unsub_reasons_area').style.display = 'block';
			}else{
				document.getElementById('unsub_reasons_area').style.display = 'none';
			}
		}
		";
		$doc->addScriptDeclaration( $js );

		$path = JLanguage::getLanguagePath(JPATH_ROOT);
		$dirs = JFolder::folders( $path );
		$languages = array();

		foreach ($dirs as $dir)
		{
			if(strlen($dir) != 5 || $dir == "xx-XX") continue;
			$xmlFiles = JFolder::files( $path.DS.$dir, '^([-_A-Za-z]*)\.xml$' );
			$xmlFile = reset($xmlFiles);
			if(empty($xmlFile)){
				$data = array();
			}else{
				$data = JApplicationHelper::parseXMLLangMetaFile($path.DS.$dir.DS.$xmlFile);
			}

			$oneLanguage = new stdClass();
			$oneLanguage->language 	= $dir;
			$oneLanguage->name = empty($data['name']) ? $dir : $data['name'];

			$languageFiles = JFolder::files( $path.DS.$dir, '^(.*)\.com_acymailing\.ini$' );
			$languageFile = reset($languageFiles);
			if(!empty($languageFile)){
				$linkEdit = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=file&amp;task=language&amp;code='.$oneLanguage->language;
				$oneLanguage->edit = ' <a class="modal" title="'.JText::_('EDIT_LANGUAGE_FILE',true).'"  href="'.$linkEdit.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><img id="image'.$oneLanguage->language.'" class="icon16" src="'.ACYMAILING_IMAGES.'icons/icon-16-edit.png" alt="'.JText::_('EDIT_LANGUAGE_FILE',true).'"/></a>';
			}else{
				$linkEdit = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=file&amp;task=language&amp;code='.$oneLanguage->language;
				$oneLanguage->edit = ' <a class="modal" title="'.JText::_('ADD_LANGUAGE_FILE',true).'"  href="'.$linkEdit.'" rel="{handler: \'iframe\', size:{x:800, y:500}}"><img id="image'.$oneLanguage->language.'" class="icon16"  src="'.ACYMAILING_IMAGES.'icons/icon-16-add.png" alt="'.JText::_('ADD_LANGUAGE_FILE',true).'"/></a>';
			}

			$languages[] = $oneLanguage;

		}

		$js = "function updateConfirmation(newvalue){";
		$js .= "if(newvalue == 0) {window.document.getElementById('confirmemail').style.display = 'none'; window.document.getElementById('confirm_redirect').disabled = true;}else{window.document.getElementById('confirmemail').style.display = 'inline'; window.document.getElementById('confirm_redirect').disabled = false;}";
		$js .= '}';
		$js .= "function updateModification(newvalue){ if(newvalue != 'none') {window.document.getElementById('modifemail').style.display = 'none';}else{window.document.getElementById('modifemail').style.display = 'inline';}} ";
		$js .='window.addEvent(\'load\', function(){ updateModification(\''.$config->get('allow_modif','data').'\'); updateConfirmation('.$config->get('require_confirmation',0).'); });';
		$doc->addScriptDeclaration( $js );

		$elements->require_confirmation = JHTML::_('acyselect.booleanlist', "config[require_confirmation]" , 'onclick="updateConfirmation(this.value)"',$config->get('require_confirmation',0) );

		$allowmodif = array();
		$allowmodif[] = JHTML::_('select.option', "none",JText::_('JOOMEXT_NO'));
		$allowmodif[] = JHTML::_('select.option', "data",JText::_('ONLY_SUBSCRIPTION'));
		$allowmodif[] = JHTML::_('select.option', "all",JText::_('JOOMEXT_YES'));
		$elements->allow_modif = JHTML::_('acyselect.radiolist', $allowmodif, "config[allow_modif]" , 'size="1" onclick="updateModification(this.value)"', 'value', 'text',$config->get('allow_modif','data'));


		if(!ACYMAILING_J16){
			$db->setQuery("SELECT name,published,id FROM `#__plugins` WHERE `folder` = 'acymailing' AND `element` NOT LIKE 'plg%' ORDER BY published DESC, ordering ASC");
		}else{
			$db->setQuery("SELECT name,enabled as published,extension_id as id FROM `#__extensions` WHERE `folder` = 'acymailing' AND `type`= 'plugin' AND `element` NOT LIKE 'plg%' ORDER BY enabled DESC, ordering ASC");
		}
		$plugins = $db->loadObjectList();

		if(!ACYMAILING_J16){
			$db->setQuery("SELECT name,published,id FROM `#__plugins` WHERE (`folder` != 'acymailing' OR `element` LIKE 'plg%') AND (`name` LIKE '%acymailing%' OR `element` LIKE '%acymailing%') ORDER BY published DESC, ordering ASC");
		}else{
			$db->setQuery("SELECT name,enabled as published ,extension_id as id FROM `#__extensions` WHERE (`folder` != 'acymailing' OR `element` LIKE 'plg%') AND `type` = 'plugin' AND (`name` LIKE '%acymailing%' OR `element` LIKE '%acymailing%') ORDER BY enabled DESC, ordering ASC");
		}

		$integrationplugins = $db->loadObjectList();

		$bounceaction = acymailing_get('type.bounceaction');
		$this->assignRef('bounceaction',$bounceaction);
		$this->assignRef('config',$config);
		$this->assignRef('languages',$languages);
		$this->assignRef('elements',$elements);
		$this->assignRef('plugins',$plugins);
		$this->assignRef('integrationplugins',$integrationplugins);


		$tabs = acymailing_get('helper.acytabs');
		$tabs->setOptions(array('useCookie' => true));

		$this->assignRef('tabs',$tabs);
		$this->assignRef('toggleClass',$toggleClass);

		if((!ACYMAILING_J16 AND !file_exists(rtrim(JPATH_SITE,DS).DS.'plugins'.DS.'acymailing'.DS.'tagsubscriber.php')) OR (ACYMAILING_J16 AND !file_exists(rtrim(JPATH_SITE,DS).DS.'plugins'.DS.'acymailing'.DS.'tagsubscriber'.DS.'tagsubscriber.php'))){
			acymailing_display(JText::sprintf('ERROR_PLUGINS','href="index.php?option=com_acymailing&amp;ctrl=update&amp;task=install"'),'warning');
		}

		return parent::display($tpl);

	}

}
