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

$test=JRequest::getVar('test');
$show=JRequest::getVar('show');			
			if(JRequest::getVar('source')):
				require_once 'source/default.php';
			
			else:


$base_path=JUri::root();
$templateUrl=JUri::root().'templates/auction/';?>
<link href="<?=$templateUrl?>magic_zoom/magiczoomplus.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?=$templateUrl?>magic_zoom/magiczoomplus.js" type="text/javascript"></script>
<?
require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';	
// var_dump($this->product); die();
/*	object(TableProducts)[292]
	  public 'virtuemart_product_id' => string '1717' (length=4)
	  public 'virtuemart_vendor_id' => string '1' (length=1)
	  public 'product_parent_id' => string '0' (length=1)
	  public 'product_sku' => string '' (length=0)
	  public 'product_name' => string 'Лот 266. Кинжал в ножнах (Кард?) 1-я половина XIX в.' (length=82)
	  public 'slug' => string 'лот-266-кинжал-в-ножнах-кард-1-я-половина-xix-в' (length=77)
	  public 'product_s_desc' => string '' (length=0)
	  public 'product_desc' => string 'Сталь, дерево, кость. Ковка, золочение. Длина общая - 40,5 см, длина клинка - 23,5 см, ширина устья - 2,8 см. Представленный кинжал, предположительно кард - персидский однолезвийный нож с золотой насечкой. Является исторически-культурной ценностью.' (length=436)
	  public 'product_weight' => string '0.0000' (length=6)
	  public 'product_weight_uom' => string 'KG' (length=2)
	  public 'product_length' => string '0.0000' (length=6)
	  public 'product_width' => string '0.0000' (length=6)
	  public 'product_height' => string '0.0000' (length=6)
	  public 'product_lwh_uom' => string 'M' (length=1)
	  public 'product_url' => string '' (length=0)
	  public 'product_in_stock' => string '1' (length=1)
	  public 'product_ordered' => string '0' (length=1)
	  public 'low_stock_notification' => string '0' (length=1)
	  public 'product_available_date' => string '2011-10-07 12:00:00' (length=19)
	  public 'product_availability' => string '' (length=0)
	  public 'product_special' => string '0' (length=1)
	  public 'auction_number' => string '3' (length=1)
	  public 'contract_number' => string '' (length=0)
	  public 'lot_number' => string '1001754' (length=7)
	  public 'product_available_date_closed' => string '2011-12-31 23:59:00' (length=19)
	  public 'auction_date_start' => string '2011-11-13 12:00:00' (length=19)
	  public 'auction_date_finish' => string '2011-11-13 17:00:00' (length=19)
	  public 'product_sales' => string '0' (length=1)
	  public 'product_unit' => string 'KG' (length=2)
	  public 'product_packaging' => string '0.0000' (length=6)
	  public 'product_params' => string 'min_order_level=null|max_order_level=null|product_box=null|' (length=59)
	  public 'intnotes' => string '' (length=0)
	  public 'customtitle' => string '' (length=0)
	  public 'metadesc' => string '' (length=0)
	  public 'metakey' => string '' (length=0)
	  public 'metarobot' => string '' (length=0)
	  public 'metaauthor' => string '' (length=0)
	  public 'layout' => string '0' (length=1)
	  public 'published' => string '1' (length=1)
	  protected '_pkey' => string 'virtuemart_product_id' (length=21)
	  protected '_pkeyForm' => string '' (length=0)
	  protected '_obkeys' => 
		array
		  'product_name' => string 'Название товара отсутствует! Не удается сохранить запись без Название товара.' (length=143)
		  'slug' => string 'Данная SEF псевдоним уже существует.' (length=64)
	  protected '_unique' => boolean true
	  protected '_unique_name' => 
		array
		  'slug' => string 'Данная SEF псевдоним уже существует.' (length=64)
	  protected '_orderingKey' => string 'ordering' (length=8)
	  protected '_slugAutoName' => string 'product_name' (length=12)
	  protected '_slugName' => string 'slug' (length=4)
	  protected '_loggable' => boolean true
	  protected '_xParams' => string 'product_params' (length=14)
	  protected '_varsToPushParam' => 
		array
		  'min_order_level' => 
			array
			  0 => null
			  1 => string 'float' (length=5)
		  'max_order_level' => 
			array
			  0 => null
			  1 => string 'float' (length=5)
		  'product_box' => 
			array
			  0 => null
			  1 => string 'float' (length=5)
	  public '_translatable' => boolean true
	  protected '_translatableFields' => 
		array
		  0 => string 'product_name' (length=12)
		  1 => string 'product_s_desc' (length=14)
		  2 => string 'product_desc' (length=12)
		  3 => string 'metadesc' (length=8)
		  4 => string 'metakey' (length=7)
		  5 => string 'customtitle' (length=11)
		  'slug' => string 'slug' (length=4)
	  public '_tablePreFix' => string 'p.' (length=2)
	  protected '_tbl' => string '#__virtuemart_products' (length=22)
	  protected '_tbl_key' => string 'virtuemart_product_id' (length=21)
	  protected '_db' => 
		object(JDatabaseMySQL)[15]
		  public 'name' => string 'mysql' (length=5)
		  protected 'nameQuote' => string '`' (length=1)
		  protected 'nullDate' => string '0000-00-00 00:00:00' (length=19)
		  protected 'dbMinimum' => string '5.0.4' (length=5)
		  private '_database' (JDatabase) => string 'auctionru_2013' (length=14)
		  protected 'connection' => resource(31, mysql link)
		  protected 'count' => int 0
		  protected 'cursor' => resource(273, Unknown)
		  protected 'debug' => boolean false
		  protected 'limit' => int 0
		  protected 'log' => 
			array
			  empty
		  protected 'offset' => int 0
		  protected 'sql' => string 'SELECT l.*, v.*, j.`extension_id`,j.`name`, j.`type`, j.`element`, j.`folder`, j.`client_id`, j.`enabled`, j.`access`, j.`protected`, j.`manifest_cache`,
					j.`params`, j.`custom_data`, j.`system_data`, j.`checked_out`, j.`checked_out_time`, j.`state`,  s.virtuemart_shoppergroup_id  FROM   `#__virtuemart_paymentmethods_ru_ru` as l  JOIN `#__virtuemart_paymentmethods` AS v   USING (`virtuemart_paymentmethod_id`)  LEFT JOIN `#__extensions` as j ON j.`extension_id` =  v.`payment_jplugin_id`  LEFT OUTER JOIN `#__virtuemart_paymentmethod_shoppergroups` AS s ON v.`virtuemart_paymentmethod_id` = s.`virtuemart_paymentmethod_id`  WHERE v.`published` = "1" AND j.`element` = "klarna"
									AND  (v.`virtuemart_vendor_id` = "1" OR   v.`virtuemart_vendor_id` = "0")
									AND  ( s.`virtuemart_shoppergroup_id`= "1" OR (s.`virtuemart_shoppergroup_id`) IS NULL ) ORDER BY v.`ordering`' (length=889)
		  protected 'tablePrefix' => string 'auc13_' (length=6)
		  protected 'utf' => boolean true
		  protected 'errorNum' => int 0
		  protected 'errorMsg' => string '' (length=0)
		  protected 'hasQuoted' => boolean false
		  protected 'quoted' => 
			array
			  empty
	  protected '_trackAssets' => boolean false
	  protected '_rules' => null
	  protected '_locked' => boolean false
	  protected '_errors' => 
		array
		  empty
	  public '_cache' => null
	  public '_query_cache' => null
	  public 'created_on' => string '2013-04-14 18:39:09' (length=19)
	  public 'created_by' => string '107' (length=3)
	  public 'modified_on' => string '2013-04-14 14:39:09' (length=19)
	  public 'modified_by' => string '107' (length=3)
	  public '_langTag' => string 'ru_ru' (length=5)
	  public '_tbl_lang' => string '#__virtuemart_products_ru_ru' (length=28)
	  public 'min_order_level' => null
	  public 'max_order_level' => null
	  public 'product_box' => null
	  public 'virtuemart_media_id' => 
		array
		  0 => string '5375' (length=4)
		  1 => string '5376' (length=4)
	  public 'shoppergroups' => 
		array
		  empty
	  public 'product_price' => null
	  public 'product_override_price' => null
	  public 'override' => null
	  public 'virtuemart_product_price_id' => null
	  public 'virtuemart_shoppergroup_id' => null
	  public 'product_price_publish_up' => null
	  public 'product_price_publish_down' => null
	  public 'price_quantity_start' => null
	  public 'price_quantity_end' => null
	  public 'prices' => 
		array
		  'costPrice' => int 0
		  'basePrice' => float 0
		  'basePriceVariant' => null
		  'basePriceWithTax' => null
		  'discountedPriceWithoutTax' => null
		  'priceBeforeTax' => null
		  'taxAmount' => null
		  'salesPriceWithDiscount' => null
		  'salesPriceTemp' => null
		  'salesPrice' => null
		  'discountAmount' => null
		  'priceWithoutTax' => null
		  'variantModification' => null
		  'unitPrice' => null
	  public 'virtuemart_manufacturer_id' => 
		array
		  empty
	  public 'mf_name' => string '' (length=0)
	  public 'mf_desc' => string '' (length=0)
	  public 'mf_url' => string '' (length=0)
	  public 'categories' => 
		array
		  0 => string '52' (length=2)
	  public 'virtuemart_category_id' => string '52' (length=2)
	  public 'ordering' => string '0' (length=1)
	  public 'id' => string '1588' (length=4)
	  public 'category_name' => string 'Антикварное оружие' (length=35)
	  public 'packaging' => int 0
	  public 'box' => int 0
	  public 'orderable' => boolean true
	  public 'quantity' => int 1
	  public 'amount' => int 1
	  public 'product_template' => string '' (length=0)
	  public 'canonical' => string 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=1717&virtuemart_category_id=52' (length=104)
	  public 'link' => string '/~auction.test/аукцион/очные-торги/торги-в-помещении/антикварное-оружие/лот-266-кинжал-в-ножнах-кард-1-я-половина-xix-в-detail' (length=205)
	  public 'event' => 
		object(stdClass)[286]
		  public 'afterDisplayTitle' => string '' (length=0)
		  public 'beforeDisplayContent' => string '' (length=0)
		  public 'afterDisplayContent' => string '' (length=0)
	  public 'text' => string 'Сталь, дерево, кость. Ковка, золочение. Длина общая - 40,5 см, длина клинка - 23,5 см, ширина устья - 2,8 см. Представленный кинжал, предположительно кард - персидский однолезвийный нож с золотой насечкой. Является исторически-культурной ценностью.' (length=436)
	  public 'images' => 
		array
		  0 => 
			object(VmImage)[304]
			  public 'media_attributes' => int 0
			  public 'setRole' => boolean false
			  public 'file_name' => string '3_266_1' (length=7)
			  public 'file_extension' => string 'jpg' (length=3)
			  public 'virtuemart_media_id' => string '5375' (length=4)
			  private '_foldersToTest' (VmMediaHandler) => 
				array
				  ...
			  private '_actions' (VmMediaHandler) => 
				array
				  ...
			  private '_mLocation' (VmMediaHandler) => 
				array
				  ...
			  private '_hidden' (VmMediaHandler) => 
				array
				  ...
			  public 'theme_url' => string 'http://localhost/~auction.test/components/com_virtuemart/' (length=57)
			  public 'virtuemart_vendor_id' => string '1' (length=1)
			  public 'file_title' => string '3_266_1.jpg' (length=11)
			  public 'file_description' => string '' (length=0)
			  public 'file_meta' => string '' (length=0)
			  public 'file_mimetype' => string 'image/jpeg' (length=10)
			  public 'file_type' => string 'product' (length=7)
			  public 'file_url' => string 'images/stories/virtuemart/product/3_266_1.jpg' (length=45)
			  public 'file_url_thumb' => int 0
			  public 'published' => string '1' (length=1)
			  public 'file_is_downloadable' => string '0' (length=1)
			  public 'file_is_forSale' => string '0' (length=1)
			  public 'file_is_product_image' => string '0' (length=1)
			  public 'shared' => string '0' (length=1)
			  public 'file_params' => string '' (length=0)
			  public '_translatable' => boolean false
			  public '_tablePreFix' => string '' (length=0)
			  public '_cache' => null
			  public '_query_cache' => null
			  public 'created_on' => string '2013-04-14 18:39:09' (length=19)
			  public 'created_by' => string '107' (length=3)
			  public 'modified_on' => string '2013-04-14 14:39:09' (length=19)
			  public 'modified_by' => string '107' (length=3)
			  public 'file_url_folder' => string 'images/stories/virtuemart/product/' (length=34)
			  public 'file_path_folder' => string 'images\stories\virtuemart\product\' (length=34)
			  public 'file_url_folder_thumb' => string 'images/stories/virtuemart/product/resized/' (length=42)
			  public 'media_role' => string 'file_is_displayable' (length=19)
			  public 'file_name_thumb' => string '3_266_1_226x226' (length=15)
			  public '_db' => 
				object(JDatabaseMySQL)[15]
				  ...
		  1 => 
			object(VmImage)[305]
			  public 'media_attributes' => int 0
			  public 'setRole' => boolean false
			  public 'file_name' => string '3_266_2' (length=7)
			  public 'file_extension' => string 'jpg' (length=3)
			  public 'virtuemart_media_id' => string '5376' (length=4)
			  private '_foldersToTest' (VmMediaHandler) => 
				array
				  ...
			  private '_actions' (VmMediaHandler) => 
				array
				  ...
			  private '_mLocation' (VmMediaHandler) => 
				array
				  ...
			  private '_hidden' (VmMediaHandler) => 
				array
				  ...
			  public 'theme_url' => string 'http://localhost/~auction.test/components/com_virtuemart/' (length=57)
			  public 'virtuemart_vendor_id' => string '1' (length=1)
			  public 'file_title' => string '3_266_2.jpg' (length=11)
			  public 'file_description' => string '' (length=0)
			  public 'file_meta' => string '' (length=0)
			  public 'file_mimetype' => string 'image/jpeg' (length=10)
			  public 'file_type' => string 'product' (length=7)
			  public 'file_url' => string 'images/stories/virtuemart/product/3_266_2.jpg' (length=45)
			  public 'file_url_thumb' => string 'images/stories/virtuemart/product/resized/3_266_2.jpg_90x90.jpg' (length=63)
			  public 'published' => string '1' (length=1)
			  public 'file_is_downloadable' => string '0' (length=1)
			  public 'file_is_forSale' => string '0' (length=1)
			  public 'file_is_product_image' => string '0' (length=1)
			  public 'shared' => string '0' (length=1)
			  public 'file_params' => string '' (length=0)
			  public '_translatable' => boolean false
			  public '_tablePreFix' => string '' (length=0)
			  public '_cache' => null
			  public '_query_cache' => null
			  public 'created_on' => string '2013-04-14 18:39:09' (length=19)
			  public 'created_by' => string '107' (length=3)
			  public 'modified_on' => string '2013-04-14 14:39:09' (length=19)
			  public 'modified_by' => string '107' (length=3)
			  public 'file_url_folder' => string 'images/stories/virtuemart/product/' (length=34)
			  public 'file_path_folder' => string 'images\stories\virtuemart\product\' (length=34)
			  public 'file_url_folder_thumb' => string 'images/stories/virtuemart/product/resized/' (length=42)
			  public 'media_role' => string 'file_is_displayable' (length=19)
	  public 'neighbours' => 
		array
		  'previous' => string '' (length=0)
		  'next' => 
			array
			  0 => 
				array
				  ...	*/
$virtuemart_category_id=$this->product->virtuemart_category_id;
$virtuemart_product_id=(int)$this->product->virtuemart_product_id;
$currency=AuctionStuff::getProductCurrency($virtuemart_product_id).'.';
$Itemid=JRequest::getVar('Itemid');

HTML::setCommonInnerMenu(array('take_lot','ask_about_lot','user'),array('ask_about_lot'=>$this->product->virtuemart_product_id));?>
<div class="lots_listing">
  <div class="width70 inBlock" style="margin-left:-8px;">    
    <ul class="table inline weak">
<?	$category_link=AuctionStuff::extractCategoryLinkFromSession($virtuemart_category_id);
	if($router = JFactory::getApplication()->getRouter()){
		$SefMode=$router->getMode();
		$session=&JFactory::getSession();
		$links=$session->get('section_links');
		$menu = JFactory::getApplication()->getMenu();
		$menus = $menu->getMenu();
		$top_layout=$menus[$Itemid]->query['layout']; // shop, fulltime die('top_layout='.$top_layout);
		//if():endif;
	}
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
		$category_link=JRoute::_('index.php?option=com_virtuemart&view=category&Itemid='.$Itemid,false);
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
  </div><?
  			// var_dump($this->product->images); die();
  ?>
<form method="post" id="add_to_favorite" name="add_to_favorite" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.addToFavorites'); ?>">    
    <input type="submit" name="btn_favor" id="btn_favor" value="добавить в избранное">
	<input type="hidden" name="option" value="com_auction2013" />
	<input type="hidden" name="task" value="auction2013.addToFavorites" />
    <input type="hidden" name="virtuemart_product_id" value="<?=$this->product->virtuemart_product_id?>" />
	  <?php echo JHtml::_('form.token');?>        
</form>  
</div>
<div>
  <div class="gallery_lot">
      <div id="galleryContainer"<? //style="clear: both;"?>>
          <div class="main_im_lot">
              <div id="galleryBigImage">
                  <div id="bigLeadImage">
                      <a href="<?=$base_path.$this->product->images[0]->file_url;?>" class="MagicZoomPlus" id="Zoomer" rel="zoom-width:370px; zoom-border:2px; zoom-height:400px; zoom-distance:25">
                       	  <img src="<?=$base_path.$this->product->images[0]->file_url;?>" width="334" style="opacity:1;">
                          <div class="MagicZoomBigImageCont">
                              <div class="MagicZoomHeader"> </div>
                          </div>
                       	  <div class="MagicZoomPup"> </div>
                      </a>
                  </div>
              </div>		
          </div>
          <div id="galleryThumbs">
              <div> 														
	<? 	foreach($this->product->images as $i => $stuff):?>	

                  <div class="th_imgage">
                      <div class="inside_image_preview">
                          <a href="<?=$base_path.$this->product->images[$i]->file_url;?>" rel="zoom-id:Zoomer" rev="<?=$base_path.$this->product->images[$i]->file_url_thumb?>" style="outline: none; " class="MagicThumb-swap">
                           	  <img src="<?=$base_path.$this->product->images[$i]->file_url_thumb?>" height="82" width="82"></a>
                      </div>
                  </div>
                                
    <?	endforeach;?>                       
            </div>
        </div>
                    
            
        <div class="clr"></div>
   	</div>
    
  </div>

  <div class="box_desc">
    <div class="bord_bottom txtBrown">
    <? $dots=".................";
	if($test){?><h3>Очные торги, Магазин:</h3>
    	<p>Наименование</p>
    	<p>Описание</p>
<? 	}?>
		<b><?=$this->product->product_name?></b>
    </div>
    <div class="o_o">
       <span style="color:#000">
   	      <?=$this->product->product_s_desc?>
       </span>
    </div>
<? 	if(date('Y-m-d')>$this->product->auction_date_finish)
				$auction_closed=true;
	// Варианты отображения данных:
		// fulltime. Если есть данные:
			// * История ставок
			// * Последняя ставка
	if($top_layout == 'fulltime'){
		if($test){?><h3>Очные торги:</h3><? }?>
    <div class="o_o">
          Номер аукциона: 
      <span class="span_o_o">
          <?=$dots?>
      </span>
      <a href="#">
          <span class="bold span_o_o">
   		      <?=$this->product->auction_number?>
          </span> 
      </a>
    </div>				
    <div class="o_o">
      <a href="<?=JRoute::_("index.php?option=com_content&view=article&id=23", false)?>">
          Поставить заочный бид
      </a>
    </div>
    <div class="o_o">
      Начало торгов:<?=$dots?>
      <span class="span_o_o">
          <b><?=JHTML::_('date', $this->product->auction_date_start, JText::_('DATE_FORMAT_LC2'))?></b>
      </span>
    </div>		   
    <div class="o_o">
      Конец торгов:<?=$dots?> 
      <span class="span_o_o">
          <b><?=JHTML::_('date', $this->product->auction_date_finish, JText::_('DATE_FORMAT_LC2'))?></b>
      </span>
    </div>				  
    <div class="o_o">
      Предварительная оценка: 
      <span class="span_o_o">
          <b><? AuctionStuff::writeProductPrices($this->product->virtuemart_product_id);?></b> <?=$currency?>
      </span>
    </div>
	<?	
	}elseif($top_layout == 'shop'){
		if($test){?><h3>Магазин:</h3><? }
		// shop
		// Если предмет продан 
		// ИЛИ
		// Истёк период продажи:
			// * "Торги по данному лоту окончены"
		// Если предмет продан:
			// * Цена продажи
		// Иначе - 
		//	// * Кнопка "Купить"
	if($auction_closed||$this->product->sales_price):?>
    <div><b>Торги по данному лоту окончены</b></div>
<?	endif;
	if($this->product->sales_price):?>
    <div class="o_o">
      Цена продажи:<?=$dots?> 
      <span class="span_o_o">
          <b><?=$this->product->sales_price?></b> <?=$currency?>
      </span>
    </div>
<?	endif;?> 
	<div class="o_o">
      Категория:<?=$dots?>
      <span class="span_o_o">
          <b><?=$this->product->category_name?></b>
      </span>
    </div>
	<?	if(!$this->product->sales_price):?>    
	<div class="o_o">
      Цена:<?=$dots?>
      <span class="span_o_o">
          <b><?	// 'price'=>'product_price'
		  if($product_price=$this->product->product_price)
		  	if (preg_match("/\.{1}[0]/", $product_price))
				unset($product_price);
          echo ($product_price)? $product_price:'Не назначена'; ?></b> <? echo $currency;?>
      </span>
    </div>
	<button type="button" class="buttonSandCool txtBrown" onclick="location.href='<?=JRoute::_("index.php?option=com_auction2013&layout=application&virtuemart_product_id=".$this->product->virtuemart_product_id.'&menu_itemid='.$Itemid, false)?>'">Купить</button>
	<?	endif;
	}?>
  </div>        
</div><?
			
			
			endif;