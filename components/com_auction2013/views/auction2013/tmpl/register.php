<?
defined('_JEXEC') or die('Restricted access');
// die('pageclass_sfx')?>
<div class="item-page<?php echo $this->params->pageclass_sfx?>">
<?	if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<h2 class="title thinBrownHeader">Регистрация</h2>
<?
// выведем статью "Предложить предмет":
$article=AuctionStuff::getArticleContent(19);	
echo $article['introtext'];?>
<form id="registration_form" method="post" action="http://auction.auction-ruseasons.ru/register.php?b=1">
		<div class="divider"></div>
	<?=AuctionStuff::sreateForm()?>
		<div>
		<br>Регистрируясь на нашем сайте, Вы принимаете наши <a href="index.php?a=28&amp;b=147">Правила</a>.
		</div>
		<div align="center">
			<input type="submit" class="button" value="Зарегистрироваться" name="submit">
		</div>
	</form>
</div>