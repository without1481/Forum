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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["create_a_blog","error_creating_user_blog_channel","error_fetching_user_blog_channels","select_a_blog"]);(function(A){var B=[".bloghome-widget"];if(!vBulletin.pageHasSelectors(B)){return false}A(document).ready(function(){vBulletin.conversation=vBulletin.conversation||{};A(document).off("click",".bloghome-widget .conversation-toolbar .new-conversation-btn").on("click",".bloghome-widget .conversation-toolbar .new-conversation-btn",function(C){parentNodeId=A(this).closest(".canvas-widget").data("blog-channel-id");vBulletin.AJAX({call:"/ajax/api/user/getGitCanStart",data:({parentNodeId:parentNodeId}),success:function(D){if(!A.isArray(D)){vBulletin.error("error","error_fetching_user_blog_channels");return }var E=D.length;if(E>1){var G=A("#user-blogs-dialog"),F=A("select.custom-dropdown",G);A.each(D,function(H,I){A("<option />").val(I.nodeid).html(I.title).appendTo(F)});G.dialog({title:vBulletin.phrase.get("select_a_blog"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:500,dialogClass:"dialog-container create-blog-dialog-container dialog-box",open:function(){F.removeClass("h-hide").selectBox()},close:function(){F.selectBox("destroy").find("option").remove()},create:function(){A(".btnContinue",this).on("click",function(){location.href="{0}/new-content/{1}".format(pageData.baseurl,A("select.custom-dropdown",G).val())});A(".btnCancel",this).on("click",function(){G.dialog("close")})}}).dialog("open")}else{if(E==1){location.href="{0}/new-content/{1}".format(pageData.baseurl,D[0].nodeid)}else{vBulletin.AJAX({call:"/ajax/api/blog/canCreateBlog",data:{parentid:parentNodeId},success:function(I){var H=A("#create-blog-dialog").dialog({title:vBulletin.phrase.get("create_a_blog"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:500,dialogClass:"dialog-container create-blog-dialog-container dialog-box",create:function(){vBulletin.ajaxForm.apply(A("form",this),[{success:function(K,L,M,J){if(A.isPlainObject(K)&&Number(K.nodeid)>0){location.href="{0}/new-content/{1}".format(pageData.baseurl,K.nodeid)}else{vBulletin.error("error","error_creating_user_blog_channel")}},error_phrase:"error_creating_user_blog_channel"}]);A(".btnCancel",this).on("click",function(){H.dialog("close")});A(".blog-adv-settings",this).on("click",function(){var K=A.trim(A(".blog-title",H).val()),J=A.trim(A(".blog-desc",H).val());if(K||J){location.href="{0}?blogtitle={1}&blogdesc={2}".format(this.href,encodeURIComponent(K),encodeURIComponent(J));return false}return true})},open:function(){A("form",this).trigger("reset")}}).dialog("open")},title_phrase:"create_a_blog"})}}},error_phrase:"error_fetching_user_blog_channels",})})})})(jQuery);