window.vBulletin=window.vBulletin||{};window.vBulletin.cache=function(){var H="vbcache-";var M="-timestamp";var B;var I;var D="global";var E;var N={global:{bucketProvider:function(){},latestChange:0,preCache:[]}};function G(){var Q="__vbcachetest__";var R=Q;if(B!==undefined){return B}try{localStorage.setItem(Q,R);var P=localStorage.getItem(Q);localStorage.removeItem(Q);B=(P==R)}catch(O){B=false}return B}function A(){if(I===undefined){I=(window.JSON!=null)}return I}function C(){var S=N[D];var O=[];for(var T in E){storedVal=E[T];if(storedVal&&T.indexOf(D)===0&&T.indexOf(M)<0){var V=T.substr(D.length);var Q=J(V);var U=L(Q);U=parseInt(U);if(U<N[D].latestChange){K(V);N[D].preCache.push(V)}else{O.push(V)}}}for(var P=N[D].preCache.length-1;P>=0;--P){for(var R=0;R<O.length;R++){if(N[D].preCache[P]==O[R]){N[D].preCache.splice(P,1);break}}}}function J(O){return O+M}function L(O){return E[D+O]}function F(P,Q){if(JSON.stringify(E)=="{}"){var O=localStorage.getItem(H);E=O?JSON.parse(O):{}}E[D+P]=Q;if(G()){localStorage.removeItem(H);localStorage.setItem(H,JSON.stringify(E))}}function K(O){if(!A()){return null}delete E[D+O];if(G()){localStorage.setItem(H,JSON.stringify(E))}}return{set:function(U,T){if(typeof T!=="string"){if(!A()){return }try{T=JSON.stringify(T)}catch(S){return }}try{F(U,T)}catch(S){if(S.name==="QUOTA_EXCEEDED_ERR"||S.name==="NS_ERROR_DOM_QUOTA_REACHED"){var V=[];var P;for(var W in E){storedVal=E[W];if(storedVal&&W.indexOf(D)===0&&W.indexOf(M)<0){var X=W.substr(D.length);var O=J(X);var R=L(O);R=parseInt(R);V.push({key:X,size:(L(X)||"").length,timestamp:R})}}V.sort(function(Z,Y){return(Y.timestamp-Z.timestamp)});var Q=(T||"").length;while(V.length&&Q>0){P=V.pop();K(P.key);K(J(P.key));Q-=P.size}try{F(U,T)}catch(S){return }}else{return }}F(J(U),window.pageData.current_server_datetime)},get:function(P){C();var Q=N[D];var O=J(P);var T=L(O);var R=L(P);if(R==null){R=Q.bucketProvider(P,N[D].preCache)}if(!R||!A()){return R}try{return JSON.parse(R)}catch(S){return R}},remove:function(O){K(O);K(J(O))},supported:function(){return G()},flush:function(){if(!G()){return }for(var P=localStorage.length-1;P>=0;--P){var O=localStorage.key(P);if(O.indexOf(H+D)===0){localStorage.removeItem(O)}}},initBucket:function(S,O,Q,R){N[S]={latestChange:O,bucketProvider:R,preCache:Q};D=S;var P=null;if(G()){P=localStorage.getItem(H)}if(P!==null){E=JSON.parse(P)}else{E={}}C()},setBucket:function(O){D=O}}}();