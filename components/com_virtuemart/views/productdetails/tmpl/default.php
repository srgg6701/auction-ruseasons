<?php
defined('_JEXEC') or die('Restricted access');
//if(JRequest::getVar('show_id')) echo "<h4>product_id = ".$this->product->virtuemart_product_id."</h4>";
//commonDebug(__FILE__,__LINE__,$this->product, true);
$Itemid = JRequest::getVar('Itemid');

$path = JPATH_SITE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
//die('path: '.$path);
require_once $path;

$topItem = AuctionStuff::getTopCatsMenuItemIds('main', false);
//commonDebug(__FILE__, __LINE__, key($topItem));
//commonDebug(__FILE__, __LINE__, $topItem, true);
if(JRequest::getVar('source')):
    require_once 'source/default.php';
else:
    $base_path=JUri::root();
    $templateUrl=JUri::root().'templates/auction/';?>
<link href="<?=$templateUrl?>magic_zoom/magiczoomplus.css" rel="stylesheet" type="text/css" media="screen">
<script src="<?=$templateUrl?>magic_zoom/magiczoomplus.js" type="text/javascript"></script>
<?php 
    $virtuemart_category_id=$this->product->virtuemart_category_id;
    $virtuemart_product_id=(int)$this->product->virtuemart_product_id;
    //echo "<h4>setCommonInnerMenu</h4>";
    HTML::setCommonInnerMenu(array('take_lot','ask_about_lot','user'),array('ask_about_lot'=>$this->product->virtuemart_product_id));
    //commonDebug(__FILE__, __LINE__, $this->product, true);?>
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
		$category_link=JRoute::_('index.php?option=com_virtuemart&view=category&Itemid='.$Itemid,false);
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
<?php require_once dirname(__FILE__).'/../default_magic_zoom.php'?>
    <div class="box_desc">
<?php
    //commonDebug(__FILE__,__LINE__,$topItem);
    //commonDebug(__FILE__,__LINE__,$Itemid);
    if((int)$topItem['shop']===(int)$Itemid): //echo "<h1>Shop</h1>";
        ?>
<form id="purchase_app_form" action="<?php 
echo JRoute::_('index.php?option=com_auction2013&task=auction2013.purchase'); 
    ?>" method="post">
      <span><b><?php echo $this->product->product_sku;?>
                <?php echo $this->product->product_name;?>
            </b></span>
      <div class="product_s_desc"><?php echo $this->product->product_s_desc;?></div>
    <div class="o_o">
      Категория:................... 
      <span class="span_o_o">
          <b><?php
          echo $this->product->category_name;
          ?></b>
      </span>
    </div>
    <div class="o_o">
      Цена:........................... 
      <span class="span_o_o">
          <b><?php 
          echo $this->product->prices['basePriceVariant'];
          ?> руб.</b>
      </span>
    </div>
<?php   if(!$bought = AuctionStuff::getPurchases(
                        array('virtuemart_product_id'=>$this->product->virtuemart_product_id,
                              'user_id'=>'?' ) )):
        //commonDebug(__FILE__,__LINE__,$bought[0], true);
?>
        <input type="hidden" name="option" value="com_auction2013" />
        <input type="hidden" name="task" value="auction2013.purchase" />
        <input type="hidden" name="menuitemid" value="<?php echo $Itemid;?>" />
        <input type="hidden" name="category_id" value="<?php echo $this->product->virtuemart_category_id;?>" />
        <input type="hidden" class="pname" value="<?php echo htmlentities($this->product->product_name, ENT_QUOTES, 'utf-8') ?>"/>
        <input type="hidden" name="virtuemart_product_id[]" value="<?php echo $this->product->virtuemart_product_id ?>"/>
        <input type="hidden" name="link" value="<?php echo $this->product->link; ?>"/>
    <?php echo JHtml::_('form.token');?>
    <button type="submit" class="buttonSandCool">Купить</button>
<?php
        else:
			$item = $bought[0];
	?>
	<h4><?php
            $p_datetime=explode(' ',$item['datetime']);
            $intime=$p_datetime[0]." в ".$p_datetime[1];
	        // подана заявка на покупку данного предмета
			if(!(int)$item['status']):
		 		// юзер на идентифицирован или заявка подана другим юзером
				if($item['user_id']==='unknown'||!(int)$item['user_id']):
					?>Предмет недоступен<?php
				// заявка подана текущим юзером
			  	else:
		?>Вы подали заявку на приобретение данного предмета <?php echo $intime;
              	endif;
		    // предмет продан
			else:
				// юзер не идентифицирован или предмет подан другому юзеру
				if($item['user_id']==='unknown'||!(int)$item['user_id']):
					?>Предмет продан<?
				// продан текущему юзеру
				else:
		?>Вы приобрели данный предмет <?php echo $intime;
				endif;
			endif;?>.</h4>
<?php	endif;
    ?>
</form>  
<?php        
    else:
?>      
    <div class="bord_bottom">
      <b><?php //"Лот "(int)$this->product->lot_number
          echo $this->product->product_name?></b>
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
          <span class="bold span_o_o">
   		      <?=$this->product->auction_number?>
          </span>
    </div>				
    <div class="o_o">
      <a href="<?=JRoute::_("index.php?option=com_content&view=article&id=23", false)?>">
          Поставить заочный бид
      </a>
    </div>
    <div class="o_o">
      Начало торгов:................. 
      <span class="span_o_o">
          <b><?php DateAndTime::setShortDate($this->product->product_available_date); ?></b>
      </span>
    </div>		   
    <div class="o_o">
      Конец торгов:................... 
      <span class="span_o_o">
          <b><?php DateAndTime::setShortDate($this->product->auction_date_finish); ?></b>
      </span>
    </div>				  
    <div class="o_o">
    <?php   if((int)$topItem['fulltime']===(int)$Itemid):
        ?>Предварительная оценка<?php
            else:
        ?>Стартовая цена<?php
            endif;
        ?>:
      <span class="span_o_o">
          <b>
		<?php
        $startPrice = substr($this->product->product_price,0,strpos($this->product->product_price,'.'));
        echo $startPrice;?>
          </b>
    <?php   if((int)$topItem['fulltime']===(int)$Itemid):
    ?>
          <b> -
       	<?php echo ($this->product->price2)? $this->product->price2:'0'; ?>
          </b>   
    <?php   endif;
            echo $this->product->currency_symbol;?>.
      </span>
    </div>
<?php    
    endif;?>      
<?php
	if((int)$topItem['online']===(int)$Itemid):
        //if((int)$this->product->published):
        $test_time=false;
        $auction_states=array(  'active'=>'Торги активны',
                                'passive'=>'Торги не начинались',
                                'closed'=>'Торги закрыты'
                            );
        // торги активны
        if(DateAndTime::getDelta($this->product->auction_date_finish)<0){
            $auction_state='active';
            if($test_time):
            /* ВНИМАНИЕ! Если время открытия публикации указано после
                времени закрытия публикации, публикация выставляется в null */
                if( DateAndTime::getDelta($this->product->product_price_publish_up)>0
                    && DateAndTime::getDelta($this->product->product_price_publish_down)<0 )
                    echo "<h4>Предмет опубликован</h4>";
                else
                    echo "<h4 class='error-text'>Предмет НЕ опубликован</h4>";
                // внештатная ситуация - время открытия торгов ПОСЛЕ времени закрытия
                if(DateAndTime::getDelta($this->product->product_available_date)<=0)
                    echo "<h4 class='error-text'>Торг НЕ был открыт</h4>";
            endif;
        }else{ // торги закрыты
            if( DateAndTime::getDelta($this->product->product_available_date)<0)
                $auction_state='passive';
            else
                $auction_state='closed';
        }
        ?>
      <div id="make_bid">
<?php   if(JFactory::getUser()->guest==1):
            if($auction_state=='active'):
    ?>
          Чтобы сделать ставку, вам необходимо <a href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>">заавторизоваться</a>.
<?php       else:
    ?>
                <h4><?php echo $auction_states[$auction_state];?></h4>
<?php
            endif;
        else: require_once "bid.php";
        endif;
?>
      </div>   
<?php      
	endif;
?>          
  </div>
</div><?php 
endif;