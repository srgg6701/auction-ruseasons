<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

if ($this->user->get('guest')):
    if($message=JRequest::getVar('message')):
        if($message=='login_to_buy'):
    ?><br/><br/>
        <div class="info_area">
            Для того, чтобы купить предмет, вам необходимо заавторизоваться
            или <a href="<?php echo JRoute::_("index.php?option=com_auction2013&layout=register")?>">зарегистрироваться</a>.
        </div><br/>
    <?php
        endif;
    endif;
	// The user is not logged in.?><div id="login_area"><?php echo $this->loadTemplate('login');?></div><?php else:
	// The user is already logged in.
	echo $this->loadTemplate('logout');
endif;?>
<script>
$( function(){
	$('input').attr('autocomplete','off');
	$('div#login_area ul li a:last-child')
		.click( function(){
			$(this).attr('href','<?=JRoute::_('index.php?option=com_auction2013&layout=register')?>');
		});
});
//
</script>
<?php 