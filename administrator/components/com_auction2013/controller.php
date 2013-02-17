<?php	
/**
 * @version     2.1.0
 * @package     com_auction2013
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS."tables".DS."auction2013.php";

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS."helpers".DS."auction2013.php";

// No direct access
defined('_JEXEC') or die;

class Auction2013Controller extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{	
		if(!$tview=JRequest::getVar('view'))
			$tview='importlots';
		$view=$this->getView($tview, 'html'); 
		$model=$this->getModel('auction2013'); 
		$view->setModel($model,true);
		//$view->setLayout( $layout ); 
		// Use the View display method 
		// Load the submenu.
		// Auction2013Helper::addSubmenu();

		//JFactory::getDocument()->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js');
		$view->display(); 
	}
	/**
 * Описание
 * @package
 * @subpackage
 */
	public function edit(){
		$pk=JRequest::getVar('id');
		$model=$this->getModel('Item');
		$model->getItem($pk);
		$this->display();
	}
}
