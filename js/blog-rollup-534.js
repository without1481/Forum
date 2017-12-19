// ***************************
// js.compressed/sb_blogs.js
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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["create_a_blog","error_creating_user_blog_channel","error_fetching_user_blog_channels","select_a_blog"]);(function(A){var B=[".bloghome-widget"];if(!vBulletin.pageHasSelectors(B)){return false}A(document).ready(function(){vBulletin.conversation=vBulletin.conversation||{};A(document).off("click",".bloghome-widget .conversation-toolbar .new-conversation-btn").on("click",".bloghome-widget .conversation-toolbar .new-conversation-btn",function(C){parentNodeId=A(this).closest(".canvas-widget").data("blog-channel-id");vBulletin.AJAX({call:"/ajax/api/user/getGitCanStart",data:({parentNodeId:parentNodeId}),success:function(D){if(!A.isArray(D)){vBulletin.error("error","error_fetching_user_blog_channels");return }var E=D.length;if(E>1){var G=A("#user-blogs-dialog"),F=A("select.custom-dropdown",G);A.each(D,function(H,I){A("<option />").val(I.nodeid).html(I.title).appendTo(F)});G.dialog({title:vBulletin.phrase.get("select_a_blog"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:500,dialogClass:"dialog-container create-blog-dialog-container dialog-box",open:function(){F.removeClass("h-hide").selectBox()},close:function(){F.selectBox("destroy").find("option").remove()},create:function(){A(".btnContinue",this).on("click",function(){location.href="{0}/new-content/{1}".format(pageData.baseurl,A("select.custom-dropdown",G).val())});A(".btnCancel",this).on("click",function(){G.dialog("close")})}}).dialog("open")}else{if(E==1){location.href="{0}/new-content/{1}".format(pageData.baseurl,D[0].nodeid)}else{vBulletin.AJAX({call:"/ajax/api/blog/canCreateBlog",data:{parentid:parentNodeId},success:function(I){var H=A("#create-blog-dialog").dialog({title:vBulletin.phrase.get("create_a_blog"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:500,dialogClass:"dialog-container create-blog-dialog-container dialog-box",create:function(){vBulletin.ajaxForm.apply(A("form",this),[{success:function(K,L,M,J){if(A.isPlainObject(K)&&Number(K.nodeid)>0){location.href="{0}/new-content/{1}".format(pageData.baseurl,K.nodeid)}else{vBulletin.error("error","error_creating_user_blog_channel")}},error_phrase:"error_creating_user_blog_channel"}]);A(".btnCancel",this).on("click",function(){H.dialog("close")});A(".blog-adv-settings",this).on("click",function(){var K=A.trim(A(".blog-title",H).val()),J=A.trim(A(".blog-desc",H).val());if(K||J){location.href="{0}?blogtitle={1}&blogdesc={2}".format(this.href,encodeURIComponent(K),encodeURIComponent(J));return false}return true})},open:function(){A("form",this).trigger("reset")}}).dialog("open")},title_phrase:"create_a_blog"})}}},error_phrase:"error_fetching_user_blog_channels",})})})})(jQuery);;

// ***************************
// js.compressed/blog_summary.js
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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["blog_subscribers_list","blog_subscribers","unable_to_contact_server_please_try_again"]);(function(C){var E=[".summary-widget",".blogadmin-widget"];if(!vBulletin.pageHasSelectors(E)){return false}var A=function(){C("#blogSubscribersSeeAll").off("click").on("click",function(F){B(C(this).attr("data-node-id"));F.stopPropagation();return false})};var D;var B=function(H,G,F){D=C("#blogSubscribersAll").dialog({title:vBulletin.phrase.get("blog_subscribers_list"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:450,dialogClass:"dialog-container dialog-box blog-subscribers-dialog"});vBulletin.pagination({context:D,onPageChanged:function(I,J){B(H,I)}});D.off("click",".blog-subscribers-close").on("click",".blog-subscribers-close",function(){D.dialog("close")});D.off("click",".action_button").on("click",".action_button",function(){if(!C(this).hasClass("subscribepending_button")){var I=C(this),K=parseInt(I.attr("data-userid"),10),J="";if(I.hasClass("subscribe_button")){J="add"}else{if(I.hasClass("unsubscribe_button")){J="delete"}}if((typeof (K)=="number")&&J){vBulletin.AJAX({call:"/profile/follow-button",data:{"do":J,follower:K,type:"follow_members"},success:function(O){if(O==1||O==2){var N,P,M;if(J=="add"){var L=(O==1)?"subscribed":"subscribepending";N="subscribe_button b-button b-button--special";P=L+"_button b-button b-button--secondary";M=(O==1)?"following":"following_pending";I.attr("disabled","disabled")}else{if(J=="delete"){N="subscribed_button unsubscribe_button b-button b-button--special";P="subscribe_button b-button b-button--secondary";M="follow"}}I.removeClass(N).addClass(P).text(vBulletin.phrase.get(M))}},title_phrase:"profile_guser",error_phrase:"unable_to_contact_server_please_try_again"})}}});if(!G){G=1}if(!F){F=10}vBulletin.AJAX({call:"/ajax/render/subscribers_list",data:{nodeid:H,page:G,perpage:F},success:function(I){D.dialog("close");C(".blog-subscribers-content",D).html(I);D.dialog("open")},title_phrase:"blog_subscribers",error_phrase:"unable_to_contact_server_please_try_again"})};C(document).ready(function(){A()})})(jQuery);;

