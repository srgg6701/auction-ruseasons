<?
defined('_JEXEC') or die('Restricted access');?>
<div id="thanx-page">
<?	if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif;

// выведем статью "Предложить предмет":
$article=AuctionStuff::getArticleContent(20);	
// var_dump($article);	
echo $article['introtext']; ?>
</div>