<?php   defined('_JEXEC') or die('Restricted access');
	    // включить заголовок в параметрах ссылки в админке!
        if ($this->params->get('show_page_heading')) :
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<br/>
<?php   endif;

    HTML::showClosedAuctions();?>
