<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
//include_once JPATH_SITE.DS.'tests.php';
//commonDebug(__FILE__,__LINE__,$this->pagination, false);?>
<div class="results-count-block"><?php
    $cnt = 'Всего найдено лотов: ';
    $cnt.=($this->pagination->total)? $this->pagination->total:'0';
    $cnt.= ' &nbsp; <a id="link-manage-search" href="javascript:void(0)" onclick="manageAdvancedSearch()">[<span>расширенный поиск</span><span style="display: none">результаты поиска</span>]</a>';
    echo $cnt;?></div>
<div class="pagination" id="pagination-search-result-1">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>
<dl class="search-results<?php echo $this->pageclass_sfx; ?>" id="search-results-block">
<?php foreach($this->results as $result) : ?>
	<dt class="result-title">
		<?php //echo $this->pagination->limitstart + $result->count.'. ';?>
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
                <span>Цена</span>
            </div></a>
		<?php else:?>
			<?php echo $this->escape($result->title);?>
		<?php endif; ?>
	</dt>
	<?php /*if ($result->section) : ?>
		<dd class="result-category">
			<span class="small<?php echo $this->pageclass_sfx; ?>">
				(<?php echo $this->escape($result->section); ?>)
			</span>
		</dd>
	<?php endif; ?>
	<dd class="result-text">
		<?php echo $result->text; ?>
	</dd>
	<?php if ($this->params->get('show_date')) : ?>
		<dd class="result-created<?php echo $this->pageclass_sfx; ?>">
			<?php echo JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created); ?>
		</dd>
	<?php endif; */?>
<?php endforeach; ?>
</dl>
<div class="results-count-block"><?php echo $cnt;?></div>
<div class="pagination" id="pagination-search-result-2">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<script>
function manageAdvancedSearch(){
    var $=jQuery;
    // searchForm // search-results-block
    $('#searchForm').fadeToggle(200, function(){
        $('#link-manage-search span').toggle();
        $('#pagination-search-result-2').toggle();
    });
    $('#search-results-block').fadeToggle(200);
    $('div.results-count-block').eq(1).fadeToggle();
}
</script>
