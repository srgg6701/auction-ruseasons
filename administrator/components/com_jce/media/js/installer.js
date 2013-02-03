/*  
 * JCE Editor                 2.3.1
 * @package                 JCE
 * @url                     http://www.joomlacontenteditor.net
 * @copyright               Copyright (C) 2006 - 2012 Ryan Demmer. All rights reserved
 * @license                 GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * @date                    10 December 2012
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * NOTE : Javascript files have been compressed for speed and can be uncompressed using http://jsbeautifier.org/
 */
(function($){Joomla.submitbutton=submitbutton=function(button){try{Joomla.submitform(button);}catch(e){submitform(button);}};$.jce.Installer={init:function(options){$(":file").upload(options);if($('body').hasClass('ui-bootstrap')){$('#tabs ul li a').click(function(e){e.preventDefault();$(this).tab('show');});}else{$('#tabs').tabs();$('button#upload_button').button({icons:{primary:'icon-install'}});$('#upload_button_container button').button({icons:{primary:'icon-browse'}});}
var n=$('#tabs-plugins, #tabs-extensions, #tabs-languages, #tabs-related').find('input[type="checkbox"]');$(n).click(function(){$('input[name="boxchecked"]').val($(n).filter(':checked').length);});$('#upload_button').click(function(e){$(this).addClass('loading');$('input[name="task"]').val('install');$('form[name="adminForm"]').submit();e.preventDefault();});$('button.install_uninstall').click(function(e){if($('div#tabs input:checkbox:checked').length){$(this).addClass('ui-state-loading');$('input[name="task"]').val('remove');$('form[name="adminForm"]').submit();}
e.preventDefault();});}};})(jQuery);