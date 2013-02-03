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

class fieldsClass extends acymailingClass{

	var $tables = array('fields');
	var $pkey = 'fieldid';
	var $errors = array();
	var $prefix = 'field_';
	var $suffix = '';
	var $excludeValue = array();
	var $formoption = '';

	var $labelClass = '';

	function getFields($area,&$user){

		if(empty($user)) $user = new stdClass();

		$where = array();
		$where[] = 'a.`published` = 1';
		if($area == 'backend'){
			$where[] = 'a.`backend` = 1';
			$where[] = 'a.`core` = 0';
		}elseif($area == 'backlisting'){
			$where[] = 'a.`listing` = 1';
		}elseif($area == 'frontcomp'){
			$where[] = 'a.`frontcomp` = 1';
		}elseif($area != 'module'){
			return false;
		}

		$this->database->setQuery('SELECT * FROM `#__acymailing_fields` as a WHERE '.implode(' AND ',$where).' ORDER BY a.`ordering` ASC');
		$fields = $this->database->loadObjectList('namekey');

		foreach($fields as $namekey => $field){
			if(!empty($fields[$namekey]->options)){
				$fields[$namekey]->options = unserialize($fields[$namekey]->options);
			}
			if(!empty($field->value)){
				$fields[$namekey]->value = $this->explodeValues($fields[$namekey]->value);
			}
			if($field->type == 'file') $this->formoption = 'enctype="multipart/form-data"';
			if(empty($user->subid)) $user->$namekey = $field->default;
		}
		return $fields;
	}

	function getFieldName($field){
		$addLabels = array('textarea','text','dropdown','multipledropdown','file');
		return '<label '.(empty($this->labelClass) ? '' : ' class="'.$this->labelClass.'" ').(in_array($field->type,$addLabels) ? ' for="'.$this->prefix.$field->namekey.$this->suffix.'" ' : '' ).'>'.$this->trans($field->fieldname).'</label>';
	}

	function trans($name){
		if(preg_match('#^[A-Z_]*$#',$name)){
			return JText::_($name);
		}
		return $name;
	}

	function listing($field,$value){
		$functionType = '_listing'.ucfirst($field->type);
		return method_exists($this,$functionType) ? $this->$functionType($field,$value) : nl2br($this->trans($value));
	}

	function explodeValues($values){
		$allValues = explode("\n",$values);
		$returnedValues = array();
		foreach($allValues as $id => $oneVal){
			$line = explode('::',trim($oneVal));
			$var = @$line[0];
			$val = @$line[1];
			if(strlen($val)<1) continue;

			$obj = new stdClass();
			$obj->value = $val;
			for($i=2;$i<count($line);$i++){
				$obj->{$line[$i]} = 1;
			}
			$returnedValues[$var] = $obj;
		}
		return $returnedValues;
	}

}
