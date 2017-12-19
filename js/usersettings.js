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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["aim","email_addresses_must_match","google_talk_im","icq","passwords_must_match","signature","signature_saved","skype","usersetting_signatures_link","yahoo_im","end_subscription","end_subscription_confirm","cancel","two_factor_authentication","mfa_enabled","mfa_verified"]);window.vBulletin.options=window.vBulletin.options||{};window.vBulletin.options.precache=window.vBulletin.options.precache||[];window.vBulletin.options.precache=$.merge(window.vBulletin.options.precache,["ctMaxChars"]);(function(C){window.vBulletin=window.vBulletin||{};var D=[".profile-settings-widget"];if(!vBulletin.pageHasSelectors(D)){return false}var A="usersettings";vBulletin.usersettings=vBulletin.usersettings||{};vBulletin.usersettings.signature;var B;vBulletin.usersettings.init=function(){C(".js-signature__edit").off("click").on("click",function(K){K.preventDefault();C("#editSignatureDialog").dialog({title:vBulletin.phrase.get("usersetting_signatures_link"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:500,dialogClass:"signature-dialog-container dialog-container dialog-box",create:function(){var L=C(this),M=C(".js-editor",L);vBulletin.ckeditor.initEditor(M,{success:function(O){var N=vBulletin.ckeditor.getEditor(O);N.setData(M.prev(".js-editor-parsed-text").html(),function(){N.resetDirty()});L.on("dialogopen",function(){N.setData(C(".js-signature__preview").html(),function(){N.resetDirty()})})},error:function(){M.val(M.prev(".js-editor-parsed-text").html().replace(/<br\s*[\/]?>/gi,"\n")).data("orig-value",M.val());L.on("dialogopen",function(){M.val(C(".js-signature__preview").html().replace(/<br\s*[\/]?>/gi,"\n")).data("orig-value",M.val())})},hideLoadingDialog:true});C(".js-signature__cancel",L).off("click").on("click",function(N){var O=vBulletin.ckeditor.editorExists(M)?vBulletin.ckeditor.getEditor(M).checkDirty():M.val()!=M.data("orig-value");if(O){openConfirmDialog({title:vBulletin.phrase.get("cancel_edit"),message:vBulletin.phrase.get("all_changes_made_will_be_lost_would_you_like_to_continue"),iconType:"warning",onClickYes:function(){L.dialog("close")}})}else{L.dialog("close")}});C(".js-signature__submit",L).off("click").on("click",function(Q){Q.preventDefault();var N=vBulletin.ckeditor.editorExists(M)?vBulletin.ckeditor.getEditor(M).getData():M.val();var P=[];C('input[name|="filedataids[]"]',L).each(function(){P.push(C(this).val())});var O=function(R){C(".js-signature__preview").html(R);vBulletin.alert("signature","signature_saved");L.dialog("close")};vBulletin.AJAX({url:this.form.action,type:this.form.method,data:{signature:N,filedataids:P},title_phrase:"signature",error_phrase:"error_saving_signature",success:O,emptyResponse:O,complete:function(){C("body").css("cursor","default")}});return false})}}).dialog("open");return false});var I=C(".js-mfa-reset"),H=I.closest("form"),G=C(".js-mfa-enable"),F=G.closest("form");var J=function(K){K.preventDefault();vBulletin.AJAX({call:"/ajax/api/user/resetMfaSecret",data:H.serializeArray(),success:function(M){C(".js-mfa-request-panel").addClass("h-hide");var P=C(".js-mfa-secret-panels"),N=C(".js-mfa-qr-code"),L=N.parent().width(),O="otpauth://totp/"+escape(N.data("descrption"))+"?secret="+M.secret;P.hide();P.removeClass("h-hide");C(".js-mfa-secret-text").text(M.secret);N.html("");N.qrcode({width:L,height:L,text:O});P.show("slow")},after_error:function(){H.find("input").not('[name="securitytoken"]').val("")}})};var E=function(K){K.preventDefault();vBulletin.AJAX({call:"/ajax/api/user/enableMfa",data:F.serializeArray(),success:function(L){vBulletin.alert("two_factor_authentication",(L.alreadyenabled?"mfa_verified":"mfa_enabled"));F.find('input[name="mfa_authcode"]').val("")},after_error:function(){F.find("input").not('[name="securitytoken"]').val("")}})};I.off("click").on("click",J);H.off("submit").on("submit",J);G.off("click").on("click",E);F.off("submit").on("submit",E)};C(document).ready(function(){C(".js-user-settings form").trigger("reset");vBulletin.usersettings.init();C(".b-form-select__select").selectBox();var K=window.vBulletin.options.get("ctMaxChars");C(".js-user-settings__reset").off("click").on("click",resetFormFields);setSelectedOption(C(".js-birth__month"),C("#bd_month"));C(".js-birth__month").selectBox().change(function(){C("#bd_day").val(C(".js-birth__day option:selected").val());updateDaySelectBox(C(".js-birth__day"),C(".js-birth__month").val())});C(".js-birth__year").off("blur").on("blur",function(){C("#bd_day").val(C(".js-birth__day option:selected").val());updateDaySelectBox(C(".js-birth__day"),C(".js-birth__month").val())});vBulletin.ajaxForm.apply(C("#profileSettings_form"),[{success:function(){window.location.reload(true)}}]);createDaySelectBox(C(".js-birth__day"));var P=C(".js-user-settings .b-tabbed-pane__tabs").data("allow-history")==1,L=vBulletin.history.instance(P),R=C(".js-module-top-anchor").attr("id");if(L.isEnabled()){var G=L.getState();if(!G||C.isEmptyObject(G.data)){var O=location.pathname.match(/\/(profile|account|privacy|notifications|subscriptions)\/?$/),Q=(O&&O[1])||"profile",T={from:"tabs",tab:Q};L.setDefaultState(T,document.title,location.href)}L.setStateChange(function(Y){var X=L.getState();if(X.data.from=="tabs"){L.log(X.data,X.title,X.url);var W=B.tabbedPane("getSelectedTab"),V="#"+X.data.tab+"Tab";if(W.children("a").prop("hash")!=V){var U=B.tabbedPane("findTabAnchorByHash",V).data("bypassPushState",true);B.tabbedPane("selectTab",U)}}},"tabs")}B=C(".b-tabbed-pane").tabbedPane({isResponsive:true,callback:function(X,U){if(L.isEnabled()){if(!X.data("bypassPushState")){var V={from:"tabs",tab:X.data("url-path")},W=vBulletin.makeTabUrl(location.href,V.tab,false);L.pushState(V,document.title,W)}}C("select.b-form-select__select").selectBox();X.data("bypassPushState",null)}});if(!L.isEnabled()){B.on("selectTab",function(V,U){location.href=vBulletin.makeTabUrl(location.href,C(U).data("url-path"),false,R);return false})}var I={aim:vBulletin.phrase.get("aim"),google:vBulletin.phrase.get("google_talk_im"),skype:vBulletin.phrase.get("skype"),yahoo:vBulletin.phrase.get("yahoo_im"),icq:vBulletin.phrase.get("icq")};var N=Object.keys(I).length;var E=function(U){var V=0;U.find(".selectBox.js-im").each(function(){var W=C(this).width();if(W>V){V=W}});return V};var H=function(V,U){V.find(".selectBox.js-im").each(function(){var W=C(this);W.width(U);W.find(".selectBox-label").css("width","auto")})};C(document).off("change",".selectBox.js-im").on("change",".selectBox.js-im",function(){$screenNamesContainer=C(this).closest(".js-screen-name__container");var U=E($screenNameContainer);updateProviders($screenNamesContainer,I);H($screenNameContainer,U)});setIMSelectedOption(C("select.js-im"),I);var F="b-icon b-icon__x-circle--light h-margin-horiz-l h-margin-top-xs h-left js-screen-name__remove";C(".js-screen-name__new").off("click").on("click",function(Y){Y.preventDefault();var W=C(this),Z=C(this).parents(".js-screen-names").find(".js-screen-name__container"),U=Z.find(".js-screen-name").length,X=E(Z);if(U<N){var V='<div class="h-margin-bottom-xs h-clearfix js-screen-name">';V+='<input type="text" name="user_screennames[]" class="js-user_screenname h-left b-form-input__input h-margin-right-l h-margin-bottom-s" value="" />';V+='<select name="user_im_providers[]" class="js-im--'+U+' js-im b-form-select__select h-left" data-orig-value-class="js-im--'+U+'">';V+=getAvailableIMs(Z,I);V+="</select>";V+='<div class="'+F+'"></div></div>';Z.append(V);Z.find(".js-im--"+U).selectBox();Z.find(".js-user_screenname:last").focus();if(Z.find(".js-screen-name").length>=N){W.hide()}updateProviders(Z,I);H(Z,X)}});C(".js-screen-names").each(function(){$container=C(this);var U=E($container);H($container,U);if($container.find(".selectBox.js-im").length>=N){$container.find(".js-screen-name__new").hide()}});C(document).off("click",".js-screen-name__remove").on("click",".js-screen-name__remove",function(){var V=C(this).parents(".js-screen-name"),X=C(this).closest(".js-screen-names"),W=E(X),U=V.siblings(".js-screen-name").length;if(U<1){V.find(".js-user_screenname").val("")}else{V.find("select.js-im").selectBox("destroy");V.remove()}updateProviders(X,I);H(X,W);X.find(".js-screen-name__new").show()});C("#new_useremail").off("focus."+A).on("focus."+A,function(U){U.preventDefault();C("#new_email_container").addClass("isActive").slideDown("3000")});C("#user_newpass").off("focus."+A).on("focus."+A,function(U){U.preventDefault();C("#new_pass_container").addClass("isActive").slideDown("3000")});C("#settingsErrorClose").off("click").on("click",function(){C("#settingsErrorDialog").dialog("close")});C("#accountSettings_form").ajaxForm({dataType:"json",beforeSubmit:function(W,U,V){return J()},success:function(W,X,Y,V){if(W&&W.response&&W.response.newtoken){vBulletin.doReplaceSecurityToken(W.response.newtoken)}if(W&&W.response&&W.response.errors){var Z=[];for(var U in W.response.errors){if(W.response.errors[U][0]!="exception_trace"&&W.response.errors[U][0]!="errormsg"){Z.push(vBulletin.phrase.get(W.response.errors[U]))}}openAlertDialog({title:vBulletin.phrase.get("error"),message:Z.join("<br />"),iconType:"warning"})}else{window.location.reload(true)}}});var J=function(){var U=C("#user_newpass"),c=C("#user_newpass2"),d=U.val(),a=c.val();var b=function(f,e){vBulletin.error("error",f,function(){e.focus()})};if(d||a){var Y=C("#user_currentpass");if(Y.val()==""){b("enter_current_password",Y[0]);return false}if(d!=a){b("passwords_must_match",U[0]);return false}}else{U.removeAttr("name");c.removeAttr("name")}var Z=C("#new_useremail"),V=C("#new_useremail2"),W=Z.val(),X=V.val();if(W||X){var Y=C("#user_currentpass");if(Y.val()==""){b("enter_current_password",Y[0]);return false}if(W!=X){b("email_addresses_must_match",Z[0]);return false}if(!isValidEmailAddress(Z.val())){b("invalid_email_address",Z[0]);return false}}else{Z.removeAttr("name");V.removeAttr("name")}return true};S();function S(){var V=C("#ignorelist_container").val(),U=new vBulletin_Autocomplete(C("#ignorelist_container"),{apiClass:"user",minLength:C("#minuserlength").val(),maxItems:C("#maxitems").val()});if(V){V=V.split(",");C.each(V,function(W,X){X=C.trim(X);U.addElement(X)})}}C("#follower_request").off("change").on("change",function(){if(C(this).attr("checked")=="checked"){C("#general_followrequest").attr("checked",true)}});C("#general_followrequest").off("change").on("change",function(){if(!C(this).prop("checked")){C("#follower_request").prop("checked",false)}});var M=C("#subscriptionsTab");if(M.length>0){C(".js-subscription-cost",M).change(function(){var c=C(this).closest(".js-newsubscription_row"),W=C(this).find("option:selected").first(),Y=W.attr("data-currency"),Z=c.attr("data-id"),V=W.attr("data-subid"),a=C(this).closest(".js-subscriptions_list"),X=C(".js-order_confirm",M),b=C(".js-payment-form",M),U=C.parseJSON(c.attr("data-allowedapis"));C(this).selectBox("value","");C('<tr class="confirm_data"><td>'+W.attr("data-subtitle")+"</td><td>"+W.attr("data-duration")+"</td><td>"+W.attr("data-value")+"</td></tr>").appendTo(C(".js-order_confirm_table",X));a.addClass("h-hide");X.off("click",".js-subscription__remove").on("click",".js-subscription__remove",function(){C(".confirm_data",X).remove();C(".js-paymentapi",X).closest("label").removeClass("h-hide");C(".js-subscriptions-order",X).prop("disabled",false);C(".js-subscription-cost",a).selectBox("value","");X.addClass("h-hide");a.removeClass("h-hide");return false});X.off("click",".js-paymentapi").on("click",".js-paymentapi",function(){C(".js-subscriptions-order",X).prop("disabled",false)});C(".js-paymentapi",X).each(function(){var d=C(this).attr("data-currency").split(",");if(C.inArray(Y,d)==-1||C.inArray(C(this).val(),U)==-1){C(this).closest("label").addClass("h-hide")}});X.off("click",".js-subscriptions-order").on("click",".js-subscriptions-order",function(){C(".js-subscriptions-order",X).prop("disabled",true);C("body").css("cursor","wait");vBulletin.AJAX({call:"/ajax/api/paidsubscription/placeorder",data:{subscriptionid:Z,subscriptionsubid:V,paymentapiclass:C(".js-paymentapi:checked",X).val(),currency:Y},complete:function(){C("body").css("cursor","auto")},success:function(d){b.html(d);X.addClass("h-hide");a.addClass("h-hide");b.off("click",".js-subscription__cancel").on("click",".js-subscription__cancel",function(){b.html("").addClass("h-hide");C(".js-subscription__remove",X).click();return false});b.removeClass("h-hide")},emptyResponse:C.noop,api_error:C.noop})});X.removeClass("h-hide")});C(".js-subscription__end",M).click(function(){var U=C(this).attr("data-id"),V=C(this).closest("tr"),W=C(this).closest("table");openConfirmDialog({title:vBulletin.phrase.get("end_subscription"),message:vBulletin.phrase.get("end_subscription_confirm"),iconType:"warning",buttonLabel:{yesLabel:vBulletin.phrase.get("end_subscription"),noLabel:vBulletin.phrase.get("cancel")},onClickYes:function(){C("body").css("cursor","wait");vBulletin.AJAX({call:"/ajax/api/paidsubscription/endsubscription",data:{subscriptionid:U},complete:function(){C("body").css("cursor","auto")},success:function(X){V.remove();if(W.find("tr").length<=1){W.closest(".js-existing-subscription-row").remove()}},emptyResponse:C.noop,api_error:C.noop})}});return false})}});createDaySelectBox=function(E){var G="",I=C(".js-birth__month").val(),H=C("#bd_year").val(),J=new Date(H,I,0);if(C("#bd_day").val()==""||C("#bd_day").val()==null){G+="<option name='day' value='' selected='selected'></option>"}for(var F=1;F<=J.getDate();F++){F=(F<10)?("0"+F):F;if(F==C("#bd_day").val()){G+="<option name='day' value='"+F+"' selected='selected'>"+F+"</option>"}else{G+="<option name='day' value='"+F+"'>"+F+"</option>"}}E.html(G);E.selectBox("destroy").selectBox()};updateDaySelectBox=function(E,I){var H=(C(".js-birth__year").val()!="")?C(".js-birth__year").val():0,J=new Date(H,I,0),G="";J=J.getDate();for(var F=1;F<=J;F++){F=(F<10)?("0"+F):F;G+="<option name='day' value='"+F+"'>"+F+"</option>"}updateSelectBox(E,G)};updateSelectBox=function(E,F,H){F=(F)?F:E.html();var G=E.attr("class").split(" ");E.selectBox("destroy");E.removeData("selectBoxControl");E.removeData("selectBoxSettings");E.html(F);if(C("#"+G[0]).val()!=undefined){E.val(C("#"+G[0]).val())}E.selectBox()};setSelectedOption=function(F,E){F.val(E.val())};setIMSelectedOption=function(E,G){var F="";C.each(E,function(K,I){var J=C(I).data("orig-value-class");F=C(I).parent().children(":input[type=hidden]."+J).val();C(I).val(F)});var H="";C.each(E,function(J,I){$screenNameContainer=C(E).closest(".js-screen-name__container");H=C(I).closest(".js-screen-name").find("select.js-im option:selected");C.each(C(H),function(K,L){if(C(I).val()!=C(L).val()){C(I).find("option[value= "+C(L).val()+"]").remove()}});updateProviders($screenNameContainer,G)})};getAvailableIMs=function(F,G){var E="";C.each(G,function(H,I){if(F.find("select.js-im option:selected[value= "+H+"]").length==0){E+="<option name='im_provider' value='"+H+"'> "+I+"</option>"}});return E};updateImSelectBox=function(E,F){E.selectBox("destroy");E.removeData("selectBoxControl");E.removeData("selectBoxSettings");E.html(F);E.selectBox()};updateProviders=function(F,E){F.find("select.js-im").each(function(I,G){var J=C(G).val(),H="";C.each(E,function(K,L){if(F.find("select.js-im option:selected[value= "+K+"]").length==0){H+="<option name='im_provider' value='"+K+"'>"+L+"</option>"}else{if(K==J){H+="<option name='im_provider' value='"+K+"' selected='selected'>"+L+"</option>"}}});updateImSelectBox(C(G),H)})};resetFormFields=function(){var E=C(this.form);setTimeout(function(){C("input",E).trigger("change");C("select.b-form-select__select",E).each(function(){updateSelectBox(C(this))})},100)}})(jQuery);