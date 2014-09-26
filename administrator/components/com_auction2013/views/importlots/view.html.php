<?php

/**
 *
 * Category View
 *
 * @package	VirtueMart
 * @subpackage Category
 * @author RickG, jseros
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6475 2012-09-21 11:54:21Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
//
require_once JPATH_SITE.DS.'modules'.DS.'mod_vlotscats'.DS.'helper.php';
/**
 * HTML View class for maintaining the list of categories
 *
 * @package	VirtueMart
 * @subpackage Category
 * @author RickG, jseros
 */
class Auction2013ViewImportlots extends JView {
	
	public $categories_data;
	
	function display($tpl = null) {
        // $this->_layout == 'default' для импорта
        /**
            NULL в качестве параметра нужен для того, чтобы не добавлять к запросу
            фильтр даты публикации предмета
        */
		$this->categories_data=modVlotscatsHelper::getCategoriesData(NULL);
        //commonDebug(__FILE__,__LINE__,$this->categories_data);
        $this->addToolbar($this->_layout);
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar($layout=false)
	{	
		require_once JPATH_COMPONENT . '/helpers/auction2013.php';
		if($layout=='clear'){
			JToolBarHelper::title(JText::_('Очистка таблиц предметов'), 'trash.png');
			JToolBarHelper::custom('', 'trash', '', JText::_('Очистить!'), false);
		}else{
			JToolBarHelper::title(JText::_('Импорт данных предметов аукциона'), 'csv.png');
			JToolBarHelper::custom('', 'publish', '', JText::_('Импортировать!'), false);
		}
	}
}

// pure php no closing tag
