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
var WFLinkBrowser={options:{element:'#link-browser',onClick:$.noop},init:function(options){$.extend(this.options,options);this._createTree();},_createTree:function(){var self=this;$(this.options.element).tree({collapseTree:true,charLength:50,onInit:function(e,callback){if($.isFunction(callback)){callback.apply();}},onNodeClick:function(e,node){var v;if(!$('span.nolink',node).length){v=$('a',node).attr('href');if(v=='javascript:;')
v=$(node).attr('id');if($.isFunction(self.options.onClick)){self.options.onClick.call(this,$.String.decode(v));}}
if($('span',node).is('.folder')){$(this).tree('toggleNode',e,node);}
e.preventDefault();},onNodeLoad:function(e,node){var self=this;$(this).tree('toggleLoader',node);var query=$.String.query($.String.unescape($(node).attr('id')));$.JSON.request('getLinks',{'json':query},function(o){if(o){if(!o.error){var ul=$('ul:first',node);if(ul){$(ul).remove();}
$(self).tree('createNode',o.folders,node);$(self).tree('toggleNodeState',node,true);}else{$.Dialog.alert(o.error);}}
$(self).tree('toggleLoader',node);},self);}});}};