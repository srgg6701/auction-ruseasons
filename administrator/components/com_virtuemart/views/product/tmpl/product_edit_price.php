<?php
// Секция Информация / Стоимость и сроки
/**
 *
 * Main product information
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author Max Milbers
 * @todo Price update calculations
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2012 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_price.php 6669 2012-11-14 12:16:55Z alatak $
 * http://www.seomoves.org/blog/web-design-development/dynotable-a-jquery-plugin-by-bob-tantlinger-2683/
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access'); ?>
<style>
#productPriceBody .vmicon,
#mainPriceTable +div div.blank #add_new_price{
	display:none;
}
input[name="mprices[salesPrice][]"]{
	width:60px;
}
</style>
<?php   $rowColor = 0;  ?>
<table class="adminform productPriceTable">
    <tr class="row<?php echo $rowColor?>">
        <td>
            <div style="text-align: right; font-weight: bold;">
                <span class="hasTip" title="<?php echo JText::_ ('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST_TIP'); ?>">
                    <?php echo JText::_ ('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST') ?>
                </span>
            </div>
        </td>
        <td><input type="text" class="inputbox" name="mprices[product_price][]" size="12" style="text-align:right;" value="<?php echo $this->calculatedPrices['costPrice']; ?>"/>
            <input type="hidden" name="mprices[virtuemart_product_price_id][]" value="<?php echo  $this->tempProduct->virtuemart_product_price_id; ?>"/>
        </td>
        <td colspan="3">
			<?php echo $this->lists['currencies']; ?>
        </td>
        <?	/*?>
        <td colspan="2">

			<?php echo $this->lists['shoppergroups'];  ?>
        </td>
        <?	*/?>
    </tr>
<?php	
		$rowColor = 1 - $rowColor; 
	?>
    <tr class="row<?php echo $rowColor?>">
        <td>
            <div style="text-align: right; font-weight: bold;">
				<span
                        class="hasTip"
                        title="<?php echo JText::_ ('COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL_TIP'); ?>">
					<?php 
		// TODO: разобраться, можно ли изменить TOP slug с кириллицы на латиницу (торги-в-помещении, магазин)
		if($this->product->top_category_slug==='onlajn-torgi'){
			$text="COM_VIRTUEMART_PRODUCT_FORM_PRICE_MINIMAL";
			$mprice=$this->product->minimal_price;
		}else{			
			$text="COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE";
			$mprice=$this->calculatedPrices['basePrice'];
		}
		echo JText::_($text) ?>
				</span>
            </div>
            <input type="hidden" name="top_category_slug" value="<?=$this->product->top_category_slug?>"/>
        </td>
        <td nowrap><input
                type="text"
                name="mprices[salesPrice][]"
                size="12"
                style="text-align:right;"
                value="<?php echo $mprice; ?>"/>
			<?php echo $this->vendor_currency; ?>
        </td>
        <td style="text-align:right;">
        	<strong><?=JText::_ ('COM_VIRTUEMART_PRODUCT_FORM_PUBLISH_PERIOD')?></strong>
			<?php //echo $this->lists['discounts']; <br/>?> 
        </td>
		<td  nowrap>
			<?php echo  vmJsApi::jDate ($this->tempProduct->product_price_publish_up, 'mprices[product_price_publish_up][]'); ?>
            <input type="time" name="publish_time_from" />
        </td>
        <td  nowrap>
			<?php echo  vmJsApi::jDate ($this->tempProduct->product_price_publish_down, 'mprices[product_price_publish_down][]'); ?>
            <input type="time" name="publish_time_to" />
            <input  type="hidden" name="product_in_stock" value="1" size="10" />
        </td>
    </tr>
<?	if($this->product->top_category_slug==='onlajn-torgi'):
?>
	<tr class="row0">
    	<td></td>
        <td>        
        </td>
    	<td>
			<div style="text-align:right;font-weight:bold;">
				<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_AUCTION_DATE_PERIOD') ?>
			</div>
		</td>
		<td>
			<?php
			echo vmJsApi::jDate($this->product->product_available_date, 'product_available_date'); ?>
        	<input type="time" name="auction_time_from" />
		</td>
        <td nowrap><?
        echo vmJsApi::jDate($this->product->auction_date_finish, 'auction_date_finish');	?>
        	<input type="time" name="auction_time_to" />
        </td>
	</tr>
<?	endif;
?>    
</table>



