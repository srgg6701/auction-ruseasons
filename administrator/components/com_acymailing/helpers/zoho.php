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

class zohoHelper {
		var $conn;
		var $authtoken = '';
		var $error = '';

		function connect() {
			if (is_resource($this->conn))
					return true;
			$this->conn = fsockopen('ssl://crm.zoho.com', 443, $errno, $errstr, 20);
			if (!$this->conn) {
				$this->error = 'Could not open connection ( error '.$errno.' : '.$errstr.' )';
				return false;
			}
			return true;
		}

		function sendInfo($userList){
			if (!$this->connect())	return false;
			$res = '';
			$header = "GET /crm/private/xml/" . urlencode($userList) . "/getRecords?newFormat=1&authtoken=" . urlencode($this->authtoken) . "&scope=crmapi HTTP/1.0\r\n";
			$header .= "Host: crm.zoho.com \r\n";
			$header .= "Content-Type: text/xml\r\n";
			$header .= "Connection: Close \r\n\r\n";
			fwrite($this->conn, $header);
			while (!feof($this->conn)) {
				$res .= fread($this->conn, 1024);
			}
			if (!empty($res) && preg_match('#error#', $res) == 1) {
				preg_match('#<message>(.*)</message>#Ui', $res, $explodedResults);
				$this->error = $explodedResults[1];
				return false;
			}
			return $res;
		}

		function parseXML($res,$userList,$selectedFields,$confirmedUsers) {
			$xml = substr($res,strpos($res,'<?xml'));
			$xml = new SimpleXMLElement($xml);
			$emailArray= array();

			foreach($xml->result->$userList->row as $key=>$row){
				$informations = new stdClass();
				$informations->zoholist = strtolower($userList[0]);
				$informations->confirmed = $confirmedUsers;
				foreach($selectedFields as $oneField){
					if(empty($oneField)) continue;
				 	$informations->$oneField = '';
				}

				foreach($row->FL as $key => $value){
					if($value['val'] == 'CONTACTID' || $value['val'] == 'LEADID' ||$value['val'] == 'VENDORID' )	$informations->zohoid =(string)$value;
					elseif($value['val'] == 'Email Opt Out'){
						if ($value == 'false')	$informations->accept=1;
						else $informations->accept=0;
					}
					elseif(!empty($selectedFields[(string)$value['val']]))
						$informations->$selectedFields[(string)$value['val']] = (string)$value;
					elseif($value['val'] == 'Email')
						$informations->email = (string)$value;
				}
				if(!empty($informations->email)){
					$emailArray[]=$informations;
				}
			}
			if(empty($emailArray)) $this->error .= 'There is no email Address in the '.$userList.' list';
			return $emailArray;
		}

		function subscribe($acyList, $zohoList){
			$db = JFactory::getDBO();
			if(empty($acyList) || empty($zohoList)) return 0;

			$query = 'INSERT IGNORE INTO #__acymailing_listsub (subid, listid, status, subdate) SELECT subid,'.$acyList.',1,'.time().' FROM #__acymailing_subscriber WHERE zoholist = "'.strtolower($zohoList[0]).'"';
			$db->setQuery($query);
			$db->query();
			return $db->getAffectedRows();
		}

		function deleteAddress(&$allSubid, $userList) {
			$db = JFactory::getDBO();
			$subscriberClass= acymailing_get('class.subscriber');
			$IdArray = array();
			foreach($allSubid as $oneID)	$IdArray[] = $db->Quote($oneID);
			$query = 'SELECT subid FROM  #__acymailing_subscriber WHERE zoholist LIKE "'.$userList[0].'" AND zohoid IS NOT NULL AND subid NOT IN ('.implode(',',$IdArray).')';
			$db->setQuery($query);
			$subidToDelete = acymailing_loadResultArray($db);
			$subscriberClass->delete($subidToDelete);
		}

		function close() {
			fclose($this->conn);
		}

}
