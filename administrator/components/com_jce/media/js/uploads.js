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
(function($){$.widget("ui.upload",{options:{labels:{browse:'Browse',alert:'Incorrect file type'},extensions:['xml'],readonly:false,width:200,task:null,button:null,iframe:false,report:null},_init:function(){var self=this;if($.browser.webkit&&/Safari/.test(navigator.userAgent)){$(window).load(function(){self._createUploader();});}else{self._createUploader();}},_createUploader:function(){var self=this,o=this.options,iframe;var re='.('+o.extensions.join('|')+')$';var $form=$('form[name="adminForm"]');if(o.iframe){iframe=this.createIFrame();}
var $button=$('<button/>').html(o.labels.browse).prepend('<i class="icon-search" />&nbsp;').addClass('upload-browse btn').button({icons:{primary:'ui-icon-search'}});var $buttoncontainer=$('<div/>').addClass('upload_button_container').insertBefore(this.element).append(this.element).hover(function(){$button.addClass('ui-state-hover');},function(){$button.removeClass('ui-state-hover');});var $inputcontainer=$('<div/>').addClass('upload_input_container').insertBefore($buttoncontainer);var $input=$('<input/>').attr({'type':'text','name':$(this.element).attr('name')+'_input','placeholder':$(this.element).attr('placeholder'),'size':$(this.element).attr('size')||30}).addClass('upload_text').appendTo($inputcontainer);if(o.readonly){$input.prop('readonly','readonly').appendTo($buttoncontainer);}
$('<span/>').addClass('upload_clear ui-icon ui-icon-circle-close').css('opacity',0.15).appendTo($inputcontainer).click(function(){$input.val('').focus();$(self.element).val('');});$button.click(function(e){e.preventDefault();});$(this.element).css({'opacity':0});$input.placeholder();$input.click(function(){if($(self.element).val()){$(this,self.element).val('');}});$button.insertBefore($(this.element));if(o.button){var submit=o.submit;$(o.button).click(function(e){if($input.hasClass('placeholder')){$input.val('');}
if(iframe){$form.attr('target',iframe.name);}
if($(self.element).val()||$input.val()){$(this).addClass('ui-state-loading');$('input[name="task"]').val(o.task||'');$form.submit();}
e.preventDefault();}).appendTo($buttoncontainer);$('<span class="upload-input-wrapper"></span>').css({'width':$button.outerWidth(),'height':$button.outerHeight(true)}).insertBefore(this.element).append(this.element);}
if(!window.XMLHttpRequest){$(this.element).addClass('ie_upload_input_file');$input.addClass('ie_input_text');$button.addClass('ie_button');}
$(this.element).change(function(){var file=self.getFileName($(this).val());if(!new RegExp(re).test(file)){alert(o.labels.alert);$(this).val('');}else{$input.val(file).addClass('upload_file').removeClass('placeholder');}});},createIFrame:function(){var o=this.options;var iframe=document.getElementById('upload_iframe');if(!iframe){iframe=document.createElement('iframe');var $form=$('form[name="adminForm"]');$(iframe).attr({'src':'javascript:""','name':'upload_iframe','id':'upload_iframe'}).hide().load(function(e){var n=e.target,el;try{el=n.contentWindow.document||n.contentDocument||window.frames[n.id].document;}catch(ex){alert("UPLOAD SECURITY ERROR");return;}
if(el.location.href=='about:blank'){return;}
var result=el.body.innerHTML||el.documentElement.innerText||el.documentElement.textContent;if(result!=''){$form.removeAttr('target');if(!document.getElementById(o.report)){$('#jce').append('<div id="'+o.report+'"></div>');}
$('div#'+o.report,'#jce').hide().html(result).fadeIn();}
if(o.button){var btn=document.getElementById(o.button);$(btn).removeClass('loading');}}).appendTo($form);if(!$.support.cssFloat){window.frames['upload_iframe'].name='upload_iframe';}
$('<input/>').attr({'type':'hidden','name':'method'}).val('iframe').appendTo($form);}
return iframe;},getFileName:function(file){file=file.replace(/\\/g,'/');return file.substring(file.lastIndexOf('/')+1);},destroy:function(){$.Widget.prototype.destroy.apply(this,arguments);}});})(jQuery);