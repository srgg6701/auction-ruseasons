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
(function($){$.jce.Preferences={init:function(){var self=this;$('#tabs').tabs();$('#access-accordian').accordion({collapsible:true,heightStyle:"content"});$('.hasTip').removeClass('hasTip');$('input[name="task"]').val('apply');$('#apply, #save').button().click(function(){if($(this).attr('id')=='save'){$('input[name="task"]').val('save');}
$('form').submit();});$('#cancel').button().click(function(e){var win=window.parent;if(typeof win.SqueezeBox!=='undefined'){return win.SqueezeBox.close();}else{this.close();}
e.preventDefault();});},close:function(){this.init();window.setTimeout(function(){window.parent.document.location.href="index.php?option=com_jce&view=cpanel";},1000);}};})(jQuery);