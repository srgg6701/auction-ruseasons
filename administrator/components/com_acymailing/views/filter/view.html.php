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

class FilterViewFilter extends acymailingView
{

	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function form(){
		$db = JFactory::getDBO();
		$config = acymailing_config();
		$filid = acymailing_getCID('filid');

		$filterClass = acymailing_get('class.filter');
		if(!empty($filid)){
			$filter = $filterClass->get($filid);
		}else{
			$filter = new stdClass();
			$filter->action = JRequest::getVar('action');
			$filter->filter = JRequest::getVar('filter');
			$filter->published = 1;
		}

		JPluginHelper::importPlugin('acymailing');
		$this->dispatcher = JDispatcher::getInstance();

		$typesFilters = array();
		$typesActions = array();

		$outputFilters = implode('',$this->dispatcher->trigger('onAcyDisplayFilters',array(&$typesFilters,'massactions')));
		$outputActions = implode('',$this->dispatcher->trigger('onAcyDisplayActions',array(&$typesActions)));

		$typevaluesFilters = array();
		$typevaluesActions = array();
		$typevaluesFilters[] = JHTML::_('select.option', '',JText::_('FILTER_SELECT'));
		$typevaluesActions[] = JHTML::_('select.option', '',JText::_('ACTION_SELECT'));
		$doc = JFactory::getDocument();
		foreach($typesFilters as $oneType => $oneName){
			$typevaluesFilters[] = JHTML::_('select.option', $oneType,$oneName);
		}
		foreach($typesActions as $oneType => $oneName){
			$typevaluesActions[] = JHTML::_('select.option', $oneType,$oneName);
		}

		$js = "function updateFilter(filterNum){
				currentFilterType =window.document.getElementById('filtertype'+filterNum).value;
				if(!currentFilterType){
					window.document.getElementById('filterarea_'+filterNum).innerHTML = '';
					document.getElementById('countresult_'+filterNum).innerHTML = '';
					return;
				}
				filterArea = 'filter__num__'+currentFilterType;
				window.document.getElementById('filterarea_'+filterNum).innerHTML = window.document.getElementById(filterArea).innerHTML.replace(/__num__/g,filterNum);
			}

			function updateAction(actionNum){
				currentActionType =window.document.getElementById('actiontype'+actionNum).value;
				if(!currentActionType){
					window.document.getElementById('actionarea_'+actionNum).innerHTML = '';
					return;
				}
				actionArea = 'action__num__'+currentActionType;
				window.document.getElementById('actionarea_'+actionNum).innerHTML = window.document.getElementById(actionArea).innerHTML.replace(/__num__/g,actionNum);
			}";

		$js .= "var numFilters = 0;
				var numActions = 0;
				function addAcyFilter(){
					var newdiv = document.createElement('div');
					newdiv.id = 'filter'+numFilters;
					newdiv.className = 'plugarea';
					newdiv.innerHTML = '';
					if(numFilters > 0) newdiv.innerHTML += '".JText::_('FILTER_AND')."';
					newdiv.innerHTML += document.getElementById('filters_original').innerHTML.replace(/__num__/g, numFilters);
					if(document.getElementById('allfilters')){
						document.getElementById('allfilters').appendChild(newdiv); updateFilter(numFilters); numFilters++;
					}
				}
				function addAction(){
					var newdiv = document.createElement('div');
					newdiv.id = 'action'+numActions;
					newdiv.className = 'plugarea';
					newdiv.innerHTML = document.getElementById('actions_original').innerHTML.replace(/__num__/g, numActions);
					document.getElementById('allactions').appendChild(newdiv); updateAction(numActions); numActions++;
				}";

		if(ACYMAILING_J30) {
			$js .= 'window.addEvent("domready", function(){
				jQuery("#acybase_filters .chzn-container").remove();
				jQuery("#acybase_filters .chzn-done").removeClass("chzn-done").show();
			});';
		}

		$js .= "window.addEvent('domready', function(){ addAcyFilter(); addAction(); });";

		if(!ACYMAILING_J16){
			$js .= 	'function submitbutton(pressbutton){
						if (pressbutton != \'save\') {
							submitform( pressbutton );
							return;
						}';
		}else{
			$js .= 	'Joomla.submitbutton = function(pressbutton) {
						if (pressbutton != \'save\') {
							Joomla.submitform(pressbutton,document.adminForm);
							return;
						}';
		}
		$js .= 	"if(window.document.getElementById('filterinfo').style.display == 'none'){
						window.document.getElementById('filterinfo').style.display = 'block';
						try{allspans = window.document.getElementById('toolbar-save').getElementsByTagName(\"span\"); allspans[0].className = 'icon-32-apply';}catch(err){}
						return false;}
					if(window.document.getElementById('title').value.length < 2){alert('".JText::_('ENTER_TITLE',true)."'); return false;}";
		if(!ACYMAILING_J16){
			$js .= 	"submitform( pressbutton );} ";
		}else{ $js .= 	"Joomla.submitform(pressbutton,document.adminForm);}; "; }

		$js .= "function countresults(num){
					document.getElementById('countresult_'+num).innerHTML = '<span class=\"onload\"></span>';
					var form = $('adminForm');
					var data = form.toQueryString();
					data += '&task=countresults&ctrl=filter';
					try{
						new Ajax('index.php?option=com_acymailing&tmpl=component&ctrl=filter&task=countresults&num='+num,{
							method: 'post',
							data: data,
							update: document.getElementById('countresult_'+num)
						}).request();
					}catch(err){
						new Request({
							method: 'post',
							data: data,
							url: 'index.php?option=com_acymailing&tmpl=component&ctrl=filter&task=countresults&num='+num,
							onSuccess: function(responseText, responseXML) {
								document.getElementById('countresult_'+num).innerHTML = responseText;
							}
						}).send();
					}
				}";

		$doc->addScriptDeclaration( $js );

		$js = '';
		$data = array('addAction' => 'action','addAcyFilter' => 'filter');
		foreach($data as $jsFunction => $datatype){
			if(empty($filter->$datatype)) continue;
			foreach($filter->{$datatype}['type'] as $num => $oneType){
				if(empty($oneType)) continue;
				$js .= "while(!document.getElementById('".$datatype."type$num')){".$jsFunction."();}
						document.getElementById('".$datatype."type$num').value= '$oneType';
						update".ucfirst($datatype)."($num);";
				if(empty($filter->{$datatype}[$num][$oneType])) continue;
				foreach($filter->{$datatype}[$num][$oneType] as $key => $value){
					$js .= "document.adminForm.elements['".$datatype."[$num][$oneType][$key]'].value = '".addslashes(str_replace(array("\n","\r"),' ',$value))."';";
					$js .= "if(document.adminForm.elements['".$datatype."[$num][$oneType][$key]'].type && document.adminForm.elements['".$datatype."[$num][$oneType][$key]'].type == 'checkbox'){ document.adminForm.elements['".$datatype."[$num][$oneType][$key]'].checked = 'checked'; }";
				}

				if($datatype == 'filter') $js.= " countresults($num);";
			}
		}

		$listid = JRequest::getInt('listid');
		if(!empty($listid)){
			$js .= "document.getElementById('actiontype0').value = 'list'; updateAction(0); document.adminForm.elements['action[0][list][selectedlist]'].value = '".$listid."';";

		}

		$doc->addScriptDeclaration( "window.addEvent('domready', function(){ $js });" );

		$triggers = array();
		$triggers['daycron'] = JText::_('AUTO_CRON_FILTER');
		$nextDate = $config->get('cron_plugins_next');
		if(!empty($nextDate)){
			$triggers['daycron'] .= ' ('.JText::_('NEXT_RUN').' : '.acymailing_getDate($nextDate,'%d %B %Y  %H:%M').')';
		}
		$triggers['subcreate'] = JText::_('ON_USER_CREATE');
		$triggers['subchange'] = JText::_('ON_USER_CHANGE');
		$this->dispatcher->trigger('onAcyDisplayTriggers',array(&$triggers));

		$name = empty($filter->name) ? '' : ' : '.$filter->name;
		acymailing_setTitle(JText::_('ACY_FILTER').$name,'filter','filter&task=edit&filid='.$filid);

		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Confirm', JText::_('PROCESS_CONFIRMATION'), 'process', JText::_('PROCESS'), 'process', false, false );
		JToolBarHelper::divider();
		if(acymailing_level(3)){
			JToolBarHelper::save();
			if(!empty($filter->filid)) $bar->appendButton( 'Link', 'new', JText::_('ACY_NEW'), acymailing_completeLink('filter&task=edit&filid=0') );
		}
		$bar->appendButton( 'Link', 'cancel', JText::_('ACY_CLOSE'), acymailing_completeLink('dashboard') );
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','filter');

		$subid = JRequest::getString('subid');
		if(!empty($subid)){
			$subArray = explode(',',trim($subid,','));
			JArrayHelper::toInteger($subArray);

			$db->setQuery('SELECT `name`,`email` FROM `#__acymailing_subscriber` WHERE `subid` IN ('.implode(',',$subArray).')');
			$users = $db->loadObjectList();
			if(!empty($users)){
				$this->assignRef('users',$users);
				$this->assignRef('subid',$subid);
			}

		}

		$this->assignRef('typevaluesFilters',$typevaluesFilters);
		$this->assignRef('typevaluesActions',$typevaluesActions);
		$this->assignRef('outputFilters',$outputFilters);
		$this->assignRef('outputActions',$outputActions);
		$this->assignRef('filter',$filter);

		$this->assignRef('triggers',$triggers);
		if(JRequest::getCmd('tmpl') == 'component'){
			$doc->addStyleSheet( ACYMAILING_CSS.'frontendedition.css' );
		}

		if(acymailing_level(3) AND JRequest::getCmd('tmpl') != 'component'){
			$db->setQuery('SELECT * FROM #__acymailing_filter ORDER BY `published` DESC, `filid` DESC');
			$filters = $db->loadObjectList();

			$toggleClass = acymailing_get('helper.toggle');
			$this->assignRef('toggleClass',$toggleClass);
			$this->assignRef('filters',$filters);
		}
	}
}
