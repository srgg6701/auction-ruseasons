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
//commonDebug(__FILE__,__LINE__,$this->layout, true);
    HTML::setVmPagination($this->layout, true);?>
<dl class="search-results" id="search-results-block">
    <?php foreach($this->results as $result) : ?>
        <dt class="result-title">
            <?php if ($result->href) :?>
                <a href="<?php echo JRoute::_($result->href); ?>">
                    <?php
                    if($result->image):
                        ?><img src="<?php echo $result->image;?>"><?php
                    else:?><img src="<?php echo JURI::base() .'images/no-image.gif';?>" width="226" height="226"><?php
                    endif;
                    $link_text = str_replace("&laquo;","«",$result->title);
                    $link_text = str_replace("&raquo;","»",$link_text);?>
                    <div class="name">
                    <span><?php
                        echo $this->escape($link_text);
                        ?></span>
                    </div>
            <?php else:?>
                <?php echo $this->escape($result->title);?>
            <?php endif; ?>
        </dt>
    <?php endforeach; ?>
</dl>
<?php  /*<div class="results-count-block"><?php echo $cnt;?></div> ?>
<div class="pagination" id="pagination-search-result-2">
    <?php echo $pagination; ?>
</div><?php */
    HTML::setVmPagination($this->layout);
endif;