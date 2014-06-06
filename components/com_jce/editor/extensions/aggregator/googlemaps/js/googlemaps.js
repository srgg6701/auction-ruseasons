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
WFAggregator.add('googlemaps',{params:{width:425,height:350},props:{},setup:function(){},getTitle:function(){return this.title||this.name;},getType:function(){return'iframe';},isSupported:function(v){if(typeof v=='object'){v=v.src||v.data||'';}
if(/maps\.google\./i.test(v)){return'googlemaps';}
return false;},getValues:function(src){var self=this,data={},args={},type=this.getType();if(!/&(amp;)?output=embed/.test(src)){src+='&amp;output=embed';}
data.src=src;if(type=='iframe'){$.extend(data,{frameborder:0,marginwidth:0,marginheight:0});}
return data;},setValues:function(data){var self=this,id='',src=data.src||data.data||'';if(!src){return data;}
src=src.replace(/&(amp;)?output=embed/,'');data.src=src;return data;},getAttributes:function(src){var args={},data=this.setValues({src:src})||{};$.extend(args,{'src':data.src||src,'width':this.params.width,'height':this.params.height});return args;},setAttributes:function(){},onSelectFile:function(){},onInsert:function(){}});