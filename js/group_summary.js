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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["group_subscribers_list","group_subscribers","unable_to_contact_server_please_try_again"]);(function(A){var B=[".summary-widget"];if(!vBulletin.pageHasSelectors(B)){return false}vBulletin.group=vBulletin.group||{};vBulletin.group.initSeeAllSubscribers=function(){var C=A("#groupSubscribersSeeAll");if(C.length>0){C.click(function(D){vBulletin.group.showSubscribers(A(this).attr("data-node-id"));D.stopPropagation();return false})}};vBulletin.group.showSubscribers=function(E,D,C){if(!vBulletin.group.groupSubscribersAllOverlay){vBulletin.group.groupSubscribersAllOverlay=A("#groupSubscribersAll").dialog({title:vBulletin.phrase.get("group_subscribers_list"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:450,dialogClass:"dialog-container dialog-box group-subscribers-dialog"});vBulletin.pagination({context:"#groupSubscribersAll",onPageChanged:function(F,G){vBulletin.group.showSubscribers(E,F)}});A(document).off("click",".group-subscribers-close").on("click",".group-subscribers-close",function(){vBulletin.group.groupSubscribersAllOverlay.dialog("close")});A(document).off("click","#groupSubscribersAll .action_button").on("click","#groupSubscribersAll .action_button",function(){if(!A(this).hasClass("subscribepending_button")){var F=A(this);var H=parseInt(F.attr("data-userid"));var G="";if(F.hasClass("subscribe_button")){G="add"}else{if(F.hasClass("unsubscribe_button")){G="delete"}}if((typeof (H)=="number")&&G){A.ajax({url:vBulletin.getAjaxBaseurl()+"/profile/follow-button?do="+G+"&follower="+H+"&type=follow_members",type:"POST",dataType:"json",success:function(J){if(J==1||J==2){if(G=="add"){var I=(J==1)?"subscribed":"subscribepending";var K=(J==1)?"following":"following_pending";F.removeClass("subscribe_button b-button b-button--special").addClass(I+"_button b-button b-button--secondary").text(vBulletin.phrase.get(K))}else{if(G=="delete"){F.removeClass("subscribed_button unsubscribe_button b-button b-button--special").addClass("subscribe_button b-button b-button--secondary").text(vBulletin.phrase.get("follow"))}}}else{if(J.errors){openAlertDialog({title:vBulletin.phrase.get("profile_guser"),message:vBulletin.phrase.get("error_x",J.errors[0][0]),iconType:"error"})}}},error:function(){openAlertDialog({title:vBulletin.phrase.get("profile_guser"),message:vBulletin.phrase.get("unable_to_contact_server_please_try_again"),iconType:"error"})}})}}})}if(!D){D=1}if(!C){C=10}A.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/render/subscribers_list",type:"POST",data:{nodeid:E,page:D,perpage:C},dataType:"json",success:function(F){if(F&&F.errors){openAlertDialog({title:vBulletin.phrase.get("group_subscribers"),message:vBulletin.phrase.get(F.errors[0]),iconType:"error"})}else{A(".group-subscribers-content",vBulletin.group.groupSubscribersAllOverlay).html(F)}},error:function(){openAlertDialog({title:vBulletin.phrase.get("group_subscribers"),message:vBulletin.phrase.get("unable_to_contact_server_please_try_again"),iconType:"error"})}});vBulletin.group.groupSubscribersAllOverlay.dialog("open")};A(document).ready(function(){vBulletin.group.initSeeAllSubscribers()})})(jQuery);