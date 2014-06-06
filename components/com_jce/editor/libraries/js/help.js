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
(function($){$.jce={Help:{options:{url:'',key:[],pattern:''},init:function(options){var key,id,n,self=this;$.extend(this.options,options);$('body').addClass('ui-jce');$(window).bind('resize',function(){$('#jce').height($('body').height()-20);});if($('#help-menu')){$('dd.subtopics','#help-menu').click(function(){$(this).parent('dl').children('dl').addClass('hidden');$(this).next('dl').removeClass('hidden');});this.nodes=$('dd[id]','#help-menu').click(function(e){$('dd.loading','#help-menu').removeClass('loading');self.loadItem(e.target);});$('iframe#help-iframe').load(function(){$('.loading','#help-menu').removeClass('loading');});key=this.options.key;if(!key.length){n=this.nodes[0];}else{id=key.join('.');n=document.getElementById(id)||this.nodes[0];}
if(n){this.loadItem(n);}}
$('#help-menu-toggle div').click(function(){$('#help-menu').parent().toggle();$('#help-frame').parent().toggleClass('span8 span12');});},loadItem:function(el){var s,n,keys,p,map;$(el).addClass('loading');var id=$(el).attr('id');if(this.options.pattern){keys=id.split('.');map={'section':keys[0]||'','category':keys[1]||'','article':keys[2]||''};p=this.options.pattern;s=p.replace(/\{\$([^\}]+)\}/g,function(a,b){return map[b]||'';});}else{s=id;}
$('iframe#help-iframe').attr('src',this.options.url+s);}}};})(jQuery);