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
var WFMediaPlayer=WFExtensions.add('MediaPlayer',{params:{extensions:'flv,f4v',dimensions:{},path:''},type:'flash',init:function(o){tinymce.extend(this,o);return this;},setup:function(){},getTitle:function(){return this.title||this.name;},getType:function(){return this.type;},isSupported:function(){return false;},getParam:function(param){return this.params[param]||'';},setParams:function(o){tinymce.extend(this.params,o);},getPath:function(){return this.getParam('path');},onSelectFile:function(file){},onInsert:function(){},onChangeType:function(){}});