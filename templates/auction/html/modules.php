<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

function modChrome_artblock($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>
<div class="Block">
    <div class="Block-tl"></div>
    <div class="Block-tr"></div>
    <div class="Block-bl"></div>
    <div class="Block-br"></div>
    <div class="Block-tc"></div>
    <div class="Block-bc"></div>
    <div class="Block-cl"></div>
    <div class="Block-cr"></div>
    <div class="Block-cc"></div>
    <div class="Block-body">

<?php if ($module->showtitle != 0) : ?>
<div class="BlockHeader">
    <div class="l"></div>
    <div class="r"></div>
    <div class="header-tag-icon">
        <div class="t">
<?php echo $module->title; ?>
</div>
    </div>
</div>
<?php endif; ?>
<div class="BlockContent">
    <div class="BlockContent-tl"></div>
    <div class="BlockContent-tr"></div>
    <div class="BlockContent-bl"></div>
    <div class="BlockContent-br"></div>
    <div class="BlockContent-tc"></div>
    <div class="BlockContent-bc"></div>
    <div class="BlockContent-cl"></div>
    <div class="BlockContent-cr"></div>
    <div class="BlockContent-cc"></div>
    <div class="BlockContent-body">

<?php echo $module->content; ?>

    </div>
</div>


    </div>
</div>

<?php endif;
}

function modChrome_artpost($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>
<div class="Post">
    <div class="Post-body">
<div class="Post-inner">

<?php if ($module->showtitle != 0) : ?>
<h2 class="PostHeaderIcon-wrapper"> <span class="PostHeader">
<?php echo $module->title; ?>
</span>
</h2>

<?php endif; ?>
<div class="PostContent">

<?php echo $module->content; ?>

</div>
<div class="cleared"></div>


</div>

    </div>
</div>

<?php endif;
}


function modChrome_grayblock($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>

<?php if ($module->showtitle != 0) : ?>
<h1><span><?php echo $module->title; ?></span></h1>
<?php endif; ?>


<div class="big_block_top">
                	<div class="big_block_bottom">                    	
                    	<div class="dig_block_right">
                    	<div class="big_block">  
                  <?php echo $module->content; ?>
			<div class="clear"></div>
 </div>
                        </div>
                    </div>
                </div>

<?php endif;
}


function modChrome_left($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>

<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3>
<?php endif; ?>
 <div id="left_menu">
                  <?php echo $module->content; ?>
 </div>
              
<?php endif;
}

function modChrome_right($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>

<?php if ($module->showtitle != 0) : ?>
<h3><span><?php echo $module->title; ?></span></h3>
<?php endif; ?>
 <div>
                  <?php echo $module->content; ?>
 </div>
              
<?php endif;
}