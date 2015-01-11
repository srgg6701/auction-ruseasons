<?php
defined('_JEXEC') or die('Restricted access'); // no direct access
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php'; 
$document = isset($this) ? $this : null;
$baseUrl = $this->baseurl;
$templateUrl = $this->baseurl . '/templates/' . $this->template;
//artxComponentWrapper($document);
$document->title=str_replace("&laquo;","«",$document->title);
$document->title=str_replace("&raquo;","»",$document->title);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="icon" type="image/png" href="favicon.png" />
    <script src="<?=$templateUrl?>/js/jquery-1.7.1.min.js"></script>
<script src="<?=$templateUrl?>/js/jquery-ui-1.8.18.custom.min.js"></script>
<jdoc:include type="head" />
<?php
    if(!JRequest::getVar('less')):?>
<link href="<?php echo $templateUrl; ?>/css/default.css" rel="stylesheet" type="text/css" />
<?php
    else:?>
<link href="<?php echo $templateUrl; ?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $templateUrl; ?>/css/mobile/default.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $templateUrl; ?>/css/mobile/screens.css" rel="stylesheet" type="text/css" />
<?php
endif;
require_once JPATH_LIBRARIES . '/joomla/environment/browser.php';
//C:\WebServers\home\localhost\www\auction-ruseasons\libraries\joomla\environment\browser.php
if (JBrowser::getInstance()->getBrowser()=='mozilla'):?>
    <link href="<?php echo $templateUrl; ?>/css/firefox.css" rel="stylesheet" type="text/css" />
<?php
endif;
//$document->addStyleSheet($templateUrl . '/css/firefox.css' );
?>
<link href="administrator/components/com_auction2013/system-xtra.css" rel="stylesheet" type="text/css">

    <!--[if lte IE 6]>
	<script src="<?php echo $templateUrl; ?>/js/DD_belatedPNG.js"></script>
	<script> DD_belatedPNG.fix('*');</script>		
    <link type="text/css" rel="stylesheet" href="<?php echo $templateUrl; ?>/css/styleIE6.css" />
<![endif]-->
<!--
<script type="text/javascript" src="<?php echo $templateUrl; ?>/js/mootools.js"></script>
<script type="text/javascript" src="<?php echo $templateUrl; ?>/js/menu.js"></script>-->
<?php if(JRequest::getVar('option')!=='com_component'):?> 
<!-- Not com_content, include exmplicitly: -->
  <script src="<?=$baseUrl?>/media/system/js/mootools-core.js" type="text/javascript"></script>
  <script src="<?=$baseUrl?>/media/system/js/core.js" type="text/javascript"></script>
  <script src="<?=$baseUrl?>/media/system/js/caption.js" type="text/javascript"></script>
  <script type="text/javascript">
window.addEvent('load', function() {
				new JCaption('img.caption');
			});
  </script>
<?php endif;
$dev_server = strstr($_SERVER['HTTP_HOST'],"localhost");
/*if(!$dev_server){
    ?>
<script type="text/javascript">
<!--
window.addEvent('domready', function() {
	function jsClock24hr(){
		var time = new Date();
		var hour = time.getHours();
		var minute = time.getMinutes();
		var second = time.getSeconds();
		var temp = "" + ((hour < 10) ? "0" : "") + hour;
		temp += ((minute < 10) ? ":0" : ":") + minute;
		temp += ((second < 10) ? ":0" : ":") + second;
		document.getElementById('clock').innerHTML=temp;				
	}
	setInterval(jsClock24hr,1000);
	
	if (window.navigator.userAgent.indexOf ("MSIE") >= 0)
    {
		if(parseInt(window.navigator.userAgent.substr(window.navigator.userAgent.indexOf("MSIE")+5,3))==6)
				{
					$$('#main_menu li').addEvent('mouseenter', function(){	
							this.addClass('hover'); 
								   });
								 
								 $$('#main_menu li').addEvent('mouseleave', function(){
									   $$('#main_menu li').removeClass("hover");
									   });
				}
    }
});
-->
</script>
<?php }*/?>
<script src="<?=$templateUrl?>/js/common.js"></script>
<?php /*?>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<?php */
    if(!$dev_server){?>
<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<?php }?>
<div style="display:none;"><script type="text/javascript">
try { var yaCounter1106646 = new Ya.Metrika(1106646); } catch(e){}
</script></div>
<noscript><div style="position:absolute"><img src="//mc.yandex.ru/watch/1106646" alt="" /></div></noscript>
<?php // СПЕЦИАЛЬНО ДЛЯ АЦЦКОГО FF:
	if(strstr($_SERVER['HTTP_USER_AGENT'],'Firefox')):
	// hello, community! 
	// Thank you for the inventation to the HELL:?>
<script>
$( function(){
	var parentLI=$('div#main_menu ul.menu li[class^="item"]');
	$(parentLI)
		.css('position','relative')
			.children('ul')
				.css({
			background: '#417337',
			borderTop: '1px solid #e9e4bc',
			display: 'none',
			left: '0',
			position: 'absolute',
			width: 'auto',
			zIndex: 2	
		});
	$(parentLI)
		.mouseenter( function() {
			var childUL=$(this).children('ul');
			var thisOffLeft=$(this).offset().left;
			var thisHalfWidth=$(this).width()/2;
			$(childUL).css({
				display:'block',
				top:$(this).offset().top+4+'px',
				// дебильный FF считает от края НЕ страницы, а div#page почему-то...
				left:thisOffLeft-$('div#page').offset().left+'px'
			});
			//console.info('left: '+$(this).offset().left+', ul.left: '+$(childUL).css('left')+'\noffsetPage: '+$('div#page').offset().left);
		});
	$(parentLI)
		.mouseleave( function(){
			$(this).children('ul').css('display','none');
		});
});
</script>
<?php endif;?>
</head>
<body>
<?php   if(JRequest::getVar('rulers')):?>
<style>
    #rulers-horizontal, #rulers-vertical{
        background-color: black;
        cursor: move;
        opacity: 0.5;
        position: absolute;
        z-index: 10;
    }
    #rulers-horizontal{
        height: 35px;
        top:35px;
        width: 100%;
    }
    #rulers-vertical{
        height: 100%;
        left: 35px;
        width: 35px;
    }
</style>
<script>
    jQuery(function(){
        var $=jQuery,
            diff= 0,
            attrDr='data-dragged',
            horizontal=$('#rulers-horizontal'),
            vertical=$('#rulers-vertical');
        $('body').on('mousemove', function(){
            if($(horizontal).attr(attrDr)){
                $(horizontal).css('top',(event.clientY-diff)+'px');
                //console.log('clientY:'+event.clientY+'\n');
            }
            if($(vertical).attr(attrDr)){
                //console.log('clientX:'+event.clientX);
                $(vertical).css('left',(event.clientX-diff)+'px');
            }
        });
        $(horizontal).on('mousedown mousemove mouseup',function(event){
            moveRulers(this,event,attrDr)
        });
        $(vertical).on('mousedown mousemove mouseup',function(event){
            moveRulers(this,event,attrDr)
        });
        function moveRulers(obj,event,attrDr){
            var diff;
            switch(event.type){
                case 'mousedown':
                    $(obj).attr(attrDr,1);
                    if(obj.id=='rulers-horizontal'){
                        diff=event.clientY-parseInt($(obj).css('top'));
                        //console.log('Down, diff H:'+diff);
                    }
                    if(obj.id=='rulers-vertical'){
                        diff=event.clientX-parseInt($(obj).css('left'));
                        //console.log('Down, diff: V'+diff);
                    }
                    break;
                case 'mouseup':
                    $(obj).removeAttr(attrDr);
                    //console.log('%cAttribute is removed','color:red');
                    break;
            }
        }
   });
</script>
<div id="rulers-horizontal"></div>
<div id="rulers-vertical"></div>
<?php   endif;?>
    <section id="user-go">
        <div>
            <section>
            <?php   $user = JFactory::getUser();
            if($user->guest==1):
                $link_tail="view=login";
            else:
                $link_tail="layout=lots";
            endif;?>
                <div id="authorize">
                    <a href="<?php echo JRoute::_('index.php?option=com_users&'.$link_tail);?>">Кабинет</a>
                    <?php
                    if($user->guest==1):?>/
                        <a href="<?php echo JRoute::_('index.php?option=com_auction2013&layout=register');?>">Регистрация</a>
                    <?php   endif;?>
                </div>
            </section>
        </div>
    </section>
<?php   if($dev_server) include_once 'pixel-perfect/dev.php';
?>
	<div id="page">
        <div id="header">
            <div id="pic_top">
            <?php
            function makeAuctionText($div_id,$logo_id){?>
                <div class="floatLeft" id="<?php echo $div_id;?>">
                    <a href="">
                        <div id="<?php echo $logo_id;?>">
                            <div>
                                <span id="saloon">Салон</span>
                                <span class="separator">&nbsp;</span>
                                <span>Аукцион</span>
                            </div>
                            <h1>Антикварные&nbsp;<wbr/>сезоны</h1>
                        </div>
                    </a>
                </div>
            <?php
            }
            makeAuctionText("header-left-side","logo-texts");?>
            <div class="floatLeft" id="header-right-side"><?php
                makeAuctionText("header-left-inside","logo-texts-inside");
                    ?></div>
            </div>
            	
            <div id="main_menu">
                <div id="search_box" class="floatLeft">
                    <jdoc:include type="modules" name="search" />
                </div>
                <hr class="vertical-spacer hide-from-middle"/>
                <div class="mobile_menu" id="mobile-menu-menu">Меню</div>
                <div class="mobile_menu" id="mobile-menu-products">Предметы</div>
                <jdoc:include type="modules" name="additional_menu" />
                <jdoc:include type="modules" name="user3" />
            </div>
    	</div>
        <div id="content">
		<?php $style="";?>
  	<?php $style="left "?>
  <?php $layout=JRequest::getVar('layout');
		$option=JRequest::getVar('option');
		$view=JRequest::getVar('view');
  		$hide_left_panel=false;
		//echo "<div class=''>$layout, ".$option.", $view, ".$user->guest."</div>";
  		if( $layout=='register'
			//|| $layout=='application'
		  	|| $layout=='askaboutlot'
			|| ( $option=='com_users'
			   	 && $view=='login'
				 && $user->guest
			   )
		  ): $hide_left_panel=true;
  		endif; //die('hide_left_panel= '.$hide_left_panel);
  		if ($this->countModules('left_panel')&&!$hide_left_panel): ?>
          <div id="left_part">
			<jdoc:include type="modules" name="left_panel" style="xhtml" />  
  	<?php	if ($this->countModules('left')): ?>
    		<jdoc:include type="modules" name="left" style="xhtml" />
  	<?php 	endif; ?>
          </div>
  <?php else: $style.=" leftLess";
  		endif;?>
  
  <?php if ($this->countModules('right')!= 0): ?>
   <?php $style.="right"?>
          <div id="right_part">
          	<jdoc:include type="modules" name="right_over" style="xhtml" />    
            <jdoc:include type="modules" name="right" style="xhtml" />
            <jdoc:include type="modules" name="right_under" style="xhtml" />
            <jdoc:include type="modules" name="right_bottom" style="xhtml" />
          </div>
  <?php endif; ?>
			
            <div id="main_content" class="<?php echo $style; ?> ">
                <div class="Post">
                    <div class="Post-body">
                		<jdoc:include type="component" />
                    </div>
                </div>
            </div>
        	
        </div>
        <div id="footer">
            <section>
                <div id="copyright">
                <?php if ($this->countModules('copyright') == 0): ?>
                <div class="vertically-aligned">&copy; 2010 Антикварные Сезоны</div>
      <?php else: ?>
      <jdoc:include type="modules" name="copyright" />
      <?php endif;?>
                </div>
                <div id="bottom_menu">
                    <div id="padd_bot_menu">
                        <?php // ul ?>
                        <jdoc:include type="modules" name="footer" />
                        <div id="mailru_counter">
    <?php if(!$dev_server){?>
                            <!--Rating@Mail.ru counter-->
                            <script language="javascript" type="text/javascript">//<![CDATA[
                            d=document;var a='';a+=';r='+escape(d.referrer);js=10;//]]></script>
                            <script language="javascript1.1" type="text/javascript">//<![CDATA[
                            a+=';j='+navigator.javaEnabled();js=11;//]]></script>
                            <script language="javascript1.2" type="text/javascript">//<![CDATA[
                            s=screen;a+=';s='+s.width+'*'+s.height;
                            a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth);js=12;//]]></script>
                            <script language="javascript1.3" type="text/javascript">//<![CDATA[
                            js=13;//]]></script><script language="javascript" type="text/javascript">//<![CDATA[
                            d.write('<a href="http://top.mail.ru/jump?from=1859529" target="_top">'+
                            '<img src="http://df.c5.bc.a1.top.mail.ru/counter?id=1859529;t=85;js='+js+
                            a+';rand='+Math.random()+'" alt="Рейтинг@Mail.ru" border="0" '+
                            'height="18" width="88" \/><\/a>');if(11<js)d.write('<'+'!-- ');//]]></script>
                            <noscript><a target="_top" href="http://top.mail.ru/jump?from=1859529">
                            <img src="http://df.c5.bc.a1.top.mail.ru/counter?js=na;id=1859529;t=85"
                            height="18" width="88" border="0" alt="Рейтинг@Mail.ru" /></a></noscript>
                            <script language="javascript" type="text/javascript">//<![CDATA[
                            if(11<js)d.write('--'+'&#062');//]]></script>
                            <!--// Rating@Mail.ru counter-->
    <?php }?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php //TEST: 
	/*?>
<div id="dOutput">
<?php //showDebugTrace();?>
</div>
<?php */
	//TEST?>
<script>
jQuery( function($){
	
	//$('div#dOutput').draggable();
	$('div#dOutput').dblclick( function(){
			$(this).toggleClass('opaque');
		});
	
	$('div.testPadding')
		.mouseenter( function(){
			//alert('solid');
			$(this).css('opacity',1);
		});
	$('div.testPadding')
		.mouseleave( function(){
			//alert('solid');
			if (!$(this).hasClass('solid'))
				$(this).css('opacity',0.2);
		});
	
	$('div.testPadding')
		.click( function(){
				$(this).toggleClass('solid');
				//.fadeToggle(500);
			});
	var menuHdrs=$('.menuH');
	$(menuHdrs)
		.click( function(){
				$(this).toggleClass('menuH2');
			});
	// for test blocks
	$('span.link, ').on('dblclick',function(event){
		var innerBlock = $(this).next('div.test-box');
		event.stopPropagation();
		if(!$(innerBlock).hasClass('bottom')) {
			$(innerBlock).toggle();
		}
	});
<?php   if($dev_server):?>
    //console.log(window.outerWidth);
    window.onresize=function(){
        document.title = window.outerWidth;
    };
    var sel_name_sbstr = '[src*="metabar.ru"]';
    var intv = setInterval(function() {
        var metabars = document.querySelectorAll(sel_name_sbstr);
        if (metabars.length) {
            for (var el in metabars) {
                if (typeof metabars[el] == 'object') {
                    metabars[el].remove();
                }
            }
        } else
            clearInterval(intv);
    }, 1000);
<?php   endif;?>
});
</script>
</div>
</body>
</html>