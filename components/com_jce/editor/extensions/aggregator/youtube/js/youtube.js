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
WFAggregator.add('youtube',{params:{width:425,height:350,embed:true},props:{rel:1,autohide:2,autoplay:0,controls:1,enablejsapi:0,loop:0,playlist:'',start:'',privacy:0},setup:function(){$('#youtube_embed').toggle(this.params.embed);$('#youtube_privacy').click(function(){if($(this).is(':checked')){$('#youtube_embed').attr('checked',true).attr('disabled',true);}else{$('#youtube_embed').attr('disabled',false);}});$('#youtube_embed').click(function(){if(!$(this).is(':checked')){$('#youtube_privacy').attr('checked',false);}});},getTitle:function(){return this.title||this.name;},getType:function(){return $('#youtube_embed:visible').is(':checked')?'flash':'iframe';},isSupported:function(v){if(typeof v=='object'){v=v.src||v.data||'';}
if(/youtu(\.)?be(.+)?\/(.+)/.test(v)){return'youtube';}
return false;},getValues:function(src){var self=this,data={},args={},type=this.getType();$.extend(args,$.String.query(src));if($('#youtube_https').is(':checked')){src=src.replace(/^http:\/\//,'https://');}else{src=src.replace(/^https:\/\//,'http://');}
$(':input','#youtube_options').not('#youtube_embed, #youtube_https').each(function(){var k=$(this).attr('id'),v=$(this).val();k=k.substr(k.indexOf('_')+1);if($(this).is(':checkbox')){v=$(this).is(':checked')?1:0;}
if(k=='autohide'){v=parseInt(v);}
if(self.props[k]===v||v===''){return;}
args[k]=v;});src=src.replace(/youtu(\.)?be([^\/]+)?\/(.+)/,function(a,b,c,d){d=d.replace(/(watch\?v=|v\/|embed\/)/,'');if(b&&!c){c='.com';}
return'youtube'+c+'/'+(type=='iframe'?'embed':'v')+'/'+d;});if($('#youtube_privacy').is(':checked')){src=src.replace('youtube','youtube-nocookie');}else{src=src.replace('youtube-nocookie','youtube');}
var query=$.param(args);if(query){src=src+(/\?/.test(src)?'&':'?')+query;}
data.src=src;if(type=='iframe'){$.extend(data,{allowfullscreen:true,frameborder:0});}else{$.extend(true,data,{param:{allowfullscreen:true,wmode:'opaque'}});}
return data;},setValues:function(data){var self=this,id='',src=data.src||data.data||'';if(!src){return data;}
var query=$.String.query(src);$.extend(data,query);if(/https:\/\//.test(src)){data['https']=true;}
if(/youtube-nocookie/.test(src)){data['privacy']=true;}
if(data.param){data['embed']=true;}
if(query.v){id=query.v;delete query.v;}else{var s=/(\.be|\/(embed|v))\/([^\/\?&]+)/.exec(src);if(s.length>2){id=s[3];}}
if(data.playlist){data.playlist=decodeURIComponent(data.playlist);}
$.each(query,function(k,v){if(typeof self.props[k]=='undefined'){$('#youtube_options table').append('<tr><td><label for="youtube_'+k+'">'+k+'</label><input type="text" id="youtube_'+k+'" value="'+v+'" /></td></tr>');}});src=src.replace(/youtu(\.)?be([^\/]+)?\/(.+)/,function(a,b,c,d){var args='youtube';if(b){args+='.com';}
if(c){args+=c;}
if($('#youtube_embed').is(':checked')){args+='/v';}else{args+='/embed';}
args+='/'+id;return args;}).replace(/\/\/youtube/i,'//www.youtube');data.src=src;return data;},getAttributes:function(src){var args={},data=this.setValues({src:src})||{};$.each(data,function(k,v){if(k=='src'){return;}
args['youtube_'+k]=v;});$.extend(args,{'src':data.src||src,'width':this.params.width,'height':this.params.height});return args;},setAttributes:function(){},onSelectFile:function(){},onInsert:function(){}});