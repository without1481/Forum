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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,[]);(function(A){function B(){A(".js-nojs-warning").remove();var C=A(".js-api-form");A(".js-api-form-submit").on("click",function(){var E=A(':input[name="api[class]"]',C).val()||A(':input[name="api[class]"]',C).attr("placeholder"),L=A(':input[name="api[method]"]',C).val()||A(':input[name="api[method]"]',C).attr("placeholder"),H=A(':input[name="parameters"]',C).val()||A(':input[name="parameters"]',C).attr("placeholder"),I=A.parseJSON(H)||{},D=C.attr("action"),J=A(':input[name="bogus_securitytoken"]',C).is(":checked");I.securitytoken=A(':input[name="securitytoken"]',C).val();if(J){I.securitytoken="hammertime"}D+=E+"/"+L;var G="Attempting to make ajax call with...  url: "+D+"  data: "+JSON.stringify(I);console.log(G);var K=A(':input[name="output"]'),F=A(".js-robot-helper");K.val(G);F.text("Waiting");vBulletin.AJAX({url:D,data:I,success:function(M){console.log(D+" success!");console.log(JSON.stringify(M));K.val(JSON.stringify(M));F.text("Done")},api_error:function(M){console.log(D+" API error!");console.log(JSON.stringify(M));K.val(JSON.stringify(M));F.text("Error")},error:function(M,O,N){console.log(D+" error!");console.dir(M);K.val(M.responseText+"\n\njqXHR: "+JSON.stringify(M)+"\nText Status: "+JSON.stringify(O)+"\nError: "+JSON.stringify(N));F.text("Error")},})});A(".js-api-form-addparam").on("click",function(){})}A(document).ready(B)})(jQuery);