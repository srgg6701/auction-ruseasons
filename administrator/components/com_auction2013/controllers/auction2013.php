<?php
/**
 * @version     2.1.0
 * @package     com_collector1
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');
// подключить главный контроллер компонента:
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'controller.php';
/**
 * Customer_orders controller class.
 */
class Auction2013ControllerAuction2013 extends JControllerForm
{
	public $default_view='default';
    function __construct() {
        parent::__construct();
    }
    /**
     * Проверить номер аукциона
     */
    function check_auction_number(){
        echo $this->getModel()->checkAuctionNumber(JRequest::getVar('number'),JRequest::getVar('virtuemart_product_id'));
            /*'AUCTION NUMBER: ' . JRequest::getVar('number') .
            ', VIRTUEMART_RPODUCT_ID: ' . JRequest::getVar('virtuemart_product_id');*/
        exit;
    }

    public function display($view=false)
	{	if(!$view)
			$view=$this->prepareView($this->default_view);
		$view->display(); 
	}
/**
 * Подготовить данные представления
 * @package
 * @subpackage
 */
	public function prepareView($layout=false,$dview=false){
		if (!$dview) $dview=$this->default_view;
		$view=$this->getView($dview, 'html' ); 
		$model=$this->getModel('Item'); 
		$view->setModel($model,true);
		$view->setLayout($layout);
		return $view; 
	}
}