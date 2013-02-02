<?php
defined('_JEXEC') or die('Restricted access'); // no direct access
// require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php'; 
$document = isset($this) ? $this : null;
$baseUrl = $this->baseurl;
$templateUrl = $this->baseurl . '/templates/' . $this->template;
//artxComponentWrapper($document);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<jdoc:include type="head" />
<link href="<?php echo $templateUrl; ?>/css/style.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
	<script src="<?php echo $templateUrl; ?>/js/DD_belatedPNG.js"></script>
	<script> DD_belatedPNG.fix('*');</script>		
    <link type="text/css" rel="stylesheet" href="<?php echo $templateUrl; ?>/css/styleIE6.css" />
<![endif]-->
<script type="text/javascript" src="<?php echo $templateUrl; ?>/js/mootools.js"></script>
<script type="text/javascript" src="<?php echo $templateUrl; ?>/js/menu.js"></script>

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
<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<div style="display:none;"><script type="text/javascript">
try { var yaCounter1106646 = new Ya.Metrika(1106646); } catch(e){}
</script></div>
<noscript><div style="position:absolute"><img src="//mc.yandex.ru/watch/1106646" alt="" /></div></noscript>
<!-- /Yandex.Metrika -->
<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<div style="display:none;"><script type="text/javascript">
try { var yaCounter1061964 = new Ya.Metrika(1061964); } catch(e){}
</script></div>
<noscript><div style="position:absolute"><img src="//mc.yandex.ru/watch/1061964" alt="" /></div></noscript>
<!-- /Yandex.Metrika -->
</head>

<body>
	<div id="page">
	
	    	<div id="header"> 
<div id="pic_top"></div>			
           <div id="main_menu">
            	<jdoc:include type="modules" name="user3" />
            </div>   
<div style="position:absolute;float:right;top:10px; right:30px;"><jdoc:include type="modules" name="search" /></div>
            <div id="clock">
            </div>  
            <a href="#" id="logo_img"><img src="<?php echo $templateUrl; ?>/images/logo_img.png" width="234" height="243" alt="" /></a>
            <a href="#" id="logo_text"><img src="<?php echo $templateUrl; ?>/images/logo_text.png" width="266" height="50" alt="" /></a>
        </div>
        <div id="content">
		<?php $style="";?>
						<?php if ($this->countModules('left') == 0): ?>
     
  <?php else: ?>
  <?php $style="left "?>
  <div id="left_part">
  	<jdoc:include type="modules" name="left" style="xhtml" />    

            	<?php // echo artxModules($document, 'left', 'left'); ?>
  </div>
  <?php endif; ?>
		
			

			<?php if ($this->countModules('right') == 0): ?>
     
  <?php else: ?>
   <?php $style.="right"?>
            <div id="right_part">
              	<jdoc:include type="modules" name="right" style="xhtml" />    

            	<?php // echo artxModules($document, 'right', 'right'); ?>
            </div>
  <?php endif; ?>
			
            <div id="main_content" class="<?php echo $style; ?> ">
            	<jdoc:include type="component" />
            </div>
        </div>
        <div id="footer">
        	
        	<div id="bottom_menu">
			<div id="padd_bot_menu">
            	<jdoc:include type="modules" name="footer" />
			
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
</div>	
            </div>
            <div id="copyright">
			<?php if ($this->countModules('copyright') == 0): ?>
&copy; 2010 Русские Сезоны
  <?php else: ?>
  <jdoc:include type="modules" name="copyright" />
  <?php endif; ?>
			</div>
        </div>
    </div>
</body>
</html>