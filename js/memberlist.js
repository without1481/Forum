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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,[]);(function(F){F(document).ready(function(){vBulletin.pagination({context:".memberlist-widget .conversation-toolbar",onPageChanged:A});var G=F(".js-drop-down-startswith");if(G.is(":visible")){G.selectBox().change(A)}else{var I=G.closest(".h-hide-on-large");I.removeClass("h-hide-on-large");G.selectBox().change(A);I.addClass("h-hide-on-large")}var H;H=".memberlist-widget .js-memberlist__letterfilter .letter";F(document).off("click",H).on("click",H,A);H=".memberlist-widget .js-sort-label";F(document).off("click",H).on("click",H,A);H=".js-memberlist-sortby-option";F(document).off("click",H).on("click",H,A);H=".js-pagenav .js-pagenav-button";F(document).off("click",H).on("click",H,C)});function C(I){var J=F(".pagenav-form"),H=J.get(0),G=F(this).data("page");J.find(".js-pagenum").val(G);A.call(H,G);return false}function B(G){var H=F(".memberlist-widget .js-toolbar-pagenav");H.find(".js-pagenum").val(G);H.find(".left-arrow, .right-arrow").removeClass("h-disabled");if(G<=1){H.find(".left-arrow").addClass("h-disabled")}var I=H.find(".pagetotal").text();if(G>=I){H.find(".right-arrow").addClass("h-disabled")}}function E(I){var H=F(".memberlist-widget .js-toolbar-pagenav"),G=H.find(".js-pagenum").val(),J=H.find(".right-arrow");H.find(".pagetotal").text(I);J.addClass("h-disabled");if(G<I){H.find(".right-arrow").removeClass("h-disabled")}}function D(I,H){F(".memberlist-widget .js-sort-by").removeClass("selected").find(".vb-icon").removeClass("vb-icon-triangle-up-wide vb-icon-triangle-down-wide").end().filter("[data-sortby={0}]".format(I)).addClass("selected").find(".vb-icon").addClass("vb-icon-triangle-{0}-wide".format((H=="asc")?"up":"down"));var G=F(".memberlist-widget .js-memberlist-sortby-option").closest(".b-comp-menu-dropdown__content-item").removeClass("b-comp-menu-dropdown__content-item--current").end().filter("[data-sortby={0}][data-sortorder={1}]".format(I,H)).closest(".b-comp-menu-dropdown__content-item").addClass("b-comp-menu-dropdown__content-item--current");G.closest(".js-comp-menu-dropdown").find(".js-comp-menu-dropdown__trigger-text").text(G.text())}function A(R){var N=F(this),O={},K,P,I;O.perpage=F(".memberlist-widget .js-per-page").data("perpage");if(N.hasClass("pagenav-form")){O.pagenumber=R}else{R=F(".memberlist-widget .toolbar-pagenav .js-pagenum").val()}O.pagenumber=R;var H=F(".js-memberlist__letterfilter .letter"),G=H.filter(".selected");if(N.hasClass("js-drop-down-startswith")){G.removeClass("selected");delete O.pagenumber;K=F(".js-drop-down-startswith").val();if(F(this.options[this.selectedIndex]).hasClass("all")){K="";H.filter(".all").addClass(".selected")}else{if(F(this.options[this.selectedIndex]).hasClass("numbers")){H.filter(".all").addClass(".numbers")}else{H.each(function(){if(F(this).html()==K){F(this).addClass("selected");return }})}}B(1)}else{if(N.hasClass("letter")){delete O.pagenumber;K=N.html();var L=K;if(N.hasClass("all")){K="";L="All"}F(".js-drop-down-startswith").val(L).selectBox("value",L);G.removeClass("selected");N.addClass("selected");B(1)}else{K=G.html();if(G.hasClass("all")){K=""}}}if(K.length){O.startswith=K}if(N.hasClass("js-memberlist-sortby-option")){delete O.pagenumber;P=N.data("sortby");I=N.data("sortorder");D(P,I);B(1)}else{if(N.hasClass("js-sort-label")){delete O.pagenumber;var J=N.parents(".js-sort-by");P=J.data("sortby");I=J.data("sortorder");if(J.hasClass("selected")){I=(I=="asc")?"desc":"asc";J.data("sortorder",I)}D(P,I);B(1)}else{var M=F(".memberlist-widget .js-sort-by.selected");P=M.data("sortby");I=M.data("sortorder")}}O.sortfield=P;O.sortorder=I;var Q=F(".js-memberlist-route-info").data("routeinfojson");vBulletin.AJAX({call:"/ajax/render/memberlist_items",data:{criteria:O,routeInfoJson:Q},success:function(S){F(".js-memberlist-table-body").replaceWith(S);var W=F(".js-memberlist-table-body"),U=W.data("totalPages"),T=W.find(".js-pagenav-current-button").data("page")||1;E(U);B(T);var V=W.find(".js-pagenav").clone();F(".memberlist-widget .js-under-toolbar-pagenav .js-pagenav").replaceWith(V);vBulletin.Responsive.Debounce.checkShrinkEvent(F(".js-pagenav"))}});return false}})(jQuery);