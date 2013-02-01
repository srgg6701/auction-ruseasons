<?php
/**
 * CSV Improved Database class
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: csvidb.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die;

class CsviDb {

	private $_database = null;
	private $_error = null;

	public function __construct() {
		$this->_database = JFactory::getDbo();
	}

	public function setQuery($sql, $offset = 0, $limit = 0) {
		$this->_database->setQuery($sql, $offset, $limit);
		if (!$this->cur = $this->_database->query()) {
			$this->_error = $this->_database->getErrorMsg();
		}
	}

	public function getRow() {
		if (!is_object($this->cur)) $array = mysql_fetch_object($this->cur);
		else $array = $this->cur->fetch_object();
		if ($array) {
			return $array;
		}
		else {
			if (!is_object($this->cur)) mysql_free_result( $this->cur );
			else $this->cur->free_result();
			return false;
		}
	}

	public function getErrorMsg() {
		return $this->_error;
	}

	public function getNumRows() {
		return $this->_database->getNumRows($this->cur);
	}

	public function getQuery() {
		return $this->_database->getQuery();
	}
}