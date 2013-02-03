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
(function($){$.fn.checkList=function(options){this.each(function(){return $.CheckList.init(this,options);});};$.CheckList={options:{valueAsClassName:false,onCheck:$.noop},init:function(el,options){var self=this;$.extend(this.options,options);var ul=document.createElement('ul');var elms=[];if(el.nodeName=='SELECT'){$.each($('option',el),function(){elms.push({name:$(this).html(),value:$(this).val(),selected:$(this).prop('selected'),disabled:$(this).prop('disabled')});});}else{$.each(el.value.split(','),function(){elms.push({name:this,value:this});});}
$(el).hide();$(ul).addClass('widget-checklist').insertBefore(el);if($(el).hasClass('buttonlist')){$(ul).wrap('<div class="defaultSkin buttonlist" />');}
$.each(elms,function(){self.createElement(el,ul,this);});if($(el).hasClass('sortable')){$(ul).addClass('sortable').sortable({axis:'y',tolerance:'intersect',update:function(event,ui){self.setValue(el,$(ui.item).parent());},placeholder:"ui-state-highlight"}).disableSelection();}},createElement:function(el,ul,n){var self=this,d=document,li=d.createElement('li'),plugin,button,toolbar;$(li).attr({title:n.value}).addClass('ui-widget-content ui-corner-all').appendTo(ul);if($(el).hasClass('buttonlist')){var name=el.name,s=name.split(/[^\w]+/);if(s&&s.length>1){plugin=s[1];}}
if(plugin){toolbar=$('span.profileLayoutContainerToolbar ul','#profileLayoutTable');button=$('span[data-button="'+n.value+'"]',toolbar);}
$('<input type="checkbox" />').addClass('checkbox inline').prop('checked',n.selected).prop('disabled',n.disabled).click(function(){$(this).trigger('checklist:check',this.checked);}).appendTo(li).on('checklist:check',function(e,state){self.setValue(el,ul);if(button){$(button).toggle(state);}
self.options.onCheck.call(self,[this,n]);});$(li).append('<label class="checkbox inline widget-checklist-'+n.value+'" title="'+n.name+'">'+n.name+'</label>');if(button&&$(el).hasClass('buttonlist')){$('label',li).before($(button).clone());}},setValue:function(el,ul){var x=$.map($('input[type="checkbox"]:checked',$('li',ul)),function(n){return $(n).parents('li:first').attr('title');});if(el.nodeName=='SELECT'){$('option',el).each(function(){$(this).prop('selected',$.inArray(this.value,x)!=-1);})}else{el.value=x.join(',');}}};})(jQuery);