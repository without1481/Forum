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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["close","email_addresses_must_match","error","invalid_email_address","moderateuser","paid_subscription_required","paid_subscriptions","password_must_be_at_least_four_chars","passwords_must_match","please_enter_a_username","please_enter_your_email_address","please_enter_your_parent_or_guardians_email_address","please_select_a_day","please_select_a_month","please_select_a_year","register_not_agreed","registeremail","registration_complete","registration_coppa_fail","registration_gregister","registration_start_failed","site_terms_and_rules","site_terms_and_rules_title"]);window.vBulletin.options=window.vBulletin.options||{};window.vBulletin.options.precache=window.vBulletin.options.precache||[];window.vBulletin.options.precache=$.merge(window.vBulletin.options.precache,["checkcoppa","regrequirepaidsub","reqbirthday","usecoppa","webmasteremail"]);(function(A){var B=[".registration-widget"];if(!vBulletin.pageHasSelectors(B)){return false}A(document).ready(function(){var K=parseInt(window.vBulletin.options.get("usecoppa")),I=false,E=false,D=parseInt(window.vBulletin.options.get("reqbirthday")),P=parseInt(window.vBulletin.options.get("regrequirepaidsub")),M,F,O,L;function J(){A("#frmRegister").trigger("reset");setTimeout(vBulletin.hv.reset,0);if(K||D){var W=A.cookie(pageData.cookie_prefix+"coppaage");var T,Q,R,V=false;if(K&&W&&parseInt(window.vBulletin.options.get("checkcoppa"))){var U=W.split("-",3);if(U.length==3){T=parseInt(U[0]);Q=parseInt(U[1]);R=parseInt(U[2]);if(T!=0&&Q!=0&&R!=0){V=true;A("#regMonth").val(T);A("#regDay").val(Q);A("#regYear").val(R)}H(T,Q,R,V,true)}}else{A(".signup-content").removeClass("h-hide")}A("#regMonth, #regDay, #regYear").off("change").on("change",function(){T=A("#regMonth").val();Q=A("#regDay").val();R=A("#regYear").val();H(T,Q,R,V)})}else{A(".signup-content").removeClass("h-hide")}A(".registration-widget select").selectBox();A("#regDataUsername").off("keydown blur").on("keydown",function(X){if(X.keyCode==13){A(this).triggerHandler("blur")}return true}).on("blur",function(){if(A.trim(this.value)==""){return true}E=true;var X=this;vBulletin.AJAX({call:"/registration/checkusername",data:{username:A.trim(X.value)},success:function(Y){},complete:function(){E=false},after_error:function(){X.value="";X.select();X.focus()}})});A("#viewTerms").off("click").on("click",function(){console.log(window.vBulletin.options.get("webmasteremail"));openAlertDialog({title:vBulletin.phrase.get("site_terms_and_rules_title"),message:vBulletin.phrase.get("site_terms_and_rules",window.vBulletin.options.get("webmasteremail"),pageData.baseurl),buttonLabel:vBulletin.phrase.get("close"),width:600,maxHeight:400});return false});var S=A(".paidsubscription_row");if(S.length>0){A("select.cost",S).change(function(){var c=A(this).closest(".newsubscription_row"),a=A(this).find("option:selected").first(),Z=A(this).closest(".subscriptions_list"),Y=A(".order_confirm",S),b=A(".payment-form",S),X=c.data("allowedapis");M=c.data("id");F=a.data("subid");L=a.data("currency");A('<tr class="confirm_data"><td>'+a.data("subtitle")+"</td><td>"+a.data("duration")+"</td><td>"+a.data("value")+"</td></tr>").appendTo(A(".order_confirm_table",Y));Z.addClass("h-hide");Y.off("click",".remove_subscription").on("click",".remove_subscription",function(){A(".confirm_data",Y).remove();A("input.paymentapi",Y).closest("label").removeClass("h-hide");A(".subscriptions-order",Y).prop("disabled",false);A("select.cost",Z).selectBox("value","");Y.addClass("h-hide");Z.removeClass("h-hide");M=0;return false});Y.off("click","input.paymentapi").on("click","input.paymentapi",function(){O=A("input.paymentapi:checked",Y).val()});A("input.paymentapi",Y).each(function(){var e=A(this),d=e.data("currency").split(",");if(A.inArray(L,d)==-1||A.inArray(e.val(),X)==-1){e.closest("label").addClass("h-hide")}});Y.removeClass("h-hide")})}}function H(T,Q,S,U,R){R=R||false;if(T!=0&&Q!=0&&S!=0){if(U){A(".birth-date-wrapper").addClass("h-hide")}vBulletin.AJAX({call:"/registration/iscoppa",data:{month:T,day:Q,year:S},success:function(Y){if(Y&&typeof (Y.needcoppa)!="undefined"){I=Y.needcoppa!=0;var X=A(".birth-date-wrapper"),W=A(".signup-content");if(Y.needcoppa==2){if(!U){vBulletin.error("error","registration_coppa_fail");X.addClass("h-hide")}W.addClass("h-hide");A(".coppafail_notice").removeClass("h-hide");return }else{var Z=A(".js-registration__paidsubscription"),V=((Y.needcoppa==1)||(!R)),a=(Y.needcoppa==1);console.log(V,(Y.needcoppa==1),(!R));X.toggleClass("h-hide",V);W.removeClass("h-hide");Z.toggleClass("h-hide-imp",a);Z.find("select,input").prop("disabled",a);if(V){A("#regContent").removeClass("h-hide");A("#frmRegister .js-button-group").removeClass("h-hide")}else{A(".registration-widget select").selectBox()}}}A(".coppa")[I?"removeClass":"addClass"]("h-hide-imp")},error_phrase:"registration_start_failed"})}}J();function G(Q,R){if(Q){vBulletin.warning("error",Q,function(){R.focus()});return false}return true}function N(){var R="",W;if(K){if(A("#regMonth").val()==0){R="please_select_a_month";W="#regMonth"}else{if(A("#regDay").val()==0){R="please_select_a_day";W="#regDay"}else{if(A("#regYear").val()==0){R="please_select_a_year";W="#regYear"}}}if(!G(R,A(W).next(".selectBox"))){return false}}var V,U=A("#regDataUsername"),X=A("#regDataPassword"),S=A("#regDataConfirmpassword"),Q=A("#regDataEmail"),T=A("#regDataEmailConfirm");$parentEmail=A("#parentGuardianEmail");if(A.trim(U.val())==""){R="please_enter_a_username";V=U}else{if(X.val()==""||X.val().length<4){R="password_must_be_at_least_four_chars";V=X}else{if(X.val()!=S.val()){R="passwords_must_match";V=S}else{if(A.trim(Q.val())==""){R="please_enter_your_email_address";V=Q}else{if(!isValidEmailAddress(Q.val())){R="invalid_email_address";V=Q}else{if(Q.val()!=T.val()){R="email_addresses_must_match";V=T}else{if(I){if(A.trim($parentEmail.val())==""){R="please_enter_your_parent_or_guardians_email_address";V=$parentEmail}else{if(!isValidEmailAddress($parentEmail.val())){R="invalid_email_address";V=$parentEmail}}}}}}}}}if(!G(R,V)){return false}if(!A("#cbApproveTerms").is(":checked")){R="register_not_agreed";V=A("#cbApproveTerms")}if(P&&!M){R="paid_subscription_required";V=A(".cost")}if(M&&!O){R="please_select_a_payment_method";V=A("input.paymentapi").first()}return G(R,V)}function C(){var Q=this;console.log("Paid Subscriptions Data: subscriptionid: "+M+"; subscriptionsubid: "+F+"; paymentapiclass: "+O+"; currency: "+L);if(!N()){return false}A(".js-button-group .js-button",Q).prop("disabled",true);vBulletin.AJAX({call:"/registration/registration",data:A(Q).serializeArray(),success:function(R){if(R.usecoppa){location.replace(R.urlPath)}else{if(R.newtoken&&R.newtoken!="guest"){vBulletin.doReplaceSecurityToken(R.newtoken)}var S=function(){var U=R.msg_params;U.unshift(R.msg);vBulletin.alert("registration_gregister",U,null,function(){if(R.urlPath){location.replace(R.urlPath)}else{A(".signup-success").removeClass("h-hide");A(".signup-content").addClass("h-hide")}})};if(!M){S()}else{var T=openConfirmDialog({title:vBulletin.phrase.get("paid_subscriptions"),message:vBulletin.phrase.get("loading")+"...",width:500,dialogClass:"paidsubscription-dialog loading",buttonLabel:{yesLabel:vBulletin.phrase.get("order"),noLabel:vBulletin.phrase.get("cancel")},onClickYesWithAjax:true,onClickYes:function(){A(this).closest(".paidsubscription-dialog").find("form").submit()},onClickNo:function(){if(P){vBulletin.warning("error","paid_subscription_required",function(){T.dialog("open")})}else{S()}}});vBulletin.AJAX({call:"/ajax/api/paidsubscription/placeorder",data:{subscriptionid:M,subscriptionsubid:F,paymentapiclass:O,currency:L},complete:function(){A("body").css("cursor","auto")},success:function(U){A(".paidsubscription-dialog").removeClass("loading");A(".dialog-content .message",T).html(U);A('.dialog-content .message input[type="submit"], .dialog-content .message .js-subscription__cancel',T).hide();T.dialog("option","position",{of:window})},error_message:"error_payment_form"})}}},error_phrase:"registration_start_failed",complete:function(){A(".js-button-group .js-button",Q).prop("disabled",false)}})}A("#frmRegister").off("submit.usersignup").on("submit.usersignup",function(){var Q=this;setTimeout(function(){if(E){return }C.apply(Q)},10);return false});A("#regBtnReset").off("click").on("click",function(Q){vBulletin.hv.reset();setTimeout(function(){A(".registration-widget select").selectBox("refresh");A("#regMonth").next(".selectBox").focus()},50)})})})(jQuery);