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
(function($){$.widget("ui.listFilter",{options:{list:null,items:null,clear:null,sort:null,hide:false,filter:null},_init:function(){var self=this,el=this.element,busy;var clear=this.options.clear;if(clear){$(clear).click(function(e){if($(clear).hasClass('clear')){$(clear).removeClass('clear');if($(el).val()){self._reset();$(el).val('');self._trigger('onFind',e,[]);}}});}
$(el).keyup(function(e){if(!busy){busy=true;window.setTimeout(function(){self._find($(el).val(),e);busy=false;},500);}});},_find:function(s,e){var self=this,x=[],f,v,filter;$(this.options.clear).toggleClass('clear',!!s);if(/[a-z0-9_\.-]/i.test(s)){$(this.options.items).each(function(){var n=$.String.basename($(this).attr('title'));if(s.charAt(0)=='.'){v=s.substr(1);f=n.substr(n.lastIndexOf('.')+1);filter=true;}else{f=n.substring(0,s.length);v=s;}
if(f.toLowerCase()==v.toLowerCase()){if($.inArray(this,x)==-1){x.push(this);}}else{var i=$.inArray(this,x);if(i!=-1){x.splice(i,1);}}});}else{x=[];}
if(x.length){x=self._sort(x);self._scroll(x[0]);if(this.options.filter&&filter){$(this.options.filter).not(x).hide();}}else{self._reset();}
self._trigger('onFind',e,x);},_scroll:function(el){var self=this,$list=$(this.options.list);var pos=$(el).position();var top=$list.scrollTop();$list.css('overflow','hidden').animate({scrollTop:pos.top+top},1000,function(){$list.css('overflow','auto');});},_sort:function(x){var a=[];$(this.options.items).each(function(){if($.inArray(this,x)!=-1){a.push(this);}});return a;},_reset:function(){$(this.options.filter).show();this._scroll($('li:first',this.options.list));},destroy:function(){$.Widget.prototype.destroy.apply(this,arguments);}});$.extend($.ui.searchables,{version:"2.3.1"});})(jQuery);