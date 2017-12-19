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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["add_tags","error","error_adding_search_tips_code_x","error_adding_tags_code_x","error_fetching_popular_tags","invalid_server_response_please_try_again","invalid_x_tag_length","loading","popular_tags_cloud","tag_cloud","you_may_add_one_more_tag","you_may_add_x_more_tags","you_may_not_add_any_more_tags"]);window.vBulletin.options=window.vBulletin.options||{};window.vBulletin.options.precache=window.vBulletin.options.precache||[];window.vBulletin.options.precache=$.merge(window.vBulletin.options.precache,["tagforcelower","tagmaxlen","tagminlen"]);(function(A){vBulletin.tagEditor=vBulletin.tagEditor||{};vBulletin.tagEditor.instance=function(L,S,d){var U=this,P,W=false,a=false,Z=window.vBulletin.options.get("tagmaxlen")||25,G=window.vBulletin.options.get("tagminlen")||3,C=0,K=0,N=false,g=A(L),H=g.closest(".tag-editor-container"),F=g.closest(".tag-editor-wrapper"),M=H.data("node-id")||0,c=false,J=g.attr("placeholder")?g.attr("placeholder"):"",I=J?J.length:10,f=function(){if(g.length==0){return false}g.attr("maxlength",Z);c=new vBulletin_Autocomplete(g,{apiClass:"Tags",containerClass:(d||"")+" entry-field h-clearfix",placeholderText:J,editorContext:{type:"tags",context:U},minLength:G,beforeAdd:function(i,j){W=a?true:false;if(h()==0){return false}return true},afterAdd:function(i,j){var k=b();if(k==0){g.hide()}g.attr("placeholder","");e(H.find(".tagcloudlink"),H)},afterDelete:function(j,k){W=a?true:false;var l=b();if(S||l>0){g.show();setTimeout(function(){g[0].focus()},10)}g.attr("placeholder",function(){var m=B();return(!m||m.length==0)?J:""});if(g.attr("placeholder")){g.placeholder()}var i=H.find(".tag-cloud .tagcloudlink");if(i.length==0){i=A(".ui-tooltip-popular-tags{0} .tag-cloud .tagcloudlink".format(F.data("tag-editor-class")?"."+F.data("tag-editor-class"):""))}i.each(function(){if(A(this).data("tag")==j){A(this).removeClass("added")}})}});g=c.getInputField();if(g.attr("placeholder")){g.placeholder()}F.data("autocomplete-instance",c)},V=function(i){if(window.vBulletin.options.get("tagforcelower")==1){i=i.toLowerCase()}if(i.length>Z){i=i.substr(0,Z)}return i},X=function(l,j){if(!c){return false}if(typeof j=="undefined"||j<1){j=pageData.userid}var i=c.getMinLength();if(l.length<i){openAlertDialog({title:vBulletin.phrase.get("error"),message:vBulletin.phrase.get("invalid_x_tag_length",i),iconType:"warning"});return false}l=V(l);var k=c.addElement(l,j);g.val("");b();return k},O=function(l){var j=l.length,k;for(k=0;k<j;++k){X(l[k])}},Y=function(i){if(!c){return false}c.clearElements();O(i)},R=function(){g.attr("size",I);var i=JShtmlEncode(A.trim(g.val()));if(i==""||(i==J&&J)){return false}var j="Adding tag: "+i;if(h()>0){setTimeout(function(){X(i)},10);j+=" (Tag added)"}console.log(j)},h=function(){if(S||(C==0&&K==0)){console.log("Tag limits not enforced.");return true}var m=c.countElements(),i=A(c.getElements()).filter(function(n,o){return(pageData.userid==0||o.value==pageData.userid)}).size(),l=C>0?C-m:K-m,k=K==0?l:K-i,j=Math.min(k,l);console.log(["tagCount: "+m,"userTagCount: "+i,"maxTags: "+C,"maxUserTags: "+K,"totalCanAdd: "+l,"userCanAdd: "+k,"canAdd: "+j]);return j},b=function(){var i=H.find(".you-may-add-x-tags");var j=h();if(j===true){return true}else{if(j>1){i.removeClass("warning").html(vBulletin.phrase.get("you_may_add_x_more_tags",j))}else{if(j==1){i.removeClass("warning").html(vBulletin.phrase.get("you_may_add_one_more_tag"))}else{i.addClass("warning").html(vBulletin.phrase.get("you_may_not_add_any_more_tags"));return 0}}}return j},T=function(){c.clearElements()},B=function(){if(!c){return false}return c.getLabels()},E=function(i){if(S){g[0].focus();return }else{if(!vBulletin.tagEditor.tagLimits||!vBulletin.tagEditor.tagLimits[i]){vBulletin.tagEditor.tagLimits={};vBulletin.tagEditor.tagLimits[i]={}}else{if(vBulletin.tagEditor.tagLimits&&vBulletin.tagEditor.tagLimits[i]){C=vBulletin.tagEditor.tagLimits[i].maxTags;K=vBulletin.tagEditor.tagLimits[i].maxUserTags;N=vBulletin.tagEditor.tagLimits[i].canManageTags;Q();return }}}A.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/api/tags/getNodeTags",data:({nodeid:i}),type:"POST",dataType:"json",complete:function(){console.log("/ajax/api/tags/getNodeTags complete")},success:function(j){console.log("/ajax/api/tags/getNodeTags successful");if(j&&A.isArray(j.tags)){C=parseInt(j.maxtags,10);K=parseInt(j.maxusertags,10);N=j.canmanagetags;vBulletin.tagEditor.tagLimits={};vBulletin.tagEditor.tagLimits[i]={maxTags:C,maxUserTags:K,canManageTags:N};Q()}else{console.log("result: "+JSON.stringify(j))}},error:function(l,k,j){console.log("/ajax/api/tags/getNodeTags failed, error: "+j)}})};this.addTag=function(j,i){X(j,i)};this.addTags=function(i){O(i)};this.setTags=function(i){Y(i)};this.removeAllTags=function(){T()};this.getTags=function(){return B()};this.getTagEditor=function(){return g};if(F.data("initialized")){console.log("Tag Editor is already initialized.");return }else{F.attr("data-initialized",true)}f();g.off("keydown").on("keydown",function(j){var i=A(j.target).val().length;A(j.target).attr("size",(i>I?i:I));if(j.which==13){W=a?true:false;R();j.preventDefault();return false}});g.off("blur").on("blur",function(i){a=window.setTimeout(function(){if(!W){R()}W=false;a=false},0)});g.off("click").on("click",function(i){if(!A(i.target).hasClass("tag-input")){A(".tag-input",A(this)).focus();W=a?true:false}return false});g.each(function(){var i=A(this);i.closest("form").submit(function(){R()})});var Q=function(){if(!C&&!K){H.find(".you-may-add-text").addClass("h-hide")}else{if(C&&!K){H.find(".you-may-add-text span").text(C)}else{if(!C&&K){H.find(".you-may-add-text span").text(K)}else{H.find(".you-may-add-text span").text(Math.min(C,K))}}}if(!h()){g.hide()}else{if(g.is(":visible")){g[0].focus()}}};A(".tag-cloud-text.inline-tag-cloud .popular-tags-link",H).off("click").on("click",function(){var i=H.find(".tag-cloud");vBulletin.tagEditor.fetchPopularTags({type:"nodes",noformat:1,success:function(j){i.html(j);e(i.find(".tagcloudlink"),H)},error:function(j){i.html('<span class="error">{0}</span>'.format(j))}})});A(".tag-cloud-text:not(.inline-tag-cloud) .popular-tags-link",H).qtip({content:{text:vBulletin.phrase.get("loading")+"...",ajax:{url:vBulletin.getAjaxBaseurl()+"/search/fetchTagCloud",data:({type:"nodes",noformat:1}),type:"POST",dataType:"json",success:function(i,l,k){console.log("/search/fetchTagCloud successful, response: "+JSON.stringify(i));if(i){if(i.errors){this.set("content.text",'<span class="error">{0} {1}</span>'.format(vBulletin.phrase.get("error_fetching_popular_tags"),vBulletin.phrase.get(i.errors[0][0])))}else{this.set("content.text",'<div class="tag-cloud">{0}</div>'.format(i));A(".ui-tooltip-popular-tags").delegate(".tag-cloud .tagcloudlink label","click",D);var j=A(".ui-tooltip-popular-tags{0} .tag-cloud .tagcloudlink".format(F.data("tag-editor-id")?"."+F.data("tag-editor-id"):""));e(j,F)}}else{this.set("content.text",'<span class="error">{0}</span>'.format(vBulletin.phrase.get("error_fetching_popular_tags")))}},error:function(k,j,i){console.log("/search/fetchTagCloud failed, error: "+i);this.set("content.text",'<span class="error">{0}</span>'.format(vBulletin.phrase.get("error_fetching_popular_tags")))}},title:{text:vBulletin.phrase.get("tag_cloud"),button:false}},position:{at:"bottom center",my:"top center",viewport:A(window),effect:false},show:{event:"click",solo:true},hide:"unfocus",style:{classes:"ui-tooltip-shadow ui-tooltip-light ui-tooltip-rounded ui-tooltip-popular-tags "+(F.data("tag-editor-class")||""),width:280},events:{show:function(k,j){var i=A(".ui-tooltip-popular-tags{0} .tag-cloud .tagcloudlink".format(F.data("tag-editor-class")?"."+F.data("tag-editor-class"):""));e(i,F)}}});var D=function(j){var i=A(this);if(!i.parent().hasClass("added")&&X(i.text())){i.parent().addClass("added");if(g.is(":visible")){g[0].focus()}}return false};var e=function(i,j){if(i.length>0){var k=(j.find(".tag-input").val()||"").split(",");A.each(k,function(l,m){var n=A.trim(m);i.each(function(){if(A(this).data("tag")==n){A(this).addClass("added")}})})}};H.delegate(".tag-cloud .tagcloudlink label","click",D);F.delegate(".add-tag-link","click",function(j){j.stopPropagation();var i=A(this);var k=F.find(".tag-editor-container");if(k.length==1){k.dialog({title:vBulletin.phrase.get("add_tags"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:500,dialogClass:"dialog-container tag-editor-dialog-container dialog-box",open:function(){T();var l=F.find(".tag-input").val();if(l){var o=l.split(",");for(var n in o){X(A.trim(o[n]))}}else{if(J&&!g.attr("placeholder")){g.attr("placeholder",J)}}var p=b();if(p>0){g.show()}E(i.data("node-id"));var m=A(".tag-cloud",k);vBulletin.tagEditor.fetchPopularTags({type:F.data("type")||"nodes",noformat:1,success:function(q){m.html(q);e(m.find(".tagcloudlink"),F)},error:function(q){m.html('<span class="error">{0}</span>'.format(q))}})},close:function(){var l=F.data("autocomplete-instance");l.close()}}).dialog("open");i.data("tag-dialog",k);k.data("parent",i.closest("form, .search-controls-tags, .form_row"))}else{k=i.data("tag-dialog")||A();k.dialog("open")}return false})};vBulletin.tagEditor.fetchPopularTags=function(B){B=B||{};B.type=B.type!="search"?"nodes":B.type;A.ajax({url:vBulletin.getAjaxBaseurl()+"/search/fetchTagCloud",data:({type:B.type,noformat:B.noformat}),type:"POST",dataType:"json",success:function(C,E,D){console.log("/search/fetchTagCloud successful, response: "+JSON.stringify(C));if(C){if(C.errors){if(typeof B.error=="function"){B.error("{0} {1}".format(vBulletin.phrase.get("error_fetching_popular_tags"),vBulletin.phrase.get(C.errors[0][0])))}}else{if(typeof B.success=="function"){B.success(C,E,D)}}}else{if(typeof B.error=="function"){B.error(vBulletin.phrase.get("error_fetching_popular_tags"))}}},error:function(E,D,C){console.log("/search/fetchTagCloud failed, error: "+C);if(typeof B.error=="function"){B.error(vBulletin.phrase.get("error_fetching_popular_tags"))}},complete:function(C,D){if(typeof B.complete=="function"){B.complete(C,D)}}})};A(document).ready(function(){A(document).off("click",".tag-editor-container .cancel-tag-btn").on("click",".tag-editor-container .cancel-tag-btn",function(){A(".tag-editor-container").dialog("close")});A(document).off("click",".tag-editor-container .save-tag-btn").on("click",".tag-editor-container .save-tag-btn",function(){var E=A(this).closest(".tag-editor-container"),D=E.find(".tag-input").val(),B=D.replace(/\,/g,", "),C=E.data("parent");C.find(".tag-list span").html(B);C.find(".tag-input").val(D);C.find(".tag-list").toggleClass("h-hide",!D);E.dialog("close")});A(document).off("mousedown",".tag-editor-container .autocomplete-container").on("mousedown",".tag-editor-container .autocomplete-container",function(){var B=this;setTimeout(function(){var C=A(".autocompleteHelper:visible",B);if(C.length>0){C[0].focus()}},10);return false});if(A(".js-content-entry-tag-editor").length>0&&vBulletin.tagEditor){new vBulletin.tagEditor.instance(".js-content-entry-tag-editor")}})})(jQuery);