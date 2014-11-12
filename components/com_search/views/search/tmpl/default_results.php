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
//commonDebug(__FILE__,__LINE__,$this->results, false);?>
<div class="results-count-block"><?php
    $cnt = 'Всего найдено лотов: ';
    $cnt.=(count($this->results))?
        count($this->results):'0';
    echo $cnt;?></div>
<dl class="search-results<?php echo $this->pageclass_sfx; ?>">
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
                $link_text = str_replace("&raquo;","»",$link_text);
                echo $this->escape($link_text);?></a>
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
<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
