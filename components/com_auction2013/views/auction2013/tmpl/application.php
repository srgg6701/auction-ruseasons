<?
defined('_JEXEC') or die('Restricted access');
// die('pageclass_sfx')
$virtuemart_product_id=JRequest::getVar('virtuemart_product_id');
$menu_itemid=JRequest::getVar('menu_itemid'); // comes from button "Купить"
//var_dump(JRequest::get('get')); 
// die($menu_itemid);
/*	array
  'option' => string 'com_auction2013' (length=15)
  'layout' => string 'application' (length=11)
  'virtuemart_product_id' => string '1' (length=1)
  'menu_itemid' => string '115' (length=3)
  'Itemid' => null
*/

// Получить данные предмета:
$product=AuctionStuff::getSingleProductData(
						$virtuemart_product_id,
						'p_ru_ru.product_name, p_prices.product_price'
					);	
$price=round((int)$product['product_price']);?>
<div class="item-page<?php echo $this->params->pageclass_sfx?>">
<?
if($Itemid=JRequest::getVar('thanx_menu_id')){
	// var_dump($this); die();
if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; 

$category_id=AuctionStuff::getCategoryIdByProductId($virtuemart_product_id);?>

<h2 class="title thinBrownHeader">Ваша заявка принята!</h2>
<p>Название предмета:  <? echo $product['product_name'];?></p>
<p>Цена: <?
	echo $price;?> <?=AuctionStuff::getProductCurrency($virtuemart_product_id)?>.
<br><br>
<div align="center"><b class="txtBrown"><?

$article=AuctionStuff::getArticleContent(25);	
echo $article['introtext'];?></b>
<br><br>
<button type="button" class="buttonSandCool txtBrown" onclick="location.href='index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=<?=$virtuemart_product_id?>&virtuemart_category_id=<?=$category_id?>&Itemid=<?=$Itemid?>'">Назад к предмету</button> 
&nbsp;
<?	$menu = JFactory::getApplication()->getMenu();
	$menus = $menu->getMenu();
	$top_layout=$menus[$Itemid]->query['layout']; ?>
<button type="button" class="buttonSandCool txtBrown" onclick="location.href='index.php?option=com_virtuemart&view=category&virtuemart_category_id=0&layout=<?=$top_layout?>&Itemid=<?=$Itemid?>'">Назад в категорию</button>
</div>
<br><br>
<?

}else{
$user = JFactory::getUser();
if($user->guest):
	$session = JFactory::getSession();
	$session->set('product_id_purchasing',$virtuemart_product_id);
	JFactory::getApplication()->redirect('index.php?option=com_users&view=login');
endif;

if ($this->params->get('show_page_heading')) : 
	
	?>
</p>
<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<h2 class="title thinBrownHeader">Оформление заявки на покупку</h2>
<?
// var_dump($product); 
/*	array
	'product_name' => string '10 розеток «Цветы»' (length=32)
	'product_price' => string '1000.00000' (length=10)
*/

echo $product['product_name'];

$article=AuctionStuff::getArticleContent(24);	
echo $article['introtext'];
?>
<br>
<br>
	<div align="center">Цена лота: <b><?
	echo $price;?></b> <?=AuctionStuff::getProductCurrency($virtuemart_product_id)?>.
<br>
<br>
	  <form id="registration_form" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.sendApplication'); ?>" method="post" class="form-validate">
        	<input type="hidden" name="option" value="com_auction2013" />
			<input type="hidden" name="task" value="auction2013.sendApplication" />
            <input type="hidden" name="application_type" value="purchasing" />
		
            <input type="hidden" name="product_id" value="<?=$virtuemart_product_id?>" />
        	<input type="hidden" name="menu_itemid" value="<?=$menu_itemid?>" />
            <input type="hidden" name="price" value="<?=$price?>" />
            
            <input type="hidden" name="product_name" value="<?=$product['product_name']?>" />
		<?php echo JHtml::_('form.token');?>        

			<button type="submit" class="buttonSandCool">Купить</button>
	<br>
			<button type="button" class="buttonSandCool txtBrown" onclick="history.back(1);">Назад</button>
		</form>
    </div>
</div>
<?
}
