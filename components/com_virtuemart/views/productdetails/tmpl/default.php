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

			
if(JRequest::getVar('source')):
    require_once 'source/default.php';

else:
    $base_path=JUri::root();
    $templateUrl=JUri::root().'templates/auction/';?>
<link href="<?=$templateUrl?>magic_zoom/magiczoomplus.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?=$templateUrl?>magic_zoom/magiczoomplus.js" type="text/javascript"></script>
<?php 
    require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';	//var_dump($this->product->images); die();
    $virtuemart_category_id=$this->product->virtuemart_category_id;
    $virtuemart_product_id=(int)$this->product->virtuemart_product_id;

    HTML::setCommonInnerMenu(array('take_lot','ask_about_lot','user'),array('ask_about_lot'=>$this->product->virtuemart_product_id));
    ?>
<div class="lots_listing">
  <div class="width70 inBlock" style="margin-left:-8px;">    
    <ul class="table inline weak">
<?php
    $SefMode=JFactory::getApplication()->getRouter()->getMode();
    // возвращает ссылку уже в нужном (обычный/ЧПУ) виде:
    $category_link=AuctionStuff::extractCategoryLinkFromSession($virtuemart_category_id);
	$trinityIds=AuctionStuff::getProductNeighborhood($virtuemart_product_id,$virtuemart_category_id);
	$hide=' style="visibility:hidden"';

	
	if((int)$trinityIds[0]<$virtuemart_product_id):	
		$prev_prod_link=AuctionStuff::buildProdNeighborLink($trinityIds[0],$category_link,$SefMode);
	else: $prev_prod_link=false;
	endif;?>    
        <li><a href="<?=$prev_prod_link?>"<?php if(!$prev_prod_link) echo $hide;
	
	?>>&lt; &lt; Предыдущий <!--(<?=$trinityIds[0]?>)-->></a></li>
<?php if(!$category_link): // if no SEF only:
		$category_link=JRoute::_('index.php?option=com_virtuemart&view=category&Itemid='.JRequest::getVar('Itemid'),false);
	endif;?>	
        <li><a href="<?=$category_link?>">Вернуться к списку лотов</a></li>
<?php if($trinityIds[2]) 
		$next_prod_id=$trinityIds[2];
	elseif((int)$trinityIds[1]>$virtuemart_product_id) 
		$next_prod_id=$trinityIds[1];
	if($next_prod_id):	
		$next_prod_link=AuctionStuff::buildProdNeighborLink($next_prod_id,$category_link,$SefMode);
	endif;?>
        <li><a href="<?=$next_prod_link?>"<?php if(!$next_prod_id) echo $hide;
        
		?>>Следующий <!--(<?=$next_prod_id?>)-->&gt; &gt;</a></li>
    </ul>
</div><?php // var_dump($this->product->images); die();
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
      <div id="galleryContainer"<?php //style="clear: both;"?>>
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
	<?php foreach($this->product->images as $i => $stuff):?>	

                  <div class="th_imgage">
                      <div class="inside_image_preview">
                          <a href="<?=$base_path.$this->product->images[$i]->file_url;?>" rel="zoom-id:Zoomer" rev="<?=$base_path.$this->product->images[$i]->file_url_thumb?>" style="outline: none; " class="MagicThumb-swap">
                           	  <img src="<?=$base_path.$this->product->images[$i]->file_url_thumb?>" height="82" width="82"></a>
                      </div>
                  </div>
                                
    <?php endforeach;?>                       
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
   		      <?php echo substr($this->product->product_price,0,strpos($this->product->product_price,'.'));?>
          </b>  
          <b>
       	      - ? ? ? ?
             <!-- - 150000-->
          </b>   
          рублей
      </span>
    </div>
  </div>        
</div><?php 
endif;