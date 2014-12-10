<?php	
/**
 * @version     2.1.0
 * @package     com_auction2013
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

//require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS."tables".DS."table_name.php";

// require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS."helpers".DS."helper_name.php";
// No direct access
defined('_JEXEC') or die;
include_once JPATH_SITE.'/tests.php';
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
		//include_once JPATH_SITE.''
        $document	= JFactory::getDocument();
		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'auction2013');
		$vFormat = $document->getType(); 
		$lName	 = JRequest::getCmd('layout', 'default');
		if ($view = $this->getView($vName, $vFormat)) {
            /** 
            если юзер залогинен, не надо показывать форму регистрации - 
            перенаправить в личный кабинет! */
            if($lName=='register'&&!JFactory::getUser()->guest)
                $this->setRedirect(JRoute::_('index.php?option=com_users&view=login'));
            else{
                $model = $this->getModel('auction2013');
                $view->setModel($model, true);
                if($lName=='auctions'){
                    //die("<div>Показать предметы аукциона</div>");
                    $view->results=$model->getProductsForAuction(JRequest::getVar('auction'));
                }
                $view->setLayout($lName);
                $view->assignRef('document', $document);
                $view->display();
            }
		} 
	}
}
