<?php
// Секция Информация / Информация о товаре id: $товар_id
// TODO: Удалить закомментированный код из файлов шаблонов
/**
 *
 * Main product information
 *
 * @package	VirtueMart
 * @subpackage Product
 * @author RolandD
 * @todo Price update calculations
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_information.php 6547 2012-10-16 10:55:06Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'auction2013.php';
// список дочерних категорий для очных торгов:
$fulltime_cats_ids = Auction2013Helper::getChildCategoriesIds();
//commonDebug(__FILE__,__LINE__,$fulltime_cats_ids);
echo $this->langList;
$i=0;
// include_once JPATH_SITE.DS.'tests.php';
//commonDebug(__FILE__,__LINE__,$this->category_tree);
//die();?>
<fieldset>
	<legend>
	<?php echo JText::_('COM_VIRTUEMART_PRODUCT_INFORMATION'); echo ' id: '.$this->product->virtuemart_product_id ?></legend>
    <table width="100%">
	    <tr>
        <td width="50%">
			<table width="100%" class="adminform">
				<tr class="row<?php echo $i?>">
					<td><div style="text-align: right; font-weight: bold;">
					<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PUBLISH') ?></div>
					</td>
					<td>
						<?php echo  VmHTML::checkbox('published', $this->product->published); ?>
					</td>
                    <td><div style="text-align:right;font-weight:bold;">
						<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_SPECIAL') ?></div>
                    </td>
                    <td>
						<?php echo VmHTML::checkbox('product_special', $this->product->product_special); ?>
                    </td>
				</tr>

                <?php $i = 1 - $i; ?>
                <tr class="row<?php echo $i?>">
                    <td>
                        <div style="text-align:right;font-weight:bold;"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_AUCTION_NUMBER') ?></div>
                    </td>
                    <td  height="2" colspan="3" >
                        <input type="text" class="inputbox floatLeft" name="auction_number" id="auction_number" data-auction_number="<?php
                        echo $this->product->auction_number; ?>" value="<?php
                        echo $this->product->auction_number;
                        ?>" size="32" maxlength="64" onblur="checkFormData(this);" />
                    </td>
                </tr>

                <?php $i = 1 - $i; ?>
				<tr class="row<?php echo $i?>">
					<td>
						<div style="text-align:right;font-weight:bold;"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_SKU') ?></div>
					</td>
					<td  height="2" colspan="3" >
						<input type="text" class="inputbox" name="product_sku" id="product_sku" value="<?php echo $this->product->product_sku; ?>" size="32" maxlength="64" />
					</td>
				</tr>
				<?php $i = 1 - $i; ?>
				<tr class="row<?php echo $i?>">
					<td  height="18"><div style="text-align:right;font-weight:bold;">
						<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_NAME') ?></div>
					</td>
					<td height="18" colspan="3" >
						<input type="text" class="inputbox"  name="product_name" id="product_name" value="<?php echo htmlspecialchars($this->product->product_name); ?>" size="32" maxlength="255" />
					</td>
				</tr>
				<?php $i = 1 - $i; ?>
				<tr class="row<?php echo $i?>">
					<td height="18"><div style="text-align:right;font-weight:bold;">
						<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_ALIAS') ?></div>
					</td>
					<td  height="18" colspan="3" >
						<input type="text" class="inputbox"  name="slug" id="slug" value="<?php echo $this->product->slug; ?>" size="32" maxlength="255" />
					</td>
				</tr>
				<?php $i = 1 - $i;
                ?>
                <tr class="row<?php echo $i?>">
					<td  valign="top">
						<div style="text-align:right;font-weight:bold;">
						<?php echo JText::_('COM_VIRTUEMART_CATEGORY_S') ?></div>
					</td>
					<td colspan="3">
						<select class="inputbox" id="categories" name="categories[]" multiple="multiple" size="10">
							<option value=""><?php echo JText::_('COM_VIRTUEMART_UNCATEGORIZED')  ?></option>
							<?php echo $this->category_tree; ?>
						</select>
					</td>
					<?php
					// It is important to have all product information in the form, since we do not preload the parent
					// I place the ordering here, maybe we make it editable later.
						if(!isset($this->product->ordering)) $this->product->ordering = 0;
					?>
					<input type="hidden" value="<?php echo $this->product->ordering ?>" name="ordering">
				</tr>
                
            </table>
    </td>
	<td>
        <table width="100%" class="adminform">
<?php
	//$product = $this->product;

	if (empty($this->product->prices)) {
		$this->product->prices[] = array();
	}
	$this->i = 0;
	$rowColor = 0;
	if (!class_exists ('calculationHelper')) {
		require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'calculationh.php');
	}
	$calculator = calculationHelper::getInstance ();
	$currency_model = VmModel::getModel ('currency');
	$currencies = $currency_model->getCurrencies ();
	$nbPrice = count ($this->product->prices);
	$this->priceCounter = 0;
	$this->product->prices[$nbPrice] = $this->product_empty_price;



	if (!class_exists ('calculationHelper')) {
		require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'calculationh.php');
	}
	$calculator = calculationHelper::getInstance ();
	?>        	
            </table>
		</td>
        </tr>
	</table>
</fieldset>
		<td valign="top">
			<!-- Product pricing -->
<fieldset>
    <legend><?php echo JText::_ ('COM_VIRTUEMART_PRODUCT_MAIN_DATA'); ?></legend>	
    <table border="0" width="100%" cellpadding="2" cellspacing="3" id="mainPriceTable" class="adminform">
        
        <tbody id="productPriceBody">
		<?php
		//vmdebug('grummel ',$this->product->prices);
        //commonDebug(__FILE__,__LINE__,$this->product->prices, false);
        /* MODIFIED START
            Нужно для исключения повторной загрузки шаблона (секции) с ценой,
            если id цены не уникален. По неизвестной причине это происходит... */
        $prices_ids=array();
        /* MODIFIED END */
        // цикл всегда выполняется, как минимум, 1 раз
		foreach ($this->product->prices as $sPrices) {
            $prod_price_id=$sPrices['virtuemart_product_price_id'];
            /* MODIFIED START
                Если id цены не уникален, пропустить итерацию  */
            //echo "<div>virtuemart_product_price_id: ".$this->product->prices->virtuemart_product_price_id."</div>";
            // пропустить итерацию
            if($prod_price_id){ // цену получили
                // id цены не уникален
                if(in_array($prod_price_id, $prices_ids))
                   continue;
            }elseif(count($prices_ids)) // не получили цену, но цены уже были
                continue;

            //Добавить id цены в массив, чтобы пропустить итерацию в случае его повторения
            $prices_ids[]=$prod_price_id;
            //showTestMessage("price: " . $prod_price_id, __FILE__, __LINE__, false);
            //commonDebug(__FILE__,__LINE__,$prices_ids, false);
            /* MODIFIED END */
            if(count($sPrices) == 0) continue;
			if (empty($prod_price_id)) {
				$prod_price_id = '';
			}
			//vmdebug('my $sPrices ',$sPrices);
			$sPrices = (array)$sPrices;
			$this->tempProduct = (object)array_merge ((array)$this->product, $sPrices);
			$this->calculatedPrices = $calculator->getProductPrices ($this->tempProduct);

			if((string)$sPrices['product_price']==='0' or (string)$sPrices['product_price']===''){
				$this->calculatedPrices['costPrice'] = '';
			}

			$currency_model = VmModel::getModel ('currency');
			$this->lists['currencies'] = JHTML::_ ('select.genericlist', $currencies, 'mprices[product_currency][' . $this->priceCounter . ']', '', 'virtuemart_currency_id', 'currency_name', $this->tempProduct->product_currency);

			$DBTax = ''; //JText::_('COM_VIRTUEMART_RULES_EFFECTING') ;
			foreach ($calculator->rules['DBTax'] as $rule) {
				$DBTax .= $rule['calc_name'] . '<br />';
			}
			$this->DBTaxRules = $DBTax;

			$tax = ''; //JText::_('COM_VIRTUEMART_TAX_EFFECTING').'<br />';
			foreach ($calculator->rules['Tax'] as $rule) {
				$tax .= $rule['calc_name'] . '<br />';
			}
			foreach ($calculator->rules['VatTax'] as $rule) {
				$tax .= $rule['calc_name'] . '<br />';
			}
			$this->taxRules = $tax;

			$DATax = ''; //JText::_('COM_VIRTUEMART_RULES_EFFECTING');
			foreach ($calculator->rules['DATax'] as $rule) {
				$DATax .= $rule['calc_name'] . '<br />';
			}
			$this->DATaxRules = $DATax;

			if (!isset($this->tempProduct->product_tax_id)) {
				$this->tempProduct->product_tax_id = 0;
			}
			$this->lists['taxrates'] = ShopFunctions::renderTaxList ($this->tempProduct->product_tax_id, 'mprices[product_tax_id][' . $this->priceCounter . ']');
			if (!isset($this->tempProduct->product_discount_id)) {
				$this->tempProduct->product_discount_id = 0;
			}
			$this->lists['discounts'] = $this->renderDiscountList ($this->tempProduct->product_discount_id, 'mprices[product_discount_id][' . $this->priceCounter . ']');

			$this->lists['shoppergroups'] = ShopFunctions::renderShopperGroupList ($this->tempProduct->virtuemart_shoppergroup_id, false, 'mprices[virtuemart_shoppergroup_id][' . $this->priceCounter . ']');

			if ($this->priceCounter == $nbPrice) {
				$tmpl = "productPriceRowTmpl";
			} else {
				$tmpl = "productPriceRowTmpl_" . $this->priceCounter;
			}
			?>
        <tr id="<?php echo $tmpl ?>" class="removable row<?php echo $rowColor?>">
            <td width="100%">
                <span class="vmicon vmicon-16-move price_ordering"></span>
                <span class="vmicon vmicon-16-new price-clone" ></span>
                <span class="vmicon vmicon-16-remove price-remove"></span>
				<?php //echo JText::_ ('COM_VIRTUEMART_PRODUCT_PRICE_ORDER'); ?>
				<?php echo $this->loadTemplate ('price'); ?>
            </td>
        </tr>
			<?php
			$this->priceCounter++;
		}
        ?>
        </tbody>
    </table>
    <div class="button2-left">
        <div class="blank">
            <a href="#" id="add_new_price" ><?php echo JText::_ ('COM_VIRTUEMART_PRODUCT_ADD_PRICE') ?> </a>
        </div>
    </div>

</fieldset>
</tr>
		</td>
	</tr>
	<tr>
		<td
			width="100%"
			valign="top"
			colspan="2">
			<fieldset>
				<legend>
				<?php echo JText::_('COM_VIRTUEMART_PRODUCT_PRINT_INTNOTES'); ?></legend>
				<textarea style="width: 100%;" class="inputbox" name="intnotes" id="intnotes" cols="35" rows="6">
					<?php echo $this->product->intnotes; ?></textarea>
			</fieldset>
		</td>
	</tr>
</table>
<script type="text/javascript">
    jQuery(document).ready(function () {
        /**
         * переопределить метод, вызываемый кликом по кнопке - checkFormData().
        Нужно для дополнительных проверок полей */
        var bnt,action,actions = ['apply', 'save'];
        for(var i= 0, j=actions.length; i<j; i++){
            action = actions[i];
            btn=document.getElementById('toolbar-'+action).getElementsByTagName('a')[0];
            console.dir(btn);
            btn.setAttribute('onclick','return checkOnClick("'+action+'")');
        }
        jQuery("#mainPriceTable").dynoTable({
            removeClass:'.price-remove', //remove class name in  table
            cloneClass:'.price-clone', //Custom cloner class name in  table
            addRowTemplateId:'#productPriceRowTmpl', //Custom id for  row template
            addRowButtonId:'#add_new_price', //Click this to add a price
            lastRowRemovable:true, //Don't let the table be empty.
            orderable:true, //prices can be rearranged
            dragHandleClass:".price_ordering", //class for the click and draggable drag handle
            onRowRemove:function () {
            },
            onRowClone:function () {
            },
            onRowAdd:function () {
            },
            onTableEmpty:function () {
            },
            onRowReorder:function () {
            }
        });
    });
</script>
<script>
function getFullTimeIds(){
    var fulltimeIds = [<?php
    foreach ($fulltime_cats_ids as $i=>$cat_id) {
    if($i) echo ",";
    echo $cat_id;
    }?>];
    return fulltimeIds;
}
function getUrlToGo(auction_number){
    return '<?php echo JUri::base();
    ?>?option=com_auction2013&task=auction2013.check_auction_number&number=' +
    auction_number + '&virtuemart_product_id=<?php echo $this->product->virtuemart_product_id?>';
}
</script>
<script src="<?php echo JUri::base();
?>components/com_auction2013/js/check_saving.js"></script>
<script type="text/javascript">
var tax_rates = new Array();
<?php
if( property_exists($this, 'taxrates') && is_array( $this->taxrates )) {
	foreach( $this->taxrates as $key => $tax_rate ) {
		echo 'tax_rates["'.$tax_rate->tax_rate_id.'"] = '.$tax_rate->tax_rate."\n";
	}
}
?>
</script>


