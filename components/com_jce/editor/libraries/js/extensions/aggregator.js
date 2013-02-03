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
var WFAggregator=WFExtensions.add('Aggregator',{aggregators:{},add:function(name,o){this.aggregators[name]=o||{};},get:function(name){return this.aggregators[name]||null;},setup:function(options){var self=this;options=options||{};tinymce.each(this.aggregators,function(o,k){self.setParams(o,options);return self._call(o,'setup');});},getTitle:function(name){var f=this.get(name);if(f){return f.title;}
return name;},getType:function(name){var f=this.get(name);if(f){return f.getType();}
return'';},getValues:function(name,src){var f=this.get(name);if(f){return this._call(f,'getValues',src);}},setValues:function(name,data){var f=this.get(name);if(f){return this._call(f,'setValues',data);}},getAttributes:function(name,args){var f=this.get(name);if(f){return this._call(f,'getAttributes',args);}},setAttributes:function(name,args){var f=this.get(name);if(f){return this._call(f,'setAttributes',args);}},isSupported:function(args){var self=this,r,v;tinymce.each(this.aggregators,function(o){if(v=self._call(o,'isSupported',args)){r=v;}});return r;},getParam:function(name,param){var f=this.get(name);if(f){return f.params[param]||'';}
return'';},setParams:function(name,o){var f=this.get(name);if(f){tinymce.extend(f.params,o);}},onSelectFile:function(name){var f=this.get(name);if(f){return this._call(f,'onSelectFile');}},onInsert:function(name){var self=this,f=this.get(name);if(f){return self._call(f,'onInsert');}},_call:function(o,fn,vars){var f=o[fn]||function(){};return f.call(o,vars);}});