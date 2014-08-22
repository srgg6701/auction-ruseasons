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
(function(){var WFExtensions={types:{},add:function(n,o){this[n]=o;return this[n];},addType:function(n){this.types[n]={};},addExtension:function(type,n,o){if(typeof this.types[type]=='undefined'){this.addType(type);}
this.types[type][n]=o;},getType:function(type){return this.types[type]||false;},getExtension:function(type,ext){var s=this.getType(type);return s[ext];}};window.WFExtensions=WFExtensions;})();