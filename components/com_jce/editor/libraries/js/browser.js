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
var WFFileBrowser={settings:{},element:'',init:function(element,options){$.extend(true,this.settings,options);this.element=element;this._createBrowser();},_createBrowser:function(){$(this.element).MediaManager(this.settings);},getBaseDir:function(){return this._call('getBaseDir');},getCurrentDir:function(){return this._call('getCurrentDir');},getSelectedItems:function(key){return this._call('getSelectedItems',key);},setSelectedItems:function(items){return this._call('setSelectedItems',items);},refresh:function(){return this._call('refresh');},error:function(error){return this._call('error',error);},status:function(message,state){return this._call('setStatus',{message:message,state:state});},load:function(items){return this._call('load',items);},resize:function(fh){return this._call('resize',[null,fh]);},startUpload:function(){return this._call('startUpload');},stopUpload:function(){return this._call('stopUpload');},setUploadStatus:function(message,state){return this._call('setUploadStatus',{message:message,state:state});},get:function(fn,args){return this._call(fn,args);},_call:function(fn,args){return $(this.element).MediaManager(fn,args);}};