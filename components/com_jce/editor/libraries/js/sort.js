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
(function($){$.widget("ui.listsort",{options:{fields:{}},_init:function(){var self=this;$.each(this.options.fields,function(element,props){$(element).addClass('asc').bind('click',function(){var direction='asc';$(this).toggleClass(function(){if($(this).is('.asc')){$(this).removeClass('asc');direction='desc';}else{$(this).removeClass('desc');direction='asc';}
return direction;});var selector=props.selector;if($.type(selector)=='string'){selector=[selector];}
$.each(selector,function(i,s){self.sortList(s,$(element).data('sort-type'),props.attribute,direction);});});});},sortList:function(selector,type,attribute,direction){var self=this;switch(type){case'date':fn=this._sortDate;break;default:fn=this._sortCompare;break;}
var list=$(selector,this.element).map(function(){var v=$(this).attr(attribute)||$(this).text();if(type=='number'){v=parseFloat(v);}
if(type=='extension'){v=v.substring(v.length,v.lastIndexOf('.')+1).toLowerCase();}
if(type=='string'){v=v.toLowerCase();}
return{value:v,element:this};}).get();list.sort(fn);if(direction=='desc'||type=='extension'){list.reverse();}
$.each(list,function(i,item){$(self.element).append(item.element);});list=null;this._trigger('onSort');},_sortDate:function(a,b){var x=a.value,y=b.value,r=0,d1=0,d2=0,t1=0,t2=0;var re=/(\d{2})[\/](\d{2})[\/](\d{4}), (\d{2})[:](\d{2})/g;d1=x.replace(re,'$3$2$1');d2=y.replace(re,'$3$2$1');t1=x.replace(re,'$4$5');t2=y.replace(re,'$4$5');if(d1>d2){r=1;}
if(d1<d2){r=-1;}
if(t1>t2){r=1;}
if(t1<t2){r=-1;}
return r;},_sortCompare:function(a,b){if(a.value<b.value){return-1;}
if(b.value<a.value){return 1;}
return 0;},destroy:function(){$.Widget.prototype.destroy.apply(this,arguments);}});$.extend($.ui.listsort,{version:"2.3.1"});})(jQuery);