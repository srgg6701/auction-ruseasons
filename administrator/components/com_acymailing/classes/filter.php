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

class filterClass extends acymailingClass{

	var $tables = array('filter');
	var $pkey = 'filid';
	var $report = array();
	var $subid;
	var $onlynew = false;

	function trigger($triggerName){
		if(!acymailing_level(3)) return;

		$config = acymailing_config();
		if(!$config->get('triggerfilter_'.$triggerName)) return;

		$this->database->setQuery("SELECT * FROM `#__acymailing_filter` WHERE `trigger` LIKE '%".acymailing_getEscaped($triggerName,true)."%'");
		$filters = $this->database->loadObjectList();

		if(empty($filters)){
			$newconfig = new stdClass();
			$name = 'triggerfilter_'.$triggerName;
			$newconfig->$name = 0;
			$config->save($newconfig);
			return;
		}
		foreach($filters as $oneFilter){
			if(empty($oneFilter->published)) continue;
			if(!empty($oneFilter->filter)) $oneFilter->filter = unserialize($oneFilter->filter);
			if(!empty($oneFilter->action)) $oneFilter->action = unserialize($oneFilter->action);
			$this->execute($oneFilter->filter,$oneFilter->action);
		}
	}


	function displayFilters($filters){
		$resultFilters = array();
		if(empty($filters['type'])) return $resultFilters;
		JPluginHelper::importPlugin('acymailing');
		$dispatcher = JDispatcher::getInstance();
		foreach($filters['type'] as $num => $oneType){
			if(empty($oneType)) continue;
			$resultFilters = array_merge($resultFilters,$dispatcher->trigger('onAcyDisplayFilter_'.$oneType,array($filters[$num][$oneType])));
		}
		return $resultFilters;
	}

	function execute($filters,$actions){
		JPluginHelper::importPlugin('acymailing');
		$this->dispatcher = JDispatcher::getInstance();
		$query = new acyQuery();

		if(!empty($this->subid)){
			$subArray = explode(',',trim($this->subid,','));
			JArrayHelper::toInteger($subArray);
			$query->where[] = 'sub.subid IN ('.implode(',',$subArray).')';
		}

		if(!empty($filters['type'])){
			foreach($filters['type'] as $num => $oneType){
				if(empty($oneType)) continue;
				$this->dispatcher->trigger('onAcyProcessFilter_'.$oneType,array(&$query,$filters[$num][$oneType],$num));
			}
		}

		if(!empty($actions['type'])){
			foreach($actions['type'] as $num => $oneType){
				if(empty($oneType)) continue;
				$this->report = array_merge($this->report,$this->dispatcher->trigger('onAcyProcessAction_'.$oneType,array(&$query,$actions[$num][$oneType],$num)));
			}
		}
	}


	function saveForm(){
		$filter = new stdClass();
		$filter->filid = acymailing_getCID('filid');

		$formData = JRequest::getVar( 'data', array(), '', 'array' );

		foreach($formData['filter'] as $column => $value){
			acymailing_secureField($column);
			$filter->$column = strip_tags($value);
		}
		$config = acymailing_config();
		$alltriggers = array_keys((array)JRequest::getVar('trigger'));
		$filter->trigger = implode(',',$alltriggers);
		$newConfig = new stdClass();
		foreach($alltriggers as $oneTrigger){
			$name = 'triggerfilter_'.$oneTrigger;
			if($config->get($name)) continue;
			$newConfig->$name = 1;
		}
		if(!empty($newConfig)) $config->save($newConfig);

		$data = array('action','filter');
		foreach($data as $oneData){
			$filter->$oneData = array();
			$formData = JRequest::getVar($oneData);
			foreach($formData['type'] as $num => $oneType){
				if(empty($oneType)) continue;
				$filter->{$oneData}['type'][$num] = $oneType;
				$filter->{$oneData}[$num][$oneType] = $formData[$num][$oneType];
			}
			$filter->$oneData = serialize($filter->$oneData);
		}

			$filid = $this->save($filter);
			if(!$filid) return false;

			JRequest::setVar( 'filid', $filid);
			return true;
	}

	function get($filid,$default = null){
		$query = 'SELECT a.* FROM #__acymailing_filter as a WHERE a.`filid` = '.intval($filid).' LIMIT 1';
		$this->database->setQuery($query);

		$filter = $this->database->loadObject();
		if(!empty($filter->filter)){
			$filter->filter = unserialize($filter->filter);
		}

		if(!empty($filter->action)){
			$filter->action = unserialize($filter->action);
		}

		if(!empty($filter->trigger)){
			$filter->trigger = array_flip(explode(',',$filter->trigger));
		}

		return $filter;
	}

	function countReceivers($listids,$filters,$mailid = 0){

		if(empty($listids)) return 0;

		JPluginHelper::importPlugin('acymailing');
		$this->dispatcher = JDispatcher::getInstance();
		$query = new acyQuery();

		JArrayHelper::toInteger($listids);
		$query->from = '#__acymailing_listsub as listsub';
		$query->join[] = '#__acymailing_subscriber as sub ON sub.subid = listsub.subid';
		$query->where[] = 'listsub.listid IN ('.implode(',',$listids).') AND listsub.status=1';
		$config = acymailing_config();
		if($config->get('require_confirmation')){
			$query->where[] = 'sub.confirmed = 1';
		}
		$query->where[] = 'sub.enabled = 1 AND sub.accept = 1';

		if($this->onlynew && !empty($mailid)){
			$query->leftjoin[] = '#__acymailing_userstats as userstats ON sub.subid = userstats.subid AND userstats.mailid = '.intval($mailid);
			$query->where[] = 'userstats.subid IS NULL';
		}

		if(!empty($filters['type'])){
			foreach($filters['type'] as $num => $oneType){
				if(empty($oneType)) continue;
				$this->dispatcher->trigger('onAcyProcessFilter_'.$oneType,array(&$query,$filters[$num][$oneType],$num));
			}
		}

		return $query->count();
	}

}

class acyQuery{
	var $leftjoin = array();
	var $join = array();
	var $where = array();
	var $from = '#__acymailing_subscriber as sub';

	function acyQuery(){
		$this->db = JFactory::getDBO();
	}

	function count(){
		$myquery = $this->getQuery(array('COUNT(DISTINCT sub.subid)'));
		$this->db->setQuery($myquery);
		return $this->db->loadResult();
	}

	function getQuery($select = array()){
		$query = '';
		if(!empty($select)) $query .= ' SELECT '.implode(',',$select);
		if(!empty($this->from)) $query .= ' FROM '.$this->from;
		if(!empty($this->join)) $query .= ' JOIN '.implode(' JOIN ',$this->join);
		if(!empty($this->leftjoin)) $query .= ' LEFT JOIN '.implode(' LEFT JOIN ',$this->leftjoin);
		if(!empty($this->where)) $query .= ' WHERE ('.implode(') AND (',$this->where).')';

		return $query;
	}

	function convertQuery($as,$column,$operator,$value){
		if($operator == 'CONTAINS'){
			$operator = 'LIKE';
			$value = '%'.$value.'%';
		}elseif($operator == 'BEGINS'){
			$operator = 'LIKE';
			$value = $value.'%';
		}elseif($operator == 'END'){
			$operator = 'LIKE';
			$value = '%'.$value;
		}elseif($operator == 'NOTCONTAINS'){
			$operator = 'NOT LIKE';
			$value = '%'.$value.'%';
		}elseif(!in_array($operator,array('REGEXP','NOT REGEXP','IS NULL','IS NOT NULL','NOT LIKE','LIKE','=','!=','>','<','>=','<='))){
			die('Operator not safe : '.$operator);
		}

		 if(strpos($value,'{time}') !== false){
		 	$value = acymailing_replaceDate($value);
		 	$value = strftime('%Y-%m-%d %H:%M:%S',$value);
		 }

		 $replace = array('{year}','{month}','{day}');
		 $replaceBy = array(date('Y'),date('m'),date('d'));
		 $value = str_replace($replace,$replaceBy,$value);	 

		if(!is_numeric($value) OR in_array($operator,array('REGEXP','NOT REGEXP','NOT LIKE','LIKE'))){
			$value = $this->db->Quote($value);
		}

		if(in_array($operator,array('IS NULL','IS NOT NULL'))){
			$value = '';
		}

		return $as.'.`'.acymailing_secureField($column).'` '.$operator.' '.$value;
	}
}
