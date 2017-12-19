(function(A){A.tabbedPane=function(B,C){var F=this,H=A(B),G={paneContext:H,paneSelectedClassName:"b-tabbed-pane__pane--selected",paneSelector:".b-tabbed-pane__pane",isResponsive:false,tabSetSelector:".b-tabbed-pane__tabs",tabSelectedClassName:"b-tabbed-pane__tab--selected",tabSelector:".b-tabbed-pane__tab"},E;F.init=function(){F.settings=E=A.extend({},G,C);H.data("tabbedPane",{});E.tabSelectedClass="."+E.tabSelectedClassName;E.paneSelectedClass="."+E.paneSelectedClassName;F.getTabs();F.bindTabClicks();if(E.isResponsive){F.bindFitTabs()}};F.getTabs=function(){var I;F.tabs=H.find(E.tabSelector);F.panes=A(),F.tabs.each(function(){var K=A(this),J=K.children("a");K.data("tabbedPane",{});targetId=J.attr("href");targetId=targetId.match(/#([^\?]+)/)[0].substr(1);I=E.paneContext.find("#"+targetId);if(I.length){F.panes=F.panes.add(I);K.data("tabbedPane").pane=I}else{F.tabs=F.tabs.not(K)}})};F.bindTabClicks=function(){F.tabs.children("a").bind("click.tabbedPane",function(I,J){I.preventDefault();if(H.triggerHandler("selectTab",[this])===false){return false}F.selectTab(A(this),J||E.callback)})};F.bindFitTabs=function(){H.bind("testfit",function(){var J=A(F.tabs[0]),I=A(F.tabs[F.tabs.length-1]);H.find(E.tabSetSelector).removeClass("b-tabbed-pane__tabs--stacked");if(I.offset().top>J.offset().top){H.find(E.tabSetSelector).addClass("b-tabbed-pane__tabs--stacked")}});triggerTestFit=function(){H.trigger("testfit")};A(window).bind("load resize orientationchange",A.debounce(300,triggerTestFit))};F.selectTab=function(J,M){var K=window.location,L=K.hash.match(/^[^\?]*/)[0],I=J.parent().data("tabbedPane").pane;if(!J.hasClass(E.tabActiveClass)||!I.hasClass(E.paneActiveClass)){D(J,I,M)}};F.publicMethods={getSelectedTab:function(){return F.tabs.filter(E.tabSelectedClass)},getSelectedPane:function(){return F.panes.filter(E.paneSelectedClass)},selectTab:function(I,J){I.trigger("click",[J]).focus()},findTabAnchorByHash:function(I){return F.tabs.children("a").filter('a[href*="'+I+'"]')}};var D=function(J,I,K){F.tabs.filter(E.tabSelectedClass).removeClass(E.tabSelectedClassName).children().removeClass(E.tabSelectedClassName);J.parent().addClass(E.tabSelectedClassName).children().addClass(E.tabSelectedClassName);F.panes.filter(E.paneSelectedClass).removeClass(E.paneSelectedClassName);I.addClass(E.paneSelectedClassName);if(typeof K==="function"){K.apply(H,[J,I])}};F.init()};A.fn.tabbedPane=function(D){var C=arguments,B,E=this.each(function(){var G=A(this),F=G.data("tabbedPane");if(undefined===F){F=new A.tabbedPane(this,D);G.data("tabbedPane",F)}if(F.publicMethods[D]){B=F.publicMethods[D].apply(F,Array.prototype.slice.call(C,1));return false}});return typeof B!=="undefined"?B:E}})(jQuery);