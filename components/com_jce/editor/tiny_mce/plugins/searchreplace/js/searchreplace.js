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
var SearchReplaceDialog={settings:{},init:function(ed){var self=this,m=tinyMCEPopup.getWindowArg("mode");$.Plugin.init();$('button#next').button({icons:{primary:'ui-icon-arrowthick-1-e'}}).click(function(e){self.searchNext('none');e.preventDefault();});$('button#replaceBtn').button({icons:{primary:'ui-icon-transferthick-e-w'}}).click(function(e){self.searchNext('current');e.preventDefault();});$('button#replaceAllBtn').button({icons:{primary:'ui-icon-transferthick-e-w'}}).click(function(e){self.searchNext('all');e.preventDefault();});$('#tabs').tabs('option','select',function(e,ui){var id=ui.panel.id;self.switchMode(id.substring(0,id.indexOf('_')));}).tabs('select','#'+m+'_tab');this.switchMode(m);$('#'+m+'_panel_searchstring').val(tinyMCEPopup.getWindowArg("search_string"));$('#'+m+'_panel_searchstring').focus();},switchMode:function(m){var lm=this.lastMode;if(lm!=m){if(lm){$('#'+m+'_panel_searchstring').val($('#'+lm+'_panel_searchstring').val());$('#'+m+'_panel_backwardsu').prop('checked',$('#'+lm+'_panel_backwardsu').is(':checked'));$('#'+m+'_panel_backwardsd').prop('checked',$('#'+lm+'_panel_backwardsd').is(':checked'));$('#'+m+'_panel_casesensitivebox').prop('checked',$('#'+lm+'_panel_casesensitivebox').is(':checked'));}
$("#replaceBtn").css('display',(m=="replace")?"inline":"none");$("#replaceAllBtn").css('display',(m=="replace")?"inline":"none");this.lastMode=m;}},searchNext:function(a){var ed=tinyMCEPopup.editor,se=ed.selection,r=se.getRng(),m=this.lastMode,s,b,fl=0,w=ed.getWin(),wm=ed.windowManager,fo=0,ca,rs;s=$('#'+m+'_panel_searchstring').val();b=$('#'+m+'_panel_backwardsu').is(':checked');ca=$('#'+m+'_panel_casesensitivebox').is(':checked');rs=$('#replace_panel_replacestring').val();if(tinymce.isIE){r=ed.getDoc().selection.createRange();}
if(s=='')
return;function fix(){r=se.getRng().cloneRange();ed.getDoc().execCommand('SelectAll',false,null);se.setRng(r);};function replace(){ed.selection.setContent(rs);};if(ca)
fl=fl|4;switch(a){case'all':ed.execCommand('SelectAll');ed.selection.collapse(true);if(tinymce.isIE){ed.focus();r=ed.getDoc().selection.createRange();while(r.findText(s,b?-1:1,fl)){r.scrollIntoView();r.select();replace();fo=1;if(b){r.moveEnd("character",-(rs.length));}}
tinyMCEPopup.storeSelection();}else{while(w.find(s,ca,b,false,false,false,false)){replace();fo=1;}}
if(fo)
tinyMCEPopup.alert(ed.getLang('searchreplace_dlg.allreplaced','All occurrences of the search string were replaced.'));else
tinyMCEPopup.alert(ed.getLang('searchreplace_dlg.notfound','The search has been completed. The search string could not be found.'));return;case'current':if(!ed.selection.isCollapsed())
replace();break;}
se.collapse(b);r=se.getRng();if(!s)
return;if(tinymce.isIE){ed.focus();r=ed.getDoc().selection.createRange();if(r.findText(s,b?-1:1,fl)){r.scrollIntoView();r.select();}else
tinyMCEPopup.alert(ed.getLang('searchreplace_dlg.notfound','The search has been completed. The search string could not be found.'));tinyMCEPopup.storeSelection();}else{if(!w.find(s,ca,b,false,false,false,false))
tinyMCEPopup.alert(ed.getLang('searchreplace_dlg.notfound','The search has been completed. The search string could not be found.'));else
fix();}}};tinyMCEPopup.onInit.add(SearchReplaceDialog.init,SearchReplaceDialog);