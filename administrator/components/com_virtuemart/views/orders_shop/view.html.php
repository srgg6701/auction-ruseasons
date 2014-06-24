<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
//\auction-ruseasons\components\com_auction2013\helpers\stuff.php
//\auction-ruseasons/administrator\components\com_auction2013\helpers\stuff.php
require_once JPATH_SITE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
// Load the view framework
if(!class_exists('VmView'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmview.php');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'auction2013.php';

class VirtuemartViewOrders_shop extends VmView {

	function display($tpl = null) {
        include_once JPATH_SITE.DS.'tests.php';
        //
		//Load helpers
        $this->purchases = new vmAuctionHTML();
        //commonDebug(__FILE__,__LINE__,$purchases, true);
		//$this->purchases->applications=$purchases->makePurchasesTable();
        //$this->purchases->paid=$purchases->makePurchasesTable(true);
        $this->loadHelper('currencydisplay');

		$this->loadHelper('html');


		//$orderStatusModel=VmModel::getModel('orderstatus');
		//$orderStates = $orderStatusModel->getOrderStatusList();

		$this->SetViewTitle( 'ORDER');

		//$orderModel = VmModel::getModel();

		$curTask = JRequest::getWord('task');
        $this->setLayout('default');

        $model = VmModel::getModel();

        //$this->addStandardDefaultViewLists($model,'created_on');
        //$this->lists['state_list'] = $this->renderOrderstatesList();

        //$orderslist = $model->getOrdersList();

        //$this->assignRef('orderstatuses', $orderStates);

        if(!class_exists('CurrencyDisplay'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'currencydisplay.php');

        /* Apply currency This must be done per order since it's vendor specific */
        /*$_currencies = array(); // Save the currency data during this loop for performance reasons

        if ($orderslist) {
            foreach ($orderslist as $virtuemart_order_id => $order) {

                //This is really interesting for multi-X, but I avoid to support it now already, lets stay it in the code
                if (!array_key_exists('v'.$order->virtuemart_vendor_id, $_currencies)) {
                    $_currencies['v'.$order->virtuemart_vendor_id] = CurrencyDisplay::getInstance('',$order->virtuemart_vendor_id);
                }
                $order->order_total = $_currencies['v'.$order->virtuemart_vendor_id]->priceDisplay($order->order_total);
                $order->invoiceNumber = $model->getInvoiceNumber($order->virtuemart_order_id);

            }
        }*/

        //include_once JPATH_SITE.DS.'tests.php';
        //commonDebug(__FILE__,__LINE__,$orderslist, true);
        /*
         * UpdateStatus removed from the toolbar; don't understand how this was intented to work but
         * the order ID's aren't properly passed. Might be readded later; the controller needs to handle
         * the arguments.
         */

        /* Toolbar */
        JToolBarHelper::save('updatestatus', JText::_('COM_VIRTUEMART_UPDATE_STATUS'));

        JToolBarHelper::deleteListX();

        /* Assign the data */
        //$this->assignRef('orderslist', $orderslist);

        $pagination = $model->getPagination();
        $this->assignRef('pagination', $pagination);

        parent::display($tpl);
	}
}

