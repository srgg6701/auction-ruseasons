<?php
//showTestMessage("Auction<hr>", __FILE__, __LINE__, false);
// no direct access
defined('_JEXEC') or die;
// include_once JPATH_SITE.DS.'tests.php';
commonDebug(__FILE__,__LINE__,array('count'=>count($this->results),$this->results), true);
/*
  array(1) {
    [0]=>
    object(stdClass)#243 (8) {
      ["virtuemart_product_id"]=>
      string(4) "4194"
      ["product_name"]=>
      string(27) "Жывопысный Пук"
      ["slug"]=>
      string(16) "zhivopis-grafika"
      ["virtuemart_category_id"]=>
      string(2) "24"
      ["product_s_desc"]=>
      string(28) "Чудова картина!"
      ["top_category_alias"]=>
      string(21) "очные-торги"
      ["auction_number"]=>
      string(6) "102030"
      ["file_url_thumb"]=>
      string(87) "images/stories/virtuemart/product/resized/akvarelnye-goroda-maji-vronskoj-2_226x226.jpg"
    }
  }*/
//commonDebug(__FILE__,__LINE__,$this, true);


if(!count($this->results)):?>
    <h4 class="thin">По вашему запросу ничего не найдено...</h4>
<?php
else:
//commonDebug(__FILE__,__LINE__,$this->pagination, true);?>
<div class="results-count-block"><?php
    $cnt = 'Всего найдено лотов: ';
    $cnt.=($this->pagination->total)? $this->pagination->total:'0';
    $cnt.= ' &nbsp; <a id="link-manage-search" href="javascript:void(0)" onclick="manageAdvancedSearch()">[<span>расширенный поиск</span><span style="display: none">результаты поиска</span>]</a>';
    echo $cnt;?></div>
<div class="pagination" id="pagination-search-result-1">
    <?php
    $pagination = str_ireplace('>В начало', '>&lt;&lt;',   $this->pagination->getPagesLinks());
    $pagination = str_ireplace('>Назад', '>&lt;', $pagination);
    $pagination = str_ireplace('>Вперёд', '>&gt;', $pagination);
    $pagination = str_ireplace('>В конец', '>&gt;&gt;', $pagination);
    echo $pagination; ?>
</div>
<dl class="search-results" id="search-results-block">
    <?php foreach($this->results as $result) : ?>
        <dt class="result-title">
            <?php if ($result->href) :?>
                <a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>><?php
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
                    <div class="price">
                        <span><?php echo $result->price .' '.$result->currency;?></span>
                    </div></a>
            <?php else:?>
                <?php echo $this->escape($result->title);?>
            <?php endif; ?>
        </dt>
    <?php endforeach; ?>
</dl>
<div class="results-count-block"><?php echo $cnt;?></div>
<div class="pagination" id="pagination-search-result-2">
    <?php echo $pagination; ?>
</div><?php
endif;