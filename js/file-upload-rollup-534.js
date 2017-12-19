// ***************************
// js.compressed/jquery/jquery.tmpl.min.js
// ***************************
(function(a){var r=a.fn.domManip,d="_tmplitem",q=/^[^<]*(<[\w\W]+>)[^>]*$|\{\{\! /,b={},f={},e,p={key:0,data:{}},h=0,c=0,l=[];function g(e,d,g,i){var c={data:i||(d?d.data:{}),_wrap:d?d._wrap:null,tmpl:null,parent:d||null,nodes:[],calls:u,nest:w,wrap:x,html:v,update:t};e&&a.extend(c,e,{nodes:[],parent:d});if(g){c.tmpl=g;c._ctnt=c._ctnt||c.tmpl(a,c);c.key=++h;(l.length?f:b)[h]=c}return c}a.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(f,d){a.fn[f]=function(n){var g=[],i=a(n),k,h,m,l,j=this.length===1&&this[0].parentNode;e=b||{};if(j&&j.nodeType===11&&j.childNodes.length===1&&i.length===1){i[d](this[0]);g=this}else{for(h=0,m=i.length;h<m;h++){c=h;k=(h>0?this.clone(true):this).get();a.fn[d].apply(a(i[h]),k);g=g.concat(k)}c=0;g=this.pushStack(g,f,i.selector)}l=e;e=null;a.tmpl.complete(l);return g}});a.fn.extend({tmpl:function(d,c,b){return a.tmpl(this[0],d,c,b)},tmplItem:function(){return a.tmplItem(this[0])},template:function(b){return a.template(b,this[0])},domManip:function(d,l,j){if(d[0]&&d[0].nodeType){var f=a.makeArray(arguments),g=d.length,i=0,h;while(i<g&&!(h=a.data(d[i++],"tmplItem")));if(g>1)f[0]=[a.makeArray(d)];if(h&&c)f[2]=function(b){a.tmpl.afterManip(this,b,j)};r.apply(this,f)}else r.apply(this,arguments);c=0;!e&&a.tmpl.complete(b);return this}});a.extend({tmpl:function(d,h,e,c){var j,k=!c;if(k){c=p;d=a.template[d]||a.template(null,d);f={}}else if(!d){d=c.tmpl;b[c.key]=c;c.nodes=[];c.wrapped&&n(c,c.wrapped);return a(i(c,null,c.tmpl(a,c)))}if(!d)return[];if(typeof h==="function")h=h.call(c||{});e&&e.wrapped&&n(e,e.wrapped);j=a.isArray(h)?a.map(h,function(a){return a?g(e,c,d,a):null}):[g(e,c,d,h)];return k?a(i(c,null,j)):j},tmplItem:function(b){var c;if(b instanceof a)b=b[0];while(b&&b.nodeType===1&&!(c=a.data(b,"tmplItem"))&&(b=b.parentNode));return c||p},template:function(c,b){if(b){if(typeof b==="string")b=o(b);else if(b instanceof a)b=b[0]||{};if(b.nodeType)b=a.data(b,"tmpl")||a.data(b,"tmpl",o(b.innerHTML));return typeof c==="string"?(a.template[c]=b):b}return c?typeof c!=="string"?a.template(null,c):a.template[c]||a.template(null,q.test(c)?c:a(c)):null},encode:function(a){return(""+a).split("<").join("&lt;").split(">").join("&gt;").split('"').join("&#34;").split("'").join("&#39;")}});a.extend(a.tmpl,{tag:{tmpl:{_default:{$2:"null"},open:"if($notnull_1){_=_.concat($item.nest($1,$2));}"},wrap:{_default:{$2:"null"},open:"$item.calls(_,$1,$2);_=[];",close:"call=$item.calls();_=call._.concat($item.wrap(call,_));"},each:{_default:{$2:"$index, $value"},open:"if($notnull_1){$.each($1a,function($2){with(this){",close:"}});}"},"if":{open:"if(($notnull_1) && $1a){",close:"}"},"else":{_default:{$1:"true"},open:"}else if(($notnull_1) && $1a){"},html:{open:"if($notnull_1){_.push($1a);}"},"=":{_default:{$1:"$data"},open:"if($notnull_1){_.push($.encode($1a));}"},"!":{open:""}},complete:function(){b={}},afterManip:function(f,b,d){var e=b.nodeType===11?a.makeArray(b.childNodes):b.nodeType===1?[b]:[];d.call(f,b);m(e);c++}});function i(e,g,f){var b,c=f?a.map(f,function(a){return typeof a==="string"?e.key?a.replace(/(<\w+)(?=[\s>])(?![^>]*_tmplitem)([^>]*)/g,"$1 "+d+'="'+e.key+'" $2'):a:i(a,e,a._ctnt)}):e;if(g)return c;c=c.join("");c.replace(/^\s*([^<\s][^<]*)?(<[\w\W]+>)([^>]*[^>\s])?\s*$/,function(f,c,e,d){b=a(e).get();m(b);if(c)b=j(c).concat(b);if(d)b=b.concat(j(d))});return b?b:j(c)}function j(c){var b=document.createElement("div");b.innerHTML=c;return a.makeArray(b.childNodes)}function o(b){return new Function("jQuery","$item","var $=jQuery,call,_=[],$data=$item.data;with($data){_.push('"+a.trim(b).replace(/([\\'])/g,"\\$1").replace(/[\r\t\n]/g," ").replace(/\$\{([^\}]*)\}/g,"{{= $1}}").replace(/\{\{(\/?)(\w+|.)(?:\(((?:[^\}]|\}(?!\}))*?)?\))?(?:\s+(.*?)?)?(\(((?:[^\}]|\}(?!\}))*?)\))?\s*\}\}/g,function(m,l,j,d,b,c,e){var i=a.tmpl.tag[j],h,f,g;if(!i)throw"Template command not found: "+j;h=i._default||[];if(c&&!/\w$/.test(b)){b+=c;c=""}if(b){b=k(b);e=e?","+k(e)+")":c?")":"";f=c?b.indexOf(".")>-1?b+c:"("+b+").call($item"+e:b;g=c?f:"(typeof("+b+")==='function'?("+b+").call($item):("+b+"))"}else g=f=h.$1||"null";d=k(d);return"');"+i[l?"close":"open"].split("$notnull_1").join(b?"typeof("+b+")!=='undefined' && ("+b+")!=null":"true").split("$1a").join(g).split("$1").join(f).split("$2").join(d?d.replace(/\s*([^\(]+)\s*(\((.*?)\))?/g,function(d,c,b,a){a=a?","+a+")":b?")":"";return a?"("+c+").call($item"+a:d}):h.$2||"")+"_.push('"})+"');}return _;")}function n(c,b){c._wrap=i(c,true,a.isArray(b)?b:[q.test(b)?b:a(b).html()]).join("")}function k(a){return a?a.replace(/\\'/g,"'").replace(/\\\\/g,"\\"):null}function s(b){var a=document.createElement("div");a.appendChild(b.cloneNode(true));return a.innerHTML}function m(o){var n="_"+c,k,j,l={},e,p,i;for(e=0,p=o.length;e<p;e++){if((k=o[e]).nodeType!==1)continue;j=k.getElementsByTagName("*");for(i=j.length-1;i>=0;i--)m(j[i]);m(k)}function m(j){var p,i=j,k,e,m;if(m=j.getAttribute(d)){while(i.parentNode&&(i=i.parentNode).nodeType===1&&!(p=i.getAttribute(d)));if(p!==m){i=i.parentNode?i.nodeType===11?0:i.getAttribute(d)||0:0;if(!(e=b[m])){e=f[m];e=g(e,b[i]||f[i],null,true);e.key=++h;b[h]=e}c&&o(m)}j.removeAttribute(d)}else if(c&&(e=a.data(j,"tmplItem"))){o(e.key);b[e.key]=e;i=a.data(j.parentNode,"tmplItem");i=i?i.key:0}if(e){k=e;while(k&&k.key!=i){k.nodes.push(j);k=k.parent}delete e._ctnt;delete e._wrap;a.data(j,"tmplItem",e)}function o(a){a=a+n;e=l[a]=l[a]||g(e,b[e.parent.key+n]||e.parent,null,true)}}}function u(a,d,c,b){if(!a)return l.pop();l.push({_:a,tmpl:d,item:this,data:c,options:b})}function w(d,c,b){return a.tmpl(a.template(d),c,b,this)}function x(b,d){var c=b.options||{};c.wrapped=d;return a.tmpl(a.template(b.tmpl),b.data,c,b.item)}function v(d,c){var b=this._wrap;return a.map(a(a.isArray(b)?b.join(""):b).filter(d||"*"),function(a){return c?a.innerText||a.textContent:a.outerHTML||s(a)})}function t(){var b=this.nodes;a.tmpl(null,null,null,this).insertBefore(b[0]);a(b).remove()}})(jQuery);

// ***************************
// js.compressed/blueimp/jquery.iframe-transport.min.js
// ***************************
/*
 * jQuery Iframe Transport Plugin 1.2.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 */

/*jslint unparam: true */
/*global jQuery */
(function(a){"use strict";var b=0;a.ajaxTransport("iframe",function(c,d,e){if(c.type==="POST"||c.type==="GET"){var f,g;return{send:function(d,e){f=a('<form style="display:none;"></form>');g=a('<iframe src="javascript:false;" name="iframe-transport-'+(b+=1)+'"></iframe>').bind("load",function(){var b;g.unbind("load").bind("load",function(){var b;try{b=g.contents()}catch(c){b=a()}e(200,"success",{iframe:b});a('<iframe src="javascript:false;"></iframe>').appendTo(f);f.remove()});f.prop("target",g.prop("name")).prop("action",c.url).prop("method",c.type);if(c.formData){a.each(c.formData,function(b,c){a('<input type="hidden"/>').prop("name",c.name).val(c.value).appendTo(f)})}if(c.fileInput&&c.fileInput.length&&c.type==="POST"){b=c.fileInput.clone();c.fileInput.after(function(a){return b[a]});if(c.paramName){c.fileInput.each(function(){a(this).prop("name",c.paramName)})}f.append(c.fileInput).prop("enctype","multipart/form-data").prop("encoding","multipart/form-data")}f.submit();if(b&&b.length){c.fileInput.each(function(c,d){var e=a(b[c]);a(d).prop("name",e.prop("name"));e.replaceWith(d)})}});f.append(g).appendTo("body")},abort:function(){if(g){g.unbind("load").prop("src","javascript".concat(":false;"))}if(f){f.remove()}}}}});a.ajaxSetup({converters:{"iframe text":function(a){return a.text()},"iframe json":function(b){return a.parseJSON(b.text())},"iframe html":function(a){return a.find("body").html()},"iframe script":function(b){return a.globalEval(b.text())}}})})(jQuery);

// ***************************
// js.compressed/blueimp/jquery.fileupload.min.js
// ***************************
/*
 * jQuery File Upload Plugin 5.0.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global document, XMLHttpRequestUpload, Blob, File, FormData, location, jQuery */
(function(a){"use strict";a.widget("blueimp.fileupload",{options:{namespace:undefined,dropZone:a(document),fileInput:undefined,replaceFileInput:true,paramName:undefined,singleFileUploads:true,sequentialUploads:false,forceIframeTransport:false,multipart:true,maxChunkSize:undefined,uploadedBytes:undefined,recalculateProgress:true,formData:function(a){return a.serializeArray()},add:function(a,b){b.submit()},processData:false,contentType:false,cache:false},_refreshOptionsList:["namespace","dropZone","fileInput"],_isXHRUpload:function(a){var b="undefined";return!a.forceIframeTransport&&typeof XMLHttpRequestUpload!==b&&typeof File!==b&&(!a.multipart||typeof FormData!==b)},_getFormData:function(b){var c;if(typeof b.formData==="function"){return b.formData(b.form)}else if(a.isArray(b.formData)){return b.formData}else if(b.formData){c=[];a.each(b.formData,function(a,b){c.push({name:a,value:b})});return c}return[]},_getTotal:function(b){var c=0;a.each(b,function(a,b){c+=b.size||1});return c},_onProgress:function(a,b){if(a.lengthComputable){var c=b.total||this._getTotal(b.files),d=parseInt(a.loaded/a.total*(b.chunkSize||c),10)+(b.uploadedBytes||0);this._loaded+=d-(b.loaded||b.uploadedBytes||0);b.lengthComputable=true;b.loaded=d;b.total=c;this._trigger("progress",a,b);this._trigger("progressall",a,{lengthComputable:true,loaded:this._loaded,total:this._total})}},_initProgressListener:function(b){var c=this,d=b.xhr?b.xhr():a.ajaxSettings.xhr();if(d.upload&&d.upload.addEventListener){d.upload.addEventListener("progress",function(a){c._onProgress(a,b)},false);b.xhr=function(){return d}}},_initXHRData:function(b){var c,d=b.files[0];if(!b.multipart||b.blob){b.headers=a.extend(b.headers,{"X-File-Name":d.name,"X-File-Type":d.type,"X-File-Size":d.size});if(!b.blob){b.contentType=d.type;b.data=d}else if(!b.multipart){b.contentType="application/octet-stream";b.data=b.blob}}if(b.multipart&&typeof FormData!=="undefined"){if(b.formData instanceof FormData){c=b.formData}else{c=new FormData;a.each(this._getFormData(b),function(a,b){c.append(b.name,b.value)})}if(b.blob){c.append(b.paramName,b.blob)}else{a.each(b.files,function(a,d){if(d instanceof Blob){c.append(b.paramName,d)}})}b.data=c}b.blob=null},_initIframeSettings:function(a){a.dataType="iframe "+(a.dataType||"");a.formData=this._getFormData(a)},_initDataSettings:function(a){if(this._isXHRUpload(a)){if(!this._chunkedUpload(a,true)){if(!a.data){this._initXHRData(a)}this._initProgressListener(a)}}else{this._initIframeSettings(a)}},_initFormSettings:function(b){if(!b.form||!b.form.length){b.form=a(b.fileInput.prop("form"))}if(!b.paramName){b.paramName=b.fileInput.prop("name")||"files[]"}if(!b.url){b.url=b.form.prop("action")||location.href}b.type=(b.type||b.form.prop("method")||"").toUpperCase();if(b.type!=="POST"&&b.type!=="PUT"){b.type="POST"}},_getAJAXSettings:function(b){var c=a.extend({},this.options,b);this._initFormSettings(c);this._initDataSettings(c);return c},_enhancePromise:function(a){a.success=a.done;a.error=a.fail;a.complete=a.always;return a},_getXHRPromise:function(b,c,d){var e=a.Deferred(),f=e.promise();c=c||this.options.context||f;if(b===true){e.resolveWith(c,d)}else if(b===false){e.rejectWith(c,d)}f.abort=e.promise;return this._enhancePromise(f)},_chunkedUpload:function(b,c){var d=this,e=b.files[0],f=e.size,g=b.uploadedBytes=b.uploadedBytes||0,h=b.maxChunkSize||f,i=e.webkitSlice||e.mozSlice||e.slice,j,k,l,m;if(!(this._isXHRUpload(b)&&i&&(g||h<f))||b.data){return false}if(c){return true}if(g>=f){e.error="uploadedBytes";return this._getXHRPromise(false)}k=Math.ceil((f-g)/h);j=function(c){if(!c){return d._getXHRPromise(true)}return j(c-=1).pipe(function(){var f=a.extend({},b);f.blob=i.call(e,g+c*h,g+(c+1)*h);f.chunkSize=f.blob.size;d._initXHRData(f);d._initProgressListener(f);l=(a.ajax(f)||d._getXHRPromise(false,f.context)).done(function(){if(!f.loaded){d._onProgress(a.Event("progress",{lengthComputable:true,loaded:f.chunkSize,total:f.chunkSize}),f)}b.uploadedBytes=f.uploadedBytes+=f.chunkSize});return l})};m=j(k);m.abort=function(){return l.abort()};return this._enhancePromise(m)},_beforeSend:function(a,b){if(this._active===0){this._trigger("start")}this._active+=1;this._loaded+=b.uploadedBytes||0;this._total+=this._getTotal(b.files)},_onDone:function(b,c,d,e){if(!this._isXHRUpload(e)){this._onProgress(a.Event("progress",{lengthComputable:true,loaded:1,total:1}),e)}e.result=b;e.textStatus=c;e.jqXHR=d;this._trigger("done",null,e)},_onFail:function(a,b,c,d){d.jqXHR=a;d.textStatus=b;d.errorThrown=c;this._trigger("fail",null,d);if(d.recalculateProgress){this._loaded-=d.loaded||d.uploadedBytes||0;this._total-=d.total||this._getTotal(d.files)}},_onAlways:function(a,b,c,d,e){this._active-=1;e.result=a;e.textStatus=b;e.jqXHR=c;e.errorThrown=d;this._trigger("always",null,e);if(this._active===0){this._trigger("stop");this._loaded=this._total=0}},_onSend:function(b,c){var d=this,e,f,g=d._getAJAXSettings(c),h=function(c,f){e=e||(c!==false&&d._trigger("send",b,g)!==false&&(d._chunkedUpload(g)||a.ajax(g))||d._getXHRPromise(false,g.context,f)).done(function(a,b,c){d._onDone(a,b,c,g)}).fail(function(a,b,c){d._onFail(a,b,c,g)}).always(function(a,b,c){if(!c||typeof c==="string"){d._onAlways(undefined,b,a,c,g)}else{d._onAlways(a,b,c,undefined,g)}});return e};this._beforeSend(b,g);if(this.options.sequentialUploads){f=this._sequence=this._sequence.pipe(h,h);f.abort=function(){if(!e){return h(false,[undefined,"abort","abort"])}return e.abort()};return this._enhancePromise(f)}return h()},_onAdd:function(b,c){var d=this,e=true,f=a.extend({},this.options,c);if(f.singleFileUploads&&this._isXHRUpload(f)){a.each(c.files,function(f,g){var h=a.extend({},c,{files:[g]});h.submit=function(){return d._onSend(b,h)};return e=d._trigger("add",b,h)});return e}else if(c.files.length){c=a.extend({},c);c.submit=function(){return d._onSend(b,c)};return this._trigger("add",b,c)}},_normalizeFile:function(a,b){if(b.name===undefined&&b.size===undefined){b.name=b.fileName;b.size=b.fileSize}},_replaceFileInput:function(b){var c=b.clone(true);a("<form></form>").append(c)[0].reset();b.after(c).detach();this.options.fileInput=this.options.fileInput.map(function(a,d){if(d===b[0]){return c[0]}return d})},_onChange:function(b){var c=b.data.fileupload,d={files:a.each(a.makeArray(b.target.files),c._normalizeFile),fileInput:a(b.target),form:a(b.target.form)};if(!d.files.length){d.files=[{name:b.target.value.replace(/^.*\\/,"")}]}if(d.form.length){d.fileInput.data("blueimp.fileupload.form",d.form)}else{d.form=d.fileInput.data("blueimp.fileupload.form")}if(c.options.replaceFileInput){c._replaceFileInput(d.fileInput)}if(c._trigger("change",b,d)===false||c._onAdd(b,d)===false){return false}},_onDrop:function(b){var c=b.data.fileupload,d=b.dataTransfer=b.originalEvent.dataTransfer,e={files:a.each(a.makeArray(d&&d.files),c._normalizeFile)};if(c._trigger("drop",b,e)===false||c._onAdd(b,e)===false){return false}b.preventDefault()},_onDragOver:function(a){var b=a.data.fileupload,c=a.dataTransfer=a.originalEvent.dataTransfer;if(b._trigger("dragover",a)===false){return false}if(c){c.dropEffect=c.effectAllowed="copy"}a.preventDefault()},_initEventHandlers:function(){var a=this.options.namespace||this.name;this.options.dropZone.bind("dragover."+a,{fileupload:this},this._onDragOver).bind("drop."+a,{fileupload:this},this._onDrop);this.options.fileInput.bind("change."+a,{fileupload:this},this._onChange)},_destroyEventHandlers:function(){var a=this.options.namespace||this.name;this.options.dropZone.unbind("dragover."+a,this._onDragOver).unbind("drop."+a,this._onDrop);this.options.fileInput.unbind("change."+a,this._onChange)},_beforeSetOption:function(a,b){this._destroyEventHandlers()},_afterSetOption:function(b,c){var d=this.options;if(!d.fileInput){d.fileInput=a()}if(!d.dropZone){d.dropZone=a()}this._initEventHandlers()},_setOption:function(b,c){var d=a.inArray(b,this._refreshOptionsList)!==-1;if(d){this._beforeSetOption(b,c)}a.Widget.prototype._setOption.call(this,b,c);if(d){this._afterSetOption(b,c)}},_create:function(){var b=this.options;if(b.fileInput===undefined){b.fileInput=this.element.is("input:file")?this.element:this.element.find("input:file")}else if(!b.fileInput){b.fileInput=a()}if(!b.dropZone){b.dropZone=a()}this._sequence=this._getXHRPromise(true);this._active=this._loaded=this._total=0;this._initEventHandlers()},destroy:function(){this._destroyEventHandlers();a.Widget.prototype.destroy.call(this)},enable:function(){a.Widget.prototype.enable.call(this);this._initEventHandlers()},disable:function(){this._destroyEventHandlers();a.Widget.prototype.disable.call(this)},add:function(b){if(!b||this.options.disabled){return}b.files=a.each(a.makeArray(b.files),this._normalizeFile);this._onAdd(null,b)},send:function(b){if(b&&!this.options.disabled){b.files=a.each(a.makeArray(b.files),this._normalizeFile);if(b.files.length){return this._onSend(null,b)}}return this._getXHRPromise(false,b&&b.context)}})})(jQuery);

// ***************************
// js.compressed/fileupload.js
// ***************************
/*=======================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.3.4
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2017 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["add_caption","attach_files","click_to_add_caption","error_uploading_image","invalid_image_allowed_filetypes_are","remove_all_photos_confirmation_message","remove_all_photos_q","upload","upload_more","uploading","you_are_already_editing_continue","you_must_be_logged_in_to_upload_photos","enter_url_file"]);(function(D){window.vBulletin=window.vBulletin||{};vBulletin.upload=vBulletin.upload||{};vBulletin.gallery=vBulletin.gallery||{};vBulletin.permissions=vBulletin.permissions||{};var C=3,E=2,G=[];vBulletin.gallery.onBeforeSerializeEditForm=function(H){H.find(".caption-box").each(function(){var I=D(this);if(I.hasClass("placeholder")&&I.val()==I.attr("placeholder")){I.val("")}});return true};vBulletin.upload.changeButtonText=function(H,I){if(!H.data("default-text")){H.data("default-text",H.text())}H.text(I)};vBulletin.upload.restoreButtonText=function(H){if(H.data("default-text")){H.text(H.data("default-text"))}};vBulletin.upload.initializePhotoUpload=function(J){console.log("Fileupload: vBulletin.upload.initializePhotoUpload");if(!J||J.length==0){J=D("body");if(D(".js-photo-display",J).length>1){console.log("Fileupload: multiple upload forms, abort");return false}}D(".js-continue-button",J).off("click").on("click",function(O){console.log("Fileupload: continue");var M=D(document).data("gallery-container"),L=M.find(".js-photo-postdata"),N={},K=D(this).closest(".js-photo-display"),P=[];L.empty();K.find(".photo-item-wrapper:not(.h-hide)").each(function(){var S=D(this).find(".filedataid"),R=S.val(),U=S.data("nodeid"),T=D.trim(D(this).find(".caption-box").val());D(this).removeClass("tmp-photo");if(typeof U!=="undefined"){D(this).removeClass("tmp-photo");var Q=D.inArray(U,G);if(Q==-1){G.push(U)}}L.append(D("<input />").attr({type:"hidden",name:"filedataid[]"}).val(R)).append(D("<input />").attr({type:"hidden",name:"title_"+R}).val(T));P.push({filedataid:R,title:T})});N.photocount=P.length;N.photos=P;K.find(".photo-item-wrapper:hidden").remove();K.dialog("close");if(N.photos.length>0){vBulletin.AJAX({call:"/ajax/render/editor_gallery_photoblock",data:N,type:"POST",dataType:"json",success:function(Q){var R=D(Q);R.find(".b-gallery-thumbnail-list__aside").addClass("h-invisible");M.find(".js-gallery-content").removeClass("h-hide").find(".js-panel-content").empty().append(R);setTimeout(function(){R.find(".b-gallery-thumbnail-list__aside").removeClass("h-invisible")},500);vBulletin.upload.initializePhotoUpload(M);D(document).data("gallery-container",null)},error:vBulletin.ajaxtools.logAjaxError,api_error:vBulletin.ajaxtools.logApiError});D(".js-edit-photos",M).removeClass("h-hide");vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",M),vBulletin.phrase.get("upload_more"))}else{D(".photo-display-result",M).empty();D(".js-edit-photos",M).addClass("h-hide");vBulletin.upload.restoreButtonText(D(".b-button--upload .js-upload-label",M))}});D(".js-edit-photos",J).off("click").on("click",function(L){console.log("Fileupload: edit photos");D(document).data("gallery-container",D(this).closest(".b-content-entry-panel__content--gallery"));var K=vBulletin.upload.getUploadedPhotosDlg(false);vBulletin.upload.relocateLastInRowClass(K.find(".photo-item-wrapper"));I(K);K.dialog("open");vBulletin.upload.adjustPhotoDialogForScrollbar(K);D(".b-button--upload",K).trigger("focus")});D(".js-cancel-button",J).off("click").on("click",function(M){console.log("Fileupload: cancel");var K=D(this).closest(".js-photo-display"),L=D(document).data("gallery-container");D(".photo-item-wrapper.tmp-photo",K).remove();D(".photo-item-wrapper",K).removeClass("h-hide");if(D(".js-panel-content",L).length>0){vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",L),vBulletin.phrase.get("upload_more"))}else{vBulletin.upload.restoreButtonText(D(".b-button--upload .js-upload-label",L))}K.dialog("close")});var I=function(K){vBulletin.upload.updateButtons(K,(D(".photo-display .photo-item-wrapper:not(.h-hide)",K).length>0))};D(".b-content-entry-panel__content--gallery, .js-profile-media-photoupload-dialog",J).fileupload({dropZone:null,dataType:"json",url:vBulletin.getAjaxBaseurl()+"/uploader/upload-photo",type:"POST",formData:function(L){console.log("Fileupload: gallery formData");var K=L.find(".b-content-entry-panel__content--gallery");if(K.length==0){K=D("<form>")}if(K.find('input[name="securitytoken"]').length){K.find('input[name="securitytoken"]').val(pageData.securitytoken)}else{K.append('<input type="hidden" name="securitytoken" value="'+pageData.securitytoken+'" />')}return K.find(":input").filter(function(){return !D(this).parent().hasClass("js-photo-postdata")}).serializeArray()},acceptFileTypes:/(gif|jpg|jpeg|jpe|png)$/i,add:function(N,M){console.log("Fileupload: gallery add");var L=D(this);D(document).data("gallery-container",L);var K=vBulletin.upload.getUploadedPhotosDlg(true);D(".js-upload-progress",K).removeClass("h-hide");vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",K),vBulletin.phrase.get("uploading"));M.submit();D(".b-button--upload",K).trigger("focus")},done:function(Q,P){console.log("Fileupload: gallery done");var N=D(this),S=(P&&P.result&&P.result.errors&&(P.result.errors.length>0)),R;D(document).data("gallery-container",N);var M=vBulletin.upload.getUploadedPhotosDlg(false);if(P&&P.result&&!S&&P.result.edit){var K=D(P.result.edit),O=D(".photo-display",M),L=M.parent(),T=D(".photo-item-wrapper:not(.h-hide)",O).length;K.addClass("tmp-photo");if((T+1)%C==0){K.addClass("last-in-row")}if((T+1)>vBulletin.contentEntryBox.ATTACHLIMIT){D(".js-attach-limit-warning",M).show()}vBulletin.upload.adjustPhotoDialogForScrollbar(M);O.append(K);K.fadeIn("fast",function(){A(O);M.dialog("option","position",{of:window});if(L.hasClass("has-scrollbar")){O.animate({scrollTop:O[0].scrollHeight-O.height()},"fast")}});D(".js-continue-button, .btnPhotoUploadSave",M).show();return }else{if(S){R=P.result.errors[0][0]||P.result.errors[0][1];switch(R){case"please_login_first":R="you_must_be_logged_in_to_upload_photos";break;default:R=P.result.errors[0];break}}else{R="unknown_error"}}I(M);vBulletin.warning("upload",R)},fail:function(O,N){console.log("Fileupload: gallery fail");var L="error_uploading_image",K="error";if(N&&N.files.length>0){switch(N.files[0].error){case"acceptFileTypes":L="invalid_image_allowed_filetypes_are";K="warning";break}}var M=vBulletin.upload.getUploadedPhotosDlg(false);I(M);vBulletin.alert("upload",L)},always:function(M,L){console.log("Fileupload: gallery always");var K=vBulletin.upload.getUploadedPhotosDlg(false);K.find(".js-upload-progress").addClass("h-hide");I(K)}});D(".b-content-entry-panel__content--attachment",J).off("click",".js-upload-from-url").on("click",".js-upload-from-url",function(L){var K=D(this);$promtDlg=openPromptDialog({title:vBulletin.phrase.get("enter_url_file"),message:"",buttonLabel:{okLabel:vBulletin.phrase.get("ok"),cancelLabel:vBulletin.phrase.get("cancel")},onClickOK:function(N){var M=K.parent().find(".js-upload-progress");M.removeClass("h-hide");vBulletin.AJAX({call:"/uploader/url",data:{urlupload:N,attachment:1,uploadFrom:D(".js-uploadFrom",J).val()},skipdefaultsuccess:true,complete:function(){M.addClass("h-hide")},success:function(O){if(O.imageUrl){$panel=D(".b-content-entry-panel__content--attachment");O.name=O.filename;H.call($panel,O)}},title_phrase:"error_uploading_image"})}});return false});D(".b-content-entry-panel__content--attachment",J).fileupload({dropZone:null,dataType:"json",url:vBulletin.getAjaxBaseurl()+"/uploader/upload",type:"POST",previewAsCanvas:false,autoUpload:true,formData:function(L){console.log("Fileupload: attachments formData");var K=L.find(".b-content-entry-panel__content--attachment");if(K.length==0){K=D("<form>")}if(K.find('input[name="securitytoken"]').length){K.find('input[name="securitytoken"]').val(pageData.securitytoken)}else{K.append('<input type="hidden" name="securitytoken" value="'+pageData.securitytoken+'" />')}return K.find(":input").filter(function(){return !D(this).parent().hasClass("js-attach-postdata")}).serializeArray()},add:function(L,K){console.log("Fileupload: attachments add");J.find(".js-upload-progress").removeClass("h-hide");K.submit()},done:function(O,N){console.log("Fileupload: attachments done");var L=vBulletin.phrase.get("error_uploading_image");var K="error";var P=[];var M=this;if(N&&N.result){if(N.result.error){P.push(vBulletin.phrase.get(N.result.error))}else{D.each(N.result,function(Q,R){if(!R.error){H.call(M,R);return }else{if(R.error[0]=="please_login_first"){P.push(vBulletin.phrase.get("you_must_be_logged_in_to_upload_photos"));return false}else{P.push(R.name);D.each(R.error,function(T,S){P.push(vBulletin.phrase.get(S))})}}})}}else{P.push(vBulletin.phrase.get("unknown_error"))}if(P.length>0){openAlertDialog({title:vBulletin.phrase.get("upload"),message:P.join("<br />\n"),iconType:"warning"})}},fail:function(N,M){console.log("Fileupload: attachments fail");var L=vBulletin.phrase.get("error_uploading_image");var K="error";if(M&&M.files.length>0){switch(M.files[0].error){case"acceptFileTypes":L=vBulletin.phrase.get("invalid_image_allowed_filetypes_are");K="warning";break}}openAlertDialog({title:vBulletin.phrase.get("upload"),message:L,iconType:K})},always:function(L,K){J.find(".js-upload-progress").addClass("h-hide")}});var H=function(L){console.log("Fileupload: attachDone");var S=(this instanceof D)?this:D(this),O=S.find(".js-attach-list"),N=D(".js-uploadFrom").val();if(!L.name){openAlertDialog({title:vBulletin.phrase.get("upload"),message:vBulletin.phrase.get("unknown_error"),iconType:"warning"})}var M,K,P=false;if(L.name.match(/\.(gif|jpg|jpeg|jpe|png|bmp)$/i)){K=D("<img />").attr("src",pageData.baseurl+"/filedata/fetch?type=thumb&filedataid="+L.filedataid).addClass("b-attach-item__image");P=true}else{K=D("<span />").addClass("b-icon b-icon__doc--gray h-margin-bottom-m")}var Q=O.find(".js-attach-item-sample").first().clone(true);if(N=="signature"){O.find(".js-attach-item").not(".js-attach-item-sample").remove();P=false;D('[data-action="insert"]',Q).data("action","insert_sigpic").attr("data-action","insert_sigpic")}Q.removeClass("js-attach-item-sample");Q.find(".js-attach-item-image").append(K);Q.find(".js-attach-item-filename").text(L.name);Q.append(D("<input />").attr({type:"hidden",name:"filedataids[]"}).val(L.filedataid)).append(D("<input />").attr({type:"hidden",name:"filenames[]"}).val(L.name));var R="";if(M=L.name.match(/\.([a-z]+)$/i)){R=M[1]}Q.data("fileext",R);Q.data("filename",L.name);Q.data("filedataid",L.filedataid);Q.data("attachnodeid",0);if(!P){Q.find(".js-attach-ctrl").filter(function(){return D(this).data("action")=="insert_image"||D(this).data("action")=="insert_label"}).addClass("h-hide");Q.find(".js-attach-ctrl").filter(function(){return D(this).data("action")=="insert"}).html(vBulletin.phrase.get("insert"))}if(!vBulletin.ckeditor.checkEnvironment()){Q.find(".js-attach-ctrl").filter(function(){return D(this).data("action")=="insert"||D(this).data("action")=="insert_image"||D(this).data("action")=="insert_label"}).addClass("h-hide")}Q.appendTo(O).removeClass("h-hide");O.removeClass("h-hide")};D(".gallery-submit-form",J).submit(function(){D(".js-photo-display .photo-display input",D(this).closest(".gallery-editor")).appendTo(D(this));var K=D("input[type=hidden][name=ret]",this);if(D.trim(K.val())==""){K.val(location.href)}});D(document).off("click.photoadd",".js-photo-selector-continue").on("click.photoadd",".js-photo-selector-continue",function(){console.log("Fileupload: continue 2");var K=D(this).closest(".js-photo-selector-container"),L={};K.find(".photo-item-wrapper").each(function(){var M=D(this).find(".filedataid"),P=M.data("nodeid");if(M.is(":checked")){var N=M.val(),O=D.trim(D(this).find(".photo-title").text());L[P]={imgUrl:vBulletin.getAjaxBaseurl()+"/filedata/fetch?filedataid="+N+"&thumb=1",filedataid:N,title:O}}});if(!D.isEmptyObject(L)){vBulletin.AJAX({call:"/ajax/render/photo_item",data:{items:L,wrapperClass:"tmp-photo"},success:function(N){var O=vBulletin.upload.getUploadedPhotosDlg(false),P=D(".photo-display",O),M;P.append(N);M=D(".photo-item-wrapper:not(.h-hide)",P);vBulletin.upload.relocateLastInRowClass(M);vBulletin.upload.updateButtons(O,(M.length>0));vBulletin.upload.adjustPhotoDialogForScrollbar(O);O.dialog("option","position",{of:window});O.dialog("open")}})}D(".photo-selector-galleries",K).tabs("destroy");K.dialog("close")});D(".js-photo-selector-cancel",J).off("click").on("click",function(){console.log("Fileupload: cancel 2");D(document).data("gallery-container",null);var K=D(this).closest(".js-photo-selector-container");D(".photo-selector-galleries",K).tabs("destroy");K.dialog("close")});console.log("Fileupload: vBulletin.upload.initializePhotoUpload finished")};vBulletin.upload.getUploadedPhotosDlg=function(H,J){var I;if(!J||J.length==0){J=D(document).data("gallery-container");if(!J){return D()}}if(J.hasClass("profile-media-photoupload-dialog")){I=J;if(H){I.dialog("open");vBulletin.upload.adjustPhotoDialogForScrollbar(I)}return I}I=J.find(".js-photo-display");if(I.length==0){D(".js-photo-display").each(function(){if(D(this).data("associated-editor")==J.get(0)){I=D(this);return false}})}else{I.dialog({modal:true,width:606,autoOpen:false,showCloseButton:false,closeOnEscape:false,resizable:false,showTitleBar:false,dialogClass:"dialog-container upload-photo-dialog-container dialog-box"});I.data("orig-width",I.dialog("option","width"));I.data("associated-editor",J.get(0));I.find(".b-form-input__file--hidden").prop("disabled",false)}if(H){I.dialog("open");vBulletin.upload.adjustPhotoDialogForScrollbar(I)}return I};vBulletin.upload.adjustPhotoDialogForScrollbar=function(K){var L=D(".photo-display",K);var J=K.parent();var I=D(".photo-item-wrapper:not(.h-hide)",L).length;if(!J.hasClass("has-scrollbar")&&I>=(C*E)){var H=window.vBulletin.getScrollbarWidth();J.addClass("has-scrollbar");K.dialog("option","width",K.dialog("option","width")+H+11)}};vBulletin.upload.relocateLastInRowClass=function(H){H.removeClass("last-in-row").filter(":not(.h-hide)").filter(function(I){return((I%C)==(C-1))}).addClass("last-in-row")};vBulletin.upload.updateButtons=function(K,I){var H=D(".b-button--upload .js-upload-label",K),J=D(".js-continue-button, .btnPhotoUploadSave",K);if(I){vBulletin.upload.changeButtonText(H,vBulletin.phrase.get("upload_more"))}else{vBulletin.upload.restoreButtonText(H)}J.toggle(I)};function F(){D(document).off("click.removephoto",".photo-display .photo-item .remove-icon").on("click.removephoto",".photo-display .photo-item .remove-icon",function(){var I=D(this).closest(".photo-item-wrapper"),M=D(".filedataid",I).data("nodeid"),L=I.parents(".js-photo-display").last();if(typeof M!=="undefined"){var K=D.inArray(M,G);if(K!=-1){G.splice(K,1)}}I.addClass("h-hide");var H=L.find(".photo-item-wrapper:not(.h-hide)"),J=H.length;if(J<=vBulletin.contentEntryBox.ATTACHLIMIT){D(".js-attach-limit-warning",L).hide()}vBulletin.upload.relocateLastInRowClass(H);if(H.length<=(C*E)){L.parent().removeClass("has-scrollbar");L.dialog("option","width",L.data("orig-width"))}vBulletin.upload.updateButtons(L,(H.length>0))});D(document).off("blur.photocaption",".photo-display .photo-caption .caption-box").on("blur.photocaption",".photo-display .photo-caption .caption-box",function(){D(this).scrollTop(0)});vBulletin.conversation.bindEditFormEventHandlers("gallery")}function A(I){var H=D(".photo-item .photo-caption .caption-box",I);H.filter("[placeholder]").placeholder()}function B(H){D(this).replaceWith(H);A(H)}D(document).ready(function(){vBulletin.upload.initializePhotoUpload();F()})})(jQuery);;
