<?php
defined('_JEXEC') or die('Restricted access'); // no direct access
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php';
$document = isset($this) ? $this : null;
$baseUrl = $this->baseurl;
$templateUrl = $this->baseurl . '/templates/' . $this->template;
artxComponentWrapper($document);
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
</head>

<body>
	<div id="page">
    	<div id="header">   
        	<div id="clock">
            </div>
        	<a href="/" id="logo"></a> 
            <div id="main_menu">
            	<jdoc:include type="modules" name="user3" />
            </div>   
<div style="position:absolute;float:right;top:10px; right:30px;"><jdoc:include type="modules" name="search" /></div>
			
        </div>
        <div id="content">
		<?php $style="";?>
						<?php if (artxCountModules($document, 'left') == 0): ?>
     
  <?php else: ?>
  <?php $style="left "?>
  <div id="left_part">
            	<?php echo artxModules($document, 'left', 'left'); ?>
  </div>
  <?php endif; ?>
		
			

			<?php if (artxCountModules($document, 'right') == 0): ?>
     
  <?php else: ?>
   <?php $style.="right"?>
            <div id="right_part">
            	<?php echo artxModules($document, 'right', 'right'); ?>
            </div>
  <?php endif; ?>
			
            <div id="main_content" class="<?php echo $style; ?> ">
            	<jdoc:include type="component" />
            </div>
        </div>
        <div id="footer">
        	
        	<div id="bottom_menu">
            	<jdoc:include type="modules" name="footer" />
            </div>
            <div id="copyright">
			<?php if (artxCountModules($document, 'copyright') == 0): ?>
&copy; 2010 Русские Сезоны
  <?php else: ?>
  <jdoc:include type="modules" name="copyright" />
  <?php endif; ?>
			</div>
        </div>
    </div>
</body>
</html>
