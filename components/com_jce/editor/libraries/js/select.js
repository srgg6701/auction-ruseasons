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
(function($){$.widget("ui.combobox",{options:{label:'Add Value',change:$.noop},_init:function(options){var self=this;$(this.element).removeClass('mceEditableSelect').addClass('editable');$('<span role="button" class="editable-edit" title="'+this.options.label+'"></span>').insertAfter(this.element).click(function(e){if($(this).hasClass('disabled'))
return;self._onChangeEditableSelect(e);});if($(this.element).is(':disabled')){$(this.element).next('span.editable-edit').addClass('disabled');}},_onChangeEditableSelect:function(e){var self=this;this.input=document.createElement('input');$(this.input).attr('type','text').addClass('editable-input').val($(this.element).val()).insertBefore($(this.element)).width($(this.element).width());$(this.input).blur(function(){self._onBlurEditableSelectInput();}).keydown(function(e){self._onKeyDown(e);});$(this.element).hide();this.input.focus();},_onBlurEditableSelectInput:function(){var self=this,o,found,v=$(this.input).val();if(v!=''){if($('option[value="'+v+'"]',this.element).is('option')){$(this.element).val(v);}else{if(!found){var pattern=$(this.element).data('pattern');if(pattern&&!new RegExp('^(?:'+pattern+')$').test(v)){var n=new RegExp('('+pattern+')').exec(v);v=n?n[0]:'';}
if(v!=''){if($('option[value="'+v+'"]',this.element).length==0){$(this.element).append(new Option(v,v));}
$(this.element).val(v);}}}
self.options.change.call(self,v);}else{$(this.element).val('')||$('option:first',this.element).attr('selected','selected');}
$(this.element).show();$(this.input).remove();},_onKeyDown:function(e){if(e.which==13||e.which==27){this._onBlurEditableSelectInput();}},destroy:function(){$.Widget.prototype.destroy.apply(this,arguments);}});$.extend($.ui.combobox,{version:"2.3.1"});})(jQuery);