<?php   defined('_JEXEC') or die('Restricted access');
	    // включить заголовок в параметрах ссылки:
        if ($this->params->get('show_page_heading')) :
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php   endif;
/*
<p><span style="float: left; width: 105px;"><a href="documents/Russians_seasones_auction_01.jpg"></a><a href="images/illustrations/Russians_seasones_auction_01.jpg"><img src="documents/preview_Russians_seasones_auction_01.jpg" border="0" alt="Антиквариат Каталог №1" width="100" height="142" /></a></span>
</p>
<p>Аукцион №1<br />14.11.2010<br /><a href="documents/Russians_seasones_auction_01.pdf">Скачать КАТАЛОГ в PDF формате</a>
</p>
<p><a href="documents/russians_seasones_auction_01.doc">Результаты торгов</a>
</p>
<div style="min-height: 32px; border-bottom: 1px solid; padding-top: 10px;"><a href="../index.php?a=5&amp;b=234&amp;search=1&amp;num_auction=%B91">Проданные лоты - Аукцион №1</a>
</div>*/
// var_dump($article);	
HTML::showClosedAuctions();
//echo $article['introtext']; ?>
