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

class DataController extends acymailingController{

	function listing()
	{
		return $this->import();

	}

	function import(){
		if(!$this->isAllowed('subscriber','import')) return;
		JRequest::setVar( 'layout', 'import'  );
		return parent::display();
	}

	function export(){
		if(!$this->isAllowed('subscriber','export')) return;
		JRequest::setVar( 'layout', 'export'  );
		return parent::display();
	}

	function doimport(){
		if(!$this->isAllowed('subscriber','import')) return;
		JRequest::checkToken() or die( 'Invalid Token' );


		$function = JRequest::getCmd('importfrom');

		$importHelper = acymailing_get('helper.import');
		if(!$importHelper->$function()){
			return $this->import();
		}
		$app = JFactory::getApplication();
		$this->setRedirect(acymailing_completeLink($app->isAdmin() ? 'subscriber' : 'frontsubscriber',false,true));
	}


	function ajaxload(){
		if(!$this->isAllowed('subscriber','import')) return;

		$function = JRequest::getCmd('importfrom').'_ajax';

		$importHelper = acymailing_get('helper.import');
		$importHelper->$function();
		exit;
	}

	function doexport(){
		if(!$this->isAllowed('subscriber','export')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		acymailing_increasePerf();

		$filtersExport = JRequest::getVar('exportfilter');
		$listsToExport = JRequest::getVar('exportlists');
		$fieldsToExport = JRequest::getVar('exportdata');
		$fieldsToExportOthers = JRequest::getVar('exportdataother');
		$inseparator = JRequest::getString('exportseparator');
		$inseparator = str_replace(array('semicolon','colon','comma'),array(';',',',','),$inseparator);
		$exportFormat = JRequest::getString('exportformat');
		if(!in_array($inseparator,array(',',';'))) $inseparator = ';';

		$exportLists = array();

		if(!empty($filtersExport['subscribed'])){
			foreach($listsToExport as $listid => $checked){
				if(!empty($checked)) $exportLists[] = (int) $listid;
			}
		}

		$exportFields = array();
		$exportFieldsOthers = array();
		$selectOthers = '';
		foreach($fieldsToExport as $fieldName => $checked){
			if(!empty($checked)) $exportFields[] = acymailing_secureField($fieldName);
		}
		if(!empty($fieldsToExportOthers)){
			foreach($fieldsToExportOthers as $fieldName => $checked){
				if(!empty($checked)) $exportFieldsOthers[] = acymailing_secureField($fieldName);
			}
		}

		$selectFields = 's.`'.implode('`, s.`',$exportFields).'`';

		$config = acymailing_config();
		$newConfig = new stdClass();
		$newConfig->export_fields = implode(',',array_merge($exportFields,$exportFieldsOthers));
		$newConfig->export_lists = implode(',',$exportLists);
		$newConfig->export_separator = JRequest::getString('exportseparator');
		$newConfig->export_format = $exportFormat;
		$config->save($newConfig);

		$where = array();
		if(empty($exportLists)){
			$querySelect = 'SELECT '.$selectFields.' FROM '.acymailing_table('subscriber').' as s';
		}else{
			$querySelect = 'SELECT DISTINCT '.$selectFields.' FROM '.acymailing_table('listsub').' as a JOIN '.acymailing_table('subscriber').' as s on a.subid = s.subid';
			$where[] = 'a.listid IN ('.implode(',',$exportLists).')';
			$where[] = 'a.status = 1';
		}

		if(!empty($filtersExport['confirmed'])) $where[] = 's.confirmed = 1';
		if(!empty($filtersExport['registered'])) $where[] = 's.userid > 0';
		if(!empty($filtersExport['enabled'])) $where[] = 's.enabled = 1';

		if(JRequest::getInt('sessionvalues') AND !empty($_SESSION['acymailing']['exportusers'])){
			$where[] = 's.subid IN ('.implode(',',$_SESSION['acymailing']['exportusers']).')';
		}

		$query = $querySelect;
		if(!empty($where)) $query .= ' WHERE ('.implode(') AND (',$where).')';

		if(JRequest::getInt('sessionquery')){
			$currentSession = JFactory::getSession();
			$selectOthers = '';
			if(!empty($exportFieldsOthers)){
				foreach($exportFieldsOthers as $oneField){
					$selectOthers .= ' , '.$oneField.' AS '.str_replace('.','_',$oneField);
				}
			}
			$query = 'SELECT DISTINCT '.$selectFields.$selectOthers.' '.$currentSession->get('acyexportquery');
		}



		$db = JFactory::getDBO();
		$db->setQuery($query);
		$allData = $db->loadAssocList();

		$encodingClass = acymailing_get('helper.encoding');

		$exportHelper = acymailing_get('helper.export');
		$exportHelper->addHeaders('acymailingexport');

		$eol= "\r\n";
		$before = '"';
		$separator = '"'.$inseparator.'"';
		$after = '"';

		$allFields = array_merge($exportFields,$exportFieldsOthers);

		echo $before.implode($separator,$allFields).$after.$eol;
		for($i=0,$a=count($allData);$i<$a;$i++){
			if(!empty($allData[$i]['created'])) $allData[$i]['created'] = acymailing_getDate($allData[$i]['created'],'%Y-%m-%d %H:%M:%S');
			if(!empty($allData[$i]['userstats_opendate'])) $allData[$i]['userstats_opendate'] = acymailing_getDate($allData[$i]['userstats_opendate'],'%Y-%m-%d %H:%M:%S');
			if(!empty($allData[$i]['userstats_senddate'])) $allData[$i]['userstats_senddate'] = acymailing_getDate($allData[$i]['userstats_senddate'],'%Y-%m-%d %H:%M:%S');
			if(!empty($allData[$i]['urlclick_date'])) $allData[$i]['urlclick_date'] = acymailing_getDate($allData[$i]['urlclick_date'],'%Y-%m-%d %H:%M:%S');
			echo $before.$encodingClass->change(implode($separator,$allData[$i]),'UTF-8',$exportFormat).$after.$eol;
		}

		exit;

	}

}
