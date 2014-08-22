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
(function($){$.jce.Parameter={add:function(element,classname){$(document).ready(function($){var div='div.'+classname;$(div,$(element).parent()).hide();$(div+'[data-type="'+$(element).val()+'"]',$(element).parent()).show().find(':input').removeAttr('disabled');$(element).change(function(){$(div,$(this).parent()).hide();$(div+'[data-type="'+$(this).val()+'"]',$(this).parent()).show().find(':input').removeAttr('disabled');});});}};})(jQuery);