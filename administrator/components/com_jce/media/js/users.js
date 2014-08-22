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
(function($){$.jce.Users={select:function(){var u=[],v,o,h,s=window.parent.document.getElementById('users');$('input:checkbox:checked').each(function(){v=$(this).val();if(u=document.getElementById('username_'+v)){h=$.trim(u.innerHTML);if($.jce.Users.check(s,v)){return;}
var li=document.createElement('li');li.innerHTML='<input type="hidden" name="users[]" value="'+v+'" /><label><span class="users-list-delete"></span>'+h+'</label>';s.appendChild(li);}});this.close();},check:function(s,v){$.each(s.childNodes,function(i,n){var input=n.firstChild;if(input.value===v){return true;}});return false;},close:function(){var win=window.parent;if(typeof win.SqueezeBox!=='undefined'){win.SqueezeBox.close();}}};$(document).ready(function(){$('#cancel').button({icons:{primary:'ui-icon-close'}}).click(function(e){$.jce.Users.close();e.preventDefault();});$('#select').button({icons:{primary:'ui-icon-check'}}).click(function(e){$.jce.Users.select();e.preventDefault();});});})(jQuery);