<?php	
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD 
 * @author srgg6701
 */
/** 
 * this: VirtuemartViewCategory
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
// commonDebug(__FILE__,__LINE__,JRequest::get('get'), true);
//commonDebug(__FILE__,__LINE__, JRequest::get('get'));
$Itemid = JRequest::getVar('Itemid');
HTML::pageHead('online');
if(JRequest::getVar('spag'))
	var_dump($this->vmPagination); ?>
<div class="item-page-shop">
<br>
<?php if (empty($this->keyword)):
        if($this->category->category_description):?>
	<div class="category_description"><?=$this->category->category_description; 
	// проверить, включено ли отображение категории (для теста):
	//echo 'showCategory: '.VmConfig::get ('showCategory', 1);?>
	</div>
<?php   endif;
      endif;

if ( VmConfig::get ('showCategory', 1) && 
	 empty($this->keyword)
   ) :
	
	$show_children=false;
	if($show_children){
		if ($this->category->haschildren) :
			echo '<h1>Has children! Do something, Dude...</h1>';?>
		<div>
		<?php foreach ($this->category->children as $category) :
				// Category Link
                // $url, $xhtml = true, $ssl = null, $show = false, $test=false
                echo "<div>url: ".JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' .
                        $category->virtuemart_category_id, true, null, false, true)."</div>";

                $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id);
				// Show Category?>
				<h2><a href="<?=$caturl?>" title="<?=$category->category_name?>"><?=$category->category_name?></a>
					<div class="img" title="<?=$category->category_name?>">
						<a href="<?=$caturl?>"><?=$category->images[0]->displayMediaThumb ("", FALSE)?></a>
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
<?php endif;

// here all rock & roll begins! Yo.
if (!empty($this->products)) {
        //echo "<h3>this->products:</h3><pre>";var_dump($this->products);echo "</pre>"; // die();
	// array => object
	foreach($this->products as $i=>$product){
        // if SEF has been switched off, returns just the same as gets:
        //$product->link=HTML::setDetailedLink($product,'shop');
        require 'partials/product_box.php';
    }

}elseif ($this->search !== NULL) {
	echo JText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}?>
</div>
<?php HTML::setVmPagination();
 // todo: убрать закомментированный код
 /*?>
<script>
(function($){
    $('span.lot').on({
        'mouseenter': function(){
            this.title = "Сделать ставку";
        },
        'click':function(){
<?php   if(JFactory::getUser()->guest!=1):
?>
            location.href="?option=com_virtuemart&view=productdetails&virtuemart_category_id=<?php
                    echo $this->category->parents[0]->virtuemart_category_id;
                    ?>&Itemid=<?php
                    echo $Itemid;
                    ?>&virtuemart_product_id="+$(this).attr('data-product_id')+"&do=bid";
<?php   else:
?>
            alert('Чтобы сделать ставку, вам необходимо заавторизоваться.');
<?php   endif;
?>
        }
    });
})(jQuery);
</script><?php */ ?>