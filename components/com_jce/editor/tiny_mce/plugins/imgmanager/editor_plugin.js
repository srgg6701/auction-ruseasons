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
(function(){tinymce.create('tinymce.plugins.ImageManager',{init:function(ed,url){this.editor=ed;function isMceItem(n){return/mceItem/.test(n.className);};ed.addCommand('mceImageManager',function(){var n=ed.selection.getNode();if(n.nodeName=='IMG'&&isMceItem(n)){return;}
ed.windowManager.open({file:ed.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=imgmanager',width:780+ed.getLang('imgmanager.delta_width',0),height:640+ed.getLang('imgmanager.delta_height',0),inline:1,popup_css:false,size:'large-landscape'},{plugin_url:url});});ed.addButton('imgmanager',{title:'imgmanager.desc',cmd:'mceImageManager'});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('imgmanager',n.nodeName=='IMG'&&!isMceItem(n));});ed.onInit.add(function(){if(ed&&ed.plugins.contextmenu){ed.plugins.contextmenu.onContextMenu.add(function(th,m,e){m.add({title:'imgmanager.desc',icon:'imgmanager',cmd:'mceImageManager'});});}});},insertUploadedFile:function(o){var ed=this.editor;if(/\.(gif|png|jpeg|jpg)$/.test(o.file)){var args={'src':o.file,'alt':o.alt||o.name,'style':{}};var attribs=['alt','title','id','dir','class','usemap','style','longdesc'];if(o.styles){var s=ed.dom.parseStyle(ed.dom.serializeStyle(o.styles));tinymce.extend(args.style,s);delete o.styles;}
if(o.style){var s=ed.dom.parseStyle(o.style);tinymce.extend(args.style,s);delete o.style;}
tinymce.each(attribs,function(k){if(typeof o[k]!=='undefined'){args[k]=o[k];}});return ed.dom.create('img',args);}
return false;},getUploadURL:function(file){if(/image\/(gif|png|jpeg|jpg)/.test(file.type)){return this.editor.getParam('site_url')+'index.php?option=com_jce&view=editor&layout=plugin&plugin=imgmanager';}
return false;},getInfo:function(){return{longname:'Image Manager',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net/index2.php?option=com_content&amp;task=findkey&amp;pop=1&amp;lang=en&amp;keyref=imgmanager.about',version:'2.3.1'};}});tinymce.PluginManager.add('imgmanager',tinymce.plugins.ImageManager);})();