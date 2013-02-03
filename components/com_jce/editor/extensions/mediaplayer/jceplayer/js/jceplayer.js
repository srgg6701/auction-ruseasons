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
WFMediaPlayer.init({params:{extensions:'mp3,mp4,flv,f4v',dimensions:{'audio':{width:300,height:35}},path:'media/jce/mediaplayer/mediaplayer.swf'},props:{autoPlay:false,bufferingOverlay:false,controlBarAutoHide:true,controlBarMode:'docked',loop:false,muted:false,playButtonOverlay:true,bufferingOverlay:true,volume:1,audioPan:0},type:'flash',setup:function(){$('#mediaplayer_volume, #mediaplayer_audioPan').each(function(){var n=this;$('<span id="'+$(n).attr('id')+'_slider" class="ui-slider-block"></span>').insertAfter(this).slider();});$('#mediaplayer_volume').change(function(){var v=parseFloat($(this).val());v=Math.ceil(v);$('#mediaplayer_volume_slider').slider('value',v);$(this).val(v);});$('#mediaplayer_audioPan').change(function(){$('#mediaplayer_audioPan_slider').slider('value',($(this).val()*10)+20);});$('#mediaplayer_volume_slider').slider('option',{min:0,max:100,step:10,value:$('#mediaplayer_volume').val(),slide:function(event,ui){$('#mediaplayer_volume').val(ui.value);}});$('#mediaplayer_audioPan_slider').slider('option',{min:10,max:30,step:1,value:($('#mediaplayer_audioPan').val()*10)+20,slide:function(event,ui){$('#mediaplayer_audioPan').val((ui.value-20)/10);}});},isSupported:function(data){var r,file='',ext=tinymce.explode(this.getParam('extensions')).join('|'),re=new RegExp('\.('+ext+')$','i');var src=data.src||data.data||'';if(data.param){var fv=this.parseValues(data.param.flashvars||'');if(fv){file=fv.src||'';}}
r=re.test(src)||re.test(file);if(!r){return new RegExp(this.getPath()).test(src);}
return r;},getValues:function(s){var self=this,s,u,k,v,data=[];var url=tinyMCEPopup.getParam('document_base_url');if(!/http(s)?:\/\//.test(s)){s=$.String.path(url,s);}
data.push('src='+$.String.encodeURI(s,true));$(':input','#mediaplayer_options').each(function(){k=$(this).attr('id'),v=$(this).val();k=k.substr(k.indexOf('_')+1);switch(k){case'volume':v=parseInt(v)/100;break;case'audioPan':v=parseInt(v);break;case'backgroundColor':v=v.replace('#','0x');break;case'poster':case'endOfVideoOverlay':if(v){u=/http(s)?:\/\/[^\/]+(.*)/.exec(url);s=(u&&u.length>1)?u[2]:'';v=$.String.path(s,v);}
break;default:break;}
if($(this).is(':checkbox')){v=$(this).is(':checked');}
if(k=='controlBarAutoHide'){v=!v;}
if(self.props[k]===v||v===''){return;}
data.push(k+'='+$.String.encodeURI(v,true));});return{'src':this.getPath(),'type':'application/x-shockwave-flash','param':{'flashvars':data.join('&'),'allowfullscreen':true,'wmode':'opaque'}};},parseValues:function(s){var ed=tinyMCEPopup.editor,data={},o=$.String.query(s.replace(/\?/,'&'));$.each(o,function(k,v){switch(k){case'src':data['src']=ed.convertURL(v);break;case'volume':data['volume']=parseInt(v)*100;break;case'backgroundColor':data[k]=v.replace('0x','#');break;case'loop':case'autoPlay':case'muted':case'playButtonOverlay':case'bufferingOverlay':v=(v==='false'||v==='0')?false:!!v;data[k]=v;break;case'controlBarAutoHide':v=(v==='false'||v==='0')?false:!!v;data[k]=!v;break;case'poster':case'endOfVideoOverlay':data[k]=ed.convertURL(ed.documentBaseURI.toAbsolute(v));break;default:data[k]=v;break;}});return data;},setValues:function(data){var fv=data.param.flashvars||'';var at=this.parseValues(decodeURIComponent(fv));$.each(at,function(k,v){if(k=='src')
return;data[k]=v;});data.src=at.src;return data;},onSelectFile:function(file){if(file&&/\.mp3$/.test(file)){$('#mediaplayer_controlBarMode').val('floating').prop('disabled',true);}else{$('#mediaplayer_controlBarMode').val('docked').prop('disabled',false);}},onInsert:function(){var src=$('#src').val(),mp3=/\.mp3$/.test(src),dimensions=this.getParam('dimensions');if(mp3&&dimensions.audio){$('#width').val(dimensions.audio.width);$('#height').val(dimensions.audio.height);}
$('#flash_wmode').val('opaque');$('#flash_allowfullscreen').attr('checked',!mp3);$('#flash_menu').attr('checked',true);}});