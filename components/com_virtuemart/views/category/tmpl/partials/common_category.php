<?php
//var_dump(JRequest::get('get'));
/*	get:
		'Itemid' => string '115' (length=3)
		'option' => string 'com_virtuemart' (length=14)
		'view' => string 'category' (length=8)

	SHOP:
		'layout' => string 'shop' (length=4)
		'virtuemart_category_id' => string '0' (length=1)
	CATEGORY:
		'limitstart' => int 0
		'limit' => string 'int' (length=3)
		'virtuemart_category_id' => string '6' (length=1)

		$this->category->slug
 */
$categoryLayout=AuctionStuff::getTopLayout();
HTML::pageHead($categoryLayout);
?>
<div class="item-page-<?php echo $categoryLayout;?> category-layout">
<?php   if(JRequest::getVar('spag')) var_dump($this->vmPagination); ?>
<br>
<?php if (empty($this->keyword)):?>
    <div class="category_description"><?=$this->category->category_description; ?>
    </div>
<?php endif;

if ( VmConfig::get ('showCategory', 1) &&
    empty($this->keyword)
) :

    $show_children=false;
    if($show_children){
        if ($this->category->haschildren) :
            //echo '<h1>Has children! Do something, Dude...</h1>';?>
            <div>
                <?php foreach ($this->category->children as $category) :
                    // Category Link
                    $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id);
                    // Show Category?>
                    <h2><a href="<?=$caturl?>" title="<?=$category->category_name?>"><?=$category->category_name?></a>
                        <div class="img" title="<?=$category->category_name?>">
                            <a href="<?=$caturl?>"><?=$category->images[0]->displayMediaThumb ("", FALSE)?>&nbsp;</a>
                        </div>
                    </h2>
                <?php endforeach;?>
            </div>
        <?php endif;
    } // /show_children

endif;

if ($this->search !== NULL):?>
    <form action="<?=JRoute::_('index.php?option=com_virtuemart&view=category&limitstart=0&virtuemart_category_id=' . $this->category->virtuemart_category_id); ?>" method="get">
        <!--BEGIN Search Box -->
        <div class="virtuemart_search">
            <?=$this->searchcustom?>
            <br/>
            <?=$this->searchcustomvalues?>
            <input name="keyword" class="inputbox" type="text" size="20" value="<?=$this->keyword ?>"/>
            <input type="submit" value="<?=JText::_ ('COM_VIRTUEMART_SEARCH')?>" class="button" onclick="this.form.keyword.focus();"/>
        </div>
        <input type="hidden" name="search" value="true"/>
        <input type="hidden" name="view" value="category"/>
    </form>
    <!-- End Search Box -->
<?php endif; //var_dump($this); die();

//commonDebug(__FILE__,__LINE__,JRequest::get('get'));
//
// here all rock & roll begins! Yo.
if (!empty($this->products)) {
    // array => object
    foreach($this->products as $i=>$product){
        //if($product->virtuemart_product_id=='3890') commonDebug(__FILE__,__LINE__,$product);
        // if SEF has been switched off, returns just the same as gets:
        //$product->link=HTML::setDetailedLink($product,'shop');
        require 'product_box.php';
    }

}elseif ($this->search !== NULL) {
    echo JText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}?>
</div>
<?php HTML::setVmPagination()?>
<script>
    jQuery(function($){
        $('div.box >div:first-child >div').on('click', function(){
            var img=$(this).parents('.box').find('img')[0],
                img_id=$(this).attr('data-img-id');
            $.get(
                "<?php echo JURI::base();?>index.php?option=com_auction2013&task=auction2013.getImg",
                {
                    image_id:img_id
                },
                function(data){
                    $(img).attr('src', '<?php   echo JURI::base();?>images/stories/virtuemart/product/preview/'+data); //console.log('src = '+$(img).attr('src'));
                });
        });
    }(jQuery));
</script>