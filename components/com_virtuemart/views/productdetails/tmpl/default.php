<?php	
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz
 * @author RolandD,
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6530 2012-10-12 09:40:36Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
//var_dump(JRequest::get('get'));
/*

  'Itemid' => string '115' (length=3)
  'option' => string 'com_virtuemart' (length=14)
  'limitstart' => int 0
  'limit' => string 'int' (length=3)
  'view' => string 'productdetails' (length=14)
  'virtuemart_product_id' => string '551' (length=3)
  'virtuemart_category_id' => string '6' (length=1)
*/

require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
$native=false;
if(!$native){		
	$virtuemart_category_id=$this->product->virtuemart_category_id;
	$virtuemart_product_id=(int)$this->product->virtuemart_product_id;
	if($router = JFactory::getApplication()->getRouter()){
		$session=&JFactory::getSession();
		$links=$session->get('section_links');
	}
	HTML::setCommonInnerMenu(array('take_lot','ask_about_lot','user'),array('ask_about_lot'=>$this->product->virtuemart_product_id));?>
<div class="lots_listing">
  <div class="width70 inBlock" style="margin-left:-8px;">    
    <ul class="table inline weak">
<?	if($SefMode=$router->getMode())
		$category_link=AuctionStuff::extractCategoryLinkFromSession($virtuemart_category_id);
	
	// получить предыдущий-следующий предметы в категории:	
	$trinityIds=AuctionStuff::getProductNeighborhood($virtuemart_product_id,$virtuemart_category_id);
	$hide=' style="visibility:hidden"';

	
	if((int)$trinityIds[0]<$virtuemart_product_id):	
		$prev_prod_link=AuctionStuff::buildProdNeighborLink($trinityIds[0],$category_link,$SefMode);
	else: $prev_prod_link=false;
	endif;?>    
        <li><a href="<?=$prev_prod_link?>"<?
    
	if(!$prev_prod_link) echo $hide;
	
	?>>&lt; &lt; Предыдущий <!--(<?=$trinityIds[0]?>)-->></a></li>
<?	
	if(!$category_link): // if no SEF only:
		$category_link=JRoute::_('index.php?option=com_virtuemart&view=category&Itemid='.JRequest::getVar('Itemid'),false);
	endif;?>	
        <li><a href="<?=$category_link?>">Вернуться к списку лотов</a></li>
<?	if($trinityIds[2]) 
		$next_prod_id=$trinityIds[2];
	elseif((int)$trinityIds[1]>$virtuemart_product_id) 
		$next_prod_id=$trinityIds[1];
	if($next_prod_id):	
		$next_prod_link=AuctionStuff::buildProdNeighborLink($next_prod_id,$category_link,$SefMode);
	endif;?>
        <li><a href="<?=$next_prod_link?>"<? 
		
		if(!$next_prod_id) echo $hide;
        
		?>>Следующий <!--(<?=$next_prod_id?>)-->&gt; &gt;</a></li>
    </ul>
  </div>
  <div align="center" class="width30 inBlock" style="vertical-align:top; font-weight:bold;">
  	<a href="#">Добавить в избранное</a>
  </div>
</div>

<div>

	<div class="gallery_lot">
		
        <div id="galleryContainer">
			
            <div class="main_im_lot">
				
                <div id="galleryBigImage">
					
                    <div id="bigLeadImage">
						
						<script type="text/javascript">
							//<![CDATA[
							images[0] = new galleryAddImage( '6261', '<?	//http://auction-ruseasons.ru/items_images/1_1.jpg
							
							?>', '<? //http://auction-ruseasons.ru/items_images/preview_1_1.jpg
					
			?>', 334, 334, '', 0);
							//]]>
						</script>
                        
                        <a href="<? //http://auction-ruseasons.ru/items_images/1_1.jpg
			echo $this->product->images[0]->file_url;?>" class="MagicZoomPlus" id="Zoomer" rel="zoom-width:450px;zoom-border:2px;zoom-height:293px;" style="position: relative; display: inline-block; text-decoration: none; outline: 0px; margin: auto; width: 334px; " title="">
                        
                            <img src="<?
//http://auction-ruseasons.ru/items_images/preview_1_1.jpg
			echo $this->product->images[0]->file_url;
	
	?>" width="334" height="334" alt="" style="opacity: 1; ">                        
                            
                            <div class="MagicZoomBigImageCont" style="overflow: hidden; z-index: 100; top: -10000px; position: absolute; width: 450px; height: 293px; opacity: 1; left: 349px; ">
                                <div class="MagicZoomHeader" style="position: relative; z-index: 10; left: 0px; top: 0px; padding: 3px; display: none; visibility: hidden; ">
                                </div>
                                                    
                                <div style="overflow: hidden; ">
                            
                                	<img src="<?
                                    //http://auction-ruseasons.ru/items_images/1_1.jpg
		echo $this->product->images[0]->file_url;	
									
									?>" style="padding: 0px; margin: 0px; border: 0px; position: relative; left: -750px; top: 0px; ">
                            
                                </div>
                            
                            </div>
                            
                            <div class="MagicZoomPup" style="z-index: 10; position: absolute; overflow: hidden; display: none; visibility: hidden; width: 123px; height: 80px; opacity: 0.5; left: 209px; top: 0px; ">
                            </div>
                        
                        </a>
                        
					</div>
				
                </div>		
			
            </div>
				
			<div class="clr"></div>
	
    	</div>

	</div>
		
	<div class="box_desc">
		
        <div class="bord_bottom">

         	<b>Лот <?=$this->product->lot_number?>. <?=$this->product->product_name?>
            <!--Лот 1. Приписывается Francesco Guardi (1712-1793) «Вид Венеции»--></b>

		</div>
        
        <div class="o_o">

             <span style="color:#000">
             	<?=$this->product->product_s_desc?>
                <!--Холст, масло, XVIII в., 30х40 см-->
             </span>

        </div>
        
        <div class="o_o">
                Номер аукциона: 
            <span class="span_o_o">
                ..............
            </span>

            <a href="#">
                <span class="bold span_o_o">
            		<?=$this->product->auction_number?>
                    <!--№1 -->
                </span> 
            </a>

        </div>				
                    
        <div class="o_o">

            <a href="<?=JRoute::_("index.php?option=com_content&view=article&id=23", false)?>">
                Поставить заочный бид
            </a>

        </div>
        
        <div class="o_o">
            Начало торгов:................. 

            <span class="span_o_o">
                <b>
             		<?=$this->product->auction_date_start?>
                    <!--14.11.2010 12:00-->
                </b>
            </span>

        </div>		   
        
        <div class="o_o">
            Конец торгов:................... 

            <span class="span_o_o">
                <b>
             		<?=$this->product->auction_date_finish?>
                    <!--14.11.2010 17:00-->
                </b>
            </span>

        </div>				  
        
        <div class="o_o">
            Предварительная оценка: 

            <span class="span_o_o">
                <b>
             		<?
	echo substr($this->product->product_price,0,strpos($this->product->product_price,'.'));?>
                    <!--120000-->
                </b>  
                <b>
                	- ? ? ? ?
                   <!-- - 150000-->
                </b>   
                рублей
            </span>

        </div>
    
    </div>

</div>
<?	

// product:

/*virtuemart_product_id 
virtuemart_vendor_id 
product_parent_id
product_sku
product_name 
slug 
product_s_desc 
product_desc 
product_weight 
product_weight_uom 
product_length 
product_width 
product_height 
product_lwh_uom 
product_url
product_in_stock 
product_ordered
low_stock_notification
product_available_date 
product_availability
product_special
auction_number 
contract_number 
lot_number
product_available_date_closed 
auction_date_start 
auction_date_finish 
product_sales
product_unit 
product_packaging 
product_params 
intnotes
customtitle
metadesc
metakey
metarobot
metaauthor
layout
published

// product_prices

product_price

// categorits

virtuemart_category_id
category_name
canonical
link
images
	array 0 => 
        object(VmImage)[278]
          public 'media_attributes' => int 0
          public 'setRole' => boolean false
          public 'file_name' => string '02_215_03_1' (length=11)
          public 'file_extension' => string 'jpg' (length=3)
          public 'virtuemart_media_id' => string '1995' (length=4)

и т.д., см. var_dump($this->product);

*/



/*echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";
echo "<div class=''>".$this->product->."</div>";*/
	

}else{
// addon for joomla modal Box
JHTML::_('behavior.modal');
// JHTML::_('behavior.tooltip');
$document = JFactory::getDocument();
$document->addScriptDeclaration("
//<![CDATA[
	jQuery(document).ready(function($) {
		$('a.ask-a-question').click( function(){
			$.facebox({
				iframe: '" . $this->askquestion_url . "',
				rev: 'iframe|550|550'
			});
			return false ;
		});
	/*	$('.additional-images a').mouseover(function() {
			var himg = this.href ;
			var extension=himg.substring(himg.lastIndexOf('.')+1);
			if (extension =='png' || extension =='jpg' || extension =='gif') {
				$('.main-image img').attr('src',himg );
			}
			console.log(extension)
		});*/
	});
//]]>
");
/* Let's see if we found the product */
if (empty($this->product)) {
    echo JText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
    echo '<br /><br />  ' . $this->continue_link_html;
    return;
}
?>

<div class="productdetails-view productdetails">

    <?php
    // Product Navigation
    if (VmConfig::get('product_navigation', 1)) {
	?>
        <div class="product-neighbours">
	    <?php
	    if (!empty($this->product->neighbours ['previous'][0])) {
		$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id);
		echo JHTML::_('link', $prev_link, $this->product->neighbours ['previous'][0]
			['product_name'], array('class' => 'previous-page'));
	    }
	    if (!empty($this->product->neighbours ['next'][0])) {
		$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id);
		echo JHTML::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'], array('class' => 'next-page'));
	    }
	    ?>
    	<div class="clear"></div>
        </div>
    <?php } // Product Navigation END
    ?>
	<?php // Back To Category Button
	if ($this->product->virtuemart_category_id) {
		$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id);
		$categoryName = $this->product->category_name ;
	} else {
		$catURL =  JRoute::_('index.php?option=com_virtuemart');
		$categoryName = jText::_('COM_VIRTUEMART_SHOP_HOME') ;
	}
		/*?>
	<div class="back-to-category">
    	<a href="<?php echo $catURL ?>" class="product-details" title="<?php echo $categoryName ?>"><?php echo JText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO',$categoryName) ?></a>
	</div>
<?	*/?>
    <?php // Product Title   ?>
    <h1><?php echo $this->product->product_name ?></h1>
    <?php // Product Title END   ?>

    <?php // afterDisplayTitle Event
    echo $this->product->event->afterDisplayTitle ?>

    <?php
    // Product Edit Link
    echo $this->edit_link;
    // Product Edit Link END
    ?>

    <?php
    // PDF - Print - Email Icon
    if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_button_enable')) {
	?>
        <div class="icons">
	    <?php
	    //$link = (JVM_VERSION===1) ? 'index2.php' : 'index.php';
	    $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;
	    $MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';

	    if (VmConfig::get('pdf_icon', 1) == '1') {
		echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_button_enable', false);
	    }
	    echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
	    echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend');
	    ?>
    	<div class="clear"></div>
        </div>
    <?php } // PDF - Print - Email Icon END	  ?>

    <?php
    // Product Short Description
    if (!empty($this->product->product_s_desc)) {
	?>
        <div class="product-short-description">
	    <?php
	    /** @todo Test if content plugins modify the product description */
	    echo nl2br($this->product->product_s_desc);
	    ?>
        </div>
	<?php
    } // Product Short Description END


    if (!empty($this->product->customfieldsSorted['ontop'])) {
	$this->position = 'ontop';
	echo $this->loadTemplate('customfields');
    } // Product Custom ontop end
    ?>

    <div id="prod_content">
    <?	$show=false;
		if($show):?>
    	IMAGE COMES HERE!
    <?	endif;?>
    <div>
		<div id="bigLeadImage" class="width60 floatleft">
<?php	
		echo $this->loadTemplate('images');
?>
		</div>
    <?	if($show):?>
    	IMAGE ENDS HERE!
    <?	endif;?>
		<!-- -->
		<div class="width40 floatright">
	    	<div class="spacer-buy-area">

		<?php
		// TODO in Multi-Vendor not needed at the moment and just would lead to confusion
		/* $link = JRoute::_('index2.php?option=com_virtuemart&view=virtuemart&task=vendorinfo&virtuemart_vendor_id='.$this->product->virtuemart_vendor_id);
		  $text = JText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL');
		  echo '<span class="bold">'. JText::_('COM_VIRTUEMART_PRODUCT_DETAILS_VENDOR_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a><br />
		 */
		?>

		<?php
		if ($this->showRating) {
		    $maxrating = VmConfig::get('vm_maximum_rating_scale', 5);

		    if (empty($this->rating)) {
			?>
			<span class="vote"><?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . JText::_('COM_VIRTUEMART_UNRATED') ?></span>
			    <?php
			} else {
			    $ratingwidth = $this->rating->rating * 24; //I don't use round as percetntage with works perfect, as for me
			    ?>
			<span class="vote">
	<?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . round($this->rating->rating) . '/' . $maxrating; ?><br/>
			    <span title=" <?php echo (JText::_("COM_VIRTUEMART_RATING_TITLE") . round($this->rating->rating) . '/' . $maxrating) ?>" class="ratingbox" style="display:inline-block;">
				<span class="stars-orange" style="width:<?php echo $ratingwidth.'px'; ?>">
				</span>
			    </span>
			</span>
			<?php
		    }
		}
		if (is_array($this->productDisplayShipments)) {
		    foreach ($this->productDisplayShipments as $productDisplayShipment) {
			echo $productDisplayShipment . '<br />';
		    }
		}
		if (is_array($this->productDisplayPayments)) {
		    foreach ($this->productDisplayPayments as $productDisplayPayment) {
			echo $productDisplayPayment . '<br />';
		    }
		}
		// Product Price
		    // the test is done in show_prices
		//if ($this->show_prices and (empty($this->product->images[0]) or $this->product->images[0]->file_is_downloadable == 0)) {
		    echo $this->loadTemplate('showprices');
		//}
		?>

		<?php
		// Add To Cart Button
// 			if (!empty($this->product->prices) and !empty($this->product->images[0]) and $this->product->images[0]->file_is_downloadable==0 ) {
//		if (!VmConfig::get('use_as_catalog', 0) and !empty($this->product->prices['salesPrice'])) {
		    echo $this->loadTemplate('addtocart');
//		}  // Add To Cart Button END
		?>

		<?php
		// Availability Image
		$stockhandle = VmConfig::get('stockhandle', 'none');
		if (($this->product->product_in_stock - $this->product->product_ordered) < 1) {
			if ($stockhandle == 'risetime' and VmConfig::get('rised_availability') and empty($this->product->product_availability)) {
			?>	<div class="availability">
			    <?php echo (file_exists(JPATH_BASE . DS . VmConfig::get('assets_general_path') . 'images/availability/' . VmConfig::get('rised_availability'))) ? JHTML::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . VmConfig::get('rised_availability', '7d.gif'), VmConfig::get('rised_availability', '7d.gif'), array('class' => 'availability')) : VmConfig::get('rised_availability'); ?>
			</div>
		    <?php
			} else if (!empty($this->product->product_availability)) {
			?>
			<div class="availability">
			<?php echo (file_exists(JPATH_BASE . DS . VmConfig::get('assets_general_path') . 'images/availability/' . $this->product->product_availability)) ? JHTML::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . $this->product->product_availability, $this->product->product_availability, array('class' => 'availability')) : $this->product->product_availability; ?>
			</div>
			<?php
			}
		}
		?>

<?php
// Ask a question about this product
if (VmConfig::get('ask_question', 1) == 1) {
    ?>
    		<div class="ask-a-question">
    		    <a class="ask-a-question" href="<?php echo $this->askquestion_url ?>" ><?php echo JText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>
    		    <!--<a class="ask-a-question modal" rel="{handler: 'iframe', size: {x: 700, y: 550}}" href="<?php echo $this->askquestion_url ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>-->
    		</div>
		<?php }
		?>

		<?php
		// Manufacturer of the Product
		if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
		    echo $this->loadTemplate('manufacturer');
		}
		?>

	    </div>
	</div>
	<div class="clear"></div>
    </div>

	<?php // event onContentBeforeDisplay
	echo $this->product->event->beforeDisplayContent; ?>

	<?php
	// Product Description
	if (!empty($this->product->product_desc)) {
	    ?>
        <div class="product-description">
	<?php /** @todo Test if content plugins modify the product description */ ?>
    	<span class="title"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></span>
	<?php echo $this->product->product_desc; ?>
        </div>
	<?php
    } // Product Description END

    if (!empty($this->product->customfieldsSorted['normal'])) {
	$this->position = 'normal';
	echo $this->loadTemplate('customfields');
    } // Product custom_fields END
    // Product Packaging
    $product_packaging = '';
    if ($this->product->product_box) {
	?>
        <div class="product-box">
	    <?php
	        echo JText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
	    ?>
        </div>
    <?php } // Product Packaging END
    ?>

    <?php
    // Product Files
    // foreach ($this->product->images as $fkey => $file) {
    // Todo add downloadable files again
    // if( $file->filesize > 0.5) $filesize_display = ' ('. number_format($file->filesize, 2,',','.')." MB)";
    // else $filesize_display = ' ('. number_format($file->filesize*1024, 2,',','.')." KB)";

    /* Show pdf in a new Window, other file types will be offered as download */
    // $target = stristr($file->file_mimetype, "pdf") ? "_blank" : "_self";
    // $link = JRoute::_('index.php?view=productdetails&task=getfile&virtuemart_media_id='.$file->virtuemart_media_id.'&virtuemart_product_id='.$this->product->virtuemart_product_id);
    // echo JHTMl::_('link', $link, $file->file_title.$filesize_display, array('target' => $target));
    // }
    if (!empty($this->product->customfieldsRelatedProducts)) {
	echo $this->loadTemplate('relatedproducts');
    } // Product customfieldsRelatedProducts END

    if (!empty($this->product->customfieldsRelatedCategories)) {
	echo $this->loadTemplate('relatedcategories');
    } // Product customfieldsRelatedCategories END
    // Show child categories
    if (VmConfig::get('showCategory', 1)) {
	echo $this->loadTemplate('showcategory');
    }
    if (!empty($this->product->customfieldsSorted['onbot'])) {
    	$this->position='onbot';
    	echo $this->loadTemplate('customfields');
    } // Product Custom ontop end
    ?>

<?php // onContentAfterDisplay event
echo $this->product->event->afterDisplayContent; ?>

<?php
echo $this->loadTemplate('reviews');
?>
</div>
<?
}?>