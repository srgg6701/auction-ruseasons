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
(function(tinymce){var PLUGIN_NAME='autosave',RESTORE_DRAFT='autosave',TRUE=true,undefined,unloadHandlerAdded,Dispatcher=tinymce.util.Dispatcher;tinymce.create('tinymce.plugins.AutoSave',{init:function(ed,url){var self=this,settings=ed.settings;self.editor=ed;function parseTime(time){var multipels={s:1000,m:60000};time=/^(\d+)([ms]?)$/.exec(''+time);return(time[2]?multipels[time[2]]:1)*parseInt(time);};tinymce.each({ask_before_unload:false,interval:'30s',retention:'20m',minlength:50},function(value,key){key=PLUGIN_NAME+'_'+key;if(settings[key]===undefined)
settings[key]=value;});settings.autosave_interval=parseTime(settings.autosave_interval);settings.autosave_retention=parseTime(settings.autosave_retention);ed.addButton(RESTORE_DRAFT,{title:PLUGIN_NAME+".restore_content",onclick:function(){if(ed.getContent({draft:true}).replace(/\s|&nbsp;|<\/?p[^>]*>|<br[^>]*>/gi,"").length>0){ed.windowManager.confirm(PLUGIN_NAME+".warning_message",function(ok){if(ok)
self.restoreDraft();});}else
self.restoreDraft();}});ed.onNodeChange.add(function(){var controlManager=ed.controlManager;if(controlManager.get(RESTORE_DRAFT))
controlManager.setDisabled(RESTORE_DRAFT,!self.hasDraft());});ed.onInit.add(function(){if(ed.controlManager.get(RESTORE_DRAFT)){self.setupStorage(ed);setInterval(function(){self.storeDraft();ed.nodeChanged();},settings.autosave_interval);}});self.onStoreDraft=new Dispatcher(self);self.onRestoreDraft=new Dispatcher(self);self.onRemoveDraft=new Dispatcher(self);if(!unloadHandlerAdded){window.onbeforeunload=tinymce.plugins.AutoSave._beforeUnloadHandler;unloadHandlerAdded=TRUE;}},getInfo:function(){return{longname:'Auto save',author:'Moxiecode Systems AB',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/autosave',version:tinymce.majorVersion+"."+tinymce.minorVersion};},getExpDate:function(){return new Date(new Date().getTime()+this.editor.settings.autosave_retention).toUTCString();},setupStorage:function(ed){var self=this,testKey=PLUGIN_NAME+'_test',testVal="OK";self.key=PLUGIN_NAME+ed.id;tinymce.each([function(){if(localStorage){localStorage.setItem(testKey,testVal);if(localStorage.getItem(testKey)===testVal){localStorage.removeItem(testKey);return localStorage;}}},function(){if(sessionStorage){sessionStorage.setItem(testKey,testVal);if(sessionStorage.getItem(testKey)===testVal){sessionStorage.removeItem(testKey);return sessionStorage;}}},function(){if(tinymce.isIE){ed.getElement().style.behavior="url('#default#userData')";return{autoExpires:TRUE,setItem:function(key,value){var userDataElement=ed.getElement();userDataElement.setAttribute(key,value);userDataElement.expires=self.getExpDate();try{userDataElement.save("TinyMCE");}catch(e){}},getItem:function(key){var userDataElement=ed.getElement();try{userDataElement.load("TinyMCE");return userDataElement.getAttribute(key);}catch(e){return null;}},removeItem:function(key){ed.getElement().removeAttribute(key);}};}},],function(setup){try{self.storage=setup();if(self.storage)
return false;}catch(e){}});},storeDraft:function(){var self=this,storage=self.storage,editor=self.editor,expires,content;if(storage){if(!storage.getItem(self.key)&&!editor.isDirty())
return;content=editor.getContent({draft:true});if(content.length>editor.settings.autosave_minlength){expires=self.getExpDate();if(!self.storage.autoExpires)
self.storage.setItem(self.key+"_expires",expires);self.storage.setItem(self.key,content);self.onStoreDraft.dispatch(self,{expires:expires,content:content});}}},restoreDraft:function(){var self=this,storage=self.storage,content;if(storage){content=storage.getItem(self.key);if(content){self.editor.setContent(content);self.onRestoreDraft.dispatch(self,{content:content});}}},hasDraft:function(){var self=this,storage=self.storage,expDate,exists;if(storage){exists=!!storage.getItem(self.key);if(exists){if(!self.storage.autoExpires){expDate=new Date(storage.getItem(self.key+"_expires"));if(new Date().getTime()<expDate.getTime())
return TRUE;self.removeDraft();}else
return TRUE;}}
return false;},removeDraft:function(){var self=this,storage=self.storage,key=self.key,content;if(storage){content=storage.getItem(key);storage.removeItem(key);storage.removeItem(key+"_expires");if(content){self.onRemoveDraft.dispatch(self,{content:content});}}},"static":{_beforeUnloadHandler:function(e){var msg;tinymce.each(tinyMCE.editors,function(ed){if(ed.plugins.autosave)
ed.plugins.autosave.storeDraft();if(ed.getParam("fullscreen_is_enabled"))
return;if(!msg&&ed.isDirty()&&ed.getParam("autosave_ask_before_unload"))
msg=ed.getLang("autosave.unload_msg");});return msg;}}});tinymce.PluginManager.add('autosave',tinymce.plugins.AutoSave);})(tinymce);