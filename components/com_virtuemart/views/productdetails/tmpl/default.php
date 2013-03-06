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
defined('_JEXEC') or die('Restricted access');?>
<link href="<?=JUri::root()?>templates/auction/magic_zoom/magicstyle.css" rel="stylesheet" type="text/css">
<link href="<?=JUri::root()?>templates/auction/magic_zoom/magiczoomplus.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?=JUri::root()?>templates/auction/magic_zoom/magiczoomplus.js" type="text/javascript"></script>
<?
require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';	//var_dump($this->product->images); die();
$virtuemart_category_id=$this->product->virtuemart_category_id;
$virtuemart_product_id=(int)$this->product->virtuemart_product_id;
if($router = JFactory::getApplication()->getRouter()){
	$session=&JFactory::getSession();
	$links=$session->get('section_links');
}
HTML::setCommonInnerMenu(array('take_lot','ask_about_lot','user'),array('ask_about_lot'=>$this->product->virtuemart_product_id));
$path=JUri::root().'templates/auction/magic_zoom/';?>
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

<form method="post" id="add_to_favorite" name="add_to_favorite" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.addToFavorites'); ?>">    
    <input type="submit" name="btn_favor" id="btn_favor" value="добавить в избранное">
	<input type="hidden" name="option" value="com_auction2013" />
	<input type="hidden" name="task" value="auction2013.addToFavorites" />
    <input type="hidden" name="virtuemart_product_id" value="<?=$this->product->virtuemart_product_id?>" />
		<?php echo JHtml::_('form.token');?>        
</form>  </div>
</div>

<div class="outer_shell">
    <div class="content_shell">		
        <div class="left">
            <div>
                <div class="gallery_lot">
                    <div id="galleryContainer" style="clear: both;">
                        <div class="main_im_lot">
                            <div id="galleryBigImage">
                                <div id="bigLeadImage">
                                    <a href="<?=$base_path.$this->product->images[0]->file_url;?>" class="MagicZoomPlus" id="Zoomer" rel="zoom-width:450px;zoom-border:2px;zoom-height:293px;" style="position: relative; display: inline-block; text-decoration: none; outline: 0px; margin: auto; width: 334px; " title="">
                                    	<img src="<?=$base_path.$this->product->images[0]->file_url;?>" width="334" alt="" style="opacity: 1; ">
                                    		<div class="MagicZoomBigImageCont" style="overflow: hidden; z-index: 100; top: -10000px; position: absolute; width: 450px; height: 293px; opacity: 1; left: 349px; ">
                                    			<div class="MagicZoomHeader" style="position: relative; z-index: 10; left: 0px; top: 0px; padding: 3px; display: none; visibility: hidden; ">
                                                </div>
                                                
                                                <!--<div style="overflow: hidden; ">
                                    				<img src="<?=$base_path.$this->product->images[0]->file_url;?>" style="padding: 0px; margin: 0px; border: 0px; position: relative; left: -109.2px; top: -235.4px; ">
                                    			</div>-->
                                    		
                                            </div>
                                    	<div class="MagicZoomPup" style="z-index: 10; position: absolute; overflow: hidden; display: none; visibility: hidden; width: 123px; height: 80px; opacity: 0.5; left: 0px; top: 80px; "></div>
                                    </a>
                                </div>
                            </div>		
                        </div>




                        <div id="galleryThumbs">
                            <div style="padding-left: 0px; margin-left: 0px;"> 														
                                
	<? 	foreach($this->product->images as $i => $stuff):?>	

                                <div class="th_imgage">
                                    <div class="inside_image_preview">
                                        <a href="<?=$base_path.$this->product->images[$i]->file_url;?>" rel="zoom-id:Zoomer" rev="<?=$base_path.$this->product->images[$i]->file_url_thumb?>" style="outline: none; " class="MagicThumb-swap">
                                        	<img src="<?=$base_path.$this->product->images[$i]->file_url_thumb?>" height="82" width="82" alt="" title=""></a>
                                    </div>
                                </div>
                                
    <?	endforeach;?>                       
                    </div>
                </div>
                    
            
            <div class="clr"></div>
        	</div>
    
           	</div>

			<div class="box_desc">
        <div class="bord_bottom">
         	<b>Лот <?=$this->product->lot_number?>. <?=$this->product->product_name?></b>
		</div>
        <div class="o_o">
             <span style="color:#000">
             	<?=$this->product->product_s_desc?>
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
                </b>
            </span>
        </div>		   
        <div class="o_o">
            Конец торгов:................... 
            <span class="span_o_o">
                <b>
             		<?=$this->product->auction_date_finish?>
                </b>
            </span>
        </div>				  
        <div class="o_o">
            Предварительная оценка: 
            <span class="span_o_o">
                <b>
             		<?
	echo substr($this->product->product_price,0,strpos($this->product->product_price,'.'));?>
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
            </div>
            
        </div>
</div>



<?
if(1>2){?>
<div>
<? $base_path=JUri::root();?>
	<div class="gallery_lot">
        <div id="galleryContainer" style="clear: both;">
            <div class="main_im_lot">
                <div id="galleryBigImage">					
                    <div id="bigLeadImage">
                        <a href="<?=$base_path.$this->product->images[0]->file_url;?>" class="MagicZoomPlus" id="Zoomer" rel="zoom-width:450px;zoom-border:2px;zoom-height:293px;" style="position: relative; display: inline-block; text-decoration: none; outline: 0px; margin: auto; width: 334px; " title=""><img src="<?=$base_path.$this->product->images[0]->file_url?>" width="334" height="334" alt="" style="opacity: 1;">
                        <div class="MagicZoomBigImageCont" style="overflow: hidden; z-index: 100; top: -10000px; position: absolute; width: 450px; height: 293px; opacity: 1; left: 349px; ">
                                <div class="MagicZoomHeader" style="position: relative; z-index: 10; left: 0px; top: 0px; padding: 3px; display: none; visibility: hidden; ">
                                </div>
                                                    
                                <div style="overflow: hidden; ">
                            
                                	<img src="<?=$base_path.$this->product->images[0]->file_url_thumb?>" style="padding: 0px; margin: 0px; border: 0px; position: relative; left: -750px; top: 0px; ">
                            
                                </div>
                            
                            </div>
                            
                            <div class="MagicZoomPup" style="z-index: 10; position: absolute; overflow: hidden; display: none; visibility: hidden; width: 123px; height: 80px; opacity: 0.5; left: 209px; top: 0px; ">
                            </div></a>
					</div>
                </div>		
            </div>
            <div id="galleryThumbs">
                <div class="tumb">
	<? 	foreach($this->product->images as $i => $stuff):?>	
                    <div class="th_imgage">
                        <div class="inside_image_preview">
                        	<a href="<?=$base_path.$this->product->images[$i]->file_url;?>" rel="zoom-id:Zoomer" rev="<?=$base_path.$this->product->images[$i]->file_url;?>" style="outline: none; " class="MagicThumb-swap"><img src="<?=$base_path.$this->product->images[$i]->file_url;?>" height="82" width="82"></a>
                    	</div>
                	</div>
	<?	endforeach;?>																	
				</div>
			</div>
			<div class="clr"></div>
    	</div>
	</div>




	<div class="box_desc">
        <div class="bord_bottom">
         	<b>Лот <?=$this->product->lot_number?>. <?=$this->product->product_name?></b>
		</div>
        <div class="o_o">
             <span style="color:#000">
             	<?=$this->product->product_s_desc?>
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
                </b>
            </span>
        </div>		   
        <div class="o_o">
            Конец торгов:................... 
            <span class="span_o_o">
                <b>
             		<?=$this->product->auction_date_finish?>
                </b>
            </span>
        </div>				  
        <div class="o_o">
            Предварительная оценка: 
            <span class="span_o_o">
                <b>
             		<?
	echo substr($this->product->product_price,0,strpos($this->product->product_price,'.'));?>
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
<?	}?>