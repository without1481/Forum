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
function init_validation(C){var B=fetch_object(C);for(var A=0;A<B.elements.length;A++){switch(B.elements[A].tagName){case"INPUT":switch(B.elements[A].type){case"text":case"password":case"file":YAHOO.util.Event.on(B.elements[A],"blur",validate_setting);break;case"radio":case"checkbox":case"button":if(is_opera){YAHOO.util.Event.on(B.elements[A],"keypress",validate_setting)}YAHOO.util.Event.on(B.elements[A],"click",validate_setting);break;default:}break;case"SELECT":YAHOO.util.Event.on(B.elements[A],"change",validate_setting);break;case"TEXTAREA":YAHOO.util.Event.on(B.elements[A],"blur",validate_setting);break;default:}}YAHOO.util.Event.on(document,"mousedown",capture_results);YAHOO.util.Event.on(document,"mouseup",display_results)}var settings_validation=new Array();var settings_validation_cache=new Array();var settings_validation_cleanup=new Array();var mouse_down=false;function capture_results(A){A=A?A:window.event;mouse_down=(A.type=="mousedown")}function display_results(B){B=B?B:window.event;mouse_down=(B.type=="mousedown");for(var A in settings_validation_cleanup){if(YAHOO.lang.hasOwnProperty(settings_validation_cleanup,A)){fetch_object("tbody_error_"+A).style.display="none";delete settings_validation_cleanup[A]}}for(var A in settings_validation_cache){if(YAHOO.lang.hasOwnProperty(settings_validation_cache,A)){fetch_object("tbody_error_"+A).style.display="";fetch_object("span_error_"+A).innerHTML=settings_validation_cache[A];delete settings_validation_cache[A]}}}function validate_setting(A){A=A?A:window.event;if(this.id){this.varname=this.id.replace(/^.+\[(.+)\].*$/,"$1")}else{this.varname=this.name.replace(/^.+\[(.+)\].*$/,"$1")}settings_validation[this.varname]=new vB_Setting_Validator(this.varname);return true}function vB_Setting_Validator(A){this.varname=A;this.query_string="";this.check_setting()}vB_Setting_Validator.prototype.check_setting=function(){this.container=fetch_object("tbody_"+this.varname);this.form=new vB_Hidden_Form("options.php");this.form.add_variable("do","validate");try{this.form.add_variable("adminhash",fetch_object("optionsform").adminhash.value)}catch(A){}this.form.add_variables_from_object(this.container);this.query_string=this.form.build_query_string()+"varname="+this.varname;this.form=null;YAHOO.util.Connect.asyncRequest("POST","admincp/options.php?do=validate&varname=",{success:handle_validation,timeout:vB_Default_Timeout},SESSIONURL+"securitytoken="+SECURITYTOKEN+"&"+this.query_string)};function handle_validation(C){if(C.responseXML){var B=C.responseXML.getElementsByTagName("varname")[0].firstChild.nodeValue;var A=C.responseXML.getElementsByTagName("valid")[0].firstChild.nodeValue;var D=fetch_object("tbody_error_"+B);if(D){if(D.style.display!="none"){if(mouse_down){settings_validation_cleanup[B]=true}else{D.style.display="none"}}if(A!=1){if(mouse_down){settings_validation_cache[B]=A}else{fetch_object("tbody_error_"+B).style.display="";fetch_object("span_error_"+B).innerHTML=A}}}else{}}}function count_errors(){var A=fetch_tags(document,"tbody");for(var B=0;B<A.length;B++){if(A[B].id&&A[B].id.substr(0,12)=="tbody_error_"&&A[B].style.display!="none"){return confirm(error_confirmation_phrase)}}return true}if(AJAX_Compatible){init_validation("optionsform")};