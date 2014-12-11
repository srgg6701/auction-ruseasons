<?php
//showTestMessage("Auction<hr>", __FILE__, __LINE__, false);
// no direct access
defined('_JEXEC') or die;
//commonDebug(__FILE__,__LINE__,array('count'=>count($this->results),$this->results), false);
/*
  array(1) {
    [0]=>
    object(stdClass)#243 (5) {
      ["virtuemart_product_id"]=>
      string(4) "4194"
      ["title"]=>
      string(27) "Жывопысный Пук"
      ["product_s_desc"]=>
      string(28) "Чудова картина!"
      ["href"]=>
      string(104) "http://localhost/auction-ruseasons/аукцион/очные-торги/zhivopis-grafika/zhivopiz-detail"
      ["image"]=>
      string(87) "images/stories/virtuemart/product/resized/akvarelnye-goroda-maji-vronskoj-2_226x226.jpg"
    }
  } */
//commonDebug(__FILE__,__LINE__,$this, true);


if(!count($this->results)):?>
    <h4 class="thin">Ничего не найдено...</h4>
<?php
else:
//commonDebug(__FILE__,__LINE__,$this->getLayout(), true);
    HTML::setVmPagination($this->getLayout(), true);?>
<dl class="search-results auction" id="search-results-block">
    <?php foreach($this->results as $result) : ?>
        <dt class="result-title">
            <?php if ($result->href) :?>
                <a href="<?=JRoute::_($result->href)?>">
            <?php   if($result->image):
                        ?><img src="<?=$result->image?>"><?php
                    else:
                        ?><img src="<?=JURI::base() .'images/no-image.gif'?>" width="226" height="226"><?php
                    endif;
                    $link_text = str_replace("&laquo;","«",$result->title);
                    $link_text = str_replace("&raquo;","»",$link_text);?>
                    <div class="name">
                    <span>
                        <b><?=$this->escape($link_text)?></b>
                        <p><?=$result->product_s_desc?></p>
                        <section><?php
                        echo $result->prices." ";
                        echo($result->currency_symbol=='$')?
                            "у.е":$result->currency_symbol; ?>.</section>
                    </span>
                    </div>
            <?php else:?>
                <?=$this->escape($result->title)?>
            <?php endif;?>
                </a>
        </dt>
    <?php endforeach; ?>
</dl>
<?php  /*<div class="results-count-block"><?php echo $cnt;?></div> ?>
<div class="pagination" id="pagination-search-result-2">
    <?php echo $pagination; ?>
</div><?php */
    HTML::setVmPagination();
endif;