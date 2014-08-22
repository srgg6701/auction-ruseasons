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
(function(){tinymce.create("tinymce.plugins.Directionality",{init:function(b,c){var d=this;d.editor=b;function a(e){var h=b.dom,g,f=b.selection.getSelectedBlocks();if(f.length){g=h.getAttrib(f[0],"dir");tinymce.each(f,function(i){if(!h.getParent(i.parentNode,"*[dir='"+e+"']",h.getRoot())){if(g!=e){h.setAttrib(i,"dir",e)}else{h.setAttrib(i,"dir",null)}}});b.nodeChanged()}}b.addCommand("mceDirectionLTR",function(){a("ltr")});b.addCommand("mceDirectionRTL",function(){a("rtl")});b.addButton("ltr",{title:"directionality.ltr_desc",cmd:"mceDirectionLTR"});b.addButton("rtl",{title:"directionality.rtl_desc",cmd:"mceDirectionRTL"});b.onNodeChange.add(d._nodeChange,d)},getInfo:function(){return{longname:"Directionality",author:"Moxiecode Systems AB",authorurl:"http://tinymce.moxiecode.com",infourl:"http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/directionality",version:tinymce.majorVersion+"."+tinymce.minorVersion}},_nodeChange:function(b,a,e){var d=b.dom,c;e=d.getParent(e,d.isBlock);if(!e){a.setDisabled("ltr",1);a.setDisabled("rtl",1);return}c=d.getAttrib(e,"dir");a.setActive("ltr",c=="ltr");a.setDisabled("ltr",0);a.setActive("rtl",c=="rtl");a.setDisabled("rtl",0)}});tinymce.PluginManager.add("directionality",tinymce.plugins.Directionality)})();