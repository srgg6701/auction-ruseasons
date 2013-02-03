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

class FilterController extends acymailingController{
	var $pkey = 'filid';
	var $table = 'filter';

	function listing(){
		return $this->add();
	}

	function countresults(){
		$filterClass = acymailing_get('class.filter');
		$num = JRequest::getInt('num');
		$filters = JRequest::getVar('filter');
		$query = new acyQuery();
		if(empty($filters['type'][$num])) exit;
		$currentType = $filters['type'][$num];
		if(empty($filters[$num][$currentType])) exit;
		$currentFilterData = $filters[$num][$currentType];
		JPluginHelper::importPlugin('acymailing');
		$dispatcher = JDispatcher::getInstance();
		$messages = $dispatcher->trigger('onAcyProcessFilterCount_'.$currentType,array(&$query,$currentFilterData,$num));
		echo implode(' | ',$messages);
		exit;
	}

	function process(){
		if(!$this->isAllowed('lists','filter')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		$filid = JRequest::getInt('filid');
		if(!empty($filid)){
			$this->store();
		}

		$filterClass = acymailing_get('class.filter');
		$filterClass->subid = JRequest::getString('subid');
		$filterClass->execute(JRequest::getVar('filter'),JRequest::getVar('action'));

		if(!empty($filterClass->report)){
			if(JRequest::getCmd('tmpl') == 'component'){
				echo acymailing_display($filterClass->report,'info');
				$js = "setTimeout('redirect()',2000); function redirect(){window.top.location.href = 'index.php?option=com_acymailing&ctrl=subscriber'; }";
				$doc = JFactory::getDocument();
				$doc->addScriptDeclaration( $js );
				return;
			}else{
				$app = JFactory::getApplication();
				foreach($filterClass->report as $oneReport){
					$app->enqueueMessage($oneReport);
				}
			}
		}
		return $this->edit();
	}

	function store(){
		if(!$this->isAllowed('lists','filter')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		$app = JFactory::getApplication();

		$class = acymailing_get('class.filter');
		$status = $class->saveForm();
		if($status){
			$app->enqueueMessage(JText::_( 'JOOMEXT_SUCC_SAVED' ), 'message');
		}else{
			$app->enqueueMessage(JText::_( 'ERROR_SAVING' ), 'error');
			if(!empty($class->errors)){
				foreach($class->errors as $oneError){
					$app->enqueueMessage($oneError, 'error');
				}
			}
		}
	}
}
