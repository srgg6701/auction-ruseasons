<?php
/**
 *
 * Handle the orders view
 *
 * @package	VirtueMart
 * @subpackage Orders
 * @author Oscar van Eijk
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 5432 2012-02-14 02:20:35Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if(!class_exists('VmView'))require(JPATH_VM_SITE.DS.'helpers'.DS.'vmview.php');

// Set to '0' to use tabs i.s.o. sliders
// Might be a config option later on, now just here for testing.
define ('__VM_ORDER_USE_SLIDERS', 0);

/**
 * Handle the orders view
 */
class VirtuemartViewInvoice extends VmView {

	var $format = 'html';
	var $doVendor = false;
	var $uselayout	= '';
	var $orderDetails = 0;
	var $invoiceNumber =0;

	public function display($tpl = null)
	{

		$document = JFactory::getDocument();

		if(empty($this->uselayout)){
			$layout = JRequest::getWord('layout','mail');
		} else {
			$layout = $this->uselayout;
		}
		if($layout == 'mail'){
			if (VmConfig::get('order_mail_html')) {
				$layout = 'mail_html';
			} else {
				$layout = 'mail_raw';
			}
		}
		$this->setLayout($layout);

		$tmpl = JRequest::getWord('tmpl');
		$print = false;
		if($tmpl){
			$print = true;
		}
		$this->assignRef('print', $print);

		$this->format = JRequest::getWord('format','html');
		if($layout == 'invoice'){
			$document->setTitle( JText::_('COM_VIRTUEMART_INVOICE') );
		}
		$order_print=false;

		if ($print and $this->format=='html') {
			$order_print=true;
		}


		$orderModel = VmModel::getModel('orders');

		$orderDetails = $this->orderDetails;

		if($orderDetails==0){

			// If the user is not logged in, we will check the order number and order pass
			if ($orderPass = JRequest::getString('order_pass',false) and $orderNumber = JRequest::getString('order_number',false)){
				$orderId = $orderModel->getOrderIdByOrderPass($orderNumber,$orderPass);
				if(empty($orderId)){
					echo 'Invalid order_number/password '.JText::_('COM_VIRTUEMART_RESTRICTED_ACCESS');
					return 0;
				}
				$orderDetails = $orderModel->getOrder($orderId);
			}

			if($orderDetails==0){

				$_currentUser = JFactory::getUser();
				$cuid = $_currentUser->get('id');

				// If the user is logged in, we will check if the order belongs to him
				$virtuemart_order_id = JRequest::getInt('virtuemart_order_id',0) ;
				if (!$virtuemart_order_id) {
					$virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber(JRequest::getString('order_number'));
				}
				$orderDetails = $orderModel->getOrder($virtuemart_order_id);

				if(!class_exists('Permissions')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'permissions.php');
				if(!Permissions::getInstance()->check("admin")) {
					if(!empty($orderDetails['details']['BT']->virtuemart_user_id)){
						if ($orderDetails['details']['BT']->virtuemart_user_id != $cuid) {
							echo 'view '.JText::_('COM_VIRTUEMART_RESTRICTED_ACCESS');
							return ;
						}
					}
				}
			}

		}

		if(empty($orderDetails['details'])){
			echo JText::_('COM_VIRTUEMART_ORDER_NOTFOUND');
			return 0;
		}
		$this->assignRef('orderDetails', $orderDetails);
        // if it is order print, invoice number should not be created, either it is there, either it has not been created
		if(empty($this->invoiceNumber) and !$order_print){
		    $invoiceNumberDate=array();
			if (  $orderModel->createInvoiceNumber($orderDetails['details']['BT'], $invoiceNumberDate)) {
                if (ShopFunctions::InvoiceNumberReserved( $invoiceNumberDate[0])) {
	                if  ($this->uselayout!='mail') {
		                $document->setTitle( JText::_('COM_VIRTUEMART_PAYMENT_INVOICE') );
                        return ;
	                }
                }
			    $this->invoiceNumber = $invoiceNumberDate[0];
			    $this->invoiceDate = $invoiceNumberDate[1];
			    if(!$this->invoiceNumber or empty($this->invoiceNumber)){
				    vmError('Cant create pdf, createInvoiceNumber failed');
				    if  ($this->uselayout!='mail') {
					    return ;
				    }
			    }
			} else {
				// Could OR should not create Invoice Number, createInvoiceNumber failed
				if  ($this->uselayout!='mail') {
					return ;
				}
			}
		}
		$company= empty($orderDetails['details']['BT']->company) ?"":$orderDetails['details']['BT']->company.", ";
		$shopperName =  $company. $orderDetails['details']['BT']->title.' '.$orderDetails['details']['BT']->first_name.' '.$orderDetails['details']['BT']->last_name;
		$this->assignRef('shopperName', $shopperName);

		//Todo multix
		$vendorId=1;
		$emailCurrencyId=0;
		$exchangeRate=FALSE;
		if(!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS.DS.'vmpsplugin.php');
		  JPluginHelper::importPlugin('vmpayment');
	    $dispatcher = JDispatcher::getInstance();
	    $dispatcher->trigger('plgVmgetEmailCurrency',array( $orderDetails['details']['BT']->virtuemart_paymentmethod_id, $orderDetails['details']['BT']->virtuemart_order_id, &$emailCurrencyId));
		if(!class_exists('CurrencyDisplay')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'currencydisplay.php');
		$currency = CurrencyDisplay::getInstance($emailCurrencyId,$vendorId);
			if ($emailCurrencyId) {
				$currency->exchangeRateShopper=$orderDetails['details']['BT']->user_currency_rate;
			}
		$this->assignRef('currency', $currency);

		//Create BT address fields
		$userFieldsModel = VmModel::getModel('userfields');
		$_userFields = $userFieldsModel->getUserFields(
				 'account'
				, array('captcha' => true, 'delimiters' => true) // Ignore these types
				, array('delimiter_userinfo','user_is_vendor' ,'username','password', 'password2', 'agreed', 'address_type') // Skips
		);

		$userfields = $userFieldsModel->getUserFieldsFilled( $_userFields ,$orderDetails['details']['BT']);
		$this->assignRef('userfields', $userfields);


		//Create ST address fields
		$orderst = (array_key_exists('ST', $orderDetails['details'])) ? $orderDetails['details']['ST'] : $orderDetails['details']['BT'];

		$shipmentFieldset = $userFieldsModel->getUserFields(
				 'shipment'
				, array() // Default switches
				, array('delimiter_userinfo', 'username', 'email', 'password', 'password2', 'agreed', 'address_type') // Skips
		);

		$shipmentfields = $userFieldsModel->getUserFieldsFilled( $shipmentFieldset ,$orderst );
		$this->assignRef('shipmentfields', $shipmentfields);


		// Create an array to allow orderlinestatuses to be translated
		// We'll probably want to put this somewhere in ShopFunctions..
		$orderStatusModel = VmModel::getModel('orderstatus');
		$_orderstatuses = $orderStatusModel->getOrderStatusList();
		$orderstatuses = array();
		foreach ($_orderstatuses as $_ordstat) {
			$orderstatuses[$_ordstat->order_status_code] = JText::_($_ordstat->order_status_name);
		}
		$this->assignRef('orderstatuslist', $orderstatuses);
		$this->assignRef('orderstatuses', $orderstatuses);

		$_itemStatusUpdateFields = array();
		$_itemAttributesUpdateFields = array();
		foreach($orderDetails['items'] as $_item) {
// 			$_itemStatusUpdateFields[$_item->virtuemart_order_item_id] = JHTML::_('select.genericlist', $orderstatuses, "item_id[".$_item->virtuemart_order_item_id."][order_status]", 'class="selectItemStatusCode"', 'order_status_code', 'order_status_name', $_item->order_status, 'order_item_status'.$_item->virtuemart_order_item_id,true);
			$_itemStatusUpdateFields[$_item->virtuemart_order_item_id] =  $_item->order_status;

		}

		if (empty($orderDetails['shipmentName']) ) {
		    if (!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
		    JPluginHelper::importPlugin('vmshipment');
		    $dispatcher = JDispatcher::getInstance();
		    $returnValues = $dispatcher->trigger('plgVmOnShowOrderFEShipment',array(  $orderDetails['details']['BT']->virtuemart_order_id, $orderDetails['details']['BT']->virtuemart_shipmentmethod_id, &$orderDetails['shipmentName']));
		}

		if (empty($orderDetails['paymentName']) ) {
		    if(!class_exists('vmPSPlugin')) require(JPATH_VM_PLUGINS.DS.'vmpsplugin.php');
		    JPluginHelper::importPlugin('vmpayment');
		    $dispatcher = JDispatcher::getInstance();
		    $returnValues = $dispatcher->trigger('plgVmOnShowOrderFEPayment',array( $orderDetails['details']['BT']->virtuemart_order_id, $orderDetails['details']['BT']->virtuemart_paymentmethod_id,  &$orderDetails['paymentName']));

		}

		$virtuemart_vendor_id=1;
		$vendorModel = VmModel::getModel('vendor');
		$vendor = $vendorModel->getVendor($virtuemart_vendor_id);
		$vendorModel->addImages($vendor);
		$vendor->vendorFields = $vendorModel->getVendorAddressFields();
		$this->assignRef('vendor', $vendor);

// 		vmdebug('vendor', $vendor);
		$task = JRequest::getWord('task',0);
		if($task == 'checkStoreInvoice'){
			$headFooter = false;
		} else {
			$headFooter = true;
		}
		if (strpos($layout,'mail') !== false) {
			$lineSeparator="<br />";
		} else {
			$lineSeparator="\n";
		}
		$this->assignRef('headFooter', $headFooter);

		//Attention, this function will be removed, it wont be deleted, but it is obsoloete in any view.html.php
		if(!class_exists('ShopFunctions')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'shopfunctions.php');
	    $vendorAddress= shopFunctions::renderVendorAddress($virtuemart_vendor_id, $lineSeparator);
		$this->assignRef('vendorAddress', $vendorAddress);

		$vendorEmail = $vendorModel->getVendorEmail($virtuemart_vendor_id);
		$vars['vendorEmail'] = $vendorEmail;

		// this is no setting in BE to change the layout !
		//shopFunctionsF::setVmTemplate($this,0,0,$layoutName);

		//vmdebug('renderMailLayout invoice '.date('H:i:s'),$this->order);
		if (strpos($layout,'mail') !== false) {
		    if ($this->doVendor) {
		    	 //Old text key COM_VIRTUEMART_MAIL_SUBJ_VENDOR_C
			    $this->subject = JText::sprintf('COM_VIRTUEMART_MAIL_SUBJ_VENDOR_'.$orderDetails['details']['BT']->order_status, $this->shopperName, strip_tags($currency->priceDisplay($orderDetails['details']['BT']->order_total)), $orderDetails['details']['BT']->order_number);
			    $recipient = 'vendor';
		    } else {
			    $this->subject = JText::sprintf('COM_VIRTUEMART_MAIL_SUBJ_SHOPPER_'.$orderDetails['details']['BT']->order_status, $vendor->vendor_store_name, strip_tags($currency->priceDisplay($orderDetails['details']['BT']->order_total)), $orderDetails['details']['BT']->order_number );
			    $recipient = 'shopper';
		    }
		    $this->assignRef('recipient', $recipient);
		}

		$tpl = null;

// 		vmdebug('my view data',$this->getLayout(),$layout);
// 		ob_start();
// 		echo '<pre>';
// 		echo debug_print_backtrace();
// 		echo '</pre>';
// 		$dumptrace = ob_get_contents();
// 		ob_end_clean();
// 		return false;


		parent::display($tpl);
	}

	// FE public function renderMailLayout($doVendor=false)
	function renderMailLayout ($doVendor, $recipient) {

		$this->doVendor=$doVendor;
		$this->frompdf=false;
		$this->uselayout = 'mail';
		$this->display();

	}


}
