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
(function(){var DOM=tinymce.DOM,Event=tinymce.dom.Event;tinymce.create('tinymce.plugins.FullScreenPlugin',{init:function(ed,url){var t=this,s={},vp,posCss;t.editor=ed;ed.onFullScreen=new tinymce.util.Dispatcher();ed.addCommand('mceFullScreen',function(){var win,de=DOM.doc.documentElement;if(ed.getParam('fullscreen_is_enabled')){DOM.win.setTimeout(function(){Event.remove(DOM.win,'resize',t.resizeFunc);tinyMCE.get(ed.getParam('fullscreen_editor_id')).setContent(ed.getContent());tinyMCE.remove(ed);DOM.remove('mce_fullscreen_container');de.style.overflow=ed.getParam('fullscreen_html_overflow');DOM.setStyle(DOM.doc.body,'overflow',ed.getParam('fullscreen_overflow'));DOM.win.scrollTo(ed.getParam('fullscreen_scrollx'),ed.getParam('fullscreen_scrolly'));tinyMCE.get(ed.getParam('fullscreen_editor_id')).onFullScreen.dispatch(false,ed.settings);},10);return;}
s.fullscreen_overflow=DOM.getStyle(DOM.doc.body,'overflow',1)||'auto';s.fullscreen_html_overflow=DOM.getStyle(de,'overflow',1);vp=DOM.getViewPort();s.fullscreen_scrollx=vp.x;s.fullscreen_scrolly=vp.y;if(tinymce.isOpera&&s.fullscreen_overflow=='visible')
s.fullscreen_overflow='auto';if(tinymce.isIE&&s.fullscreen_overflow=='scroll')
s.fullscreen_overflow='auto';if(tinymce.isIE&&(s.fullscreen_html_overflow=='visible'||s.fullscreen_html_overflow=='scroll'))
s.fullscreen_html_overflow='auto';if(s.fullscreen_overflow=='0px')
s.fullscreen_overflow='';DOM.setStyle(DOM.doc.body,'overflow','hidden');de.style.overflow='hidden';vp=DOM.getViewPort();DOM.win.scrollTo(0,0);if(tinymce.isIE)
vp.h-=1;if(tinymce.isIE6||document.compatMode=='BackCompat')
posCss='absolute;top:'+vp.y;else
posCss='fixed;top:0';n=DOM.add(DOM.doc.body,'div',{id:'mce_fullscreen_container',style:'position:'+posCss+';left:0;width:'+vp.w+'px;height:'+vp.h+'px;z-index:200000;'});DOM.add(n,'div',{id:'mce_fullscreen'});tinymce.each(ed.settings,function(v,n){s[n]=v;});s.id='mce_fullscreen';s.width=n.clientWidth;s.height=n.clientHeight-15;s.fullscreen_is_enabled=true;s.fullscreen_editor_id=ed.id;s.theme_advanced_resizing=false;s.save_onsavecallback=function(){ed.setContent(tinyMCE.get(s.id).getContent());ed.execCommand('mceSave');};tinymce.each(ed.getParam('fullscreen_settings'),function(v,k){s[k]=v;});if(s.theme_advanced_toolbar_location==='external')
s.theme_advanced_toolbar_location='top';t.fullscreenEditor=new tinymce.Editor('mce_fullscreen',s);t.fullscreenEditor.onInit.add(function(){t.fullscreenEditor.setContent(ed.getContent());t.fullscreenEditor.focus();});t.fullscreenEditor.render();t.fullscreenElement=new tinymce.dom.Element('mce_fullscreen_container');t.fullscreenElement.update();t.resizeFunc=tinymce.dom.Event.add(DOM.win,'resize',function(){var vp=tinymce.DOM.getViewPort(),fed=t.fullscreenEditor,outerSize,innerSize;outerSize=fed.dom.getSize(fed.getContainer().getElementsByTagName('table')[0]);innerSize=fed.dom.getSize(fed.getContainer().getElementsByTagName('iframe')[0]);fed.theme.resizeTo(vp.w-outerSize.w+innerSize.w,vp.h-outerSize.h+innerSize.h);});ed.onFullScreen.dispatch(true,s);});ed.addButton('fullscreen',{title:'fullscreen.desc',cmd:'mceFullScreen'});ed.onNodeChange.add(function(ed,cm){cm.setActive('fullscreen',ed.getParam('fullscreen_is_enabled'));});},getInfo:function(){return{longname:'Fullscreen',author:'Moxiecode Systems AB',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/fullscreen',version:tinymce.majorVersion+"."+tinymce.minorVersion};}});tinymce.PluginManager.add('fullscreen',tinymce.plugins.FullScreenPlugin);})();