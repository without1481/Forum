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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["january","february","march","april","may","june","july","august","september","october","november","december","january_short","february_short","march_short","april_short","may_short","june_short","july_short","august_short","september_short","october_short","november_short","december_short","sunday","monday","tuesday","wednesday","thursday","friday","saturday","sunday_short","monday_short","tuesday_short","wednesday_short","thursday_short","friday_short","saturday_short","datepicker_clicktotoggle","datepicker_rangeseparator","datepicker_scrolltoincrement","datepicker_weekabbreviation",]);window.vBulletin.options=window.vBulletin.options||{};window.vBulletin.options.precache=window.vBulletin.options.precache||[];window.vBulletin.options.precache=$.merge(window.vBulletin.options.precache,["pickerdateformat",]);(function(F){function O(Z,W){var V=["january","february","march","april","may","june","july","august","september","october","november","december"],a=["sunday","monday","tuesday","wednesday","thursday","friday","saturday"],X=(W=="long")?"":"_short",U=(Z=="month")?V:a,Y=[];F.each(U,function(b,c){Y.push(vBulletin.phrase.get(c+X))});return Y}function L(){var Y=vBulletin.options.get("pickerdateformat"),V="",Z="";if(pageData.user_lang_pickerdateformatoverride){Y=pageData.user_lang_pickerdateformatoverride}var b="",e=null,a=null,W=null,U=null,X=false,d=false;F.each(Y.split(""),function(f,i){var h=!!i.match(/[GHhiSsK]/),j=!!i.match(/[a-z]/i),g=j&&!h;if(b!="\\"){if(h){if(i==="K"){X=true}if(i==="h"){d=true}if(e===null){e=f}a=f}else{if(g){if(W===null){W=f}U=f}}}b=i});if(a<W){var c=a+1;Z=Y.slice(0,c);V=Y.slice(c)}else{if(U<e){var c=U+1;V=Y.slice(0,c);Z=Y.slice(c)}else{V=Y;Z=Y}}return{datetime:Y,date:V,time:Z,use24hour:!(X||d)}}function J(V){var U="m/d/Y",Z="H:i",X=L(),Y;var W={time_24hr:X.use24hour,onReady:C,onOpen:G,onChange:P,altInput:true,locale:{weekdays:{shorthand:O("weekday","short"),longhand:O("weekday","long"),},months:{shorthand:O("month","short"),longhand:O("month","long"),},rangeSeparator:vBulletin.phrase.get("datepicker_rangeseparator"),weekAbbreviation:vBulletin.phrase.get("datepicker_weekabbreviation"),scrollTitle:vBulletin.phrase.get("datepicker_scrolltoincrement"),toggleTitle:vBulletin.phrase.get("datepicker_clicktotoggle"),firstDayOfWeek:(parseInt(pageData.user_startofweek,10)||1)-1,},};if(vBulletin.isRtl()){W.nextArrow="<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z' /></svg>";W.prevArrow="<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z' /></svg>"}switch(V){case"date":W.enableTime=false;W.noCalendar=false;W.dateFormat=U;W.altFormat=X.date;break;case"time":W.enableTime=true;W.noCalendar=true;W.dateFormat=Z;W.altFormat=X.time;break;case"datetime":W.enableTime=true;W.noCalendar=false;W.dateFormat=U+" "+Z;W.altFormat=X.datetime;break}return W}function A(W,V){var U=["onChange","onClose","onOpen","onMonthChange","onYearChange","onReady","onValueUpdate","onDayCreate"];F.each(U,function(X,Y){if(typeof V[Y]=="function"){V[Y]=[V[Y]]}if(typeof W[Y]=="function"){W[Y]=[W[Y]]}});return F.extend(true,{},W,V)}function D(W,V,U){V=V||{};V=A(J(U),V);W.flatpickr(V);W.attr("data-vb-flatpicker-initialized",U);W.trigger("vb-flatpicker-initialized")}function E(V,U){D(V,U,"date")}function N(V,U){D(V,U,"time")}function S(V,U){D(V,U,"datetime")}function T(Z){var X=Z.find(".js-daterange--min[data-vb-flatpicker-initialized]"),W=Z.find(".js-daterange--max[data-vb-flatpicker-initialized]");if(Z.data("vb-daterange-initialized")){return true}if(!X.length){F(".js-daterange--min",Z).one("vb-flatpicker-initialized",function(){T(Z)});return true}if(!W.length){F(".js-daterange--max",Z).one("vb-flatpicker-initialized",function(){T(Z)});return true}if(X.length&&W.length){var a=vBulletin.flatpickr.getInstance(X),b=vBulletin.flatpickr.getInstance(W);if(!a||!b){return true}var c=a.config.onChange,V=(function(){var d=false;return function(g,f,e,h){if(!d){d=true;if(f){b.set("minDate",f)}d=false}}})(),U=b.config.onChange,Y=(function(){var d=false;return function(g,f,e,h){if(!d){d=true;if(f){a.set("maxDate",f)}d=false}}})();c.push(V);a.set("onChange",c);U.push(Y);b.set("onChange",U)}Z.attr("data-vb-daterange-initialized",1)}function B(){F(".js-datepicker").each(function(){E(F(this))});F(".js-timepicker").each(function(){N(F(this))});F(".js-datetimepicker").each(function(){S(F(this))});F(".js-daterange").each(function(){T(F(this))})}function R(U){try{return U.get(0)._flatpickr}catch(V){}return null}function I(U){var V=R(U);if(V){U.data("vb-flatpickr-disabled","1");V.clear();V.close()}}function K(U){var V=R(U);if(V){U.data("vb-flatpickr-disabled","0")}}function H(b){if(!vBulletin.isRtl()){return }if(!(b&&b.calendarContainer&&b._positionElement)){return }var Y=b.calendarContainer,U=b._positionElement,Z=Y.offsetWidth,W=U.offsetWidth,X=U.getBoundingClientRect(),V=window.pageXOffset+X.left,a=window.document.body.offsetWidth-X.right,c=V+W<Z;if(!c){F(Y).addClass("rightMost").css({left:"auto",right:a+"px"})}}function C(W,V,U,X){H(U)}function G(X,V,U,Y){var W=F(U.element);H(U);if(W.data("vb-flatpickr-disabled")=="1"){U.close()}}function P(X,V,U,Y){var W=F(U.element);W.change();H(U)}function M(){B()}function Q(){F(M);vBulletin.flatpickr=vBulletin.flatpickr||{};vBulletin.flatpickr.initPickers=B;vBulletin.flatpickr.getInstance=R;vBulletin.flatpickr.disablePicker=I;vBulletin.flatpickr.enablePicker=K;vBulletin.flatpickr.initDatePicker=E;vBulletin.flatpickr.initTimePicker=N;vBulletin.flatpickr.initDateTimePicker=S}Q()})(jQuery);