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
var ColorPicker={settings:{},init:function(){var self=this,ed=tinyMCEPopup.editor,color=tinyMCEPopup.getWindowArg('input_color')||'#FFFFFF';$('#tmp_color').val(color).colorpicker($.extend(this.settings,{dialog:true,insert:function(){return ColorPicker.insert();},close:function(){return tinyMCEPopup.close();}}));$('button#insert').button({icons:{primary:'ui-icon-check'}});$('#jce').css('display','block');},insert:function(){var color=$("#colorpicker_color").val(),f=tinyMCEPopup.getWindowArg('func');tinyMCEPopup.restoreSelection();if(f)
f(color);tinyMCEPopup.close();}};tinyMCEPopup.onInit.add(ColorPicker.init,ColorPicker);