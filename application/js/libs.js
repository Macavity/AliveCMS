/*! jQuery v1.9.0 | (c) 2005, 2012 jQuery Foundation, Inc. | jquery.org/license */(function(e,t){"use strict";function n(e){var t=e.length,n=st.type(e);return st.isWindow(e)?!1:1===e.nodeType&&t?!0:"array"===n||"function"!==n&&(0===t||"number"==typeof t&&t>0&&t-1 in e)}function r(e){var t=Tt[e]={};return st.each(e.match(lt)||[],function(e,n){t[n]=!0}),t}function i(e,n,r,i){if(st.acceptData(e)){var o,a,s=st.expando,u="string"==typeof n,l=e.nodeType,c=l?st.cache:e,f=l?e[s]:e[s]&&s;if(f&&c[f]&&(i||c[f].data)||!u||r!==t)return f||(l?e[s]=f=K.pop()||st.guid++:f=s),c[f]||(c[f]={},l||(c[f].toJSON=st.noop)),("object"==typeof n||"function"==typeof n)&&(i?c[f]=st.extend(c[f],n):c[f].data=st.extend(c[f].data,n)),o=c[f],i||(o.data||(o.data={}),o=o.data),r!==t&&(o[st.camelCase(n)]=r),u?(a=o[n],null==a&&(a=o[st.camelCase(n)])):a=o,a}}function o(e,t,n){if(st.acceptData(e)){var r,i,o,a=e.nodeType,u=a?st.cache:e,l=a?e[st.expando]:st.expando;if(u[l]){if(t&&(r=n?u[l]:u[l].data)){st.isArray(t)?t=t.concat(st.map(t,st.camelCase)):t in r?t=[t]:(t=st.camelCase(t),t=t in r?[t]:t.split(" "));for(i=0,o=t.length;o>i;i++)delete r[t[i]];if(!(n?s:st.isEmptyObject)(r))return}(n||(delete u[l].data,s(u[l])))&&(a?st.cleanData([e],!0):st.support.deleteExpando||u!=u.window?delete u[l]:u[l]=null)}}}function a(e,n,r){if(r===t&&1===e.nodeType){var i="data-"+n.replace(Nt,"-$1").toLowerCase();if(r=e.getAttribute(i),"string"==typeof r){try{r="true"===r?!0:"false"===r?!1:"null"===r?null:+r+""===r?+r:wt.test(r)?st.parseJSON(r):r}catch(o){}st.data(e,n,r)}else r=t}return r}function s(e){var t;for(t in e)if(("data"!==t||!st.isEmptyObject(e[t]))&&"toJSON"!==t)return!1;return!0}function u(){return!0}function l(){return!1}function c(e,t){do e=e[t];while(e&&1!==e.nodeType);return e}function f(e,t,n){if(t=t||0,st.isFunction(t))return st.grep(e,function(e,r){var i=!!t.call(e,r,e);return i===n});if(t.nodeType)return st.grep(e,function(e){return e===t===n});if("string"==typeof t){var r=st.grep(e,function(e){return 1===e.nodeType});if(Wt.test(t))return st.filter(t,r,!n);t=st.filter(t,r)}return st.grep(e,function(e){return st.inArray(e,t)>=0===n})}function p(e){var t=zt.split("|"),n=e.createDocumentFragment();if(n.createElement)for(;t.length;)n.createElement(t.pop());return n}function d(e,t){return e.getElementsByTagName(t)[0]||e.appendChild(e.ownerDocument.createElement(t))}function h(e){var t=e.getAttributeNode("type");return e.type=(t&&t.specified)+"/"+e.type,e}function g(e){var t=nn.exec(e.type);return t?e.type=t[1]:e.removeAttribute("type"),e}function m(e,t){for(var n,r=0;null!=(n=e[r]);r++)st._data(n,"globalEval",!t||st._data(t[r],"globalEval"))}function y(e,t){if(1===t.nodeType&&st.hasData(e)){var n,r,i,o=st._data(e),a=st._data(t,o),s=o.events;if(s){delete a.handle,a.events={};for(n in s)for(r=0,i=s[n].length;i>r;r++)st.event.add(t,n,s[n][r])}a.data&&(a.data=st.extend({},a.data))}}function v(e,t){var n,r,i;if(1===t.nodeType){if(n=t.nodeName.toLowerCase(),!st.support.noCloneEvent&&t[st.expando]){r=st._data(t);for(i in r.events)st.removeEvent(t,i,r.handle);t.removeAttribute(st.expando)}"script"===n&&t.text!==e.text?(h(t).text=e.text,g(t)):"object"===n?(t.parentNode&&(t.outerHTML=e.outerHTML),st.support.html5Clone&&e.innerHTML&&!st.trim(t.innerHTML)&&(t.innerHTML=e.innerHTML)):"input"===n&&Zt.test(e.type)?(t.defaultChecked=t.checked=e.checked,t.value!==e.value&&(t.value=e.value)):"option"===n?t.defaultSelected=t.selected=e.defaultSelected:("input"===n||"textarea"===n)&&(t.defaultValue=e.defaultValue)}}function b(e,n){var r,i,o=0,a=e.getElementsByTagName!==t?e.getElementsByTagName(n||"*"):e.querySelectorAll!==t?e.querySelectorAll(n||"*"):t;if(!a)for(a=[],r=e.childNodes||e;null!=(i=r[o]);o++)!n||st.nodeName(i,n)?a.push(i):st.merge(a,b(i,n));return n===t||n&&st.nodeName(e,n)?st.merge([e],a):a}function x(e){Zt.test(e.type)&&(e.defaultChecked=e.checked)}function T(e,t){if(t in e)return t;for(var n=t.charAt(0).toUpperCase()+t.slice(1),r=t,i=Nn.length;i--;)if(t=Nn[i]+n,t in e)return t;return r}function w(e,t){return e=t||e,"none"===st.css(e,"display")||!st.contains(e.ownerDocument,e)}function N(e,t){for(var n,r=[],i=0,o=e.length;o>i;i++)n=e[i],n.style&&(r[i]=st._data(n,"olddisplay"),t?(r[i]||"none"!==n.style.display||(n.style.display=""),""===n.style.display&&w(n)&&(r[i]=st._data(n,"olddisplay",S(n.nodeName)))):r[i]||w(n)||st._data(n,"olddisplay",st.css(n,"display")));for(i=0;o>i;i++)n=e[i],n.style&&(t&&"none"!==n.style.display&&""!==n.style.display||(n.style.display=t?r[i]||"":"none"));return e}function C(e,t,n){var r=mn.exec(t);return r?Math.max(0,r[1]-(n||0))+(r[2]||"px"):t}function k(e,t,n,r,i){for(var o=n===(r?"border":"content")?4:"width"===t?1:0,a=0;4>o;o+=2)"margin"===n&&(a+=st.css(e,n+wn[o],!0,i)),r?("content"===n&&(a-=st.css(e,"padding"+wn[o],!0,i)),"margin"!==n&&(a-=st.css(e,"border"+wn[o]+"Width",!0,i))):(a+=st.css(e,"padding"+wn[o],!0,i),"padding"!==n&&(a+=st.css(e,"border"+wn[o]+"Width",!0,i)));return a}function E(e,t,n){var r=!0,i="width"===t?e.offsetWidth:e.offsetHeight,o=ln(e),a=st.support.boxSizing&&"border-box"===st.css(e,"boxSizing",!1,o);if(0>=i||null==i){if(i=un(e,t,o),(0>i||null==i)&&(i=e.style[t]),yn.test(i))return i;r=a&&(st.support.boxSizingReliable||i===e.style[t]),i=parseFloat(i)||0}return i+k(e,t,n||(a?"border":"content"),r,o)+"px"}function S(e){var t=V,n=bn[e];return n||(n=A(e,t),"none"!==n&&n||(cn=(cn||st("<iframe frameborder='0' width='0' height='0'/>").css("cssText","display:block !important")).appendTo(t.documentElement),t=(cn[0].contentWindow||cn[0].contentDocument).document,t.write("<!doctype html><html><body>"),t.close(),n=A(e,t),cn.detach()),bn[e]=n),n}function A(e,t){var n=st(t.createElement(e)).appendTo(t.body),r=st.css(n[0],"display");return n.remove(),r}function j(e,t,n,r){var i;if(st.isArray(t))st.each(t,function(t,i){n||kn.test(e)?r(e,i):j(e+"["+("object"==typeof i?t:"")+"]",i,n,r)});else if(n||"object"!==st.type(t))r(e,t);else for(i in t)j(e+"["+i+"]",t[i],n,r)}function D(e){return function(t,n){"string"!=typeof t&&(n=t,t="*");var r,i=0,o=t.toLowerCase().match(lt)||[];if(st.isFunction(n))for(;r=o[i++];)"+"===r[0]?(r=r.slice(1)||"*",(e[r]=e[r]||[]).unshift(n)):(e[r]=e[r]||[]).push(n)}}function L(e,n,r,i){function o(u){var l;return a[u]=!0,st.each(e[u]||[],function(e,u){var c=u(n,r,i);return"string"!=typeof c||s||a[c]?s?!(l=c):t:(n.dataTypes.unshift(c),o(c),!1)}),l}var a={},s=e===$n;return o(n.dataTypes[0])||!a["*"]&&o("*")}function H(e,n){var r,i,o=st.ajaxSettings.flatOptions||{};for(r in n)n[r]!==t&&((o[r]?e:i||(i={}))[r]=n[r]);return i&&st.extend(!0,e,i),e}function M(e,n,r){var i,o,a,s,u=e.contents,l=e.dataTypes,c=e.responseFields;for(o in c)o in r&&(n[c[o]]=r[o]);for(;"*"===l[0];)l.shift(),i===t&&(i=e.mimeType||n.getResponseHeader("Content-Type"));if(i)for(o in u)if(u[o]&&u[o].test(i)){l.unshift(o);break}if(l[0]in r)a=l[0];else{for(o in r){if(!l[0]||e.converters[o+" "+l[0]]){a=o;break}s||(s=o)}a=a||s}return a?(a!==l[0]&&l.unshift(a),r[a]):t}function q(e,t){var n,r,i,o,a={},s=0,u=e.dataTypes.slice(),l=u[0];if(e.dataFilter&&(t=e.dataFilter(t,e.dataType)),u[1])for(n in e.converters)a[n.toLowerCase()]=e.converters[n];for(;i=u[++s];)if("*"!==i){if("*"!==l&&l!==i){if(n=a[l+" "+i]||a["* "+i],!n)for(r in a)if(o=r.split(" "),o[1]===i&&(n=a[l+" "+o[0]]||a["* "+o[0]])){n===!0?n=a[r]:a[r]!==!0&&(i=o[0],u.splice(s--,0,i));break}if(n!==!0)if(n&&e["throws"])t=n(t);else try{t=n(t)}catch(c){return{state:"parsererror",error:n?c:"No conversion from "+l+" to "+i}}}l=i}return{state:"success",data:t}}function _(){try{return new e.XMLHttpRequest}catch(t){}}function F(){try{return new e.ActiveXObject("Microsoft.XMLHTTP")}catch(t){}}function O(){return setTimeout(function(){Qn=t}),Qn=st.now()}function B(e,t){st.each(t,function(t,n){for(var r=(rr[t]||[]).concat(rr["*"]),i=0,o=r.length;o>i;i++)if(r[i].call(e,t,n))return})}function P(e,t,n){var r,i,o=0,a=nr.length,s=st.Deferred().always(function(){delete u.elem}),u=function(){if(i)return!1;for(var t=Qn||O(),n=Math.max(0,l.startTime+l.duration-t),r=n/l.duration||0,o=1-r,a=0,u=l.tweens.length;u>a;a++)l.tweens[a].run(o);return s.notifyWith(e,[l,o,n]),1>o&&u?n:(s.resolveWith(e,[l]),!1)},l=s.promise({elem:e,props:st.extend({},t),opts:st.extend(!0,{specialEasing:{}},n),originalProperties:t,originalOptions:n,startTime:Qn||O(),duration:n.duration,tweens:[],createTween:function(t,n){var r=st.Tween(e,l.opts,t,n,l.opts.specialEasing[t]||l.opts.easing);return l.tweens.push(r),r},stop:function(t){var n=0,r=t?l.tweens.length:0;if(i)return this;for(i=!0;r>n;n++)l.tweens[n].run(1);return t?s.resolveWith(e,[l,t]):s.rejectWith(e,[l,t]),this}}),c=l.props;for(R(c,l.opts.specialEasing);a>o;o++)if(r=nr[o].call(l,e,c,l.opts))return r;return B(l,c),st.isFunction(l.opts.start)&&l.opts.start.call(e,l),st.fx.timer(st.extend(u,{elem:e,anim:l,queue:l.opts.queue})),l.progress(l.opts.progress).done(l.opts.done,l.opts.complete).fail(l.opts.fail).always(l.opts.always)}function R(e,t){var n,r,i,o,a;for(n in e)if(r=st.camelCase(n),i=t[r],o=e[n],st.isArray(o)&&(i=o[1],o=e[n]=o[0]),n!==r&&(e[r]=o,delete e[n]),a=st.cssHooks[r],a&&"expand"in a){o=a.expand(o),delete e[r];for(n in o)n in e||(e[n]=o[n],t[n]=i)}else t[r]=i}function W(e,t,n){var r,i,o,a,s,u,l,c,f,p=this,d=e.style,h={},g=[],m=e.nodeType&&w(e);n.queue||(c=st._queueHooks(e,"fx"),null==c.unqueued&&(c.unqueued=0,f=c.empty.fire,c.empty.fire=function(){c.unqueued||f()}),c.unqueued++,p.always(function(){p.always(function(){c.unqueued--,st.queue(e,"fx").length||c.empty.fire()})})),1===e.nodeType&&("height"in t||"width"in t)&&(n.overflow=[d.overflow,d.overflowX,d.overflowY],"inline"===st.css(e,"display")&&"none"===st.css(e,"float")&&(st.support.inlineBlockNeedsLayout&&"inline"!==S(e.nodeName)?d.zoom=1:d.display="inline-block")),n.overflow&&(d.overflow="hidden",st.support.shrinkWrapBlocks||p.done(function(){d.overflow=n.overflow[0],d.overflowX=n.overflow[1],d.overflowY=n.overflow[2]}));for(r in t)if(o=t[r],Zn.exec(o)){if(delete t[r],u=u||"toggle"===o,o===(m?"hide":"show"))continue;g.push(r)}if(a=g.length){s=st._data(e,"fxshow")||st._data(e,"fxshow",{}),"hidden"in s&&(m=s.hidden),u&&(s.hidden=!m),m?st(e).show():p.done(function(){st(e).hide()}),p.done(function(){var t;st._removeData(e,"fxshow");for(t in h)st.style(e,t,h[t])});for(r=0;a>r;r++)i=g[r],l=p.createTween(i,m?s[i]:0),h[i]=s[i]||st.style(e,i),i in s||(s[i]=l.start,m&&(l.end=l.start,l.start="width"===i||"height"===i?1:0))}}function $(e,t,n,r,i){return new $.prototype.init(e,t,n,r,i)}function I(e,t){var n,r={height:e},i=0;for(t=t?1:0;4>i;i+=2-t)n=wn[i],r["margin"+n]=r["padding"+n]=e;return t&&(r.opacity=r.width=e),r}function z(e){return st.isWindow(e)?e:9===e.nodeType?e.defaultView||e.parentWindow:!1}var X,U,V=e.document,Y=e.location,J=e.jQuery,G=e.$,Q={},K=[],Z="1.9.0",et=K.concat,tt=K.push,nt=K.slice,rt=K.indexOf,it=Q.toString,ot=Q.hasOwnProperty,at=Z.trim,st=function(e,t){return new st.fn.init(e,t,X)},ut=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,lt=/\S+/g,ct=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,ft=/^(?:(<[\w\W]+>)[^>]*|#([\w-]*))$/,pt=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,dt=/^[\],:{}\s]*$/,ht=/(?:^|:|,)(?:\s*\[)+/g,gt=/\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,mt=/"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,yt=/^-ms-/,vt=/-([\da-z])/gi,bt=function(e,t){return t.toUpperCase()},xt=function(){V.addEventListener?(V.removeEventListener("DOMContentLoaded",xt,!1),st.ready()):"complete"===V.readyState&&(V.detachEvent("onreadystatechange",xt),st.ready())};st.fn=st.prototype={jquery:Z,constructor:st,init:function(e,n,r){var i,o;if(!e)return this;if("string"==typeof e){if(i="<"===e.charAt(0)&&">"===e.charAt(e.length-1)&&e.length>=3?[null,e,null]:ft.exec(e),!i||!i[1]&&n)return!n||n.jquery?(n||r).find(e):this.constructor(n).find(e);if(i[1]){if(n=n instanceof st?n[0]:n,st.merge(this,st.parseHTML(i[1],n&&n.nodeType?n.ownerDocument||n:V,!0)),pt.test(i[1])&&st.isPlainObject(n))for(i in n)st.isFunction(this[i])?this[i](n[i]):this.attr(i,n[i]);return this}if(o=V.getElementById(i[2]),o&&o.parentNode){if(o.id!==i[2])return r.find(e);this.length=1,this[0]=o}return this.context=V,this.selector=e,this}return e.nodeType?(this.context=this[0]=e,this.length=1,this):st.isFunction(e)?r.ready(e):(e.selector!==t&&(this.selector=e.selector,this.context=e.context),st.makeArray(e,this))},selector:"",length:0,size:function(){return this.length},toArray:function(){return nt.call(this)},get:function(e){return null==e?this.toArray():0>e?this[this.length+e]:this[e]},pushStack:function(e){var t=st.merge(this.constructor(),e);return t.prevObject=this,t.context=this.context,t},each:function(e,t){return st.each(this,e,t)},ready:function(e){return st.ready.promise().done(e),this},slice:function(){return this.pushStack(nt.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(e){var t=this.length,n=+e+(0>e?t:0);return this.pushStack(n>=0&&t>n?[this[n]]:[])},map:function(e){return this.pushStack(st.map(this,function(t,n){return e.call(t,n,t)}))},end:function(){return this.prevObject||this.constructor(null)},push:tt,sort:[].sort,splice:[].splice},st.fn.init.prototype=st.fn,st.extend=st.fn.extend=function(){var e,n,r,i,o,a,s=arguments[0]||{},u=1,l=arguments.length,c=!1;for("boolean"==typeof s&&(c=s,s=arguments[1]||{},u=2),"object"==typeof s||st.isFunction(s)||(s={}),l===u&&(s=this,--u);l>u;u++)if(null!=(e=arguments[u]))for(n in e)r=s[n],i=e[n],s!==i&&(c&&i&&(st.isPlainObject(i)||(o=st.isArray(i)))?(o?(o=!1,a=r&&st.isArray(r)?r:[]):a=r&&st.isPlainObject(r)?r:{},s[n]=st.extend(c,a,i)):i!==t&&(s[n]=i));return s},st.extend({noConflict:function(t){return e.$===st&&(e.$=G),t&&e.jQuery===st&&(e.jQuery=J),st},isReady:!1,readyWait:1,holdReady:function(e){e?st.readyWait++:st.ready(!0)},ready:function(e){if(e===!0?!--st.readyWait:!st.isReady){if(!V.body)return setTimeout(st.ready);st.isReady=!0,e!==!0&&--st.readyWait>0||(U.resolveWith(V,[st]),st.fn.trigger&&st(V).trigger("ready").off("ready"))}},isFunction:function(e){return"function"===st.type(e)},isArray:Array.isArray||function(e){return"array"===st.type(e)},isWindow:function(e){return null!=e&&e==e.window},isNumeric:function(e){return!isNaN(parseFloat(e))&&isFinite(e)},type:function(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?Q[it.call(e)]||"object":typeof e},isPlainObject:function(e){if(!e||"object"!==st.type(e)||e.nodeType||st.isWindow(e))return!1;try{if(e.constructor&&!ot.call(e,"constructor")&&!ot.call(e.constructor.prototype,"isPrototypeOf"))return!1}catch(n){return!1}var r;for(r in e);return r===t||ot.call(e,r)},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},error:function(e){throw Error(e)},parseHTML:function(e,t,n){if(!e||"string"!=typeof e)return null;"boolean"==typeof t&&(n=t,t=!1),t=t||V;var r=pt.exec(e),i=!n&&[];return r?[t.createElement(r[1])]:(r=st.buildFragment([e],t,i),i&&st(i).remove(),st.merge([],r.childNodes))},parseJSON:function(n){return e.JSON&&e.JSON.parse?e.JSON.parse(n):null===n?n:"string"==typeof n&&(n=st.trim(n),n&&dt.test(n.replace(gt,"@").replace(mt,"]").replace(ht,"")))?Function("return "+n)():(st.error("Invalid JSON: "+n),t)},parseXML:function(n){var r,i;if(!n||"string"!=typeof n)return null;try{e.DOMParser?(i=new DOMParser,r=i.parseFromString(n,"text/xml")):(r=new ActiveXObject("Microsoft.XMLDOM"),r.async="false",r.loadXML(n))}catch(o){r=t}return r&&r.documentElement&&!r.getElementsByTagName("parsererror").length||st.error("Invalid XML: "+n),r},noop:function(){},globalEval:function(t){t&&st.trim(t)&&(e.execScript||function(t){e.eval.call(e,t)})(t)},camelCase:function(e){return e.replace(yt,"ms-").replace(vt,bt)},nodeName:function(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()},each:function(e,t,r){var i,o=0,a=e.length,s=n(e);if(r){if(s)for(;a>o&&(i=t.apply(e[o],r),i!==!1);o++);else for(o in e)if(i=t.apply(e[o],r),i===!1)break}else if(s)for(;a>o&&(i=t.call(e[o],o,e[o]),i!==!1);o++);else for(o in e)if(i=t.call(e[o],o,e[o]),i===!1)break;return e},trim:at&&!at.call("\ufeff\u00a0")?function(e){return null==e?"":at.call(e)}:function(e){return null==e?"":(e+"").replace(ct,"")},makeArray:function(e,t){var r=t||[];return null!=e&&(n(Object(e))?st.merge(r,"string"==typeof e?[e]:e):tt.call(r,e)),r},inArray:function(e,t,n){var r;if(t){if(rt)return rt.call(t,e,n);for(r=t.length,n=n?0>n?Math.max(0,r+n):n:0;r>n;n++)if(n in t&&t[n]===e)return n}return-1},merge:function(e,n){var r=n.length,i=e.length,o=0;if("number"==typeof r)for(;r>o;o++)e[i++]=n[o];else for(;n[o]!==t;)e[i++]=n[o++];return e.length=i,e},grep:function(e,t,n){var r,i=[],o=0,a=e.length;for(n=!!n;a>o;o++)r=!!t(e[o],o),n!==r&&i.push(e[o]);return i},map:function(e,t,r){var i,o=0,a=e.length,s=n(e),u=[];if(s)for(;a>o;o++)i=t(e[o],o,r),null!=i&&(u[u.length]=i);else for(o in e)i=t(e[o],o,r),null!=i&&(u[u.length]=i);return et.apply([],u)},guid:1,proxy:function(e,n){var r,i,o;return"string"==typeof n&&(r=e[n],n=e,e=r),st.isFunction(e)?(i=nt.call(arguments,2),o=function(){return e.apply(n||this,i.concat(nt.call(arguments)))},o.guid=e.guid=e.guid||st.guid++,o):t},access:function(e,n,r,i,o,a,s){var u=0,l=e.length,c=null==r;if("object"===st.type(r)){o=!0;for(u in r)st.access(e,n,u,r[u],!0,a,s)}else if(i!==t&&(o=!0,st.isFunction(i)||(s=!0),c&&(s?(n.call(e,i),n=null):(c=n,n=function(e,t,n){return c.call(st(e),n)})),n))for(;l>u;u++)n(e[u],r,s?i:i.call(e[u],u,n(e[u],r)));return o?e:c?n.call(e):l?n(e[0],r):a},now:function(){return(new Date).getTime()}}),st.ready.promise=function(t){if(!U)if(U=st.Deferred(),"complete"===V.readyState)setTimeout(st.ready);else if(V.addEventListener)V.addEventListener("DOMContentLoaded",xt,!1),e.addEventListener("load",st.ready,!1);else{V.attachEvent("onreadystatechange",xt),e.attachEvent("onload",st.ready);var n=!1;try{n=null==e.frameElement&&V.documentElement}catch(r){}n&&n.doScroll&&function i(){if(!st.isReady){try{n.doScroll("left")}catch(e){return setTimeout(i,50)}st.ready()}}()}return U.promise(t)},st.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(e,t){Q["[object "+t+"]"]=t.toLowerCase()}),X=st(V);var Tt={};st.Callbacks=function(e){e="string"==typeof e?Tt[e]||r(e):st.extend({},e);var n,i,o,a,s,u,l=[],c=!e.once&&[],f=function(t){for(n=e.memory&&t,i=!0,u=a||0,a=0,s=l.length,o=!0;l&&s>u;u++)if(l[u].apply(t[0],t[1])===!1&&e.stopOnFalse){n=!1;break}o=!1,l&&(c?c.length&&f(c.shift()):n?l=[]:p.disable())},p={add:function(){if(l){var t=l.length;(function r(t){st.each(t,function(t,n){var i=st.type(n);"function"===i?e.unique&&p.has(n)||l.push(n):n&&n.length&&"string"!==i&&r(n)})})(arguments),o?s=l.length:n&&(a=t,f(n))}return this},remove:function(){return l&&st.each(arguments,function(e,t){for(var n;(n=st.inArray(t,l,n))>-1;)l.splice(n,1),o&&(s>=n&&s--,u>=n&&u--)}),this},has:function(e){return st.inArray(e,l)>-1},empty:function(){return l=[],this},disable:function(){return l=c=n=t,this},disabled:function(){return!l},lock:function(){return c=t,n||p.disable(),this},locked:function(){return!c},fireWith:function(e,t){return t=t||[],t=[e,t.slice?t.slice():t],!l||i&&!c||(o?c.push(t):f(t)),this},fire:function(){return p.fireWith(this,arguments),this},fired:function(){return!!i}};return p},st.extend({Deferred:function(e){var t=[["resolve","done",st.Callbacks("once memory"),"resolved"],["reject","fail",st.Callbacks("once memory"),"rejected"],["notify","progress",st.Callbacks("memory")]],n="pending",r={state:function(){return n},always:function(){return i.done(arguments).fail(arguments),this},then:function(){var e=arguments;return st.Deferred(function(n){st.each(t,function(t,o){var a=o[0],s=st.isFunction(e[t])&&e[t];i[o[1]](function(){var e=s&&s.apply(this,arguments);e&&st.isFunction(e.promise)?e.promise().done(n.resolve).fail(n.reject).progress(n.notify):n[a+"With"](this===r?n.promise():this,s?[e]:arguments)})}),e=null}).promise()},promise:function(e){return null!=e?st.extend(e,r):r}},i={};return r.pipe=r.then,st.each(t,function(e,o){var a=o[2],s=o[3];r[o[1]]=a.add,s&&a.add(function(){n=s},t[1^e][2].disable,t[2][2].lock),i[o[0]]=function(){return i[o[0]+"With"](this===i?r:this,arguments),this},i[o[0]+"With"]=a.fireWith}),r.promise(i),e&&e.call(i,i),i},when:function(e){var t,n,r,i=0,o=nt.call(arguments),a=o.length,s=1!==a||e&&st.isFunction(e.promise)?a:0,u=1===s?e:st.Deferred(),l=function(e,n,r){return function(i){n[e]=this,r[e]=arguments.length>1?nt.call(arguments):i,r===t?u.notifyWith(n,r):--s||u.resolveWith(n,r)}};if(a>1)for(t=Array(a),n=Array(a),r=Array(a);a>i;i++)o[i]&&st.isFunction(o[i].promise)?o[i].promise().done(l(i,r,o)).fail(u.reject).progress(l(i,n,t)):--s;return s||u.resolveWith(r,o),u.promise()}}),st.support=function(){var n,r,i,o,a,s,u,l,c,f,p=V.createElement("div");if(p.setAttribute("className","t"),p.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",r=p.getElementsByTagName("*"),i=p.getElementsByTagName("a")[0],!r||!i||!r.length)return{};o=V.createElement("select"),a=o.appendChild(V.createElement("option")),s=p.getElementsByTagName("input")[0],i.style.cssText="top:1px;float:left;opacity:.5",n={getSetAttribute:"t"!==p.className,leadingWhitespace:3===p.firstChild.nodeType,tbody:!p.getElementsByTagName("tbody").length,htmlSerialize:!!p.getElementsByTagName("link").length,style:/top/.test(i.getAttribute("style")),hrefNormalized:"/a"===i.getAttribute("href"),opacity:/^0.5/.test(i.style.opacity),cssFloat:!!i.style.cssFloat,checkOn:!!s.value,optSelected:a.selected,enctype:!!V.createElement("form").enctype,html5Clone:"<:nav></:nav>"!==V.createElement("nav").cloneNode(!0).outerHTML,boxModel:"CSS1Compat"===V.compatMode,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0,boxSizingReliable:!0,pixelPosition:!1},s.checked=!0,n.noCloneChecked=s.cloneNode(!0).checked,o.disabled=!0,n.optDisabled=!a.disabled;try{delete p.test}catch(d){n.deleteExpando=!1}s=V.createElement("input"),s.setAttribute("value",""),n.input=""===s.getAttribute("value"),s.value="t",s.setAttribute("type","radio"),n.radioValue="t"===s.value,s.setAttribute("checked","t"),s.setAttribute("name","t"),u=V.createDocumentFragment(),u.appendChild(s),n.appendChecked=s.checked,n.checkClone=u.cloneNode(!0).cloneNode(!0).lastChild.checked,p.attachEvent&&(p.attachEvent("onclick",function(){n.noCloneEvent=!1}),p.cloneNode(!0).click());for(f in{submit:!0,change:!0,focusin:!0})p.setAttribute(l="on"+f,"t"),n[f+"Bubbles"]=l in e||p.attributes[l].expando===!1;return p.style.backgroundClip="content-box",p.cloneNode(!0).style.backgroundClip="",n.clearCloneStyle="content-box"===p.style.backgroundClip,st(function(){var r,i,o,a="padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;",s=V.getElementsByTagName("body")[0];s&&(r=V.createElement("div"),r.style.cssText="border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px",s.appendChild(r).appendChild(p),p.innerHTML="<table><tr><td></td><td>t</td></tr></table>",o=p.getElementsByTagName("td"),o[0].style.cssText="padding:0;margin:0;border:0;display:none",c=0===o[0].offsetHeight,o[0].style.display="",o[1].style.display="none",n.reliableHiddenOffsets=c&&0===o[0].offsetHeight,p.innerHTML="",p.style.cssText="box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;",n.boxSizing=4===p.offsetWidth,n.doesNotIncludeMarginInBodyOffset=1!==s.offsetTop,e.getComputedStyle&&(n.pixelPosition="1%"!==(e.getComputedStyle(p,null)||{}).top,n.boxSizingReliable="4px"===(e.getComputedStyle(p,null)||{width:"4px"}).width,i=p.appendChild(V.createElement("div")),i.style.cssText=p.style.cssText=a,i.style.marginRight=i.style.width="0",p.style.width="1px",n.reliableMarginRight=!parseFloat((e.getComputedStyle(i,null)||{}).marginRight)),p.style.zoom!==t&&(p.innerHTML="",p.style.cssText=a+"width:1px;padding:1px;display:inline;zoom:1",n.inlineBlockNeedsLayout=3===p.offsetWidth,p.style.display="block",p.innerHTML="<div></div>",p.firstChild.style.width="5px",n.shrinkWrapBlocks=3!==p.offsetWidth,s.style.zoom=1),s.removeChild(r),r=p=o=i=null)}),r=o=u=a=i=s=null,n}();var wt=/(?:\{[\s\S]*\}|\[[\s\S]*\])$/,Nt=/([A-Z])/g;st.extend({cache:{},expando:"jQuery"+(Z+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(e){return e=e.nodeType?st.cache[e[st.expando]]:e[st.expando],!!e&&!s(e)},data:function(e,t,n){return i(e,t,n,!1)},removeData:function(e,t){return o(e,t,!1)},_data:function(e,t,n){return i(e,t,n,!0)},_removeData:function(e,t){return o(e,t,!0)},acceptData:function(e){var t=e.nodeName&&st.noData[e.nodeName.toLowerCase()];return!t||t!==!0&&e.getAttribute("classid")===t}}),st.fn.extend({data:function(e,n){var r,i,o=this[0],s=0,u=null;if(e===t){if(this.length&&(u=st.data(o),1===o.nodeType&&!st._data(o,"parsedAttrs"))){for(r=o.attributes;r.length>s;s++)i=r[s].name,i.indexOf("data-")||(i=st.camelCase(i.substring(5)),a(o,i,u[i]));st._data(o,"parsedAttrs",!0)}return u}return"object"==typeof e?this.each(function(){st.data(this,e)}):st.access(this,function(n){return n===t?o?a(o,e,st.data(o,e)):null:(this.each(function(){st.data(this,e,n)}),t)},null,n,arguments.length>1,null,!0)},removeData:function(e){return this.each(function(){st.removeData(this,e)})}}),st.extend({queue:function(e,n,r){var i;return e?(n=(n||"fx")+"queue",i=st._data(e,n),r&&(!i||st.isArray(r)?i=st._data(e,n,st.makeArray(r)):i.push(r)),i||[]):t},dequeue:function(e,t){t=t||"fx";var n=st.queue(e,t),r=n.length,i=n.shift(),o=st._queueHooks(e,t),a=function(){st.dequeue(e,t)};"inprogress"===i&&(i=n.shift(),r--),o.cur=i,i&&("fx"===t&&n.unshift("inprogress"),delete o.stop,i.call(e,a,o)),!r&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return st._data(e,n)||st._data(e,n,{empty:st.Callbacks("once memory").add(function(){st._removeData(e,t+"queue"),st._removeData(e,n)})})}}),st.fn.extend({queue:function(e,n){var r=2;return"string"!=typeof e&&(n=e,e="fx",r--),r>arguments.length?st.queue(this[0],e):n===t?this:this.each(function(){var t=st.queue(this,e,n);st._queueHooks(this,e),"fx"===e&&"inprogress"!==t[0]&&st.dequeue(this,e)})},dequeue:function(e){return this.each(function(){st.dequeue(this,e)})},delay:function(e,t){return e=st.fx?st.fx.speeds[e]||e:e,t=t||"fx",this.queue(t,function(t,n){var r=setTimeout(t,e);n.stop=function(){clearTimeout(r)}})},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,n){var r,i=1,o=st.Deferred(),a=this,s=this.length,u=function(){--i||o.resolveWith(a,[a])};for("string"!=typeof e&&(n=e,e=t),e=e||"fx";s--;)r=st._data(a[s],e+"queueHooks"),r&&r.empty&&(i++,r.empty.add(u));return u(),o.promise(n)}});var Ct,kt,Et=/[\t\r\n]/g,St=/\r/g,At=/^(?:input|select|textarea|button|object)$/i,jt=/^(?:a|area)$/i,Dt=/^(?:checked|selected|autofocus|autoplay|async|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped)$/i,Lt=/^(?:checked|selected)$/i,Ht=st.support.getSetAttribute,Mt=st.support.input;st.fn.extend({attr:function(e,t){return st.access(this,st.attr,e,t,arguments.length>1)},removeAttr:function(e){return this.each(function(){st.removeAttr(this,e)})},prop:function(e,t){return st.access(this,st.prop,e,t,arguments.length>1)},removeProp:function(e){return e=st.propFix[e]||e,this.each(function(){try{this[e]=t,delete this[e]}catch(n){}})},addClass:function(e){var t,n,r,i,o,a=0,s=this.length,u="string"==typeof e&&e;if(st.isFunction(e))return this.each(function(t){st(this).addClass(e.call(this,t,this.className))});if(u)for(t=(e||"").match(lt)||[];s>a;a++)if(n=this[a],r=1===n.nodeType&&(n.className?(" "+n.className+" ").replace(Et," "):" ")){for(o=0;i=t[o++];)0>r.indexOf(" "+i+" ")&&(r+=i+" ");n.className=st.trim(r)}return this},removeClass:function(e){var t,n,r,i,o,a=0,s=this.length,u=0===arguments.length||"string"==typeof e&&e;if(st.isFunction(e))return this.each(function(t){st(this).removeClass(e.call(this,t,this.className))});if(u)for(t=(e||"").match(lt)||[];s>a;a++)if(n=this[a],r=1===n.nodeType&&(n.className?(" "+n.className+" ").replace(Et," "):"")){for(o=0;i=t[o++];)for(;r.indexOf(" "+i+" ")>=0;)r=r.replace(" "+i+" "," ");n.className=e?st.trim(r):""}return this},toggleClass:function(e,t){var n=typeof e,r="boolean"==typeof t;return st.isFunction(e)?this.each(function(n){st(this).toggleClass(e.call(this,n,this.className,t),t)}):this.each(function(){if("string"===n)for(var i,o=0,a=st(this),s=t,u=e.match(lt)||[];i=u[o++];)s=r?s:!a.hasClass(i),a[s?"addClass":"removeClass"](i);else("undefined"===n||"boolean"===n)&&(this.className&&st._data(this,"__className__",this.className),this.className=this.className||e===!1?"":st._data(this,"__className__")||"")})},hasClass:function(e){for(var t=" "+e+" ",n=0,r=this.length;r>n;n++)if(1===this[n].nodeType&&(" "+this[n].className+" ").replace(Et," ").indexOf(t)>=0)return!0;return!1},val:function(e){var n,r,i,o=this[0];{if(arguments.length)return i=st.isFunction(e),this.each(function(r){var o,a=st(this);1===this.nodeType&&(o=i?e.call(this,r,a.val()):e,null==o?o="":"number"==typeof o?o+="":st.isArray(o)&&(o=st.map(o,function(e){return null==e?"":e+""})),n=st.valHooks[this.type]||st.valHooks[this.nodeName.toLowerCase()],n&&"set"in n&&n.set(this,o,"value")!==t||(this.value=o))});if(o)return n=st.valHooks[o.type]||st.valHooks[o.nodeName.toLowerCase()],n&&"get"in n&&(r=n.get(o,"value"))!==t?r:(r=o.value,"string"==typeof r?r.replace(St,""):null==r?"":r)}}}),st.extend({valHooks:{option:{get:function(e){var t=e.attributes.value;return!t||t.specified?e.value:e.text}},select:{get:function(e){for(var t,n,r=e.options,i=e.selectedIndex,o="select-one"===e.type||0>i,a=o?null:[],s=o?i+1:r.length,u=0>i?s:o?i:0;s>u;u++)if(n=r[u],!(!n.selected&&u!==i||(st.support.optDisabled?n.disabled:null!==n.getAttribute("disabled"))||n.parentNode.disabled&&st.nodeName(n.parentNode,"optgroup"))){if(t=st(n).val(),o)return t;a.push(t)}return a},set:function(e,t){var n=st.makeArray(t);return st(e).find("option").each(function(){this.selected=st.inArray(st(this).val(),n)>=0}),n.length||(e.selectedIndex=-1),n}}},attr:function(e,n,r){var i,o,a,s=e.nodeType;if(e&&3!==s&&8!==s&&2!==s)return e.getAttribute===t?st.prop(e,n,r):(a=1!==s||!st.isXMLDoc(e),a&&(n=n.toLowerCase(),o=st.attrHooks[n]||(Dt.test(n)?kt:Ct)),r===t?o&&a&&"get"in o&&null!==(i=o.get(e,n))?i:(e.getAttribute!==t&&(i=e.getAttribute(n)),null==i?t:i):null!==r?o&&a&&"set"in o&&(i=o.set(e,r,n))!==t?i:(e.setAttribute(n,r+""),r):(st.removeAttr(e,n),t))},removeAttr:function(e,t){var n,r,i=0,o=t&&t.match(lt);if(o&&1===e.nodeType)for(;n=o[i++];)r=st.propFix[n]||n,Dt.test(n)?!Ht&&Lt.test(n)?e[st.camelCase("default-"+n)]=e[r]=!1:e[r]=!1:st.attr(e,n,""),e.removeAttribute(Ht?n:r)},attrHooks:{type:{set:function(e,t){if(!st.support.radioValue&&"radio"===t&&st.nodeName(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(e,n,r){var i,o,a,s=e.nodeType;if(e&&3!==s&&8!==s&&2!==s)return a=1!==s||!st.isXMLDoc(e),a&&(n=st.propFix[n]||n,o=st.propHooks[n]),r!==t?o&&"set"in o&&(i=o.set(e,r,n))!==t?i:e[n]=r:o&&"get"in o&&null!==(i=o.get(e,n))?i:e[n]},propHooks:{tabIndex:{get:function(e){var n=e.getAttributeNode("tabindex");return n&&n.specified?parseInt(n.value,10):At.test(e.nodeName)||jt.test(e.nodeName)&&e.href?0:t}}}}),kt={get:function(e,n){var r=st.prop(e,n),i="boolean"==typeof r&&e.getAttribute(n),o="boolean"==typeof r?Mt&&Ht?null!=i:Lt.test(n)?e[st.camelCase("default-"+n)]:!!i:e.getAttributeNode(n);return o&&o.value!==!1?n.toLowerCase():t},set:function(e,t,n){return t===!1?st.removeAttr(e,n):Mt&&Ht||!Lt.test(n)?e.setAttribute(!Ht&&st.propFix[n]||n,n):e[st.camelCase("default-"+n)]=e[n]=!0,n}},Mt&&Ht||(st.attrHooks.value={get:function(e,n){var r=e.getAttributeNode(n);return st.nodeName(e,"input")?e.defaultValue:r&&r.specified?r.value:t
},set:function(e,n,r){return st.nodeName(e,"input")?(e.defaultValue=n,t):Ct&&Ct.set(e,n,r)}}),Ht||(Ct=st.valHooks.button={get:function(e,n){var r=e.getAttributeNode(n);return r&&("id"===n||"name"===n||"coords"===n?""!==r.value:r.specified)?r.value:t},set:function(e,n,r){var i=e.getAttributeNode(r);return i||e.setAttributeNode(i=e.ownerDocument.createAttribute(r)),i.value=n+="","value"===r||n===e.getAttribute(r)?n:t}},st.attrHooks.contenteditable={get:Ct.get,set:function(e,t,n){Ct.set(e,""===t?!1:t,n)}},st.each(["width","height"],function(e,n){st.attrHooks[n]=st.extend(st.attrHooks[n],{set:function(e,r){return""===r?(e.setAttribute(n,"auto"),r):t}})})),st.support.hrefNormalized||(st.each(["href","src","width","height"],function(e,n){st.attrHooks[n]=st.extend(st.attrHooks[n],{get:function(e){var r=e.getAttribute(n,2);return null==r?t:r}})}),st.each(["href","src"],function(e,t){st.propHooks[t]={get:function(e){return e.getAttribute(t,4)}}})),st.support.style||(st.attrHooks.style={get:function(e){return e.style.cssText||t},set:function(e,t){return e.style.cssText=t+""}}),st.support.optSelected||(st.propHooks.selected=st.extend(st.propHooks.selected,{get:function(e){var t=e.parentNode;return t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex),null}})),st.support.enctype||(st.propFix.enctype="encoding"),st.support.checkOn||st.each(["radio","checkbox"],function(){st.valHooks[this]={get:function(e){return null===e.getAttribute("value")?"on":e.value}}}),st.each(["radio","checkbox"],function(){st.valHooks[this]=st.extend(st.valHooks[this],{set:function(e,n){return st.isArray(n)?e.checked=st.inArray(st(e).val(),n)>=0:t}})});var qt=/^(?:input|select|textarea)$/i,_t=/^key/,Ft=/^(?:mouse|contextmenu)|click/,Ot=/^(?:focusinfocus|focusoutblur)$/,Bt=/^([^.]*)(?:\.(.+)|)$/;st.event={global:{},add:function(e,n,r,i,o){var a,s,u,l,c,f,p,d,h,g,m,y=3!==e.nodeType&&8!==e.nodeType&&st._data(e);if(y){for(r.handler&&(a=r,r=a.handler,o=a.selector),r.guid||(r.guid=st.guid++),(l=y.events)||(l=y.events={}),(s=y.handle)||(s=y.handle=function(e){return st===t||e&&st.event.triggered===e.type?t:st.event.dispatch.apply(s.elem,arguments)},s.elem=e),n=(n||"").match(lt)||[""],c=n.length;c--;)u=Bt.exec(n[c])||[],h=m=u[1],g=(u[2]||"").split(".").sort(),p=st.event.special[h]||{},h=(o?p.delegateType:p.bindType)||h,p=st.event.special[h]||{},f=st.extend({type:h,origType:m,data:i,handler:r,guid:r.guid,selector:o,needsContext:o&&st.expr.match.needsContext.test(o),namespace:g.join(".")},a),(d=l[h])||(d=l[h]=[],d.delegateCount=0,p.setup&&p.setup.call(e,i,g,s)!==!1||(e.addEventListener?e.addEventListener(h,s,!1):e.attachEvent&&e.attachEvent("on"+h,s))),p.add&&(p.add.call(e,f),f.handler.guid||(f.handler.guid=r.guid)),o?d.splice(d.delegateCount++,0,f):d.push(f),st.event.global[h]=!0;e=null}},remove:function(e,t,n,r,i){var o,a,s,u,l,c,f,p,d,h,g,m=st.hasData(e)&&st._data(e);if(m&&(u=m.events)){for(t=(t||"").match(lt)||[""],l=t.length;l--;)if(s=Bt.exec(t[l])||[],d=g=s[1],h=(s[2]||"").split(".").sort(),d){for(f=st.event.special[d]||{},d=(r?f.delegateType:f.bindType)||d,p=u[d]||[],s=s[2]&&RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"),a=o=p.length;o--;)c=p[o],!i&&g!==c.origType||n&&n.guid!==c.guid||s&&!s.test(c.namespace)||r&&r!==c.selector&&("**"!==r||!c.selector)||(p.splice(o,1),c.selector&&p.delegateCount--,f.remove&&f.remove.call(e,c));a&&!p.length&&(f.teardown&&f.teardown.call(e,h,m.handle)!==!1||st.removeEvent(e,d,m.handle),delete u[d])}else for(d in u)st.event.remove(e,d+t[l],n,r,!0);st.isEmptyObject(u)&&(delete m.handle,st._removeData(e,"events"))}},trigger:function(n,r,i,o){var a,s,u,l,c,f,p,d=[i||V],h=n.type||n,g=n.namespace?n.namespace.split("."):[];if(s=u=i=i||V,3!==i.nodeType&&8!==i.nodeType&&!Ot.test(h+st.event.triggered)&&(h.indexOf(".")>=0&&(g=h.split("."),h=g.shift(),g.sort()),c=0>h.indexOf(":")&&"on"+h,n=n[st.expando]?n:new st.Event(h,"object"==typeof n&&n),n.isTrigger=!0,n.namespace=g.join("."),n.namespace_re=n.namespace?RegExp("(^|\\.)"+g.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,n.result=t,n.target||(n.target=i),r=null==r?[n]:st.makeArray(r,[n]),p=st.event.special[h]||{},o||!p.trigger||p.trigger.apply(i,r)!==!1)){if(!o&&!p.noBubble&&!st.isWindow(i)){for(l=p.delegateType||h,Ot.test(l+h)||(s=s.parentNode);s;s=s.parentNode)d.push(s),u=s;u===(i.ownerDocument||V)&&d.push(u.defaultView||u.parentWindow||e)}for(a=0;(s=d[a++])&&!n.isPropagationStopped();)n.type=a>1?l:p.bindType||h,f=(st._data(s,"events")||{})[n.type]&&st._data(s,"handle"),f&&f.apply(s,r),f=c&&s[c],f&&st.acceptData(s)&&f.apply&&f.apply(s,r)===!1&&n.preventDefault();if(n.type=h,!(o||n.isDefaultPrevented()||p._default&&p._default.apply(i.ownerDocument,r)!==!1||"click"===h&&st.nodeName(i,"a")||!st.acceptData(i)||!c||!i[h]||st.isWindow(i))){u=i[c],u&&(i[c]=null),st.event.triggered=h;try{i[h]()}catch(m){}st.event.triggered=t,u&&(i[c]=u)}return n.result}},dispatch:function(e){e=st.event.fix(e);var n,r,i,o,a,s=[],u=nt.call(arguments),l=(st._data(this,"events")||{})[e.type]||[],c=st.event.special[e.type]||{};if(u[0]=e,e.delegateTarget=this,!c.preDispatch||c.preDispatch.call(this,e)!==!1){for(s=st.event.handlers.call(this,e,l),n=0;(o=s[n++])&&!e.isPropagationStopped();)for(e.currentTarget=o.elem,r=0;(a=o.handlers[r++])&&!e.isImmediatePropagationStopped();)(!e.namespace_re||e.namespace_re.test(a.namespace))&&(e.handleObj=a,e.data=a.data,i=((st.event.special[a.origType]||{}).handle||a.handler).apply(o.elem,u),i!==t&&(e.result=i)===!1&&(e.preventDefault(),e.stopPropagation()));return c.postDispatch&&c.postDispatch.call(this,e),e.result}},handlers:function(e,n){var r,i,o,a,s=[],u=n.delegateCount,l=e.target;if(u&&l.nodeType&&(!e.button||"click"!==e.type))for(;l!=this;l=l.parentNode||this)if(l.disabled!==!0||"click"!==e.type){for(i=[],r=0;u>r;r++)a=n[r],o=a.selector+" ",i[o]===t&&(i[o]=a.needsContext?st(o,this).index(l)>=0:st.find(o,this,null,[l]).length),i[o]&&i.push(a);i.length&&s.push({elem:l,handlers:i})}return n.length>u&&s.push({elem:this,handlers:n.slice(u)}),s},fix:function(e){if(e[st.expando])return e;var t,n,r=e,i=st.event.fixHooks[e.type]||{},o=i.props?this.props.concat(i.props):this.props;for(e=new st.Event(r),t=o.length;t--;)n=o[t],e[n]=r[n];return e.target||(e.target=r.srcElement||V),3===e.target.nodeType&&(e.target=e.target.parentNode),e.metaKey=!!e.metaKey,i.filter?i.filter(e,r):e},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(e,t){return null==e.which&&(e.which=null!=t.charCode?t.charCode:t.keyCode),e}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(e,n){var r,i,o,a=n.button,s=n.fromElement;return null==e.pageX&&null!=n.clientX&&(r=e.target.ownerDocument||V,i=r.documentElement,o=r.body,e.pageX=n.clientX+(i&&i.scrollLeft||o&&o.scrollLeft||0)-(i&&i.clientLeft||o&&o.clientLeft||0),e.pageY=n.clientY+(i&&i.scrollTop||o&&o.scrollTop||0)-(i&&i.clientTop||o&&o.clientTop||0)),!e.relatedTarget&&s&&(e.relatedTarget=s===e.target?n.toElement:s),e.which||a===t||(e.which=1&a?1:2&a?3:4&a?2:0),e}},special:{load:{noBubble:!0},click:{trigger:function(){return st.nodeName(this,"input")&&"checkbox"===this.type&&this.click?(this.click(),!1):t}},focus:{trigger:function(){if(this!==V.activeElement&&this.focus)try{return this.focus(),!1}catch(e){}},delegateType:"focusin"},blur:{trigger:function(){return this===V.activeElement&&this.blur?(this.blur(),!1):t},delegateType:"focusout"},beforeunload:{postDispatch:function(e){e.result!==t&&(e.originalEvent.returnValue=e.result)}}},simulate:function(e,t,n,r){var i=st.extend(new st.Event,n,{type:e,isSimulated:!0,originalEvent:{}});r?st.event.trigger(i,null,t):st.event.dispatch.call(t,i),i.isDefaultPrevented()&&n.preventDefault()}},st.removeEvent=V.removeEventListener?function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n,!1)}:function(e,n,r){var i="on"+n;e.detachEvent&&(e[i]===t&&(e[i]=null),e.detachEvent(i,r))},st.Event=function(e,n){return this instanceof st.Event?(e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||e.returnValue===!1||e.getPreventDefault&&e.getPreventDefault()?u:l):this.type=e,n&&st.extend(this,n),this.timeStamp=e&&e.timeStamp||st.now(),this[st.expando]=!0,t):new st.Event(e,n)},st.Event.prototype={isDefaultPrevented:l,isPropagationStopped:l,isImmediatePropagationStopped:l,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=u,e&&(e.preventDefault?e.preventDefault():e.returnValue=!1)},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=u,e&&(e.stopPropagation&&e.stopPropagation(),e.cancelBubble=!0)},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=u,this.stopPropagation()}},st.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(e,t){st.event.special[e]={delegateType:t,bindType:t,handle:function(e){var n,r=this,i=e.relatedTarget,o=e.handleObj;return(!i||i!==r&&!st.contains(r,i))&&(e.type=o.origType,n=o.handler.apply(this,arguments),e.type=t),n}}}),st.support.submitBubbles||(st.event.special.submit={setup:function(){return st.nodeName(this,"form")?!1:(st.event.add(this,"click._submit keypress._submit",function(e){var n=e.target,r=st.nodeName(n,"input")||st.nodeName(n,"button")?n.form:t;r&&!st._data(r,"submitBubbles")&&(st.event.add(r,"submit._submit",function(e){e._submit_bubble=!0}),st._data(r,"submitBubbles",!0))}),t)},postDispatch:function(e){e._submit_bubble&&(delete e._submit_bubble,this.parentNode&&!e.isTrigger&&st.event.simulate("submit",this.parentNode,e,!0))},teardown:function(){return st.nodeName(this,"form")?!1:(st.event.remove(this,"._submit"),t)}}),st.support.changeBubbles||(st.event.special.change={setup:function(){return qt.test(this.nodeName)?(("checkbox"===this.type||"radio"===this.type)&&(st.event.add(this,"propertychange._change",function(e){"checked"===e.originalEvent.propertyName&&(this._just_changed=!0)}),st.event.add(this,"click._change",function(e){this._just_changed&&!e.isTrigger&&(this._just_changed=!1),st.event.simulate("change",this,e,!0)})),!1):(st.event.add(this,"beforeactivate._change",function(e){var t=e.target;qt.test(t.nodeName)&&!st._data(t,"changeBubbles")&&(st.event.add(t,"change._change",function(e){!this.parentNode||e.isSimulated||e.isTrigger||st.event.simulate("change",this.parentNode,e,!0)}),st._data(t,"changeBubbles",!0))}),t)},handle:function(e){var n=e.target;return this!==n||e.isSimulated||e.isTrigger||"radio"!==n.type&&"checkbox"!==n.type?e.handleObj.handler.apply(this,arguments):t},teardown:function(){return st.event.remove(this,"._change"),!qt.test(this.nodeName)}}),st.support.focusinBubbles||st.each({focus:"focusin",blur:"focusout"},function(e,t){var n=0,r=function(e){st.event.simulate(t,e.target,st.event.fix(e),!0)};st.event.special[t]={setup:function(){0===n++&&V.addEventListener(e,r,!0)},teardown:function(){0===--n&&V.removeEventListener(e,r,!0)}}}),st.fn.extend({on:function(e,n,r,i,o){var a,s;if("object"==typeof e){"string"!=typeof n&&(r=r||n,n=t);for(s in e)this.on(s,n,r,e[s],o);return this}if(null==r&&null==i?(i=n,r=n=t):null==i&&("string"==typeof n?(i=r,r=t):(i=r,r=n,n=t)),i===!1)i=l;else if(!i)return this;return 1===o&&(a=i,i=function(e){return st().off(e),a.apply(this,arguments)},i.guid=a.guid||(a.guid=st.guid++)),this.each(function(){st.event.add(this,e,i,r,n)})},one:function(e,t,n,r){return this.on(e,t,n,r,1)},off:function(e,n,r){var i,o;if(e&&e.preventDefault&&e.handleObj)return i=e.handleObj,st(e.delegateTarget).off(i.namespace?i.origType+"."+i.namespace:i.origType,i.selector,i.handler),this;if("object"==typeof e){for(o in e)this.off(o,n,e[o]);return this}return(n===!1||"function"==typeof n)&&(r=n,n=t),r===!1&&(r=l),this.each(function(){st.event.remove(this,e,r,n)})},bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,r){return this.on(t,e,n,r)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)},trigger:function(e,t){return this.each(function(){st.event.trigger(e,t,this)})},triggerHandler:function(e,n){var r=this[0];return r?st.event.trigger(e,n,r,!0):t},hover:function(e,t){return this.mouseenter(e).mouseleave(t||e)}}),st.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(e,t){st.fn[t]=function(e,n){return arguments.length>0?this.on(t,null,e,n):this.trigger(t)},_t.test(t)&&(st.event.fixHooks[t]=st.event.keyHooks),Ft.test(t)&&(st.event.fixHooks[t]=st.event.mouseHooks)}),function(e,t){function n(e){return ht.test(e+"")}function r(){var e,t=[];return e=function(n,r){return t.push(n+=" ")>C.cacheLength&&delete e[t.shift()],e[n]=r}}function i(e){return e[P]=!0,e}function o(e){var t=L.createElement("div");try{return e(t)}catch(n){return!1}finally{t=null}}function a(e,t,n,r){var i,o,a,s,u,l,c,d,h,g;if((t?t.ownerDocument||t:R)!==L&&D(t),t=t||L,n=n||[],!e||"string"!=typeof e)return n;if(1!==(s=t.nodeType)&&9!==s)return[];if(!M&&!r){if(i=gt.exec(e))if(a=i[1]){if(9===s){if(o=t.getElementById(a),!o||!o.parentNode)return n;if(o.id===a)return n.push(o),n}else if(t.ownerDocument&&(o=t.ownerDocument.getElementById(a))&&O(t,o)&&o.id===a)return n.push(o),n}else{if(i[2])return Q.apply(n,K.call(t.getElementsByTagName(e),0)),n;if((a=i[3])&&W.getByClassName&&t.getElementsByClassName)return Q.apply(n,K.call(t.getElementsByClassName(a),0)),n}if(W.qsa&&!q.test(e)){if(c=!0,d=P,h=t,g=9===s&&e,1===s&&"object"!==t.nodeName.toLowerCase()){for(l=f(e),(c=t.getAttribute("id"))?d=c.replace(vt,"\\$&"):t.setAttribute("id",d),d="[id='"+d+"'] ",u=l.length;u--;)l[u]=d+p(l[u]);h=dt.test(e)&&t.parentNode||t,g=l.join(",")}if(g)try{return Q.apply(n,K.call(h.querySelectorAll(g),0)),n}catch(m){}finally{c||t.removeAttribute("id")}}}return x(e.replace(at,"$1"),t,n,r)}function s(e,t){for(var n=e&&t&&e.nextSibling;n;n=n.nextSibling)if(n===t)return-1;return e?1:-1}function u(e){return function(t){var n=t.nodeName.toLowerCase();return"input"===n&&t.type===e}}function l(e){return function(t){var n=t.nodeName.toLowerCase();return("input"===n||"button"===n)&&t.type===e}}function c(e){return i(function(t){return t=+t,i(function(n,r){for(var i,o=e([],n.length,t),a=o.length;a--;)n[i=o[a]]&&(n[i]=!(r[i]=n[i]))})})}function f(e,t){var n,r,i,o,s,u,l,c=X[e+" "];if(c)return t?0:c.slice(0);for(s=e,u=[],l=C.preFilter;s;){(!n||(r=ut.exec(s)))&&(r&&(s=s.slice(r[0].length)||s),u.push(i=[])),n=!1,(r=lt.exec(s))&&(n=r.shift(),i.push({value:n,type:r[0].replace(at," ")}),s=s.slice(n.length));for(o in C.filter)!(r=pt[o].exec(s))||l[o]&&!(r=l[o](r))||(n=r.shift(),i.push({value:n,type:o,matches:r}),s=s.slice(n.length));if(!n)break}return t?s.length:s?a.error(e):X(e,u).slice(0)}function p(e){for(var t=0,n=e.length,r="";n>t;t++)r+=e[t].value;return r}function d(e,t,n){var r=t.dir,i=n&&"parentNode"===t.dir,o=I++;return t.first?function(t,n,o){for(;t=t[r];)if(1===t.nodeType||i)return e(t,n,o)}:function(t,n,a){var s,u,l,c=$+" "+o;if(a){for(;t=t[r];)if((1===t.nodeType||i)&&e(t,n,a))return!0}else for(;t=t[r];)if(1===t.nodeType||i)if(l=t[P]||(t[P]={}),(u=l[r])&&u[0]===c){if((s=u[1])===!0||s===N)return s===!0}else if(u=l[r]=[c],u[1]=e(t,n,a)||N,u[1]===!0)return!0}}function h(e){return e.length>1?function(t,n,r){for(var i=e.length;i--;)if(!e[i](t,n,r))return!1;return!0}:e[0]}function g(e,t,n,r,i){for(var o,a=[],s=0,u=e.length,l=null!=t;u>s;s++)(o=e[s])&&(!n||n(o,r,i))&&(a.push(o),l&&t.push(s));return a}function m(e,t,n,r,o,a){return r&&!r[P]&&(r=m(r)),o&&!o[P]&&(o=m(o,a)),i(function(i,a,s,u){var l,c,f,p=[],d=[],h=a.length,m=i||b(t||"*",s.nodeType?[s]:s,[]),y=!e||!i&&t?m:g(m,p,e,s,u),v=n?o||(i?e:h||r)?[]:a:y;if(n&&n(y,v,s,u),r)for(l=g(v,d),r(l,[],s,u),c=l.length;c--;)(f=l[c])&&(v[d[c]]=!(y[d[c]]=f));if(i){if(o||e){if(o){for(l=[],c=v.length;c--;)(f=v[c])&&l.push(y[c]=f);o(null,v=[],l,u)}for(c=v.length;c--;)(f=v[c])&&(l=o?Z.call(i,f):p[c])>-1&&(i[l]=!(a[l]=f))}}else v=g(v===a?v.splice(h,v.length):v),o?o(null,a,v,u):Q.apply(a,v)})}function y(e){for(var t,n,r,i=e.length,o=C.relative[e[0].type],a=o||C.relative[" "],s=o?1:0,u=d(function(e){return e===t},a,!0),l=d(function(e){return Z.call(t,e)>-1},a,!0),c=[function(e,n,r){return!o&&(r||n!==j)||((t=n).nodeType?u(e,n,r):l(e,n,r))}];i>s;s++)if(n=C.relative[e[s].type])c=[d(h(c),n)];else{if(n=C.filter[e[s].type].apply(null,e[s].matches),n[P]){for(r=++s;i>r&&!C.relative[e[r].type];r++);return m(s>1&&h(c),s>1&&p(e.slice(0,s-1)).replace(at,"$1"),n,r>s&&y(e.slice(s,r)),i>r&&y(e=e.slice(r)),i>r&&p(e))}c.push(n)}return h(c)}function v(e,t){var n=0,r=t.length>0,o=e.length>0,s=function(i,s,u,l,c){var f,p,d,h=[],m=0,y="0",v=i&&[],b=null!=c,x=j,T=i||o&&C.find.TAG("*",c&&s.parentNode||s),w=$+=null==x?1:Math.E;for(b&&(j=s!==L&&s,N=n);null!=(f=T[y]);y++){if(o&&f){for(p=0;d=e[p];p++)if(d(f,s,u)){l.push(f);break}b&&($=w,N=++n)}r&&((f=!d&&f)&&m--,i&&v.push(f))}if(m+=y,r&&y!==m){for(p=0;d=t[p];p++)d(v,h,s,u);if(i){if(m>0)for(;y--;)v[y]||h[y]||(h[y]=G.call(l));h=g(h)}Q.apply(l,h),b&&!i&&h.length>0&&m+t.length>1&&a.uniqueSort(l)}return b&&($=w,j=x),v};return r?i(s):s}function b(e,t,n){for(var r=0,i=t.length;i>r;r++)a(e,t[r],n);return n}function x(e,t,n,r){var i,o,a,s,u,l=f(e);if(!r&&1===l.length){if(o=l[0]=l[0].slice(0),o.length>2&&"ID"===(a=o[0]).type&&9===t.nodeType&&!M&&C.relative[o[1].type]){if(t=C.find.ID(a.matches[0].replace(xt,Tt),t)[0],!t)return n;e=e.slice(o.shift().value.length)}for(i=pt.needsContext.test(e)?-1:o.length-1;i>=0&&(a=o[i],!C.relative[s=a.type]);i--)if((u=C.find[s])&&(r=u(a.matches[0].replace(xt,Tt),dt.test(o[0].type)&&t.parentNode||t))){if(o.splice(i,1),e=r.length&&p(o),!e)return Q.apply(n,K.call(r,0)),n;break}}return S(e,l)(r,t,M,n,dt.test(e)),n}function T(){}var w,N,C,k,E,S,A,j,D,L,H,M,q,_,F,O,B,P="sizzle"+-new Date,R=e.document,W={},$=0,I=0,z=r(),X=r(),U=r(),V=typeof t,Y=1<<31,J=[],G=J.pop,Q=J.push,K=J.slice,Z=J.indexOf||function(e){for(var t=0,n=this.length;n>t;t++)if(this[t]===e)return t;return-1},et="[\\x20\\t\\r\\n\\f]",tt="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",nt=tt.replace("w","w#"),rt="([*^$|!~]?=)",it="\\["+et+"*("+tt+")"+et+"*(?:"+rt+et+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+nt+")|)|)"+et+"*\\]",ot=":("+tt+")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|"+it.replace(3,8)+")*)|.*)\\)|)",at=RegExp("^"+et+"+|((?:^|[^\\\\])(?:\\\\.)*)"+et+"+$","g"),ut=RegExp("^"+et+"*,"+et+"*"),lt=RegExp("^"+et+"*([\\x20\\t\\r\\n\\f>+~])"+et+"*"),ct=RegExp(ot),ft=RegExp("^"+nt+"$"),pt={ID:RegExp("^#("+tt+")"),CLASS:RegExp("^\\.("+tt+")"),NAME:RegExp("^\\[name=['\"]?("+tt+")['\"]?\\]"),TAG:RegExp("^("+tt.replace("w","w*")+")"),ATTR:RegExp("^"+it),PSEUDO:RegExp("^"+ot),CHILD:RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+et+"*(even|odd|(([+-]|)(\\d*)n|)"+et+"*(?:([+-]|)"+et+"*(\\d+)|))"+et+"*\\)|)","i"),needsContext:RegExp("^"+et+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+et+"*((?:-\\d)?\\d*)"+et+"*\\)|)(?=[^-]|$)","i")},dt=/[\x20\t\r\n\f]*[+~]/,ht=/\{\s*\[native code\]\s*\}/,gt=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,mt=/^(?:input|select|textarea|button)$/i,yt=/^h\d$/i,vt=/'|\\/g,bt=/\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,xt=/\\([\da-fA-F]{1,6}[\x20\t\r\n\f]?|.)/g,Tt=function(e,t){var n="0x"+t-65536;return n!==n?t:0>n?String.fromCharCode(n+65536):String.fromCharCode(55296|n>>10,56320|1023&n)};try{K.call(H.childNodes,0)[0].nodeType}catch(wt){K=function(e){for(var t,n=[];t=this[e];e++)n.push(t);return n}}E=a.isXML=function(e){var t=e&&(e.ownerDocument||e).documentElement;return t?"HTML"!==t.nodeName:!1},D=a.setDocument=function(e){var r=e?e.ownerDocument||e:R;return r!==L&&9===r.nodeType&&r.documentElement?(L=r,H=r.documentElement,M=E(r),W.tagNameNoComments=o(function(e){return e.appendChild(r.createComment("")),!e.getElementsByTagName("*").length}),W.attributes=o(function(e){e.innerHTML="<select></select>";var t=typeof e.lastChild.getAttribute("multiple");return"boolean"!==t&&"string"!==t}),W.getByClassName=o(function(e){return e.innerHTML="<div class='hidden e'></div><div class='hidden'></div>",e.getElementsByClassName&&e.getElementsByClassName("e").length?(e.lastChild.className="e",2===e.getElementsByClassName("e").length):!1}),W.getByName=o(function(e){e.id=P+0,e.innerHTML="<a name='"+P+"'></a><div name='"+P+"'></div>",H.insertBefore(e,H.firstChild);var t=r.getElementsByName&&r.getElementsByName(P).length===2+r.getElementsByName(P+0).length;return W.getIdNotName=!r.getElementById(P),H.removeChild(e),t}),C.attrHandle=o(function(e){return e.innerHTML="<a href='#'></a>",e.firstChild&&typeof e.firstChild.getAttribute!==V&&"#"===e.firstChild.getAttribute("href")})?{}:{href:function(e){return e.getAttribute("href",2)},type:function(e){return e.getAttribute("type")}},W.getIdNotName?(C.find.ID=function(e,t){if(typeof t.getElementById!==V&&!M){var n=t.getElementById(e);return n&&n.parentNode?[n]:[]}},C.filter.ID=function(e){var t=e.replace(xt,Tt);return function(e){return e.getAttribute("id")===t}}):(C.find.ID=function(e,n){if(typeof n.getElementById!==V&&!M){var r=n.getElementById(e);return r?r.id===e||typeof r.getAttributeNode!==V&&r.getAttributeNode("id").value===e?[r]:t:[]}},C.filter.ID=function(e){var t=e.replace(xt,Tt);return function(e){var n=typeof e.getAttributeNode!==V&&e.getAttributeNode("id");return n&&n.value===t}}),C.find.TAG=W.tagNameNoComments?function(e,n){return typeof n.getElementsByTagName!==V?n.getElementsByTagName(e):t}:function(e,t){var n,r=[],i=0,o=t.getElementsByTagName(e);if("*"===e){for(;n=o[i];i++)1===n.nodeType&&r.push(n);return r}return o},C.find.NAME=W.getByName&&function(e,n){return typeof n.getElementsByName!==V?n.getElementsByName(name):t},C.find.CLASS=W.getByClassName&&function(e,n){return typeof n.getElementsByClassName===V||M?t:n.getElementsByClassName(e)},_=[],q=[":focus"],(W.qsa=n(r.querySelectorAll))&&(o(function(e){e.innerHTML="<select><option selected=''></option></select>",e.querySelectorAll("[selected]").length||q.push("\\["+et+"*(?:checked|disabled|ismap|multiple|readonly|selected|value)"),e.querySelectorAll(":checked").length||q.push(":checked")}),o(function(e){e.innerHTML="<input type='hidden' i=''/>",e.querySelectorAll("[i^='']").length&&q.push("[*^$]="+et+"*(?:\"\"|'')"),e.querySelectorAll(":enabled").length||q.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),q.push(",.*:")})),(W.matchesSelector=n(F=H.matchesSelector||H.mozMatchesSelector||H.webkitMatchesSelector||H.oMatchesSelector||H.msMatchesSelector))&&o(function(e){W.disconnectedMatch=F.call(e,"div"),F.call(e,"[s!='']:x"),_.push("!=",ot)}),q=RegExp(q.join("|")),_=RegExp(_.join("|")),O=n(H.contains)||H.compareDocumentPosition?function(e,t){var n=9===e.nodeType?e.documentElement:e,r=t&&t.parentNode;return e===r||!(!r||1!==r.nodeType||!(n.contains?n.contains(r):e.compareDocumentPosition&&16&e.compareDocumentPosition(r)))}:function(e,t){if(t)for(;t=t.parentNode;)if(t===e)return!0;return!1},B=H.compareDocumentPosition?function(e,t){var n;return e===t?(A=!0,0):(n=t.compareDocumentPosition&&e.compareDocumentPosition&&e.compareDocumentPosition(t))?1&n||e.parentNode&&11===e.parentNode.nodeType?e===r||O(R,e)?-1:t===r||O(R,t)?1:0:4&n?-1:1:e.compareDocumentPosition?-1:1}:function(e,t){var n,i=0,o=e.parentNode,a=t.parentNode,u=[e],l=[t];if(e===t)return A=!0,0;if(e.sourceIndex&&t.sourceIndex)return(~t.sourceIndex||Y)-(O(R,e)&&~e.sourceIndex||Y);if(!o||!a)return e===r?-1:t===r?1:o?-1:a?1:0;if(o===a)return s(e,t);for(n=e;n=n.parentNode;)u.unshift(n);for(n=t;n=n.parentNode;)l.unshift(n);for(;u[i]===l[i];)i++;return i?s(u[i],l[i]):u[i]===R?-1:l[i]===R?1:0},A=!1,[0,0].sort(B),W.detectDuplicates=A,L):L},a.matches=function(e,t){return a(e,null,null,t)},a.matchesSelector=function(e,t){if((e.ownerDocument||e)!==L&&D(e),t=t.replace(bt,"='$1']"),!(!W.matchesSelector||M||_&&_.test(t)||q.test(t)))try{var n=F.call(e,t);if(n||W.disconnectedMatch||e.document&&11!==e.document.nodeType)return n}catch(r){}return a(t,L,null,[e]).length>0},a.contains=function(e,t){return(e.ownerDocument||e)!==L&&D(e),O(e,t)},a.attr=function(e,t){var n;return(e.ownerDocument||e)!==L&&D(e),M||(t=t.toLowerCase()),(n=C.attrHandle[t])?n(e):M||W.attributes?e.getAttribute(t):((n=e.getAttributeNode(t))||e.getAttribute(t))&&e[t]===!0?t:n&&n.specified?n.value:null},a.error=function(e){throw Error("Syntax error, unrecognized expression: "+e)},a.uniqueSort=function(e){var t,n=[],r=1,i=0;if(A=!W.detectDuplicates,e.sort(B),A){for(;t=e[r];r++)t===e[r-1]&&(i=n.push(r));for(;i--;)e.splice(n[i],1)}return e},k=a.getText=function(e){var t,n="",r=0,i=e.nodeType;if(i){if(1===i||9===i||11===i){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=k(e)}else if(3===i||4===i)return e.nodeValue}else for(;t=e[r];r++)n+=k(t);return n},C=a.selectors={cacheLength:50,createPseudo:i,match:pt,find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(xt,Tt),e[3]=(e[4]||e[5]||"").replace(xt,Tt),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||a.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&a.error(e[0]),e},PSEUDO:function(e){var t,n=!e[5]&&e[2];return pt.CHILD.test(e[0])?null:(e[4]?e[2]=e[4]:n&&ct.test(n)&&(t=f(n,!0))&&(t=n.indexOf(")",n.length-t)-n.length)&&(e[0]=e[0].slice(0,t),e[2]=n.slice(0,t)),e.slice(0,3))}},filter:{TAG:function(e){return"*"===e?function(){return!0}:(e=e.replace(xt,Tt).toLowerCase(),function(t){return t.nodeName&&t.nodeName.toLowerCase()===e})},CLASS:function(e){var t=z[e+" "];return t||(t=RegExp("(^|"+et+")"+e+"("+et+"|$)"))&&z(e,function(e){return t.test(e.className||typeof e.getAttribute!==V&&e.getAttribute("class")||"")})},ATTR:function(e,t,n){return function(r){var i=a.attr(r,e);return null==i?"!="===t:t?(i+="","="===t?i===n:"!="===t?i!==n:"^="===t?n&&0===i.indexOf(n):"*="===t?n&&i.indexOf(n)>-1:"$="===t?n&&i.substr(i.length-n.length)===n:"~="===t?(" "+i+" ").indexOf(n)>-1:"|="===t?i===n||i.substr(0,n.length+1)===n+"-":!1):!0}},CHILD:function(e,t,n,r,i){var o="nth"!==e.slice(0,3),a="last"!==e.slice(-4),s="of-type"===t;return 1===r&&0===i?function(e){return!!e.parentNode}:function(t,n,u){var l,c,f,p,d,h,g=o!==a?"nextSibling":"previousSibling",m=t.parentNode,y=s&&t.nodeName.toLowerCase(),v=!u&&!s;if(m){if(o){for(;g;){for(f=t;f=f[g];)if(s?f.nodeName.toLowerCase()===y:1===f.nodeType)return!1;h=g="only"===e&&!h&&"nextSibling"}return!0}if(h=[a?m.firstChild:m.lastChild],a&&v){for(c=m[P]||(m[P]={}),l=c[e]||[],d=l[0]===$&&l[1],p=l[0]===$&&l[2],f=d&&m.childNodes[d];f=++d&&f&&f[g]||(p=d=0)||h.pop();)if(1===f.nodeType&&++p&&f===t){c[e]=[$,d,p];break}}else if(v&&(l=(t[P]||(t[P]={}))[e])&&l[0]===$)p=l[1];else for(;(f=++d&&f&&f[g]||(p=d=0)||h.pop())&&((s?f.nodeName.toLowerCase()!==y:1!==f.nodeType)||!++p||(v&&((f[P]||(f[P]={}))[e]=[$,p]),f!==t)););return p-=i,p===r||0===p%r&&p/r>=0}}},PSEUDO:function(e,t){var n,r=C.pseudos[e]||C.setFilters[e.toLowerCase()]||a.error("unsupported pseudo: "+e);return r[P]?r(t):r.length>1?(n=[e,e,"",t],C.setFilters.hasOwnProperty(e.toLowerCase())?i(function(e,n){for(var i,o=r(e,t),a=o.length;a--;)i=Z.call(e,o[a]),e[i]=!(n[i]=o[a])}):function(e){return r(e,0,n)}):r}},pseudos:{not:i(function(e){var t=[],n=[],r=S(e.replace(at,"$1"));return r[P]?i(function(e,t,n,i){for(var o,a=r(e,null,i,[]),s=e.length;s--;)(o=a[s])&&(e[s]=!(t[s]=o))}):function(e,i,o){return t[0]=e,r(t,null,o,n),!n.pop()}}),has:i(function(e){return function(t){return a(e,t).length>0}}),contains:i(function(e){return function(t){return(t.textContent||t.innerText||k(t)).indexOf(e)>-1}}),lang:i(function(e){return ft.test(e||"")||a.error("unsupported lang: "+e),e=e.replace(xt,Tt).toLowerCase(),function(t){var n;do if(n=M?t.getAttribute("xml:lang")||t.getAttribute("lang"):t.lang)return n=n.toLowerCase(),n===e||0===n.indexOf(e+"-");while((t=t.parentNode)&&1===t.nodeType);return!1}}),target:function(t){var n=e.location&&e.location.hash;return n&&n.slice(1)===t.id},root:function(e){return e===H},focus:function(e){return e===L.activeElement&&(!L.hasFocus||L.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:function(e){return e.disabled===!1},disabled:function(e){return e.disabled===!0},checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,e.selected===!0},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeName>"@"||3===e.nodeType||4===e.nodeType)return!1;return!0},parent:function(e){return!C.pseudos.empty(e)},header:function(e){return yt.test(e.nodeName)},input:function(e){return mt.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||t.toLowerCase()===e.type)},first:c(function(){return[0]}),last:c(function(e,t){return[t-1]}),eq:c(function(e,t,n){return[0>n?n+t:n]}),even:c(function(e,t){for(var n=0;t>n;n+=2)e.push(n);return e}),odd:c(function(e,t){for(var n=1;t>n;n+=2)e.push(n);return e}),lt:c(function(e,t,n){for(var r=0>n?n+t:n;--r>=0;)e.push(r);return e}),gt:c(function(e,t,n){for(var r=0>n?n+t:n;t>++r;)e.push(r);return e})}};for(w in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})C.pseudos[w]=u(w);for(w in{submit:!0,reset:!0})C.pseudos[w]=l(w);S=a.compile=function(e,t){var n,r=[],i=[],o=U[e+" "];if(!o){for(t||(t=f(e)),n=t.length;n--;)o=y(t[n]),o[P]?r.push(o):i.push(o);o=U(e,v(i,r))}return o},C.pseudos.nth=C.pseudos.eq,C.filters=T.prototype=C.pseudos,C.setFilters=new T,D(),a.attr=st.attr,st.find=a,st.expr=a.selectors,st.expr[":"]=st.expr.pseudos,st.unique=a.uniqueSort,st.text=a.getText,st.isXMLDoc=a.isXML,st.contains=a.contains}(e);var Pt=/Until$/,Rt=/^(?:parents|prev(?:Until|All))/,Wt=/^.[^:#\[\.,]*$/,$t=st.expr.match.needsContext,It={children:!0,contents:!0,next:!0,prev:!0};st.fn.extend({find:function(e){var t,n,r;if("string"!=typeof e)return r=this,this.pushStack(st(e).filter(function(){for(t=0;r.length>t;t++)if(st.contains(r[t],this))return!0}));for(n=[],t=0;this.length>t;t++)st.find(e,this[t],n);return n=this.pushStack(st.unique(n)),n.selector=(this.selector?this.selector+" ":"")+e,n},has:function(e){var t,n=st(e,this),r=n.length;return this.filter(function(){for(t=0;r>t;t++)if(st.contains(this,n[t]))return!0})},not:function(e){return this.pushStack(f(this,e,!1))},filter:function(e){return this.pushStack(f(this,e,!0))},is:function(e){return!!e&&("string"==typeof e?$t.test(e)?st(e,this.context).index(this[0])>=0:st.filter(e,this).length>0:this.filter(e).length>0)},closest:function(e,t){for(var n,r=0,i=this.length,o=[],a=$t.test(e)||"string"!=typeof e?st(e,t||this.context):0;i>r;r++)for(n=this[r];n&&n.ownerDocument&&n!==t&&11!==n.nodeType;){if(a?a.index(n)>-1:st.find.matchesSelector(n,e)){o.push(n);break}n=n.parentNode}return this.pushStack(o.length>1?st.unique(o):o)},index:function(e){return e?"string"==typeof e?st.inArray(this[0],st(e)):st.inArray(e.jquery?e[0]:e,this):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){var n="string"==typeof e?st(e,t):st.makeArray(e&&e.nodeType?[e]:e),r=st.merge(this.get(),n);return this.pushStack(st.unique(r))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}}),st.fn.andSelf=st.fn.addBack,st.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return st.dir(e,"parentNode")},parentsUntil:function(e,t,n){return st.dir(e,"parentNode",n)},next:function(e){return c(e,"nextSibling")},prev:function(e){return c(e,"previousSibling")
},nextAll:function(e){return st.dir(e,"nextSibling")},prevAll:function(e){return st.dir(e,"previousSibling")},nextUntil:function(e,t,n){return st.dir(e,"nextSibling",n)},prevUntil:function(e,t,n){return st.dir(e,"previousSibling",n)},siblings:function(e){return st.sibling((e.parentNode||{}).firstChild,e)},children:function(e){return st.sibling(e.firstChild)},contents:function(e){return st.nodeName(e,"iframe")?e.contentDocument||e.contentWindow.document:st.merge([],e.childNodes)}},function(e,t){st.fn[e]=function(n,r){var i=st.map(this,t,n);return Pt.test(e)||(r=n),r&&"string"==typeof r&&(i=st.filter(r,i)),i=this.length>1&&!It[e]?st.unique(i):i,this.length>1&&Rt.test(e)&&(i=i.reverse()),this.pushStack(i)}}),st.extend({filter:function(e,t,n){return n&&(e=":not("+e+")"),1===t.length?st.find.matchesSelector(t[0],e)?[t[0]]:[]:st.find.matches(e,t)},dir:function(e,n,r){for(var i=[],o=e[n];o&&9!==o.nodeType&&(r===t||1!==o.nodeType||!st(o).is(r));)1===o.nodeType&&i.push(o),o=o[n];return i},sibling:function(e,t){for(var n=[];e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n}});var zt="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",Xt=/ jQuery\d+="(?:null|\d+)"/g,Ut=RegExp("<(?:"+zt+")[\\s/>]","i"),Vt=/^\s+/,Yt=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,Jt=/<([\w:]+)/,Gt=/<tbody/i,Qt=/<|&#?\w+;/,Kt=/<(?:script|style|link)/i,Zt=/^(?:checkbox|radio)$/i,en=/checked\s*(?:[^=]|=\s*.checked.)/i,tn=/^$|\/(?:java|ecma)script/i,nn=/^true\/(.*)/,rn=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,on={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],area:[1,"<map>","</map>"],param:[1,"<object>","</object>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:st.support.htmlSerialize?[0,"",""]:[1,"X<div>","</div>"]},an=p(V),sn=an.appendChild(V.createElement("div"));on.optgroup=on.option,on.tbody=on.tfoot=on.colgroup=on.caption=on.thead,on.th=on.td,st.fn.extend({text:function(e){return st.access(this,function(e){return e===t?st.text(this):this.empty().append((this[0]&&this[0].ownerDocument||V).createTextNode(e))},null,e,arguments.length)},wrapAll:function(e){if(st.isFunction(e))return this.each(function(t){st(this).wrapAll(e.call(this,t))});if(this[0]){var t=st(e,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&t.insertBefore(this[0]),t.map(function(){for(var e=this;e.firstChild&&1===e.firstChild.nodeType;)e=e.firstChild;return e}).append(this)}return this},wrapInner:function(e){return st.isFunction(e)?this.each(function(t){st(this).wrapInner(e.call(this,t))}):this.each(function(){var t=st(this),n=t.contents();n.length?n.wrapAll(e):t.append(e)})},wrap:function(e){var t=st.isFunction(e);return this.each(function(n){st(this).wrapAll(t?e.call(this,n):e)})},unwrap:function(){return this.parent().each(function(){st.nodeName(this,"body")||st(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(e){(1===this.nodeType||11===this.nodeType||9===this.nodeType)&&this.appendChild(e)})},prepend:function(){return this.domManip(arguments,!0,function(e){(1===this.nodeType||11===this.nodeType||9===this.nodeType)&&this.insertBefore(e,this.firstChild)})},before:function(){return this.domManip(arguments,!1,function(e){this.parentNode&&this.parentNode.insertBefore(e,this)})},after:function(){return this.domManip(arguments,!1,function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)})},remove:function(e,t){for(var n,r=0;null!=(n=this[r]);r++)(!e||st.filter(e,[n]).length>0)&&(t||1!==n.nodeType||st.cleanData(b(n)),n.parentNode&&(t&&st.contains(n.ownerDocument,n)&&m(b(n,"script")),n.parentNode.removeChild(n)));return this},empty:function(){for(var e,t=0;null!=(e=this[t]);t++){for(1===e.nodeType&&st.cleanData(b(e,!1));e.firstChild;)e.removeChild(e.firstChild);e.options&&st.nodeName(e,"select")&&(e.options.length=0)}return this},clone:function(e,t){return e=null==e?!1:e,t=null==t?e:t,this.map(function(){return st.clone(this,e,t)})},html:function(e){return st.access(this,function(e){var n=this[0]||{},r=0,i=this.length;if(e===t)return 1===n.nodeType?n.innerHTML.replace(Xt,""):t;if(!("string"!=typeof e||Kt.test(e)||!st.support.htmlSerialize&&Ut.test(e)||!st.support.leadingWhitespace&&Vt.test(e)||on[(Jt.exec(e)||["",""])[1].toLowerCase()])){e=e.replace(Yt,"<$1></$2>");try{for(;i>r;r++)n=this[r]||{},1===n.nodeType&&(st.cleanData(b(n,!1)),n.innerHTML=e);n=0}catch(o){}}n&&this.empty().append(e)},null,e,arguments.length)},replaceWith:function(e){var t=st.isFunction(e);return t||"string"==typeof e||(e=st(e).not(this).detach()),this.domManip([e],!0,function(e){var t=this.nextSibling,n=this.parentNode;(n&&1===this.nodeType||11===this.nodeType)&&(st(this).remove(),t?t.parentNode.insertBefore(e,t):n.appendChild(e))})},detach:function(e){return this.remove(e,!0)},domManip:function(e,n,r){e=et.apply([],e);var i,o,a,s,u,l,c=0,f=this.length,p=this,m=f-1,y=e[0],v=st.isFunction(y);if(v||!(1>=f||"string"!=typeof y||st.support.checkClone)&&en.test(y))return this.each(function(i){var o=p.eq(i);v&&(e[0]=y.call(this,i,n?o.html():t)),o.domManip(e,n,r)});if(f&&(i=st.buildFragment(e,this[0].ownerDocument,!1,this),o=i.firstChild,1===i.childNodes.length&&(i=o),o)){for(n=n&&st.nodeName(o,"tr"),a=st.map(b(i,"script"),h),s=a.length;f>c;c++)u=i,c!==m&&(u=st.clone(u,!0,!0),s&&st.merge(a,b(u,"script"))),r.call(n&&st.nodeName(this[c],"table")?d(this[c],"tbody"):this[c],u,c);if(s)for(l=a[a.length-1].ownerDocument,st.map(a,g),c=0;s>c;c++)u=a[c],tn.test(u.type||"")&&!st._data(u,"globalEval")&&st.contains(l,u)&&(u.src?st.ajax({url:u.src,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0}):st.globalEval((u.text||u.textContent||u.innerHTML||"").replace(rn,"")));i=o=null}return this}}),st.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,t){st.fn[e]=function(e){for(var n,r=0,i=[],o=st(e),a=o.length-1;a>=r;r++)n=r===a?this:this.clone(!0),st(o[r])[t](n),tt.apply(i,n.get());return this.pushStack(i)}}),st.extend({clone:function(e,t,n){var r,i,o,a,s,u=st.contains(e.ownerDocument,e);if(st.support.html5Clone||st.isXMLDoc(e)||!Ut.test("<"+e.nodeName+">")?s=e.cloneNode(!0):(sn.innerHTML=e.outerHTML,sn.removeChild(s=sn.firstChild)),!(st.support.noCloneEvent&&st.support.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||st.isXMLDoc(e)))for(r=b(s),i=b(e),a=0;null!=(o=i[a]);++a)r[a]&&v(o,r[a]);if(t)if(n)for(i=i||b(e),r=r||b(s),a=0;null!=(o=i[a]);a++)y(o,r[a]);else y(e,s);return r=b(s,"script"),r.length>0&&m(r,!u&&b(e,"script")),r=i=o=null,s},buildFragment:function(e,t,n,r){for(var i,o,a,s,u,l,c,f=e.length,d=p(t),h=[],g=0;f>g;g++)if(o=e[g],o||0===o)if("object"===st.type(o))st.merge(h,o.nodeType?[o]:o);else if(Qt.test(o)){for(s=s||d.appendChild(t.createElement("div")),a=(Jt.exec(o)||["",""])[1].toLowerCase(),u=on[a]||on._default,s.innerHTML=u[1]+o.replace(Yt,"<$1></$2>")+u[2],c=u[0];c--;)s=s.lastChild;if(!st.support.leadingWhitespace&&Vt.test(o)&&h.push(t.createTextNode(Vt.exec(o)[0])),!st.support.tbody)for(o="table"!==a||Gt.test(o)?"<table>"!==u[1]||Gt.test(o)?0:s:s.firstChild,c=o&&o.childNodes.length;c--;)st.nodeName(l=o.childNodes[c],"tbody")&&!l.childNodes.length&&o.removeChild(l);for(st.merge(h,s.childNodes),s.textContent="";s.firstChild;)s.removeChild(s.firstChild);s=d.lastChild}else h.push(t.createTextNode(o));for(s&&d.removeChild(s),st.support.appendChecked||st.grep(b(h,"input"),x),g=0;o=h[g++];)if((!r||-1===st.inArray(o,r))&&(i=st.contains(o.ownerDocument,o),s=b(d.appendChild(o),"script"),i&&m(s),n))for(c=0;o=s[c++];)tn.test(o.type||"")&&n.push(o);return s=null,d},cleanData:function(e,n){for(var r,i,o,a,s=0,u=st.expando,l=st.cache,c=st.support.deleteExpando,f=st.event.special;null!=(o=e[s]);s++)if((n||st.acceptData(o))&&(i=o[u],r=i&&l[i])){if(r.events)for(a in r.events)f[a]?st.event.remove(o,a):st.removeEvent(o,a,r.handle);l[i]&&(delete l[i],c?delete o[u]:o.removeAttribute!==t?o.removeAttribute(u):o[u]=null,K.push(i))}}});var un,ln,cn,fn=/alpha\([^)]*\)/i,pn=/opacity\s*=\s*([^)]*)/,dn=/^(top|right|bottom|left)$/,hn=/^(none|table(?!-c[ea]).+)/,gn=/^margin/,mn=RegExp("^("+ut+")(.*)$","i"),yn=RegExp("^("+ut+")(?!px)[a-z%]+$","i"),vn=RegExp("^([+-])=("+ut+")","i"),bn={BODY:"block"},xn={position:"absolute",visibility:"hidden",display:"block"},Tn={letterSpacing:0,fontWeight:400},wn=["Top","Right","Bottom","Left"],Nn=["Webkit","O","Moz","ms"];st.fn.extend({css:function(e,n){return st.access(this,function(e,n,r){var i,o,a={},s=0;if(st.isArray(n)){for(i=ln(e),o=n.length;o>s;s++)a[n[s]]=st.css(e,n[s],!1,i);return a}return r!==t?st.style(e,n,r):st.css(e,n)},e,n,arguments.length>1)},show:function(){return N(this,!0)},hide:function(){return N(this)},toggle:function(e){var t="boolean"==typeof e;return this.each(function(){(t?e:w(this))?st(this).show():st(this).hide()})}}),st.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=un(e,"opacity");return""===n?"1":n}}}},cssNumber:{columnCount:!0,fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":st.support.cssFloat?"cssFloat":"styleFloat"},style:function(e,n,r,i){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var o,a,s,u=st.camelCase(n),l=e.style;if(n=st.cssProps[u]||(st.cssProps[u]=T(l,u)),s=st.cssHooks[n]||st.cssHooks[u],r===t)return s&&"get"in s&&(o=s.get(e,!1,i))!==t?o:l[n];if(a=typeof r,"string"===a&&(o=vn.exec(r))&&(r=(o[1]+1)*o[2]+parseFloat(st.css(e,n)),a="number"),!(null==r||"number"===a&&isNaN(r)||("number"!==a||st.cssNumber[u]||(r+="px"),st.support.clearCloneStyle||""!==r||0!==n.indexOf("background")||(l[n]="inherit"),s&&"set"in s&&(r=s.set(e,r,i))===t)))try{l[n]=r}catch(c){}}},css:function(e,n,r,i){var o,a,s,u=st.camelCase(n);return n=st.cssProps[u]||(st.cssProps[u]=T(e.style,u)),s=st.cssHooks[n]||st.cssHooks[u],s&&"get"in s&&(o=s.get(e,!0,r)),o===t&&(o=un(e,n,i)),"normal"===o&&n in Tn&&(o=Tn[n]),r?(a=parseFloat(o),r===!0||st.isNumeric(a)?a||0:o):o},swap:function(e,t,n,r){var i,o,a={};for(o in t)a[o]=e.style[o],e.style[o]=t[o];i=n.apply(e,r||[]);for(o in t)e.style[o]=a[o];return i}}),e.getComputedStyle?(ln=function(t){return e.getComputedStyle(t,null)},un=function(e,n,r){var i,o,a,s=r||ln(e),u=s?s.getPropertyValue(n)||s[n]:t,l=e.style;return s&&(""!==u||st.contains(e.ownerDocument,e)||(u=st.style(e,n)),yn.test(u)&&gn.test(n)&&(i=l.width,o=l.minWidth,a=l.maxWidth,l.minWidth=l.maxWidth=l.width=u,u=s.width,l.width=i,l.minWidth=o,l.maxWidth=a)),u}):V.documentElement.currentStyle&&(ln=function(e){return e.currentStyle},un=function(e,n,r){var i,o,a,s=r||ln(e),u=s?s[n]:t,l=e.style;return null==u&&l&&l[n]&&(u=l[n]),yn.test(u)&&!dn.test(n)&&(i=l.left,o=e.runtimeStyle,a=o&&o.left,a&&(o.left=e.currentStyle.left),l.left="fontSize"===n?"1em":u,u=l.pixelLeft+"px",l.left=i,a&&(o.left=a)),""===u?"auto":u}),st.each(["height","width"],function(e,n){st.cssHooks[n]={get:function(e,r,i){return r?0===e.offsetWidth&&hn.test(st.css(e,"display"))?st.swap(e,xn,function(){return E(e,n,i)}):E(e,n,i):t},set:function(e,t,r){var i=r&&ln(e);return C(e,t,r?k(e,n,r,st.support.boxSizing&&"border-box"===st.css(e,"boxSizing",!1,i),i):0)}}}),st.support.opacity||(st.cssHooks.opacity={get:function(e,t){return pn.test((t&&e.currentStyle?e.currentStyle.filter:e.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":t?"1":""},set:function(e,t){var n=e.style,r=e.currentStyle,i=st.isNumeric(t)?"alpha(opacity="+100*t+")":"",o=r&&r.filter||n.filter||"";n.zoom=1,(t>=1||""===t)&&""===st.trim(o.replace(fn,""))&&n.removeAttribute&&(n.removeAttribute("filter"),""===t||r&&!r.filter)||(n.filter=fn.test(o)?o.replace(fn,i):o+" "+i)}}),st(function(){st.support.reliableMarginRight||(st.cssHooks.marginRight={get:function(e,n){return n?st.swap(e,{display:"inline-block"},un,[e,"marginRight"]):t}}),!st.support.pixelPosition&&st.fn.position&&st.each(["top","left"],function(e,n){st.cssHooks[n]={get:function(e,r){return r?(r=un(e,n),yn.test(r)?st(e).position()[n]+"px":r):t}}})}),st.expr&&st.expr.filters&&(st.expr.filters.hidden=function(e){return 0===e.offsetWidth&&0===e.offsetHeight||!st.support.reliableHiddenOffsets&&"none"===(e.style&&e.style.display||st.css(e,"display"))},st.expr.filters.visible=function(e){return!st.expr.filters.hidden(e)}),st.each({margin:"",padding:"",border:"Width"},function(e,t){st.cssHooks[e+t]={expand:function(n){for(var r=0,i={},o="string"==typeof n?n.split(" "):[n];4>r;r++)i[e+wn[r]+t]=o[r]||o[r-2]||o[0];return i}},gn.test(e)||(st.cssHooks[e+t].set=C)});var Cn=/%20/g,kn=/\[\]$/,En=/\r?\n/g,Sn=/^(?:submit|button|image|reset)$/i,An=/^(?:input|select|textarea|keygen)/i;st.fn.extend({serialize:function(){return st.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var e=st.prop(this,"elements");return e?st.makeArray(e):this}).filter(function(){var e=this.type;return this.name&&!st(this).is(":disabled")&&An.test(this.nodeName)&&!Sn.test(e)&&(this.checked||!Zt.test(e))}).map(function(e,t){var n=st(this).val();return null==n?null:st.isArray(n)?st.map(n,function(e){return{name:t.name,value:e.replace(En,"\r\n")}}):{name:t.name,value:n.replace(En,"\r\n")}}).get()}}),st.param=function(e,n){var r,i=[],o=function(e,t){t=st.isFunction(t)?t():null==t?"":t,i[i.length]=encodeURIComponent(e)+"="+encodeURIComponent(t)};if(n===t&&(n=st.ajaxSettings&&st.ajaxSettings.traditional),st.isArray(e)||e.jquery&&!st.isPlainObject(e))st.each(e,function(){o(this.name,this.value)});else for(r in e)j(r,e[r],n,o);return i.join("&").replace(Cn,"+")};var jn,Dn,Ln=st.now(),Hn=/\?/,Mn=/#.*$/,qn=/([?&])_=[^&]*/,_n=/^(.*?):[ \t]*([^\r\n]*)\r?$/gm,Fn=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,On=/^(?:GET|HEAD)$/,Bn=/^\/\//,Pn=/^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,Rn=st.fn.load,Wn={},$n={},In="*/".concat("*");try{Dn=Y.href}catch(zn){Dn=V.createElement("a"),Dn.href="",Dn=Dn.href}jn=Pn.exec(Dn.toLowerCase())||[],st.fn.load=function(e,n,r){if("string"!=typeof e&&Rn)return Rn.apply(this,arguments);var i,o,a,s=this,u=e.indexOf(" ");return u>=0&&(i=e.slice(u,e.length),e=e.slice(0,u)),st.isFunction(n)?(r=n,n=t):n&&"object"==typeof n&&(o="POST"),s.length>0&&st.ajax({url:e,type:o,dataType:"html",data:n}).done(function(e){a=arguments,s.html(i?st("<div>").append(st.parseHTML(e)).find(i):e)}).complete(r&&function(e,t){s.each(r,a||[e.responseText,t,e])}),this},st.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(e,t){st.fn[t]=function(e){return this.on(t,e)}}),st.each(["get","post"],function(e,n){st[n]=function(e,r,i,o){return st.isFunction(r)&&(o=o||i,i=r,r=t),st.ajax({url:e,type:n,dataType:o,data:r,success:i})}}),st.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:Dn,type:"GET",isLocal:Fn.test(jn[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":In,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":e.String,"text html":!0,"text json":st.parseJSON,"text xml":st.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(e,t){return t?H(H(e,st.ajaxSettings),t):H(st.ajaxSettings,e)},ajaxPrefilter:D(Wn),ajaxTransport:D($n),ajax:function(e,n){function r(e,n,r,s){var l,f,v,b,T,N=n;2!==x&&(x=2,u&&clearTimeout(u),i=t,a=s||"",w.readyState=e>0?4:0,r&&(b=M(p,w,r)),e>=200&&300>e||304===e?(p.ifModified&&(T=w.getResponseHeader("Last-Modified"),T&&(st.lastModified[o]=T),T=w.getResponseHeader("etag"),T&&(st.etag[o]=T)),304===e?(l=!0,N="notmodified"):(l=q(p,b),N=l.state,f=l.data,v=l.error,l=!v)):(v=N,(e||!N)&&(N="error",0>e&&(e=0))),w.status=e,w.statusText=(n||N)+"",l?g.resolveWith(d,[f,N,w]):g.rejectWith(d,[w,N,v]),w.statusCode(y),y=t,c&&h.trigger(l?"ajaxSuccess":"ajaxError",[w,p,l?f:v]),m.fireWith(d,[w,N]),c&&(h.trigger("ajaxComplete",[w,p]),--st.active||st.event.trigger("ajaxStop")))}"object"==typeof e&&(n=e,e=t),n=n||{};var i,o,a,s,u,l,c,f,p=st.ajaxSetup({},n),d=p.context||p,h=p.context&&(d.nodeType||d.jquery)?st(d):st.event,g=st.Deferred(),m=st.Callbacks("once memory"),y=p.statusCode||{},v={},b={},x=0,T="canceled",w={readyState:0,getResponseHeader:function(e){var t;if(2===x){if(!s)for(s={};t=_n.exec(a);)s[t[1].toLowerCase()]=t[2];t=s[e.toLowerCase()]}return null==t?null:t},getAllResponseHeaders:function(){return 2===x?a:null},setRequestHeader:function(e,t){var n=e.toLowerCase();return x||(e=b[n]=b[n]||e,v[e]=t),this},overrideMimeType:function(e){return x||(p.mimeType=e),this},statusCode:function(e){var t;if(e)if(2>x)for(t in e)y[t]=[y[t],e[t]];else w.always(e[w.status]);return this},abort:function(e){var t=e||T;return i&&i.abort(t),r(0,t),this}};if(g.promise(w).complete=m.add,w.success=w.done,w.error=w.fail,p.url=((e||p.url||Dn)+"").replace(Mn,"").replace(Bn,jn[1]+"//"),p.type=n.method||n.type||p.method||p.type,p.dataTypes=st.trim(p.dataType||"*").toLowerCase().match(lt)||[""],null==p.crossDomain&&(l=Pn.exec(p.url.toLowerCase()),p.crossDomain=!(!l||l[1]===jn[1]&&l[2]===jn[2]&&(l[3]||("http:"===l[1]?80:443))==(jn[3]||("http:"===jn[1]?80:443)))),p.data&&p.processData&&"string"!=typeof p.data&&(p.data=st.param(p.data,p.traditional)),L(Wn,p,n,w),2===x)return w;c=p.global,c&&0===st.active++&&st.event.trigger("ajaxStart"),p.type=p.type.toUpperCase(),p.hasContent=!On.test(p.type),o=p.url,p.hasContent||(p.data&&(o=p.url+=(Hn.test(o)?"&":"?")+p.data,delete p.data),p.cache===!1&&(p.url=qn.test(o)?o.replace(qn,"$1_="+Ln++):o+(Hn.test(o)?"&":"?")+"_="+Ln++)),p.ifModified&&(st.lastModified[o]&&w.setRequestHeader("If-Modified-Since",st.lastModified[o]),st.etag[o]&&w.setRequestHeader("If-None-Match",st.etag[o])),(p.data&&p.hasContent&&p.contentType!==!1||n.contentType)&&w.setRequestHeader("Content-Type",p.contentType),w.setRequestHeader("Accept",p.dataTypes[0]&&p.accepts[p.dataTypes[0]]?p.accepts[p.dataTypes[0]]+("*"!==p.dataTypes[0]?", "+In+"; q=0.01":""):p.accepts["*"]);for(f in p.headers)w.setRequestHeader(f,p.headers[f]);if(p.beforeSend&&(p.beforeSend.call(d,w,p)===!1||2===x))return w.abort();T="abort";for(f in{success:1,error:1,complete:1})w[f](p[f]);if(i=L($n,p,n,w)){w.readyState=1,c&&h.trigger("ajaxSend",[w,p]),p.async&&p.timeout>0&&(u=setTimeout(function(){w.abort("timeout")},p.timeout));try{x=1,i.send(v,r)}catch(N){if(!(2>x))throw N;r(-1,N)}}else r(-1,"No Transport");return w},getScript:function(e,n){return st.get(e,t,n,"script")},getJSON:function(e,t,n){return st.get(e,t,n,"json")}}),st.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(e){return st.globalEval(e),e}}}),st.ajaxPrefilter("script",function(e){e.cache===t&&(e.cache=!1),e.crossDomain&&(e.type="GET",e.global=!1)}),st.ajaxTransport("script",function(e){if(e.crossDomain){var n,r=V.head||st("head")[0]||V.documentElement;return{send:function(t,i){n=V.createElement("script"),n.async=!0,e.scriptCharset&&(n.charset=e.scriptCharset),n.src=e.url,n.onload=n.onreadystatechange=function(e,t){(t||!n.readyState||/loaded|complete/.test(n.readyState))&&(n.onload=n.onreadystatechange=null,n.parentNode&&n.parentNode.removeChild(n),n=null,t||i(200,"success"))},r.insertBefore(n,r.firstChild)},abort:function(){n&&n.onload(t,!0)}}}});var Xn=[],Un=/(=)\?(?=&|$)|\?\?/;st.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var e=Xn.pop()||st.expando+"_"+Ln++;return this[e]=!0,e}}),st.ajaxPrefilter("json jsonp",function(n,r,i){var o,a,s,u=n.jsonp!==!1&&(Un.test(n.url)?"url":"string"==typeof n.data&&!(n.contentType||"").indexOf("application/x-www-form-urlencoded")&&Un.test(n.data)&&"data");return u||"jsonp"===n.dataTypes[0]?(o=n.jsonpCallback=st.isFunction(n.jsonpCallback)?n.jsonpCallback():n.jsonpCallback,u?n[u]=n[u].replace(Un,"$1"+o):n.jsonp!==!1&&(n.url+=(Hn.test(n.url)?"&":"?")+n.jsonp+"="+o),n.converters["script json"]=function(){return s||st.error(o+" was not called"),s[0]},n.dataTypes[0]="json",a=e[o],e[o]=function(){s=arguments},i.always(function(){e[o]=a,n[o]&&(n.jsonpCallback=r.jsonpCallback,Xn.push(o)),s&&st.isFunction(a)&&a(s[0]),s=a=t}),"script"):t});var Vn,Yn,Jn=0,Gn=e.ActiveXObject&&function(){var e;for(e in Vn)Vn[e](t,!0)};st.ajaxSettings.xhr=e.ActiveXObject?function(){return!this.isLocal&&_()||F()}:_,Yn=st.ajaxSettings.xhr(),st.support.cors=!!Yn&&"withCredentials"in Yn,Yn=st.support.ajax=!!Yn,Yn&&st.ajaxTransport(function(n){if(!n.crossDomain||st.support.cors){var r;return{send:function(i,o){var a,s,u=n.xhr();if(n.username?u.open(n.type,n.url,n.async,n.username,n.password):u.open(n.type,n.url,n.async),n.xhrFields)for(s in n.xhrFields)u[s]=n.xhrFields[s];n.mimeType&&u.overrideMimeType&&u.overrideMimeType(n.mimeType),n.crossDomain||i["X-Requested-With"]||(i["X-Requested-With"]="XMLHttpRequest");try{for(s in i)u.setRequestHeader(s,i[s])}catch(l){}u.send(n.hasContent&&n.data||null),r=function(e,i){var s,l,c,f,p;try{if(r&&(i||4===u.readyState))if(r=t,a&&(u.onreadystatechange=st.noop,Gn&&delete Vn[a]),i)4!==u.readyState&&u.abort();else{f={},s=u.status,p=u.responseXML,c=u.getAllResponseHeaders(),p&&p.documentElement&&(f.xml=p),"string"==typeof u.responseText&&(f.text=u.responseText);try{l=u.statusText}catch(d){l=""}s||!n.isLocal||n.crossDomain?1223===s&&(s=204):s=f.text?200:404}}catch(h){i||o(-1,h)}f&&o(s,l,f,c)},n.async?4===u.readyState?setTimeout(r):(a=++Jn,Gn&&(Vn||(Vn={},st(e).unload(Gn)),Vn[a]=r),u.onreadystatechange=r):r()},abort:function(){r&&r(t,!0)}}}});var Qn,Kn,Zn=/^(?:toggle|show|hide)$/,er=RegExp("^(?:([+-])=|)("+ut+")([a-z%]*)$","i"),tr=/queueHooks$/,nr=[W],rr={"*":[function(e,t){var n,r,i=this.createTween(e,t),o=er.exec(t),a=i.cur(),s=+a||0,u=1,l=20;if(o){if(n=+o[2],r=o[3]||(st.cssNumber[e]?"":"px"),"px"!==r&&s){s=st.css(i.elem,e,!0)||n||1;do u=u||".5",s/=u,st.style(i.elem,e,s+r);while(u!==(u=i.cur()/a)&&1!==u&&--l)}i.unit=r,i.start=s,i.end=o[1]?s+(o[1]+1)*n:n}return i}]};st.Animation=st.extend(P,{tweener:function(e,t){st.isFunction(e)?(t=e,e=["*"]):e=e.split(" ");for(var n,r=0,i=e.length;i>r;r++)n=e[r],rr[n]=rr[n]||[],rr[n].unshift(t)},prefilter:function(e,t){t?nr.unshift(e):nr.push(e)}}),st.Tween=$,$.prototype={constructor:$,init:function(e,t,n,r,i,o){this.elem=e,this.prop=n,this.easing=i||"swing",this.options=t,this.start=this.now=this.cur(),this.end=r,this.unit=o||(st.cssNumber[n]?"":"px")},cur:function(){var e=$.propHooks[this.prop];return e&&e.get?e.get(this):$.propHooks._default.get(this)},run:function(e){var t,n=$.propHooks[this.prop];return this.pos=t=this.options.duration?st.easing[this.easing](e,this.options.duration*e,0,1,this.options.duration):e,this.now=(this.end-this.start)*t+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),n&&n.set?n.set(this):$.propHooks._default.set(this),this}},$.prototype.init.prototype=$.prototype,$.propHooks={_default:{get:function(e){var t;return null==e.elem[e.prop]||e.elem.style&&null!=e.elem.style[e.prop]?(t=st.css(e.elem,e.prop,"auto"),t&&"auto"!==t?t:0):e.elem[e.prop]},set:function(e){st.fx.step[e.prop]?st.fx.step[e.prop](e):e.elem.style&&(null!=e.elem.style[st.cssProps[e.prop]]||st.cssHooks[e.prop])?st.style(e.elem,e.prop,e.now+e.unit):e.elem[e.prop]=e.now}}},$.propHooks.scrollTop=$.propHooks.scrollLeft={set:function(e){e.elem.nodeType&&e.elem.parentNode&&(e.elem[e.prop]=e.now)}},st.each(["toggle","show","hide"],function(e,t){var n=st.fn[t];st.fn[t]=function(e,r,i){return null==e||"boolean"==typeof e?n.apply(this,arguments):this.animate(I(t,!0),e,r,i)}}),st.fn.extend({fadeTo:function(e,t,n,r){return this.filter(w).css("opacity",0).show().end().animate({opacity:t},e,n,r)},animate:function(e,t,n,r){var i=st.isEmptyObject(e),o=st.speed(t,n,r),a=function(){var t=P(this,st.extend({},e),o);a.finish=function(){t.stop(!0)},(i||st._data(this,"finish"))&&t.stop(!0)};return a.finish=a,i||o.queue===!1?this.each(a):this.queue(o.queue,a)},stop:function(e,n,r){var i=function(e){var t=e.stop;delete e.stop,t(r)};return"string"!=typeof e&&(r=n,n=e,e=t),n&&e!==!1&&this.queue(e||"fx",[]),this.each(function(){var t=!0,n=null!=e&&e+"queueHooks",o=st.timers,a=st._data(this);if(n)a[n]&&a[n].stop&&i(a[n]);else for(n in a)a[n]&&a[n].stop&&tr.test(n)&&i(a[n]);for(n=o.length;n--;)o[n].elem!==this||null!=e&&o[n].queue!==e||(o[n].anim.stop(r),t=!1,o.splice(n,1));(t||!r)&&st.dequeue(this,e)})},finish:function(e){return e!==!1&&(e=e||"fx"),this.each(function(){var t,n=st._data(this),r=n[e+"queue"],i=n[e+"queueHooks"],o=st.timers,a=r?r.length:0;for(n.finish=!0,st.queue(this,e,[]),i&&i.cur&&i.cur.finish&&i.cur.finish.call(this),t=o.length;t--;)o[t].elem===this&&o[t].queue===e&&(o[t].anim.stop(!0),o.splice(t,1));for(t=0;a>t;t++)r[t]&&r[t].finish&&r[t].finish.call(this);delete n.finish})}}),st.each({slideDown:I("show"),slideUp:I("hide"),slideToggle:I("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(e,t){st.fn[e]=function(e,n,r){return this.animate(t,e,n,r)}}),st.speed=function(e,t,n){var r=e&&"object"==typeof e?st.extend({},e):{complete:n||!n&&t||st.isFunction(e)&&e,duration:e,easing:n&&t||t&&!st.isFunction(t)&&t};return r.duration=st.fx.off?0:"number"==typeof r.duration?r.duration:r.duration in st.fx.speeds?st.fx.speeds[r.duration]:st.fx.speeds._default,(null==r.queue||r.queue===!0)&&(r.queue="fx"),r.old=r.complete,r.complete=function(){st.isFunction(r.old)&&r.old.call(this),r.queue&&st.dequeue(this,r.queue)},r},st.easing={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2}},st.timers=[],st.fx=$.prototype.init,st.fx.tick=function(){var e,n=st.timers,r=0;for(Qn=st.now();n.length>r;r++)e=n[r],e()||n[r]!==e||n.splice(r--,1);n.length||st.fx.stop(),Qn=t},st.fx.timer=function(e){e()&&st.timers.push(e)&&st.fx.start()},st.fx.interval=13,st.fx.start=function(){Kn||(Kn=setInterval(st.fx.tick,st.fx.interval))},st.fx.stop=function(){clearInterval(Kn),Kn=null},st.fx.speeds={slow:600,fast:200,_default:400},st.fx.step={},st.expr&&st.expr.filters&&(st.expr.filters.animated=function(e){return st.grep(st.timers,function(t){return e===t.elem}).length}),st.fn.offset=function(e){if(arguments.length)return e===t?this:this.each(function(t){st.offset.setOffset(this,e,t)});var n,r,i={top:0,left:0},o=this[0],a=o&&o.ownerDocument;if(a)return n=a.documentElement,st.contains(n,o)?(o.getBoundingClientRect!==t&&(i=o.getBoundingClientRect()),r=z(a),{top:i.top+(r.pageYOffset||n.scrollTop)-(n.clientTop||0),left:i.left+(r.pageXOffset||n.scrollLeft)-(n.clientLeft||0)}):i},st.offset={setOffset:function(e,t,n){var r=st.css(e,"position");"static"===r&&(e.style.position="relative");var i,o,a=st(e),s=a.offset(),u=st.css(e,"top"),l=st.css(e,"left"),c=("absolute"===r||"fixed"===r)&&st.inArray("auto",[u,l])>-1,f={},p={};c?(p=a.position(),i=p.top,o=p.left):(i=parseFloat(u)||0,o=parseFloat(l)||0),st.isFunction(t)&&(t=t.call(e,n,s)),null!=t.top&&(f.top=t.top-s.top+i),null!=t.left&&(f.left=t.left-s.left+o),"using"in t?t.using.call(e,f):a.css(f)}},st.fn.extend({position:function(){if(this[0]){var e,t,n={top:0,left:0},r=this[0];return"fixed"===st.css(r,"position")?t=r.getBoundingClientRect():(e=this.offsetParent(),t=this.offset(),st.nodeName(e[0],"html")||(n=e.offset()),n.top+=st.css(e[0],"borderTopWidth",!0),n.left+=st.css(e[0],"borderLeftWidth",!0)),{top:t.top-n.top-st.css(r,"marginTop",!0),left:t.left-n.left-st.css(r,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){for(var e=this.offsetParent||V.documentElement;e&&!st.nodeName(e,"html")&&"static"===st.css(e,"position");)e=e.offsetParent;return e||V.documentElement})}}),st.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(e,n){var r=/Y/.test(n);st.fn[e]=function(i){return st.access(this,function(e,i,o){var a=z(e);return o===t?a?n in a?a[n]:a.document.documentElement[i]:e[i]:(a?a.scrollTo(r?st(a).scrollLeft():o,r?o:st(a).scrollTop()):e[i]=o,t)},e,i,arguments.length,null)}}),st.each({Height:"height",Width:"width"},function(e,n){st.each({padding:"inner"+e,content:n,"":"outer"+e},function(r,i){st.fn[i]=function(i,o){var a=arguments.length&&(r||"boolean"!=typeof i),s=r||(i===!0||o===!0?"margin":"border");return st.access(this,function(n,r,i){var o;return st.isWindow(n)?n.document.documentElement["client"+e]:9===n.nodeType?(o=n.documentElement,Math.max(n.body["scroll"+e],o["scroll"+e],n.body["offset"+e],o["offset"+e],o["client"+e])):i===t?st.css(n,r,s):st.style(n,r,i,s)},n,a?i:t,a,null)}})}),e.jQuery=e.$=st,"function"==typeof define&&define.amd&&define.amd.jQuery&&define("jquery",[],function(){return st})})(window);
//@ sourceMappingURL=jquery.min.map
/*
* Placeholder plugin for jQuery
* ---
* Copyright 2010, Daniel Stocks (http://webcloud.se)
* Released under the MIT, BSD, and GPL Licenses.
*/

(function(b){function d(a){this.input=a;a.attr("type")=="password"&&this.handlePassword();b(a[0].form).submit(function(){if(a.hasClass("placeholder")&&a[0].value==a.attr("placeholder"))a[0].value=""})}d.prototype={show:function(a){if(this.input[0].value===""||a&&this.valueIsPlaceholder()){if(this.isPassword)try{this.input[0].setAttribute("type","text")}catch(b){this.input.before(this.fakePassword.show()).hide()}this.input.addClass("placeholder");this.input[0].value=this.input.attr("placeholder")}},
hide:function(){if(this.valueIsPlaceholder()&&this.input.hasClass("placeholder")&&(this.input.removeClass("placeholder"),this.input[0].value="",this.isPassword)){try{this.input[0].setAttribute("type","password")}catch(a){}this.input.show();this.input[0].focus()}},valueIsPlaceholder:function(){return this.input[0].value==this.input.attr("placeholder")},handlePassword:function(){var a=this.input;a.attr("realType","password");this.isPassword=!0;if(b.browser.msie&&a[0].outerHTML){var c=b(a[0].outerHTML.replace(/type=(['"])?password\1/gi,
"type=$1text$1"));this.fakePassword=c.val(a.attr("placeholder")).addClass("placeholder").focus(function(){a.trigger("focus");b(this).hide()});b(a[0].form).submit(function(){c.remove();a.show()})}}};var e=!!("placeholder"in document.createElement("input"));b.fn.placeholder=function(){return e?this:this.each(function(){var a=b(this),c=new d(a);c.show(!0);a.focus(function(){c.hide()});a.blur(function(){c.show(!1)});b.browser.msie&&(b(window).load(function(){a.val()&&a.removeClass("placeholder");c.show(!0)}),
a.focus(function(){if(this.value==""){var a=this.createTextRange();a.collapse(!0);a.moveStart("character",0);a.select()}}))})}})(jQuery);

$.fn.sortElements = (function(){

	var sort = [].sort;

	return function(comparator, getSortable) {

		getSortable = getSortable || function(){return this;};

		var placements = this.map(function(){

			var sortElement = getSortable.call(this),
				parentNode = sortElement.parentNode,

				// Since the element itself will change position, we have
				// to have some way of storing its original position in
				// the DOM. The easiest way is to have a 'flag' node:
				nextSibling = parentNode.insertBefore(
					document.createTextNode(''),
					sortElement.nextSibling
				);

			return function() {

				if (parentNode === this) {
					throw new Error(
						"You can't sort elements if any one is a descendant of another."
					);
				}

				// Insert before flag:
				parentNode.insertBefore(this, nextSibling);
				// Remove flag:
				parentNode.removeChild(nextSibling);

			};

		});

		return sort.call(this, comparator).each(function(i){
			placements[i].call(getSortable.call(this));
		});

	};

})();
(function(a){function d(a){var c=["Moz","Webkit","O","ms"];var d=a.charAt(0).toUpperCase()+a.substr(1);if(a in b.style){return a}for(var e=0;e<c.length;++e){var f=c[e]+d;if(f in b.style){return f}}}function e(){b.style[c.transform]="";b.style[c.transform]="rotateY(90deg)";return b.style[c.transform]!==""}function i(a){if(typeof a==="string"){this.parse(a)}return this}function j(a,b,c){if(b===true){a.queue(c)}else if(b){a.queue(b,c)}else{c()}}function k(b){var c=[];a.each(b,function(b){b=a.camelCase(b);b=a.transit.propertyMap[b]||b;b=n(b);if(a.inArray(b,c)===-1){c.push(b)}});return c}function l(b,c,d,e){var f=k(b);if(a.cssEase[d]){d=a.cssEase[d]}var g=""+p(c)+" "+d;if(parseInt(e,10)>0){g+=" "+p(e)}var h=[];a.each(f,function(a,b){h.push(b+" "+g)});return h.join(", ")}function m(b,d){if(!d){a.cssNumber[b]=true}a.transit.propertyMap[b]=c.transform;a.cssHooks[b]={get:function(c){var d=a(c).css("transform")||new i;if(d=="none")d=new i;return d.get(b)},set:function(c,d){var e=a(c).css("transform")||new i;if(e=="none")e=new i;e.setFromString(b,d);a(c).css({transform:e})}}}function n(a){return a.replace(/([A-Z])/g,function(a){return"-"+a.toLowerCase()})}function o(a,b){if(typeof a==="string"&&!a.match(/^[\-0-9\.]+$/)){return a}else{return""+a+b}}function p(b){var c=b;if(a.fx.speeds[c]){c=a.fx.speeds[c]}return o(c,"ms")}"use strict";a.transit={version:"0.1.3",propertyMap:{marginLeft:"margin",marginRight:"margin",marginBottom:"margin",marginTop:"margin",paddingLeft:"padding",paddingRight:"padding",paddingBottom:"padding",paddingTop:"padding"},enabled:true,useTransitionEnd:false};var b=document.createElement("div");var c={};var f=navigator.userAgent.toLowerCase().indexOf("chrome")>-1;c.transition=d("transition");c.transitionDelay=d("transitionDelay");c.transform=d("transform");c.transformOrigin=d("transformOrigin");c.transform3d=e();a.extend(a.support,c);var g={MozTransition:"transitionend",OTransition:"oTransitionEnd",WebkitTransition:"webkitTransitionEnd",msTransition:"MSTransitionEnd"};var h=c.transitionEnd=g[c.transition]||null;b=null;a.cssEase={_default:"ease","in":"ease-in",out:"ease-out","in-out":"ease-in-out",snap:"cubic-bezier(0,1,.5,1)"};a.cssHooks.transform={get:function(b){return a(b).data("transform")||new i},set:function(b,d){var e=d;if(!(e instanceof i)){e=new i(e)}if(c.transform==="WebkitTransform"&&!f){b.style[c.transform]=e.toString(true)}else{b.style[c.transform]=e.toString()}a(b).data("transform",e)}};a.cssHooks.transformOrigin={get:function(a){return a.style[c.transformOrigin]},set:function(a,b){a.style[c.transformOrigin]=b}};a.cssHooks.transition={get:function(a){return a.style[c.transition]},set:function(a,b){a.style[c.transition]=b}};m("scale");m("translate");m("rotate");m("rotateX");m("rotateY");m("rotate3d");m("perspective");m("skewX");m("skewY");m("x",true);m("y",true);i.prototype={setFromString:function(a,b){var c=typeof b==="string"?b.split(","):b.constructor===Array?b:[b];c.unshift(a);i.prototype.set.apply(this,c)},set:function(a){var b=Array.prototype.slice.apply(arguments,[1]);if(this.setter[a]){this.setter[a].apply(this,b)}else{this[a]=b.join(",")}},get:function(a){if(this.getter[a]){return this.getter[a].apply(this)}else{return this[a]||0}},setter:{rotate:function(a){this.rotate=o(a,"deg")},rotateX:function(a){this.rotateX=o(a,"deg")},rotateY:function(a){this.rotateY=o(a,"deg")},scale:function(a,b){if(b===undefined){b=a}this.scale=a+","+b},skewX:function(a){this.skewX=o(a,"deg")},skewY:function(a){this.skewY=o(a,"deg")},perspective:function(a){this.perspective=o(a,"px")},x:function(a){this.set("translate",a,null)},y:function(a){this.set("translate",null,a)},translate:function(a,b){if(this._translateX===undefined){this._translateX=0}if(this._translateY===undefined){this._translateY=0}if(a!==null){this._translateX=o(a,"px")}if(b!==null){this._translateY=o(b,"px")}this.translate=this._translateX+","+this._translateY}},getter:{x:function(){return this._translateX||0},y:function(){return this._translateY||0},scale:function(){var a=(this.scale||"1,1").split(",");if(a[0]){a[0]=parseFloat(a[0])}if(a[1]){a[1]=parseFloat(a[1])}return a[0]===a[1]?a[0]:a},rotate3d:function(){var a=(this.rotate3d||"0,0,0,0deg").split(",");for(var b=0;b<=3;++b){if(a[b]){a[b]=parseFloat(a[b])}}if(a[3]){a[3]=o(a[3],"deg")}return a}},parse:function(a){var b=this;a.replace(/([a-zA-Z0-9]+)\((.*?)\)/g,function(a,c,d){b.setFromString(c,d)})},toString:function(a){var b=[];for(var d in this){if(this.hasOwnProperty(d)){if(!c.transform3d&&(d==="rotateX"||d==="rotateY"||d==="perspective"||d==="transformOrigin")){continue}if(d[0]!=="_"){if(a&&d==="scale"){b.push(d+"3d("+this[d]+",1)")}else if(a&&d==="translate"){b.push(d+"3d("+this[d]+",0)")}else{b.push(d+"("+this[d]+")")}}}}return b.join(" ")}};a.fn.transition=a.fn.transit=function(b,d,e,f){var g=this;var i=0;var k=true;if(typeof d==="function"){f=d;d=undefined}if(typeof e==="function"){f=e;e=undefined}if(typeof b.easing!=="undefined"){e=b.easing;delete b.easing}if(typeof b.duration!=="undefined"){d=b.duration;delete b.duration}if(typeof b.complete!=="undefined"){f=b.complete;delete b.complete}if(typeof b.queue!=="undefined"){k=b.queue;delete b.queue}if(typeof b.delay!=="undefined"){i=b.delay;delete b.delay}if(typeof d==="undefined"){d=a.fx.speeds._default}if(typeof e==="undefined"){e=a.cssEase._default}d=p(d);var m=l(b,d,e,i);var n=a.transit.enabled&&c.transition;var o=n?parseInt(d,10)+parseInt(i,10):0;if(o===0){var q=function(a){g.css(b);if(f){f.apply(g)}if(a){a()}};j(g,k,q);return g}var r={};var s=function(d){var e=false;var i=function(){if(e){g.unbind(h,i)}if(o>0){g.each(function(){this.style[c.transition]=r[this]||null})}if(typeof f==="function"){f.apply(g)}if(typeof d==="function"){d()}};if(o>0&&h&&a.transit.useTransitionEnd){e=true;g.bind(h,i)}else{window.setTimeout(i,o)}g.each(function(){if(o>0){this.style[c.transition]=m}a(this).css(b)})};var t=function(a){var b=0;if(c.transition==="MozTransition"&&b<25){b=25}window.setTimeout(function(){s(a)},b)};j(g,k,t);return this};a.transit.getTransitionValue=l})(jQuery)
/*
 * Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-fontface-backgroundsize-borderimage-borderradius-boxshadow-flexbox-flexboxlegacy-hsla-multiplebgs-opacity-rgba-textshadow-cssanimations-csscolumns-generatedcontent-cssgradients-cssreflections-csstransforms-csstransforms3d-csstransitions-draganddrop-history-audio-video-input-inputtypes-inlinesvg-svg-svgclippaths-touch-shiv-cssclasses-teststyles-testprop-testallprops-hasevent-prefixes-domprefixes-css_boxsizing-css_resize-forms_placeholder-forms_speechinput-forms_validation-load
 */
;window.Modernizr=function(a,b,c){function C(a){j.cssText=a}function D(a,b){return C(n.join(a+";")+(b||""))}function E(a,b){return typeof a===b}function F(a,b){return!!~(""+a).indexOf(b)}function G(a,b){for(var d in a){var e=a[d];if(!F(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function H(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:E(f,"function")?f.bind(d||b):f}return!1}function I(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+p.join(d+" ")+d).split(" ");return E(b,"string")||E(b,"undefined")?G(e,b):(e=(a+" "+q.join(d+" ")+d).split(" "),H(e,b,c))}function J(){e.input=function(c){for(var d=0,e=c.length;d<e;d++)u[c[d]]=c[d]in k;return u.list&&(u.list=!!b.createElement("datalist")&&!!a.HTMLDataListElement),u}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),e.inputtypes=function(a){for(var d=0,e,f,h,i=a.length;d<i;d++)k.setAttribute("type",f=a[d]),e=k.type!=="text",e&&(k.value=l,k.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(f)&&k.style.WebkitAppearance!==c?(g.appendChild(k),h=b.defaultView,e=h.getComputedStyle&&h.getComputedStyle(k,null).WebkitAppearance!=="textfield"&&k.offsetHeight!==0,g.removeChild(k)):/^(search|tel)$/.test(f)||(/^(url|email)$/.test(f)?e=k.checkValidity&&k.checkValidity()===!1:e=k.value!=l)),t[a[d]]=!!e;return t}("search tel url email datetime date month week time datetime-local number range color".split(" "))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k=b.createElement("input"),l=":)",m={}.toString,n=" -webkit- -moz- -o- -ms- ".split(" "),o="Webkit Moz O ms",p=o.split(" "),q=o.toLowerCase().split(" "),r={svg:"http://www.w3.org/2000/svg"},s={},t={},u={},v=[],w=v.slice,x,y=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},z=function(){function d(d,e){e=e||b.createElement(a[d]||"div"),d="on"+d;var f=d in e;return f||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(d,""),f=E(e[d],"function"),E(e[d],"undefined")||(e[d]=c),e.removeAttribute(d))),e=null,f}var a={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return d}(),A={}.hasOwnProperty,B;!E(A,"undefined")&&!E(A.call,"undefined")?B=function(a,b){return A.call(a,b)}:B=function(a,b){return b in a&&E(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=w.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(w.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(w.call(arguments)))};return e}),s.flexbox=function(){return I("flexWrap")},s.flexboxlegacy=function(){return I("boxDirection")},s.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:y(["@media (",n.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},s.history=function(){return!!a.history&&!!history.pushState},s.draganddrop=function(){var a=b.createElement("div");return"draggable"in a||"ondragstart"in a&&"ondrop"in a},s.rgba=function(){return C("background-color:rgba(150,255,150,.5)"),F(j.backgroundColor,"rgba")},s.hsla=function(){return C("background-color:hsla(120,40%,100%,.5)"),F(j.backgroundColor,"rgba")||F(j.backgroundColor,"hsla")},s.multiplebgs=function(){return C("background:url(https://),url(https://),red url(https://)"),/(url\s*\(.*?){3}/.test(j.background)},s.backgroundsize=function(){return I("backgroundSize")},s.borderimage=function(){return I("borderImage")},s.borderradius=function(){return I("borderRadius")},s.boxshadow=function(){return I("boxShadow")},s.textshadow=function(){return b.createElement("div").style.textShadow===""},s.opacity=function(){return D("opacity:.55"),/^0.55$/.test(j.opacity)},s.cssanimations=function(){return I("animationName")},s.csscolumns=function(){return I("columnCount")},s.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return C((a+"-webkit- ".split(" ").join(b+a)+n.join(c+a)).slice(0,-a.length)),F(j.backgroundImage,"gradient")},s.cssreflections=function(){return I("boxReflect")},s.csstransforms=function(){return!!I("transform")},s.csstransforms3d=function(){var a=!!I("perspective");return a&&"webkitPerspective"in g.style&&y("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},s.csstransitions=function(){return I("transition")},s.fontface=function(){var a;return y('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&g.indexOf(d.split(" ")[0])===0}),a},s.generatedcontent=function(){var a;return y(["#",h,"{font:0/0 a}#",h,':after{content:"',l,'";visibility:hidden;font:3px/1 a}'].join(""),function(b){a=b.offsetHeight>=3}),a},s.video=function(){var a=b.createElement("video"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,"")}catch(d){}return c},s.audio=function(){var a=b.createElement("audio"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,"")}catch(d){}return c},s.svg=function(){return!!b.createElementNS&&!!b.createElementNS(r.svg,"svg").createSVGRect},s.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==r.svg},s.svgclippaths=function(){return!!b.createElementNS&&/SVGClipPath/.test(m.call(b.createElementNS(r.svg,"clipPath")))};for(var K in s)B(s,K)&&(x=K.toLowerCase(),e[x]=s[K](),v.push((e[x]?"":"no-")+x));return e.input||J(),e.addTest=function(a,b){if(typeof a=="object")for(var d in a)B(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},C(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._prefixes=n,e._domPrefixes=q,e._cssomPrefixes=p,e.hasEvent=z,e.testProp=function(a){return G([a])},e.testAllProps=I,e.testStyles=y,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+v.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))},Modernizr.addTest("boxsizing",function(){return Modernizr.testAllProps("boxSizing")&&(document.documentMode===undefined||document.documentMode>7)}),Modernizr.addTest("cssresize",Modernizr.testAllProps("resize")),Modernizr.addTest("placeholder",function(){return"placeholder"in(Modernizr.input||document.createElement("input"))&&"placeholder"in(Modernizr.textarea||document.createElement("textarea"))}),Modernizr.addTest("speechinput",function(){var a=document.createElement("input");return"speech"in a||"onwebkitspeechchange"in a}),function(a,b){b.formvalidationapi=!1,b.formvalidationmessage=!1,b.addTest("formvalidation",function(){var c=a.createElement("form");if("checkValidity"in c){var d=a.body,e=a.documentElement,f=!1,g=!1,h;return b.formvalidationapi=!0,c.onsubmit=function(a){window.opera||a.preventDefault(),a.stopPropagation()},c.innerHTML='<input name="modTest" required><button></button>',c.style.position="absolute",c.style.top="-99999em",d||(f=!0,d=a.createElement("body"),d.style.background="",e.appendChild(d)),d.appendChild(c),h=c.getElementsByTagName("input")[0],h.oninvalid=function(a){g=!0,a.preventDefault(),a.stopPropagation()},b.formvalidationmessage=!!h.validationMessage,c.getElementsByTagName("button")[0].click(),d.removeChild(c),f&&e.removeChild(d),g}return!1})}(document,window.Modernizr);
/*
 * JavaScript Debug - v0.4 - 6/22/2010
 * http://benalman.com/projects/javascript-debug-console-log/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 *
 * With lots of help from Paul Irish!
 * http://paulirish.com/
 */
window.debug=(function(){var i=this,b=Array.prototype.slice,d=i.console,h={},f,g,m=9,c=["error","warn","info","debug","log"],l="assert clear count dir dirxml exception group groupCollapsed groupEnd profile profileEnd table time timeEnd trace".split(" "),j=l.length,a=[];while(--j>=0){(function(n){h[n]=function(){m!==0&&d&&d[n]&&d[n].apply(d,arguments)}})(l[j])}j=c.length;while(--j>=0){(function(n,o){h[o]=function(){var q=b.call(arguments),p=[o].concat(q);a.push(p);e(p);if(!d||!k(n)){return}d.firebug?d[o].apply(i,q):d[o]?d[o](q):d.log(q)}})(j,c[j])}function e(n){if(f&&(g||!d||!d.log)){f.apply(i,n)}}h.setLevel=function(n){m=typeof n==="number"?n:9};function k(n){return m>0?m>n:c.length+m<=n}h.setCallback=function(){var o=b.call(arguments),n=a.length,p=n;f=o.shift()||null;g=typeof o[0]==="boolean"?o.shift():false;p-=typeof o[0]==="number"?o.shift():n;while(p<n){e(a[p++])}};return h})();
/*	SWFObject v2.2 <http://code.google.com/p/swfobject/> 
	is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
var swfobject=function(){var D="undefined",r="object",S="Shockwave Flash",W="ShockwaveFlash.ShockwaveFlash",q="application/x-shockwave-flash",R="SWFObjectExprInst",x="onreadystatechange",O=window,j=document,t=navigator,T=false,U=[h],o=[],N=[],I=[],l,Q,E,B,J=false,a=false,n,G,m=true,M=function(){var aa=typeof j.getElementById!=D&&typeof j.getElementsByTagName!=D&&typeof j.createElement!=D,ah=t.userAgent.toLowerCase(),Y=t.platform.toLowerCase(),ae=Y?/win/.test(Y):/win/.test(ah),ac=Y?/mac/.test(Y):/mac/.test(ah),af=/webkit/.test(ah)?parseFloat(ah.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,X=!+"\v1",ag=[0,0,0],ab=null;if(typeof t.plugins!=D&&typeof t.plugins[S]==r){ab=t.plugins[S].description;if(ab&&!(typeof t.mimeTypes!=D&&t.mimeTypes[q]&&!t.mimeTypes[q].enabledPlugin)){T=true;X=false;ab=ab.replace(/^.*\s+(\S+\s+\S+$)/,"$1");ag[0]=parseInt(ab.replace(/^(.*)\..*$/,"$1"),10);ag[1]=parseInt(ab.replace(/^.*\.(.*)\s.*$/,"$1"),10);ag[2]=/[a-zA-Z]/.test(ab)?parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0}}else{if(typeof O.ActiveXObject!=D){try{var ad=new ActiveXObject(W);if(ad){ab=ad.GetVariable("$version");if(ab){X=true;ab=ab.split(" ")[1].split(",");ag=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}}catch(Z){}}}return{w3:aa,pv:ag,wk:af,ie:X,win:ae,mac:ac}}(),k=function(){if(!M.w3){return}if((typeof j.readyState!=D&&j.readyState=="complete")||(typeof j.readyState==D&&(j.getElementsByTagName("body")[0]||j.body))){f()}if(!J){if(typeof j.addEventListener!=D){j.addEventListener("DOMContentLoaded",f,false)}if(M.ie&&M.win){j.attachEvent(x,function(){if(j.readyState=="complete"){j.detachEvent(x,arguments.callee);f()}});if(O==top){(function(){if(J){return}try{j.documentElement.doScroll("left")}catch(X){setTimeout(arguments.callee,0);return}f()})()}}if(M.wk){(function(){if(J){return}if(!/loaded|complete/.test(j.readyState)){setTimeout(arguments.callee,0);return}f()})()}s(f)}}();function f(){if(J){return}try{var Z=j.getElementsByTagName("body")[0].appendChild(C("span"));Z.parentNode.removeChild(Z)}catch(aa){return}J=true;var X=U.length;for(var Y=0;Y<X;Y++){U[Y]()}}function K(X){if(J){X()}else{U[U.length]=X}}function s(Y){if(typeof O.addEventListener!=D){O.addEventListener("load",Y,false)}else{if(typeof j.addEventListener!=D){j.addEventListener("load",Y,false)}else{if(typeof O.attachEvent!=D){i(O,"onload",Y)}else{if(typeof O.onload=="function"){var X=O.onload;O.onload=function(){X();Y()}}else{O.onload=Y}}}}}function h(){if(T){V()}else{H()}}function V(){var X=j.getElementsByTagName("body")[0];var aa=C(r);aa.setAttribute("type",q);var Z=X.appendChild(aa);if(Z){var Y=0;(function(){if(typeof Z.GetVariable!=D){var ab=Z.GetVariable("$version");if(ab){ab=ab.split(" ")[1].split(",");M.pv=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}else{if(Y<10){Y++;setTimeout(arguments.callee,10);return}}X.removeChild(aa);Z=null;H()})()}else{H()}}function H(){var ag=o.length;if(ag>0){for(var af=0;af<ag;af++){var Y=o[af].id;var ab=o[af].callbackFn;var aa={success:false,id:Y};if(M.pv[0]>0){var ae=c(Y);if(ae){if(F(o[af].swfVersion)&&!(M.wk&&M.wk<312)){w(Y,true);if(ab){aa.success=true;aa.ref=z(Y);ab(aa)}}else{if(o[af].expressInstall&&A()){var ai={};ai.data=o[af].expressInstall;ai.width=ae.getAttribute("width")||"0";ai.height=ae.getAttribute("height")||"0";if(ae.getAttribute("class")){ai.styleclass=ae.getAttribute("class")}if(ae.getAttribute("align")){ai.align=ae.getAttribute("align")}var ah={};var X=ae.getElementsByTagName("param");var ac=X.length;for(var ad=0;ad<ac;ad++){if(X[ad].getAttribute("name").toLowerCase()!="movie"){ah[X[ad].getAttribute("name")]=X[ad].getAttribute("value")}}P(ai,ah,Y,ab)}else{p(ae);if(ab){ab(aa)}}}}}else{w(Y,true);if(ab){var Z=z(Y);if(Z&&typeof Z.SetVariable!=D){aa.success=true;aa.ref=Z}ab(aa)}}}}}function z(aa){var X=null;var Y=c(aa);if(Y&&Y.nodeName=="OBJECT"){if(typeof Y.SetVariable!=D){X=Y}else{var Z=Y.getElementsByTagName(r)[0];if(Z){X=Z}}}return X}function A(){return !a&&F("6.0.65")&&(M.win||M.mac)&&!(M.wk&&M.wk<312)}function P(aa,ab,X,Z){a=true;E=Z||null;B={success:false,id:X};var ae=c(X);if(ae){if(ae.nodeName=="OBJECT"){l=g(ae);Q=null}else{l=ae;Q=X}aa.id=R;if(typeof aa.width==D||(!/%$/.test(aa.width)&&parseInt(aa.width,10)<310)){aa.width="310"}if(typeof aa.height==D||(!/%$/.test(aa.height)&&parseInt(aa.height,10)<137)){aa.height="137"}j.title=j.title.slice(0,47)+" - Flash Player Installation";var ad=M.ie&&M.win?"ActiveX":"PlugIn",ac="MMredirectURL="+O.location.toString().replace(/&/g,"%26")+"&MMplayerType="+ad+"&MMdoctitle="+j.title;if(typeof ab.flashvars!=D){ab.flashvars+="&"+ac}else{ab.flashvars=ac}if(M.ie&&M.win&&ae.readyState!=4){var Y=C("div");X+="SWFObjectNew";Y.setAttribute("id",X);ae.parentNode.insertBefore(Y,ae);ae.style.display="none";(function(){if(ae.readyState==4){ae.parentNode.removeChild(ae)}else{setTimeout(arguments.callee,10)}})()}u(aa,ab,X)}}function p(Y){if(M.ie&&M.win&&Y.readyState!=4){var X=C("div");Y.parentNode.insertBefore(X,Y);X.parentNode.replaceChild(g(Y),X);Y.style.display="none";(function(){if(Y.readyState==4){Y.parentNode.removeChild(Y)}else{setTimeout(arguments.callee,10)}})()}else{Y.parentNode.replaceChild(g(Y),Y)}}function g(ab){var aa=C("div");if(M.win&&M.ie){aa.innerHTML=ab.innerHTML}else{var Y=ab.getElementsByTagName(r)[0];if(Y){var ad=Y.childNodes;if(ad){var X=ad.length;for(var Z=0;Z<X;Z++){if(!(ad[Z].nodeType==1&&ad[Z].nodeName=="PARAM")&&!(ad[Z].nodeType==8)){aa.appendChild(ad[Z].cloneNode(true))}}}}}return aa}function u(ai,ag,Y){var X,aa=c(Y);if(M.wk&&M.wk<312){return X}if(aa){if(typeof ai.id==D){ai.id=Y}if(M.ie&&M.win){var ah="";for(var ae in ai){if(ai[ae]!=Object.prototype[ae]){if(ae.toLowerCase()=="data"){ag.movie=ai[ae]}else{if(ae.toLowerCase()=="styleclass"){ah+=' class="'+ai[ae]+'"'}else{if(ae.toLowerCase()!="classid"){ah+=" "+ae+'="'+ai[ae]+'"'}}}}}var af="";for(var ad in ag){if(ag[ad]!=Object.prototype[ad]){af+='<param name="'+ad+'" value="'+ag[ad]+'" />'}}aa.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+ah+">"+af+"</object>";N[N.length]=ai.id;X=c(ai.id)}else{var Z=C(r);Z.setAttribute("type",q);for(var ac in ai){if(ai[ac]!=Object.prototype[ac]){if(ac.toLowerCase()=="styleclass"){Z.setAttribute("class",ai[ac])}else{if(ac.toLowerCase()!="classid"){Z.setAttribute(ac,ai[ac])}}}}for(var ab in ag){if(ag[ab]!=Object.prototype[ab]&&ab.toLowerCase()!="movie"){e(Z,ab,ag[ab])}}aa.parentNode.replaceChild(Z,aa);X=Z}}return X}function e(Z,X,Y){var aa=C("param");aa.setAttribute("name",X);aa.setAttribute("value",Y);Z.appendChild(aa)}function y(Y){var X=c(Y);if(X&&X.nodeName=="OBJECT"){if(M.ie&&M.win){X.style.display="none";(function(){if(X.readyState==4){b(Y)}else{setTimeout(arguments.callee,10)}})()}else{X.parentNode.removeChild(X)}}}function b(Z){var Y=c(Z);if(Y){for(var X in Y){if(typeof Y[X]=="function"){Y[X]=null}}Y.parentNode.removeChild(Y)}}function c(Z){var X=null;try{X=j.getElementById(Z)}catch(Y){}return X}function C(X){return j.createElement(X)}function i(Z,X,Y){Z.attachEvent(X,Y);I[I.length]=[Z,X,Y]}function F(Z){var Y=M.pv,X=Z.split(".");X[0]=parseInt(X[0],10);X[1]=parseInt(X[1],10)||0;X[2]=parseInt(X[2],10)||0;return(Y[0]>X[0]||(Y[0]==X[0]&&Y[1]>X[1])||(Y[0]==X[0]&&Y[1]==X[1]&&Y[2]>=X[2]))?true:false}function v(ac,Y,ad,ab){if(M.ie&&M.mac){return}var aa=j.getElementsByTagName("head")[0];if(!aa){return}var X=(ad&&typeof ad=="string")?ad:"screen";if(ab){n=null;G=null}if(!n||G!=X){var Z=C("style");Z.setAttribute("type","text/css");Z.setAttribute("media",X);n=aa.appendChild(Z);if(M.ie&&M.win&&typeof j.styleSheets!=D&&j.styleSheets.length>0){n=j.styleSheets[j.styleSheets.length-1]}G=X}if(M.ie&&M.win){if(n&&typeof n.addRule==r){n.addRule(ac,Y)}}else{if(n&&typeof j.createTextNode!=D){n.appendChild(j.createTextNode(ac+" {"+Y+"}"))}}}function w(Z,X){if(!m){return}var Y=X?"visible":"hidden";if(J&&c(Z)){c(Z).style.visibility=Y}else{v("#"+Z,"visibility:"+Y)}}function L(Y){var Z=/[\\\"<>\.;]/;var X=Z.exec(Y)!=null;return X&&typeof encodeURIComponent!=D?encodeURIComponent(Y):Y}var d=function(){if(M.ie&&M.win){window.attachEvent("onunload",function(){var ac=I.length;for(var ab=0;ab<ac;ab++){I[ab][0].detachEvent(I[ab][1],I[ab][2])}var Z=N.length;for(var aa=0;aa<Z;aa++){y(N[aa])}for(var Y in M){M[Y]=null}M=null;for(var X in swfobject){swfobject[X]=null}swfobject=null})}}();return{registerObject:function(ab,X,aa,Z){if(M.w3&&ab&&X){var Y={};Y.id=ab;Y.swfVersion=X;Y.expressInstall=aa;Y.callbackFn=Z;o[o.length]=Y;w(ab,false)}else{if(Z){Z({success:false,id:ab})}}},getObjectById:function(X){if(M.w3){return z(X)}},embedSWF:function(ab,ah,ae,ag,Y,aa,Z,ad,af,ac){var X={success:false,id:ah};if(M.w3&&!(M.wk&&M.wk<312)&&ab&&ah&&ae&&ag&&Y){w(ah,false);K(function(){ae+="";ag+="";var aj={};if(af&&typeof af===r){for(var al in af){aj[al]=af[al]}}aj.data=ab;aj.width=ae;aj.height=ag;var am={};if(ad&&typeof ad===r){for(var ak in ad){am[ak]=ad[ak]}}if(Z&&typeof Z===r){for(var ai in Z){if(typeof am.flashvars!=D){am.flashvars+="&"+ai+"="+Z[ai]}else{am.flashvars=ai+"="+Z[ai]}}}if(F(Y)){var an=u(aj,am,ah);if(aj.id==ah){w(ah,true)}X.success=true;X.ref=an}else{if(aa&&A()){aj.data=aa;P(aj,am,ah,ac);return}else{w(ah,true)}}if(ac){ac(X)}})}else{if(ac){ac(X)}}},switchOffAutoHideShow:function(){m=false},ua:M,getFlashPlayerVersion:function(){return{major:M.pv[0],minor:M.pv[1],release:M.pv[2]}},hasFlashPlayerVersion:F,createSWF:function(Z,Y,X){if(M.w3){return u(Z,Y,X)}else{return undefined}},showExpressInstall:function(Z,aa,X,Y){if(M.w3&&A()){P(Z,aa,X,Y)}},removeSWF:function(X){if(M.w3){y(X)}},createCSS:function(aa,Z,Y,X){if(M.w3){v(aa,Z,Y,X)}},addDomLoadEvent:K,addLoadEvent:s,getQueryParamValue:function(aa){var Z=j.location.search||j.location.hash;if(Z){if(/\?/.test(Z)){Z=Z.split("?")[1]}if(aa==null){return L(Z)}var Y=Z.split("&");for(var X=0;X<Y.length;X++){if(Y[X].substring(0,Y[X].indexOf("="))==aa){return L(Y[X].substring((Y[X].indexOf("=")+1)))}}}return""},expressInstallCallback:function(){if(a){var X=c(R);if(X&&l){X.parentNode.replaceChild(l,X);if(Q){w(Q,true);if(M.ie&&M.win){l.style.display="block"}}if(E){E(B)}}a=false}}}}();
/*
 RequireJS 1.0.7 Copyright (c) 2010-2012, The Dojo Foundation All Rights Reserved.
 Available via the MIT or new BSD license.
 see: http://github.com/jrburke/requirejs for details
*/
var requirejs,require,define;
(function(){function J(a){return N.call(a)==="[object Function]"}function F(a){return N.call(a)==="[object Array]"}function Z(a,c,l){for(var j in c)if(!(j in K)&&(!(j in a)||l))a[j]=c[j];return d}function O(a,c,d){a=Error(c+"\nhttp://requirejs.org/docs/errors.html#"+a);if(d)a.originalError=d;return a}function $(a,c,d){var j,k,s;for(j=0;s=c[j];j++){s=typeof s==="string"?{name:s}:s;k=s.location;if(d&&(!k||k.indexOf("/")!==0&&k.indexOf(":")===-1))k=d+"/"+(k||s.name);a[s.name]={name:s.name,location:k||
s.name,main:(s.main||"main").replace(ea,"").replace(aa,"")}}}function U(a,c){a.holdReady?a.holdReady(c):c?a.readyWait+=1:a.ready(!0)}function fa(a){function c(b,f){var g,m;if(b&&b.charAt(0)===".")if(f){q.pkgs[f]?f=[f]:(f=f.split("/"),f=f.slice(0,f.length-1));g=b=f.concat(b.split("/"));var a;for(m=0;a=g[m];m++)if(a===".")g.splice(m,1),m-=1;else if(a==="..")if(m===1&&(g[2]===".."||g[0]===".."))break;else m>0&&(g.splice(m-1,2),m-=2);m=q.pkgs[g=b[0]];b=b.join("/");m&&b===g+"/"+m.main&&(b=g)}else b.indexOf("./")===
0&&(b=b.substring(2));return b}function l(b,f){var g=b?b.indexOf("!"):-1,m=null,a=f?f.name:null,h=b,e,d;g!==-1&&(m=b.substring(0,g),b=b.substring(g+1,b.length));m&&(m=c(m,a));b&&(m?e=(g=n[m])&&g.normalize?g.normalize(b,function(b){return c(b,a)}):c(b,a):(e=c(b,a),d=F[e],d||(d=i.nameToUrl(b,null,f),F[e]=d)));return{prefix:m,name:e,parentMap:f,url:d,originalName:h,fullName:m?m+"!"+(e||""):e}}function j(){var b=!0,f=q.priorityWait,g,a;if(f){for(a=0;g=f[a];a++)if(!r[g]){b=!1;break}b&&delete q.priorityWait}return b}
function k(b,f,g){return function(){var a=ga.call(arguments,0),c;if(g&&J(c=a[a.length-1]))c.__requireJsBuild=!0;a.push(f);return b.apply(null,a)}}function s(b,f,g){f=k(g||i.require,b,f);Z(f,{nameToUrl:k(i.nameToUrl,b),toUrl:k(i.toUrl,b),defined:k(i.requireDefined,b),specified:k(i.requireSpecified,b),isBrowser:d.isBrowser});return f}function p(b){var f,g,a,c=b.callback,h=b.map,e=h.fullName,ba=b.deps;a=b.listeners;if(c&&J(c)){if(q.catchError.define)try{g=d.execCb(e,b.callback,ba,n[e])}catch(j){f=j}else g=
d.execCb(e,b.callback,ba,n[e]);if(e)(c=b.cjsModule)&&c.exports!==void 0&&c.exports!==n[e]?g=n[e]=b.cjsModule.exports:g===void 0&&b.usingExports?g=n[e]:(n[e]=g,G[e]&&(S[e]=!0))}else e&&(g=n[e]=c,G[e]&&(S[e]=!0));if(w[b.id])delete w[b.id],b.isDone=!0,i.waitCount-=1,i.waitCount===0&&(I=[]);delete L[e];if(d.onResourceLoad&&!b.placeholder)d.onResourceLoad(i,h,b.depArray);if(f)return g=(e?l(e).url:"")||f.fileName||f.sourceURL,a=f.moduleTree,f=O("defineerror",'Error evaluating module "'+e+'" at location "'+
g+'":\n'+f+"\nfileName:"+g+"\nlineNumber: "+(f.lineNumber||f.line),f),f.moduleName=e,f.moduleTree=a,d.onError(f);for(f=0;c=a[f];f++)c(g)}function t(b,f){return function(g){b.depDone[f]||(b.depDone[f]=!0,b.deps[f]=g,b.depCount-=1,b.depCount||p(b))}}function o(b,f){var g=f.map,a=g.fullName,c=g.name,h=M[b]||(M[b]=n[b]),e;if(!f.loading)f.loading=!0,e=function(b){f.callback=function(){return b};p(f);r[f.id]=!0;z()},e.fromText=function(b,f){var g=P;r[b]=!1;i.scriptCount+=1;i.fake[b]=!0;g&&(P=!1);d.exec(f);
g&&(P=!0);i.completeLoad(b)},a in n?e(n[a]):h.load(c,s(g.parentMap,!0,function(b,a){var c=[],e,m;for(e=0;m=b[e];e++)m=l(m,g.parentMap),b[e]=m.fullName,m.prefix||c.push(b[e]);f.moduleDeps=(f.moduleDeps||[]).concat(c);return i.require(b,a)}),e,q)}function x(b){w[b.id]||(w[b.id]=b,I.push(b),i.waitCount+=1)}function C(b){this.listeners.push(b)}function u(b,f){var g=b.fullName,a=b.prefix,c=a?M[a]||(M[a]=n[a]):null,h,e;g&&(h=L[g]);if(!h&&(e=!0,h={id:(a&&!c?N++ +"__p@:":"")+(g||"__r@"+N++),map:b,depCount:0,
depDone:[],depCallbacks:[],deps:[],listeners:[],add:C},A[h.id]=!0,g&&(!a||M[a])))L[g]=h;a&&!c?(g=l(a),a in n&&!n[a]&&(delete n[a],delete Q[g.url]),a=u(g,!0),a.add(function(){var f=l(b.originalName,b.parentMap),f=u(f,!0);h.placeholder=!0;f.add(function(b){h.callback=function(){return b};p(h)})})):e&&f&&(r[h.id]=!1,i.paused.push(h),x(h));return h}function B(b,f,a,c){var b=l(b,c),d=b.name,h=b.fullName,e=u(b),j=e.id,k=e.deps,o;if(h){if(h in n||r[j]===!0||h==="jquery"&&q.jQuery&&q.jQuery!==a().fn.jquery)return;
A[j]=!0;r[j]=!0;h==="jquery"&&a&&V(a())}e.depArray=f;e.callback=a;for(a=0;a<f.length;a++)if(j=f[a])j=l(j,d?b:c),o=j.fullName,f[a]=o,o==="require"?k[a]=s(b):o==="exports"?(k[a]=n[h]={},e.usingExports=!0):o==="module"?e.cjsModule=k[a]={id:d,uri:d?i.nameToUrl(d,null,c):void 0,exports:n[h]}:o in n&&!(o in w)&&(!(h in G)||h in G&&S[o])?k[a]=n[o]:(h in G&&(G[o]=!0,delete n[o],Q[j.url]=!1),e.depCount+=1,e.depCallbacks[a]=t(e,a),u(j,!0).add(e.depCallbacks[a]));e.depCount?x(e):p(e)}function v(b){B.apply(null,
b)}function E(b,f){var a=b.map.fullName,c=b.depArray,d=!0,h,e,i,l;if(b.isDone||!a||!r[a])return l;if(f[a])return b;f[a]=!0;if(c){for(h=0;h<c.length;h++){e=c[h];if(!r[e]&&!ha[e]){d=!1;break}if((i=w[e])&&!i.isDone&&r[e])if(l=E(i,f))break}d||(l=void 0,delete f[a])}return l}function y(b,a){var g=b.map.fullName,c=b.depArray,d,h,e,i;if(!b.isDone&&g&&r[g]){if(g){if(a[g])return n[g];a[g]=!0}if(c)for(d=0;d<c.length;d++)if(h=c[d])if((e=l(h).prefix)&&(i=w[e])&&y(i,a),(e=w[h])&&!e.isDone&&r[h])h=y(e,a),b.depCallbacks[d](h);
return n[g]}}function D(){var b=q.waitSeconds*1E3,b=b&&i.startTime+b<(new Date).getTime(),a="",c=!1,l=!1,k=[],h,e;if(!(i.pausedCount>0)){if(q.priorityWait)if(j())z();else return;for(h in r)if(!(h in K)&&(c=!0,!r[h]))if(b)a+=h+" ";else if(l=!0,h.indexOf("!")===-1){k=[];break}else(e=L[h]&&L[h].moduleDeps)&&k.push.apply(k,e);if(c||i.waitCount){if(b&&a)return b=O("timeout","Load timeout for modules: "+a),b.requireType="timeout",b.requireModules=a,b.contextName=i.contextName,d.onError(b);if(l&&k.length)for(a=
0;h=w[k[a]];a++)if(h=E(h,{})){y(h,{});break}if(!b&&(l||i.scriptCount)){if((H||ca)&&!W)W=setTimeout(function(){W=0;D()},50)}else{if(i.waitCount){for(a=0;h=I[a];a++)y(h,{});i.paused.length&&z();X<5&&(X+=1,D())}X=0;d.checkReadyState()}}}}var i,z,q={waitSeconds:7,baseUrl:"./",paths:{},pkgs:{},catchError:{}},R=[],A={require:!0,exports:!0,module:!0},F={},n={},r={},w={},I=[],Q={},N=0,L={},M={},G={},S={},Y=0;V=function(b){if(!i.jQuery&&(b=b||(typeof jQuery!=="undefined"?jQuery:null))&&!(q.jQuery&&b.fn.jquery!==
q.jQuery)&&("holdReady"in b||"readyWait"in b))if(i.jQuery=b,v(["jquery",[],function(){return jQuery}]),i.scriptCount)U(b,!0),i.jQueryIncremented=!0};z=function(){var b,a,c,l,k,h;i.takeGlobalQueue();Y+=1;if(i.scriptCount<=0)i.scriptCount=0;for(;R.length;)if(b=R.shift(),b[0]===null)return d.onError(O("mismatch","Mismatched anonymous define() module: "+b[b.length-1]));else v(b);if(!q.priorityWait||j())for(;i.paused.length;){k=i.paused;i.pausedCount+=k.length;i.paused=[];for(l=0;b=k[l];l++)a=b.map,c=
a.url,h=a.fullName,a.prefix?o(a.prefix,b):!Q[c]&&!r[h]&&(d.load(i,h,c),c.indexOf("empty:")!==0&&(Q[c]=!0));i.startTime=(new Date).getTime();i.pausedCount-=k.length}Y===1&&D();Y-=1};i={contextName:a,config:q,defQueue:R,waiting:w,waitCount:0,specified:A,loaded:r,urlMap:F,urlFetched:Q,scriptCount:0,defined:n,paused:[],pausedCount:0,plugins:M,needFullExec:G,fake:{},fullExec:S,managerCallbacks:L,makeModuleMap:l,normalize:c,configure:function(b){var a,c,d;b.baseUrl&&b.baseUrl.charAt(b.baseUrl.length-1)!==
"/"&&(b.baseUrl+="/");a=q.paths;d=q.pkgs;Z(q,b,!0);if(b.paths){for(c in b.paths)c in K||(a[c]=b.paths[c]);q.paths=a}if((a=b.packagePaths)||b.packages){if(a)for(c in a)c in K||$(d,a[c],c);b.packages&&$(d,b.packages);q.pkgs=d}if(b.priority)c=i.requireWait,i.requireWait=!1,z(),i.require(b.priority),z(),i.requireWait=c,q.priorityWait=b.priority;if(b.deps||b.callback)i.require(b.deps||[],b.callback)},requireDefined:function(b,a){return l(b,a).fullName in n},requireSpecified:function(b,a){return l(b,a).fullName in
A},require:function(b,c,g){if(typeof b==="string"){if(J(c))return d.onError(O("requireargs","Invalid require call"));if(d.get)return d.get(i,b,c);c=l(b,c);b=c.fullName;return!(b in n)?d.onError(O("notloaded","Module name '"+c.fullName+"' has not been loaded yet for context: "+a)):n[b]}(b&&b.length||c)&&B(null,b,c,g);if(!i.requireWait)for(;!i.scriptCount&&i.paused.length;)z();return i.require},takeGlobalQueue:function(){T.length&&(ia.apply(i.defQueue,[i.defQueue.length-1,0].concat(T)),T=[])},completeLoad:function(b){var a;
for(i.takeGlobalQueue();R.length;)if(a=R.shift(),a[0]===null){a[0]=b;break}else if(a[0]===b)break;else v(a),a=null;a?v(a):v([b,[],b==="jquery"&&typeof jQuery!=="undefined"?function(){return jQuery}:null]);d.isAsync&&(i.scriptCount-=1);z();d.isAsync||(i.scriptCount-=1)},toUrl:function(b,a){var c=b.lastIndexOf("."),d=null;c!==-1&&(d=b.substring(c,b.length),b=b.substring(0,c));return i.nameToUrl(b,d,a)},nameToUrl:function(b,a,g){var l,k,h,e,j=i.config,b=c(b,g&&g.fullName);if(d.jsExtRegExp.test(b))a=
b+(a?a:"");else{l=j.paths;k=j.pkgs;g=b.split("/");for(e=g.length;e>0;e--)if(h=g.slice(0,e).join("/"),l[h]){g.splice(0,e,l[h]);break}else if(h=k[h]){b=b===h.name?h.location+"/"+h.main:h.location;g.splice(0,e,b);break}a=g.join("/")+(a||".js");a=(a.charAt(0)==="/"||a.match(/^\w+:/)?"":j.baseUrl)+a}return j.urlArgs?a+((a.indexOf("?")===-1?"?":"&")+j.urlArgs):a}};i.jQueryCheck=V;i.resume=z;return i}function ja(){var a,c,d;if(B&&B.readyState==="interactive")return B;a=document.getElementsByTagName("script");
for(c=a.length-1;c>-1&&(d=a[c]);c--)if(d.readyState==="interactive")return B=d;return null}var ka=/(\/\*([\s\S]*?)\*\/|([^:]|^)\/\/(.*)$)/mg,la=/require\(\s*["']([^'"\s]+)["']\s*\)/g,ea=/^\.\//,aa=/\.js$/,N=Object.prototype.toString,t=Array.prototype,ga=t.slice,ia=t.splice,H=!!(typeof window!=="undefined"&&navigator&&document),ca=!H&&typeof importScripts!=="undefined",ma=H&&navigator.platform==="PLAYSTATION 3"?/^complete$/:/^(complete|loaded)$/,da=typeof opera!=="undefined"&&opera.toString()==="[object Opera]",
K={},C={},T=[],B=null,X=0,P=!1,ha={require:!0,module:!0,exports:!0},d,t={},I,x,u,D,o,v,E,A,y,V,W;if(typeof define==="undefined"){if(typeof requirejs!=="undefined")if(J(requirejs))return;else t=requirejs,requirejs=void 0;typeof require!=="undefined"&&!J(require)&&(t=require,require=void 0);d=requirejs=function(a,c,d){var j="_",k;!F(a)&&typeof a!=="string"&&(k=a,F(c)?(a=c,c=d):a=[]);if(k&&k.context)j=k.context;d=C[j]||(C[j]=fa(j));k&&d.configure(k);return d.require(a,c)};d.config=function(a){return d(a)};
require||(require=d);d.toUrl=function(a){return C._.toUrl(a)};d.version="1.0.7";d.jsExtRegExp=/^\/|:|\?|\.js$/;x=d.s={contexts:C,skipAsync:{}};if(d.isAsync=d.isBrowser=H)if(u=x.head=document.getElementsByTagName("head")[0],D=document.getElementsByTagName("base")[0])u=x.head=D.parentNode;d.onError=function(a){throw a;};d.load=function(a,c,l){d.resourcesReady(!1);a.scriptCount+=1;d.attach(l,a,c);if(a.jQuery&&!a.jQueryIncremented)U(a.jQuery,!0),a.jQueryIncremented=!0};define=function(a,c,d){var j,k;
typeof a!=="string"&&(d=c,c=a,a=null);F(c)||(d=c,c=[]);!c.length&&J(d)&&d.length&&(d.toString().replace(ka,"").replace(la,function(a,d){c.push(d)}),c=(d.length===1?["require"]:["require","exports","module"]).concat(c));if(P&&(j=I||ja()))a||(a=j.getAttribute("data-requiremodule")),k=C[j.getAttribute("data-requirecontext")];(k?k.defQueue:T).push([a,c,d])};define.amd={multiversion:!0,plugins:!0,jQuery:!0};d.exec=function(a){return eval(a)};d.execCb=function(a,c,d,j){return c.apply(j,d)};d.addScriptToDom=
function(a){I=a;D?u.insertBefore(a,D):u.appendChild(a);I=null};d.onScriptLoad=function(a){var c=a.currentTarget||a.srcElement,l;if(a.type==="load"||c&&ma.test(c.readyState))B=null,a=c.getAttribute("data-requirecontext"),l=c.getAttribute("data-requiremodule"),C[a].completeLoad(l),c.detachEvent&&!da?c.detachEvent("onreadystatechange",d.onScriptLoad):c.removeEventListener("load",d.onScriptLoad,!1)};d.attach=function(a,c,l,j,k,o){var p;if(H)return j=j||d.onScriptLoad,p=c&&c.config&&c.config.xhtml?document.createElementNS("http://www.w3.org/1999/xhtml",
"html:script"):document.createElement("script"),p.type=k||c&&c.config.scriptType||"text/javascript",p.charset="utf-8",p.async=!x.skipAsync[a],c&&p.setAttribute("data-requirecontext",c.contextName),p.setAttribute("data-requiremodule",l),p.attachEvent&&!da?(P=!0,o?p.onreadystatechange=function(){if(p.readyState==="loaded")p.onreadystatechange=null,p.attachEvent("onreadystatechange",j),o(p)}:p.attachEvent("onreadystatechange",j)):p.addEventListener("load",j,!1),p.src=a,o||d.addScriptToDom(p),p;else ca&&
(importScripts(a),c.completeLoad(l));return null};if(H){o=document.getElementsByTagName("script");for(A=o.length-1;A>-1&&(v=o[A]);A--){if(!u)u=v.parentNode;if(E=v.getAttribute("data-main")){if(!t.baseUrl)o=E.split("/"),v=o.pop(),o=o.length?o.join("/")+"/":"./",t.baseUrl=o,E=v.replace(aa,"");t.deps=t.deps?t.deps.concat(E):[E];break}}}d.checkReadyState=function(){var a=x.contexts,c;for(c in a)if(!(c in K)&&a[c].waitCount)return;d.resourcesReady(!0)};d.resourcesReady=function(a){var c,l;d.resourcesDone=
a;if(d.resourcesDone)for(l in a=x.contexts,a)if(!(l in K)&&(c=a[l],c.jQueryIncremented))U(c.jQuery,!1),c.jQueryIncremented=!1};d.pageLoaded=function(){if(document.readyState!=="complete")document.readyState="complete"};if(H&&document.addEventListener&&!document.readyState)document.readyState="loading",window.addEventListener("load",d.pageLoaded,!1);d(t);if(d.isAsync&&typeof setTimeout!=="undefined")y=x.contexts[t.context||"_"],y.requireWait=!0,setTimeout(function(){y.requireWait=!1;y.scriptCount||
y.resume();d.checkReadyState()},0)}})();
var Router = {

    loadedJS: [],
    loadedCSS: [],
    first: true,
    page: false,

    /**
     * Assign click events
     */
    initialize: function()
    {
        debug.debug("Router.initialize()");

        // Check for pushState support
        if(history.pushState)
        {
            // Assign AJAX loading behavior to all our internal links
            $("a[href*='" + Config.URL + "']").each(function()
            {
                // Make sure it has not been assigned already
                if(!$(this).attr('data-hasEvent') && $(this).attr("target") != "_blank")
                {
                    $(this).attr('data-hasEvent', '1');

                    // Add the event listener
                    $(this).click(function(event)
                    {
                        // Indicate the loading
                        $("html").css("cursor", "wait");

                        // Get the link
                        var href = $(this).attr("href");
                        var direct = $(this).attr("direct");

                        // Load it via AJAX
                        Router.load(href, direct);

                        // Add it to the history object
                        history.pushState('', 'New URL: ' + href, href);

                        // Prevent it from refreshing the whole page
                        event.preventDefault();
                    });
                }
            });
        }
    },

    /**
     * Load the link into the content area
     * @param String link
     */
    load: function(link, direct)
    {
        if(Router.first)
        {
            Router.first = false;

            // Make it load the page if they press back or forward
            $(window).bind('popstate', function()
            {
                Router.load(location.pathname, 0);
            });
        }

        Router.page = link;

        $("#tooltip").hide();

        if(/logout/.test(link))
        {
            window.location = link;
        }
        else if(/admin/.test(link))
        {
            window.location = link;
        }
        else if(direct == "1")
        {
            window.location = link;
        }
        else
        {
            // Load the page
            $.get(link, { is_json_ajax: "1" }, function(data, textStatus, response)
            {
                // Full page response? Redirect instead
                if(/^\<!DOCTYPE html\>/.test(data))
                {
                    window.location.reload(true);

                    return;
                }

                if(Router.page == link)
                {
                    window.scrollTo(0, 0);

                    try
                    {
                        data = JSON.parse(data);
                    }
                    catch(error)
                    {
                        data = {
                            title: "Error",
                            content: "Something went wrong!<br /><br /><b>Technical data:</b> " + data,
                            js: null,
                            css: null,
                            slider: false,
                            language: false
                        };
                    }

                    // Change the cursor back to normal
                    $("html").css("cursor", "default");

                    // Change the content
                    $("#content_ajax").html(data.content);

                    Tooltip.refresh();

                    // Change the title
                    $("title").html(data.title);

                    // Make sure to assign the router to all new internal links
                    Router.initialize();

                    /**
                     * Initialize Slider elements
                     * @alive
                     */
                    if(typeof Slider != "undefined"){
                        Slider.initialize();
                    }

                    if(data.language)
                    {
                        Language.set(data.language);
                    }

                    // Add the CSS if it exists and hasn't been loaded already
                    if($.inArray(data.css, Router.loadedCSS) == -1 && data.css.length > 0)
                    {
                        Router.loadedCSS.push(data.css);

                        $("head").append('<link rel="stylesheet" type="text/css" href="' + Config.URL + 'application/' + data.css + '" />');
                    }

                    // Add the JS if it exists and hasn't been loaded already
                    if($.inArray(data.js, Router.loadedJS) == -1 && data.js.length > 0)
                    {
                        Router.loadedJS.push(data.js);

                        require([Config.URL + "application/" + data.js]);
                    }

                    if(data.slider)
                    {
                        $("#" + Config.Slider.id).show();
                    }
                    else
                    {
                        $("#" + Config.Slider.id).hide();
                    }
                }
            }).fail(function()
                {
                    if(Router.page == link)
                    {
                        $("body").css("cursor", "default");
                        $("title").html("FusionCMS");
                        UI.alert("Something went wrong! Attempting to load the page directly... <center style='margin-top:20px;'><img src='" + Config.URL + "application/images/modal-ajax.gif' /></center>", 3000);

                        setTimeout(function()
                        {
                            window.location = link;
                        }, 3000);
                    }
                });
        }
    }
};

$(document).ready(Router.initialize);
/**
 * @package FusionCMS
 * @version 6.X
 * @author Jesper Lindström
 * @author Xavier Geernick
 * @link http://raxezdev.com/fusioncms
 */

function UI()
{
	/**
	 * Initializing actions
	 */
	this.initialize = function()
	{
		// Is the image slider enabled?
		if($("#slider").length > 0)
		{
			UI.slider();
		}

		// Is the vote reminder enabled?
		if(Config.voteReminder)
		{
			UI.voteReminder();
		}

		// Give older browsers some html5-placeholder love!
		$('input[placeholder], textarea[placeholder]').placeholder();

		// Enable tooltip
		Tooltip.initialize();
	}

	/**
	 * Display the vote reminder popup
	 */
	this.voteReminder = function()
	{
		// Show box
		$("#popup_bg").fadeTo(200, 0.5);
		$("#vote_reminder").fadeTo(200, 1);

		// Assign hide-function to background
		$("#popup_bg").bind('click', function()
		{
			UI.hidePopup();
		});
	}

	/**
	 * Initialize the image slider
	 */
	this.slider = function()
	{
		var config = {
			autoplay: true,
			controls: true,
			captions: true,
			delay: Config.Slider.interval
		};

		if(Config.Slider.effect.length > 0)
		{
			config.transitions = new Array(Config.Slider.effect);
		}

		window.myFlux = new flux.slider('#slider', config);
	}

	/**
	 * Shows an alert box
	 * @param String message
	 */
	this.alert = function(question, time)
	{		
		// Put question and button text
		$("#alert_message").html(question);

		// Show box
		$("#popup_bg").fadeTo(200, 0.5);
		$("#alert").fadeTo(200, 1);

		if(typeof time == "undefined")
		{
			$("#alert_message").css({marginBottom:"10px"});
			$(".popup_links").show();

			// Assign click event
			$("#alert_button").bind('click', function()
			{
				UI.hidePopup();	
			});
		}
		else
		{
			$("#alert_message").css({marginBottom:"0px"});
			$(".popup_links").hide();

			setTimeout(function()
			{
				UI.hidePopup();
			}, time);
		}

		// Assign hide-function to background
		$("#popup_bg").bind('click', function()
		{
			UI.hidePopup();
		});

		// Assign key events
		$(document).keypress(function(event)
		{
			// If "enter"
			if(event.which == 13)
			{
				UI.hidePopup();
			}
		});
	}

	/**
	 * Shows a confirm box
	 * @param String question
	 * @param String button
	 * @param Function callback
	 * @param Int width
	 */
	this.confirm = function(question, button, callback, callback_cancel, width)
	{
		var normalWidth = $("#confirm").css("width");
		var normalMargin = $("#confirm").css("margin-left");

		if(width)
		{
			$("#confirm").css({width: width+"px"});
			$("#confirm").css({marginLeft: "-"+(width/2)+"px"});
		}

		$(".popup_links").show();
		
		// Put question and button text
		$("#confirm_question").html(question);
		$("#confirm_button").html(button);

		// Show box
		$("#popup_bg").fadeTo(200, 0.5);
		$("#confirm").fadeTo(200, 1);

		// Assign click event
		$("#confirm_button").bind('click', function()
		{
			$("#confirm").css({width:normalWidth});
			$("#confirm").css({marginLeft:normalMargin});
			callback();
			UI.hidePopup();	
		});

		// Assign hide-function to background
		$("#popup_bg").bind('click', function()
		{
			$("#confirm").css({width:normalWidth});
			$("#confirm").css({marginLeft:normalMargin});
			UI.hidePopup();
		});

		// Assign key events
		$(document).keypress(function(event)
		{
			// If "enter"
			if(event.which == 13)
			{
				$("#confirm").css({width:normalWidth});
				$("#confirm").css({marginLeft:normalMargin});
				callback();
				UI.hidePopup();
			}
		});
	}

	/**
	 * Hides the current popup box
	 */
	this.hidePopup = function()
	{
		// Hide box
		$("#popup_bg").hide();
		$("#confirm").hide();
		$("#alert").hide();
		$("#vote_reminder").hide();

		// Remove events
		$("#confirm_button").unbind('click');
		$("#alert_button").unbind('click');
		$(document).unbind('keypress');
	}

	/**
	 * Display the amount of remaining characters
	 * @param Object field
	 * @param Object indicator
	 */
	this.limitCharacters = function(field, indicator)
	{
		// Get the values
		var max = field.maxLength;
		var length = field.value.length;

		// Change the indicator
		document.getElementById(indicator).innerHTML = length + " / " + max;
	}
}

/**
 * Tooltip related functions
 */
function Tooltip()
{
	/**
	 * Add event-listeners
	 */
	this.initialize = function()
	{
		// Add the tooltip element
		$("body").prepend('<div id="tooltip"></div>');

		// Add mouse-over event listeners
		this.addEvents();

		// Add mouse listener
		$(document).mousemove(function(e)
		{
			Tooltip.move(e.pageX, e.pageY);
		});
	}

	/**
	 * Used to support Ajax content
	 * Reloads the tooltip elements
	 */
	this.refresh = function()
	{
		// Remove all
		$("[data-tip]").unbind('hover');

		// Re-add
		this.addEvents();
	}

	this.addEvents = function()
	{
		// Add mouse-over event listeners
		$("[data-tip]").hover(
			function()
			{
				Tooltip.show($(this).attr("data-tip"));
			},
			function()
			{
				$("#tooltip").hide();
			}
		);

		if(Config.UseFusionTooltip)
		{
			$("[rel]").hover(
				function()
				{
					if(/^item=[0-9]*$/.test($(this).attr("rel")))
					{
						Tooltip.Item.get(this, function(data)
						{
							Tooltip.show(data);
						});
					}
				},
				function()
				{
					$("#tooltip").hide();
				}
			);
		}
	}

	/**
	 * Moves tooltip
	 * @param Int x
	 * @param Int y
	 */
	this.move = function(x, y)
	{
		// Get half of the width
		var width = ($("#tooltip").css("width").replace("px", "") / 2);

		// Position it at the mouse, and center
		$("#tooltip").css("left", x - width).css("top", y + 25);
	}

	/**
	 * Displays the tooltip
	 * @param Object element
	 */
	this.show = function(data)
	{
		$("#tooltip").html(data).show();
	}

	/**
	 * Item tooltip object
	 */
	 this.Item = new function()
	 {
	 	/**
	 	 * Loading HTML
	 	 */
	 	this.loading = "Loading...";

	 	/**
	 	 * Runtime cache
	 	 */
	 	this.cache = new Array();

	 	/**
	 	 * The currently displayed item ID
	 	 */
	 	this.currentId = false;

	 	/**
	 	 * Load an item and display it in the tooltip
	 	 * @param Object element
	 	 * @param Function callback
	 	 */
	 	this.get = function(element, callback)
	 	{
	 		var obj = $(element);
	 		var realm = obj.attr("data-realm");
	 		var id = obj.attr("rel").replace("item=", "");
	 		Tooltip.Item.currentId = id;

	 		if(id in this.cache)
	 		{
	 			callback(this.cache[id])
	 		}
	 		else
	 		{
	 			var cache = Tooltip.Item.CacheObj.get("item_" + realm + "_" + id + "_" + Config.language);

		 		if(cache !== false)
		 		{
		 			callback(cache);
		 		}
		 		else
		 		{
		 			callback(this.loading);

			 		$.get(Config.URL + "tooltip/" + realm + "/" + id, function(data)
			 		{
			 			// Cache it this visit
			 			Tooltip.Item.cache[id] = data;
			 			Tooltip.Item.CacheObj.save("item_" + realm + "_" + id  + "_" + Config.language, data);

			 			// Make sure it's still visible
			 			if($("#tooltip").is(":visible") && Tooltip.Item.currentId == id)
			 			{
			 				callback(data);
			 			}
			 		});
			 	}
		 	}
	 	}

	 	this.CacheObj = new function()
	 	{
	 		/**
	 		 * Get cache from localStorage
	 		 * @param String name
	 		 * @return Mixed
	 		 */
	 		this.get = function(name)
	 		{
	 			if(typeof localStorage != "undefined")
	 			{
	 				var cache = localStorage.getItem(name);
	 				
		 			if(cache)
		 			{
		 				cache = JSON.parse(cache);

		 				// If it hasn't expired
		 				if(cache.expiration > Math.round((new Date()).getTime() / 1000))
		 				{
		 					return cache.data;
		 				}
		 				else
		 				{
		 					return false;
		 				}
		 			}
		 			else
		 			{
		 				return false;
		 			}
		 		}
		 		else
		 		{
		 			return false;
		 		}
	 		}

	 		/**
	 		 * Save data to localStorage
	 		 * @param String name
	 		 * @param String data
	 		 * @param Int expiration
	 		 */
	 		this.save = function(name, data)
	 		{
	 			if(typeof localStorage != "undefined")
	 			{
	 				var time = Math.round((new Date()).getTime() / 1000);
	 				var expiration = time + 60*60*24;

		 			localStorage.setItem(name, JSON.stringify({"data": data, "expiration": expiration}));
	 			}
	 		}
	 	}
	 }
}

var UI = new UI();
var Tooltip = new Tooltip();
/**
 * @package FusionCMS
 * @version 6.X
 * @author Jesper Lindström
 * @author Xavier Geernick
 * @link http://raxezdev.com/fusioncms
 */

var FusionEditor = {
	
	Tools: {

		bold: function(id)
		{
			FusionEditor.wrapSelection(id, document.createElement('b'));
			FusionEditor.addEvents(id);
		},

		italic: function(id)
		{
			FusionEditor.wrapSelection(id, document.createElement('i'));
			FusionEditor.addEvents(id);
		},

		underline: function(id)
		{
			FusionEditor.wrapSelection(id, document.createElement('u'));
			FusionEditor.addEvents(id);
		},

		left: function(id)
		{
			var element = document.createElement('div');
			element.style.textAlign = "left";
			FusionEditor.wrapSelection(id, element);
		},

		center: function(id)
		{
			var element = document.createElement('div');
			element.style.textAlign = "center";
			FusionEditor.wrapSelection(id, element);
		},

		right: function(id)
		{
			var element = document.createElement('div');
			element.style.textAlign = "right";
			FusionEditor.wrapSelection(id, element);
		},

		image: function(id)
		{
			var editor = '<form onSubmit="FusionEditor.Tools.addImage(\'' + id + '\'); return false">'
						+ '<input type="text" placeholder="http://path.to/my/image.jpg" id="editor_image_' + id + '" />'
						+ '<input type="submit" value="Add" />'
						+ '</form>';
			
			FusionEditor.open(id, editor);
		},

		addImage: function(id)
		{
			var field = $("#editor_image_" + id);

			if(field.val().length > 0
			&& /^https?:\/\/.*$/.test(field.val()))
			{
				var element = document.createElement('img');
				element.src = field.val();
				FusionEditor.wrapSelection(id, element);
				FusionEditor.close(id);
				field.val("");
				FusionEditor.addEvents(id);	
			}
			else
			{
				UI.alert("Image must be a valid link");
			}
		},

		color: function(id)
		{
			var colors = "",
				colorArr = [
				"cc0000",
				"cc006e",
				"c500cc",
				"4d00cc",
				"000acc",
				"0043cc",
				"008bcc",
				"00ccc5",
				"00cc48",
				"13cc00",
				"73cc00",
				"c0cc00",
				"cc9e00",
				"cc7300",
				"333333",
				"666666"
			];

			for(i in colorArr)
			{
				colors += '<a class="fusioneditor_color" href="javascript:void(0)" onClick="FusionEditor.Tools.addColor(\'' + id + '\', \'#' + colorArr[i] + '\')" data-tip="#' + colorArr[i] + '" style="background-color:#' + colorArr[i] + '"></a>';
			}

			FusionEditor.open(id, colors, 32);
			Tooltip.refresh();
		},

		addColor: function(id, color)
		{
			var span = document.createElement("span");
			span.style.color = color;
			FusionEditor.wrapSelection(id, span);
			FusionEditor.close(id);
		},

		size: function(id)
		{
			var sizes = "",
				sizeArr = [
				"8",
				"10",
				"12",
				"14",
				"16",
				"18",
				"20",
				"22",
				"24",
				"32",
				"42",
				"52",
				"62",
				"72",
			];

			for(i in sizeArr)
			{
				sizes += '<a class="fusioneditor_size" href="javascript:void(0)" onClick="FusionEditor.Tools.addSize(\'' + id + '\', \'' + sizeArr[i] + '\')">' + sizeArr[i] + '</a>';
			}

			FusionEditor.open(id, sizes, 32);
		},

		addSize: function(id, size)
		{
			var span = document.createElement("span");
			span.style.fontSize = size + "px";

			FusionEditor.wrapSelection(id, span);
			FusionEditor.close(id);
		},

		link: function(id)
		{
			var editor = '<form onSubmit="FusionEditor.Tools.addLink(\'' + id + '\'); return false">'
						+ '<input type="text" placeholder="http://domain.com" id="editor_link_' + id + '" />'
						+ '<input type="submit" value="Add" />'
						+ '</form>';
			
			FusionEditor.open(id, editor);
		},

		addLink: function(id)
		{
			var field = $("#editor_link_" + id);

			if(field.val().length > 0
			&& /^http:\/\/.*$/.test(field.val()))
			{
				var element = document.createElement('a');
				element.href = field.val();
				element.target = "_blank";
				FusionEditor.wrapSelection(id, element, field.val());
				FusionEditor.close(id);
				field.val("");
				FusionEditor.addEvents(id);	
			}
			else
			{
				UI.alert("Link must be valid");
			}
		},

		html: function(id)
		{
			// Prepare the HTML
			var field = '<textarea id="html_field_' + id + '" style="width:95%;height:300px;font-family:Courier" spellcheck="false">' + $("#" + id).html() + '</textarea>';

			// Get the original confirm box sizes
			var originalWidth = $("#confirm").css("width"),
				originalMargin = $("#confirm").css("margin-left");

			// Make the box bigger
			$("#confirm").css({width:"600px",marginLeft:"-300px"});

			// Show the box
			UI.confirm(field, "Save", function()
			{
				// Save and return the box to normal size
				$("#" + id).html($("#html_field_" + id).val());
				FusionEditor.addEvents(id);
				$("#confirm").css({width:originalWidth,marginLeft:originalMargin});
			});
		},

		tidy: function(id)
		{
			var regex = /\<(\/)?(b|span|i|u|div)\b([A-Za-z0-9 :;,#-="']*)?\>/g,
				field = $("#" + id),
				html = field.html();

			field.html(html.replace(regex, ""));
		}
	},

	create: function(id)
	{
		$("#" + id).attr("contenteditable", "true");

		FusionEditor.addEvents(id);

		// Prevent all other click events when clicked on the toolbox
		$("#fusioneditor_" + id + "_toolbox").bind('click', function(e)
		{
			e.stopPropagation();
		});
	},

	/**
	 * Toggle the toolbox and add content
	 * @param String id
	 * @param String content
	 */
	open: function(id, content, size)
	{
		if(size)
		{
			$("#fusioneditor_" + id + "_toolbox").css({height:size});
		}
		else
		{
			// I'm sorry for this cheap fix // Jesper
			if(Config.isACP)
			{
				$("#fusioneditor_" + id + "_toolbox").css({height:"40"});
			}
			else
			{
				$("#fusioneditor_" + id + "_toolbox").css({height:"50"});
			}
		}

		if($("#fusioneditor_" + id + "_toolbox").is(":visible"))
		{
			$("#fusioneditor_" + id + "_toolbox").transition({rotateX: '90deg', opacity: 0}, 200, function()
			{
				$(this).html(content).transition({rotateX: '0deg', opacity:1}, 200);
			});
		}
		else
		{
			$("#fusioneditor_" + id + "_toolbox").transition({rotateX: '90deg', opacity:0}, 0);

			$("#fusioneditor_" + id + "_toolbox").html(content).slideDown(100, function()
			{
				$(this).transition({rotateX: '0deg', opacity:1}, 200);
			});

			//$("#fusioneditor_" + id + "_toolbox").html(content).slideToggle(200);
			$("#fusioneditor_" + id + "_close").fadeToggle(200);
		}
	},

	/**
	 * Close the toolbox
	 * @param String id
	 */
	close: function(id)
	{
		$("#fusioneditor_" + id + "_toolbox").transition({rotateX: '90deg', opacity: 0}, 200, function()
		{
			$(this).slideUp(50);
			$("#fusioneditor_" + id + "_toolbox").transition({rotateX: '0deg'});
		});

		//$("#fusioneditor_" + id + "_toolbox").slideUp(200);
		$("#fusioneditor_" + id + "_close").fadeOut(200);
	},

	/**
	 * Make images and links editable via tooltip
	 * @param String id
	 */
	addEvents: function(id, element)
	{
		var object;

		if(element)
		{
			object = $(element);
		}
		else
		{
			object = $("#" + id);
		}
		
		object.children().each(function()
		{
			// Add the event
			FusionEditor.addEvent(id, this);

			// If it has children
			if($(this).children().length > 0)
			{
				// Loop through this element
				FusionEditor.addEvents(id, this);
			}
		});
		
	},

	addEvent: function(id, element)
	{
		if(typeof $(element).data('events') == "undefined")
		{
			// Add event
			switch(element.nodeName.toLowerCase())
			{
				case "img":
					$(element).bind('click', function(e)
					{
						FusionEditor.imageMenu(e, element, id);
					});
				break;
				
				case "a":
					$(element).bind('click', function(e)
					{
						FusionEditor.linkMenu(e, element, id);
					});
				break;
			}
		}
	},

	currentImage: false,

	saveImage: function(id)
	{
		FusionEditor.currentImage.src = $("#editor_edit_image_" + id).val();
		FusionEditor.close(id);
	},

	/**
	 * Link popup helper menu
	 */
	imageMenu: function(e, element, id)
	{
		FusionEditor.currentImage = element;

		var editor = '<form onSubmit="FusionEditor.saveImage(\'' + id + '\'); return false">'
						+ '<input type="text" value="' + element.src + '" id="editor_edit_image_' + id + '" />'
						+ '<input type="submit" value="Save" />'
						+ '</form>';

		// Show menu
		FusionEditor.open(id, editor);

		// Prevent further click event handling
		e.stopPropagation();

		// Make sure we don't stack the events
		$(document).unbind('click');

		// Add the "close" click event
		$(document).bind('click', function()
		{
			$(document).unbind('click');
			FusionEditor.close(id);
		});
	},

	currentLink: false,

	saveLink: function(id)
	{
		FusionEditor.currentLink.href = $("#editor_edit_link_" + id).val();
		FusionEditor.close(id);
	},

	/**
	 * Link popup helper menu
	 */
	linkMenu: function(e, element, id)
	{
		FusionEditor.currentLink = element;

		var editor = '<form onSubmit="FusionEditor.saveLink(\'' + id + '\'); return false">'
						+ '<input type="text" value="' + element.href + '" id="editor_edit_link_' + id + '" />'
						+ '<input type="submit" value="Save" />'
						+ '</form>';

		// Show menu
		FusionEditor.open(id, editor);

		// Prevent further click event handling
		e.stopPropagation();

		// Make sure we don't stack the events
		$(document).unbind('click');

		// Add the "close" click event
		$(document).bind('click', function()
		{
			$(document).unbind('click');
			FusionEditor.close(id);
		});
	},

	/**
	 * Add the selected content to an element
	 * @param String id
	 * @param String beginTag
	 * @param String endTag
	 * @param String extra
	 */
	wrapSelection: function(id, element, extra)
	{
		// Get the selection values
		var selection = window.getSelection();
		var string = selection.toString();

		// Make sure we're modifying the correct element
		if(string.length > 0
		&& FusionEditor.hasParent(selection.anchorNode.parentElement, document.getElementById(id)))
		{
			// Get the selection
			var selectedText = selection.getRangeAt(0).extractContents();
			
			// Add the content to the element
			element.appendChild(selectedText);

			// Replace the selected text with the element
			selection.getRangeAt(0).insertNode(element);
		}
		else
		{
			if(extra)
			{
				element.innerHTML = extra;
			}
			
			$("#" + id).append(element);
		}
	},

	/**
	 * Check if element is the child of a specific element
	 * @param Object element
	 * @param Object goal
	 * @return Boolean
	 */
	hasParent: function(element, goal)
	{
		// Check if current parent is the goal
		if(element == goal)
		{
			return true;
		}
		else
		{
			// We have to go deeper :o
			var test = element.parentNode;

			while(test != goal)
			{
				try
				{
					test = test.parentNode;
				}
				catch(error)
				{
					return false;
				}
			}

			return true;
		}
	}
}
/*
 Flux Slider v1.4.4
 http://www.joelambert.co.uk/flux

 Copyright 2011, Joe Lambert.
 Free to use under the MIT license.
 http://www.opensource.org/licenses/mit-license.php
*/
window.flux={version:"1.4.4"};(function(a){flux.slider=function(b,c){flux.browser.init();if(!flux.browser.supportsTransitions){if(window.console&&window.console.error)console.error("Flux Slider requires a browser that supports CSS3 transitions")}var d=this;this.element=a(b);this.transitions=[];for(var e in flux.transitions)this.transitions.push(e);this.options=a.extend({autoplay:true,transitions:this.transitions,delay:4e3,pagination:true,controls:false,captions:false,width:null,height:null,onTransitionEnd:null},c);this.height=this.options.height?this.options.height:null;this.width=this.options.width?this.options.width:null;var f=[];a(this.options.transitions).each(function(a,b){var c=new flux.transitions[b](this),d=true;if(c.options.requires3d&&!flux.browser.supports3d)d=false;if(c.options.compatibilityCheck)d=c.options.compatibilityCheck();if(d)f.push(b)});this.options.transitions=f;this.images=new Array;this.imageLoadedCount=0;this.currentImageIndex=0;this.nextImageIndex=1;this.playing=false;this.container=a('<div class="fluxslider"></div>').appendTo(this.element);this.surface=a('<div class="surface" style="position: relative"></div>').appendTo(this.container);this.container.bind("click",function(b){if(a(b.target).hasClass("hasLink"))window.location=a(b.target).data("href")});this.imageContainer=a('<div class="images loading"></div>').css({position:"relative",overflow:"hidden","min-height":"100px"}).appendTo(this.surface);if(this.width&&this.height){this.imageContainer.css({width:this.width+"px",height:this.height+"px"})}this.image1=a('<div class="image1" style="height: 100%; width: 100%"></div>').appendTo(this.imageContainer);this.image2=a('<div class="image2" style="height: 100%; width: 100%"></div>').appendTo(this.imageContainer);a(this.image1).add(this.image2).css({position:"absolute",top:"0px",left:"0px"});this.element.find("img, a img").each(function(b,c){var e=c.cloneNode(false),f=a(c).parent();if(f.is("a"))a(e).data("href",f.attr("href"));d.images.push(e);a(c).remove()});for(var g=0;g<this.images.length;g++){var h=new Image;h.onload=function(){d.imageLoadedCount++;d.width=d.width?d.width:this.width;d.height=d.height?d.height:this.height;if(d.imageLoadedCount>=d.images.length){d.finishedLoading();d.setupImages()}};h.src=this.images[g].src}this.element.bind("fluxTransitionEnd",function(a,b){if(d.options.onTransitionEnd){a.preventDefault();d.options.onTransitionEnd(b)}});if(this.options.autoplay)this.start();this.element.bind("swipeLeft",function(a){d.next(null,{direction:"left"})}).bind("swipeRight",function(a){d.prev(null,{direction:"right"})});setTimeout(function(){a(window).focus(function(){if(d.isPlaying())d.next()})},100)};flux.slider.prototype={constructor:flux.slider,playing:false,start:function(){var a=this;this.playing=true;this.interval=setInterval(function(){a.transition()},this.options.delay)},stop:function(){this.playing=false;clearInterval(this.interval);this.interval=null},isPlaying:function(){return this.playing},next:function(a,b){b=b||{};b.direction="left";this.showImage(this.currentImageIndex+1,a,b)},prev:function(a,b){b=b||{};b.direction="right";this.showImage(this.currentImageIndex-1,a,b)},showImage:function(a,b,c){this.setNextIndex(a);this.setupImages();this.transition(b,c)},finishedLoading:function(){var b=this;this.container.css({width:this.width+"px",height:this.height+"px"});this.imageContainer.removeClass("loading");if(this.options.pagination){this.pagination=a('<ul class="pagination"></ul>').css({margin:"0px",padding:"0px","text-align":"center"});this.pagination.bind("click",function(c){c.preventDefault();b.showImage(a(c.target).data("index"))});a(this.images).each(function(c,d){var e=a('<li data-index="'+c+'">'+(c+1)+"</li>").css({display:"inline-block","margin-left":"0.5em",cursor:"pointer"}).appendTo(b.pagination);if(c==0)e.css("margin-left",0).addClass("current")});this.container.append(this.pagination)}a(this.imageContainer).css({width:this.width+"px",height:this.height+"px"});a(this.image1).css({width:this.width+"px",height:this.height+"px"});a(this.image2).css({width:this.width+"px",height:this.height+"px"});this.container.css({width:this.width+"px",height:this.height+(this.options.pagination?this.pagination.height():0)+"px"});if(this.options.controls){this.nextButton=a('<a href="#" id="slider_next">'+Config.Theme.next+"</a>").appendTo(this.surface).bind("click",function(a){a.preventDefault();b.next()});this.prevButton=a('<a href="#" id="slider_previous">'+Config.Theme.previous+"</a>").appendTo(this.surface).bind("click",function(a){a.preventDefault();b.prev()});var c=(this.height-this.nextButton.height())/2;this.nextButton.css({top:c+"px",right:"10px"});this.prevButton.css({top:c+"px",left:"10px"})}if(this.options.captions){this.captionBar=a('<div class="caption"></div>').css({opacity:0,position:"absolute","z-index":110,width:"100%"}).css3({"transition-property":"opacity","transition-duration":"800ms","box-sizing":"border-box"}).prependTo(this.surface)}this.updateCaption()},setupImages:function(){var b=this.getImage(this.currentImageIndex),c={"background-image":'url("'+b.src+'")',"z-index":101,cursor:"auto"};if(a(b).data("href")){c.cursor="pointer";this.image1.addClass("hasLink");this.image1.data("href",a(b).data("href"))}else{this.image1.removeClass("hasLink");this.image1.data("href",null)}this.image1.css(c).children().remove();this.image2.css({"background-image":'url("'+this.getImage(this.nextImageIndex).src+'")',"z-index":100}).show();if(this.options.pagination&&this.pagination){this.pagination.find("li.current").removeClass("current");a(this.pagination.find("li")[this.currentImageIndex]).addClass("current")}},transition:function(b,c){if(b==undefined||!flux.transitions[b]){var d=Math.floor(Math.random()*this.options.transitions.length);b=this.options.transitions[d]}var e=null;try{e=new flux.transitions[b](this,a.extend(this.options[b]?this.options[b]:{},c))}catch(f){e=new flux.transition(this,{fallback:true})}e.run();this.currentImageIndex=this.nextImageIndex;this.setNextIndex(this.currentImageIndex+1);this.updateCaption()},updateCaption:function(){var b=a(this.getImage(this.currentImageIndex)).attr("title");if(this.options.captions&&this.captionBar){if(b!=="")this.captionBar.html(b);this.captionBar.css("opacity",b===""?0:1)}},getImage:function(a){a=a%this.images.length;return this.images[a]},setNextIndex:function(a){if(a==undefined)a=this.currentImageIndex+1;this.nextImageIndex=a;if(this.nextImageIndex>this.images.length-1)this.nextImageIndex=0;if(this.nextImageIndex<0)this.nextImageIndex=this.images.length-1},increment:function(){this.currentImageIndex++;if(this.currentImageIndex>this.images.length-1)this.currentImageIndex=0}}})(window.jQuery||window.Zepto);(function(a){flux.browser={init:function(){if(flux.browser.supportsTransitions!==undefined)return;var b=document.createElement("div"),c=["-webkit","-moz","-o","-ms"],d=["Webkit","Moz","O","Ms"];if(window.Modernizr&&Modernizr.csstransitions!==undefined)flux.browser.supportsTransitions=Modernizr.csstransitions;else{flux.browser.supportsTransitions=this.supportsCSSProperty("Transition")}if(window.Modernizr&&Modernizr.csstransforms3d!==undefined)flux.browser.supports3d=Modernizr.csstransforms3d;else{flux.browser.supports3d=this.supportsCSSProperty("Perspective");if(flux.browser.supports3d&&"webkitPerspective"in a("body").get(0).style){var e=a('<div id="csstransform3d"></div>');var f=a('<style media="(transform-3d), ('+c.join("-transform-3d),(")+'-transform-3d)">div#csstransform3d { position: absolute; left: 9px }</style>');a("body").append(e);a("head").append(f);flux.browser.supports3d=e.get(0).offsetLeft==9;e.remove();f.remove()}}},supportsCSSProperty:function(a){var b=document.createElement("div"),c=["-webkit","-moz","-o","-ms"],d=["Webkit","Moz","O","Ms"];var e=false;for(var f=0;f<d.length;f++){if(d[f]+a in b.style)e=e||true}return e},translate:function(a,b,c){a=a!=undefined?a:0;b=b!=undefined?b:0;c=c!=undefined?c:0;return"translate"+(flux.browser.supports3d?"3d(":"(")+a+"px,"+b+(flux.browser.supports3d?"px,"+c+"px)":"px)")},rotateX:function(a){return flux.browser.rotate("x",a)},rotateY:function(a){return flux.browser.rotate("y",a)},rotateZ:function(a){return flux.browser.rotate("z",a)},rotate:function(a,b){if(!a in{x:"",y:"",z:""})a="z";b=b!=undefined?b:0;if(flux.browser.supports3d)return"rotate3d("+(a=="x"?"1":"0")+", "+(a=="y"?"1":"0")+", "+(a=="z"?"1":"0")+", "+b+"deg)";else{if(a=="z")return"rotate("+b+"deg)";else return""}}};a(function(){flux.browser.init()})})(window.jQuery||window.Zepto);(function(a){a.fn.css3=function(a){var b={};var c=["webkit","moz","ms","o"];for(var d in a){for(var e=0;e<c.length;e++)b["-"+c[e]+"-"+d]=a[d];b[d]=a[d]}this.css(b);return this};a.fn.transitionEnd=function(b){var c=this;var d=["webkitTransitionEnd","transitionend","oTransitionEnd"];for(var e=0;e<d.length;e++){this.bind(d[e],function(c){for(var e=0;e<d.length;e++)a(this).unbind(d[e]);if(b)b.call(this,c)})}return this};flux.transition=function(b,c){this.options=a.extend({requires3d:false,after:function(){}},c);this.slider=b;if(this.options.requires3d&&!flux.browser.supports3d||!flux.browser.supportsTransitions||this.options.fallback===true){var d=this;this.options.after=undefined;this.options.setup=function(){d.fallbackSetup()};this.options.execute=function(){d.fallbackExecute()}}};flux.transition.prototype={constructor:flux.transition,hasFinished:false,run:function(){var a=this;if(this.options.setup!==undefined)this.options.setup.call(this);this.slider.image1.css({"background-image":"none"});this.slider.imageContainer.css("overflow",this.options.requires3d?"visible":"hidden");setTimeout(function(){if(a.options.execute!==undefined)a.options.execute.call(a)},5)},finished:function(){if(this.hasFinished)return;this.hasFinished=true;if(this.options.after)this.options.after.call(this);this.slider.imageContainer.css("overflow","hidden");this.slider.setupImages();this.slider.element.trigger("fluxTransitionEnd",{currentImage:this.slider.getImage(this.slider.currentImageIndex)})},fallbackSetup:function(){},fallbackExecute:function(){this.finished()}};flux.transitions={};flux.transition_grid=function(b,c){return new flux.transition(b,a.extend({columns:6,rows:6,forceSquare:false,setup:function(){var b=this.slider.image1.width(),c=this.slider.image1.height();var d=Math.floor(b/this.options.columns),e=Math.floor(c/this.options.rows);if(this.options.forceSquare){e=d;this.options.rows=Math.floor(c/e)}var f=b-this.options.columns*d,g=Math.ceil(f/this.options.columns),h=c-this.options.rows*e,i=Math.ceil(h/this.options.rows),j=150,k=this.slider.image1.height(),l=0,m=0,n=document.createDocumentFragment();for(var o=0;o<this.options.columns;o++){var p=d,m=0;if(f>0){var q=f>=g?g:f;p+=q;f-=q}for(var r=0;r<this.options.rows;r++){var s=e,t=h;if(t>0){var q=t>=i?i:t;s+=q;t-=q}var u=a('<div class="tile tile-'+o+"-"+r+'"></div>').css({width:p+"px",height:s+"px",position:"absolute",top:m+"px",left:l+"px"});this.options.renderTile.call(this,u,o,r,p,s,l,m);n.appendChild(u.get(0));m+=s}l+=p}this.slider.image1.get(0).appendChild(n)},execute:function(){var a=this,b=this.slider.image1.height(),c=this.slider.image1.find("div.barcontainer");this.slider.image2.hide();c.last().transitionEnd(function(b){a.slider.image2.show();a.finished()});c.css3({transform:flux.browser.rotateX(-90)+" "+flux.browser.translate(0,b/2,b/2)})},renderTile:function(a,b,c,d,e,f,g){}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.bars=function(b,c){return new flux.transition_grid(b,a.extend({columns:10,rows:1,delayBetweenBars:40,renderTile:function(b,c,d,e,f,g,h){a(b).css({"background-image":this.slider.image1.css("background-image"),"background-position":"-"+g+"px 0px"}).css3({"transition-duration":"400ms","transition-timing-function":"ease-in","transition-property":"all","transition-delay":c*this.options.delayBetweenBars+"ms"})},execute:function(){var b=this;var c=this.slider.image1.height();var d=this.slider.image1.find("div.tile");a(d[d.length-1]).transitionEnd(function(){b.finished()});setTimeout(function(){d.css({opacity:"0.5"}).css3({transform:flux.browser.translate(0,c)})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.bars3d=function(b,c){return new flux.transition_grid(b,a.extend({requires3d:true,columns:7,rows:1,delayBetweenBars:150,perspective:1e3,renderTile:function(b,c,d,e,f,g,h){var i=a('<div class="bar-'+c+'"></div>').css({width:e+"px",height:"100%",position:"absolute",top:"0px",left:"0px","z-index":200,"background-image":this.slider.image1.css("background-image"),"background-position":"-"+g+"px 0px","background-repeat":"no-repeat"}).css3({"backface-visibility":"hidden"}),j=a(i.get(0).cloneNode(false)).css({"background-image":this.slider.image2.css("background-image")}).css3({transform:flux.browser.rotateX(90)+" "+flux.browser.translate(0,-f/2,f/2)}),k=a('<div class="side bar-'+c+'"></div>').css({width:f+"px",height:f+"px",position:"absolute",top:"0px",left:"0px",background:"#222","z-index":190}).css3({transform:flux.browser.rotateY(90)+" "+flux.browser.translate(f/2,0,-f/2)+" "+flux.browser.rotateY(180),"backface-visibility":"hidden"}),l=a(k.get(0).cloneNode(false)).css3({transform:flux.browser.rotateY(90)+" "+flux.browser.translate(f/2,0,e-f/2)});a(b).css({width:e+"px",height:"100%",position:"absolute",top:"0px",left:g+"px","z-index":c>this.options.columns/2?1e3-c:1e3}).css3({"transition-duration":"800ms","transition-timing-function":"linear","transition-property":"all","transition-delay":c*this.options.delayBetweenBars+"ms","transform-style":"preserve-3d"}).append(i).append(j).append(k).append(l)},execute:function(){this.slider.image1.css3({perspective:this.options.perspective,"perspective-origin":"50% 50%"}).css({"-moz-transform":"perspective("+this.options.perspective+"px)","-moz-perspective":"none","-moz-transform-style":"preserve-3d"});var a=this,b=this.slider.image1.height(),c=this.slider.image1.find("div.tile");this.slider.image2.hide();c.last().transitionEnd(function(b){a.slider.image1.css3({"transform-style":"flat"});a.slider.image2.show();a.finished()});setTimeout(function(){c.css3({transform:flux.browser.rotateX(-90)+" "+flux.browser.translate(0,b/2,b/2)})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.blinds=function(b,c){return new flux.transitions.bars(b,a.extend({execute:function(){var b=this;var c=this.slider.image1.height();var d=this.slider.image1.find("div.tile");a(d[d.length-1]).transitionEnd(function(){b.finished()});setTimeout(function(){d.css({opacity:"0.5"}).css3({transform:"scalex(0.0001)"})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.blinds3d=function(b,c){return new flux.transitions.tiles3d(b,a.extend({forceSquare:false,rows:1,columns:6},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.zip=function(b,c){return new flux.transitions.bars(b,a.extend({execute:function(){var b=this;var c=this.slider.image1.height();var d=this.slider.image1.find("div.tile");a(d[d.length-1]).transitionEnd(function(){b.finished()});setTimeout(function(){d.each(function(b,d){a(d).css({opacity:"0.3"}).css3({transform:flux.browser.translate(0,b%2?"-"+2*c:c)})})},20)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.blocks=function(b,c){return new flux.transition_grid(b,a.extend({forceSquare:true,delayBetweenBars:100,renderTile:function(b,c,d,e,f,g,h){var i=Math.floor(Math.random()*10*this.options.delayBetweenBars);a(b).css({"background-image":this.slider.image1.css("background-image"),"background-position":"-"+g+"px -"+h+"px"}).css3({"transition-duration":"350ms","transition-timing-function":"ease-in","transition-property":"all","transition-delay":i+"ms"});if(this.maxDelay===undefined)this.maxDelay=0;if(i>this.maxDelay){this.maxDelay=i;this.maxDelayTile=b}},execute:function(){var b=this;var c=this.slider.image1.find("div.tile");this.maxDelayTile.transitionEnd(function(){b.finished()});setTimeout(function(){c.each(function(b,c){a(c).css({opacity:"0"}).css3({transform:"scale(0.8)"})})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.blocks2=function(b,c){return new flux.transition_grid(b,a.extend({cols:12,forceSquare:true,delayBetweenDiagnols:150,renderTile:function(b,c,d,e,f,g,h){var i=Math.floor(Math.random()*10*this.options.delayBetweenBars);a(b).css({"background-image":this.slider.image1.css("background-image"),"background-position":"-"+g+"px -"+h+"px"}).css3({"transition-duration":"350ms","transition-timing-function":"ease-in","transition-property":"all","transition-delay":(c+d)*this.options.delayBetweenDiagnols+"ms","backface-visibility":"hidden"})},execute:function(){var b=this;var c=this.slider.image1.find("div.tile");c.last().transitionEnd(function(){b.finished()});setTimeout(function(){c.each(function(b,c){a(c).css({opacity:"0"}).css3({transform:"scale(0.8)"})})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.concentric=function(b,c){return new flux.transition(b,a.extend({blockSize:60,delay:150,alternate:false,setup:function(){var b=this.slider.image1.width(),c=this.slider.image1.height(),d=Math.sqrt(b*b+c*c),e=Math.ceil((d-this.options.blockSize)/2/this.options.blockSize)+1,f=document.createDocumentFragment();for(var g=0;g<e;g++){var h=2*g*this.options.blockSize+this.options.blockSize;var i=a("<div></div>").attr("class","block block-"+g).css({width:h+"px",height:h+"px",position:"absolute",top:(c-h)/2+"px",left:(b-h)/2+"px","z-index":100+(e-g),"background-image":this.slider.image1.css("background-image"),"background-position":"center center"}).css3({"border-radius":h+"px","transition-duration":"800ms","transition-timing-function":"linear","transition-property":"all","transition-delay":(e-g)*this.options.delay+"ms"});f.appendChild(i.get(0))}this.slider.image1.get(0).appendChild(f)},execute:function(){var b=this;var c=this.slider.image1.find("div.block");a(c[0]).transitionEnd(function(){b.finished()});setTimeout(function(){c.each(function(c,d){a(d).css({opacity:"0"}).css3({transform:flux.browser.rotateZ((!b.options.alternate||c%2?"":"-")+"90")})})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.warp=function(b,c){return new flux.transitions.concentric(b,a.extend({delay:30,alternate:true},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.cube=function(b,c){return new flux.transition(b,a.extend({requires3d:true,barWidth:100,direction:"left",perspective:1e3,setup:function(){var b=this.slider.image1.width();var c=this.slider.image1.height();this.slider.image1.css3({perspective:this.options.perspective,"perspective-origin":"50% 50%"});this.cubeContainer=a('<div class="cube"></div>').css({width:b+"px",height:c+"px",position:"relative"}).css3({"transition-duration":"800ms","transition-timing-function":"linear","transition-property":"all","transform-style":"preserve-3d"});var d={height:"100%",width:"100%",position:"absolute",top:"0px",left:"0px"};var e=a('<div class="face current"></div>').css(a.extend(d,{background:this.slider.image1.css("background-image")})).css3({"backface-visibility":"hidden"});this.cubeContainer.append(e);var f=a('<div class="face next"></div>').css(a.extend(d,{background:this.slider.image2.css("background-image")})).css3({transform:this.options.transitionStrings.call(this,this.options.direction,"nextFace"),"backface-visibility":"hidden"});this.cubeContainer.append(f);this.slider.image1.append(this.cubeContainer)},execute:function(){var a=this;var b=this.slider.image1.width();var c=this.slider.image1.height();this.slider.image2.hide();this.cubeContainer.transitionEnd(function(){a.slider.image2.show();a.finished()});setTimeout(function(){a.cubeContainer.css3({transform:a.options.transitionStrings.call(a,a.options.direction,"container")})},50)},transitionStrings:function(a,b){var c=this.slider.image1.width();var d=this.slider.image1.height();var e={up:{nextFace:flux.browser.rotateX(-90)+" "+flux.browser.translate(0,d/2,d/2),container:flux.browser.rotateX(90)+" "+flux.browser.translate(0,-d/2,d/2)},down:{nextFace:flux.browser.rotateX(90)+" "+flux.browser.translate(0,-d/2,d/2),container:flux.browser.rotateX(-90)+" "+flux.browser.translate(0,d/2,d/2)},left:{nextFace:flux.browser.rotateY(90)+" "+flux.browser.translate(c/2,0,c/2),container:flux.browser.rotateY(-90)+" "+flux.browser.translate(-c/2,0,c/2)},right:{nextFace:flux.browser.rotateY(-90)+" "+flux.browser.translate(-c/2,0,c/2),container:flux.browser.rotateY(90)+" "+flux.browser.translate(c/2,0,c/2)}};return e[a]&&e[a][b]?e[a][b]:false}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.tiles3d=function(b,c){return new flux.transition_grid(b,a.extend({requires3d:true,forceSquare:true,columns:5,perspective:600,delayBetweenBarsX:200,delayBetweenBarsY:150,renderTile:function(b,c,d,e,f,g,h){var i=a("<div></div>").css({width:e+"px",height:f+"px",position:"absolute",top:"0px",left:"0px","background-image":this.slider.image1.css("background-image"),"background-position":"-"+g+"px -"+h+"px","background-repeat":"no-repeat","-moz-transform":"translateZ(1px)"}).css3({"backface-visibility":"hidden"});var j=a(i.get(0).cloneNode(false)).css({"background-image":this.slider.image2.css("background-image")}).css3({transform:flux.browser.rotateY(180),"backface-visibility":"hidden"});a(b).css({"z-index":(c>this.options.columns/2?500-c:500)+(d>this.options.rows/2?500-d:500)}).css3({"transition-duration":"800ms","transition-timing-function":"ease-out","transition-property":"all","transition-delay":c*this.options.delayBetweenBarsX+d*this.options.delayBetweenBarsY+"ms","transform-style":"preserve-3d"}).append(i).append(j)},execute:function(){this.slider.image1.css3({perspective:this.options.perspective,"perspective-origin":"50% 50%"});var a=this;var b=this.slider.image1.find("div.tile");this.slider.image2.hide();b.last().transitionEnd(function(b){a.slider.image2.show();a.finished()});setTimeout(function(){b.css3({transform:flux.browser.rotateY(180)})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.turn=function(b,c){return new flux.transition(b,a.extend({requires3d:true,perspective:1300,direction:"left",setup:function(){var b=a('<div class="tab"></div>').css({width:"50%",height:"100%",position:"absolute",top:"0px",left:this.options.direction=="left"?"50%":"0%","z-index":101}).css3({"transform-style":"preserve-3d","transition-duration":"1000ms","transition-timing-function":"ease-out","transition-property":"all","transform-origin":this.options.direction=="left"?"left center":"right center"}),c=a("<div></div>").appendTo(b).css({"background-image":this.slider.image1.css("background-image"),"background-position":(this.options.direction=="left"?"-"+this.slider.image1.width()/2:0)+"px 0",width:"100%",height:"100%",position:"absolute",top:"0",left:"0","-moz-transform":"translateZ(1px)"}).css3({"backface-visibility":"hidden"}),d=a("<div></div>").appendTo(b).css({"background-image":this.slider.image2.css("background-image"),"background-position":(this.options.direction=="left"?0:"-"+this.slider.image1.width()/2)+"px 0",width:"100%",height:"100%",position:"absolute",top:"0",left:"0"}).css3({transform:flux.browser.rotateY(180),"backface-visibility":"hidden"}),e=a("<div></div>").css({position:"absolute",top:"0",left:this.options.direction=="left"?"0":"50%",width:"50%",height:"100%","background-image":this.slider.image1.css("background-image"),"background-position":(this.options.direction=="left"?0:"-"+this.slider.image1.width()/2)+"px 0","z-index":100}),f=a('<div class="overlay"></div>').css({position:"absolute",top:"0",left:this.options.direction=="left"?"50%":"0",width:"50%",height:"100%",background:"#000",opacity:1}).css3({"transition-duration":"800ms","transition-timing-function":"linear","transition-property":"opacity"}),g=a("<div></div>").css3({width:"100%",height:"100%"}).css3({perspective:this.options.perspective,"perspective-origin":"50% 50%"}).append(b).append(e).append(f);this.slider.image1.append(g)},execute:function(){var a=this;this.slider.image1.find("div.tab").first().transitionEnd(function(){a.finished()});setTimeout(function(){a.slider.image1.find("div.tab").css3({transform:flux.browser.rotateY(a.options.direction=="left"?-179:179)});a.slider.image1.find("div.overlay").css({opacity:0})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.slide=function(b,c){return new flux.transition(b,a.extend({direction:"left",setup:function(){var b=this.slider.image1.width(),c=this.slider.image1.height(),d=a('<div class="current"></div>').css({height:c+"px",width:b+"px",position:"absolute",top:"0px",left:"0px",background:this.slider[this.options.direction=="left"?"image1":"image2"].css("background-image")}).css3({"backface-visibility":"hidden"}),e=a('<div class="next"></div>').css({height:c+"px",width:b+"px",position:"absolute",top:"0px",left:b+"px",background:this.slider[this.options.direction=="left"?"image2":"image1"].css("background-image")}).css3({"backface-visibility":"hidden"});this.slideContainer=a('<div class="slide"></div>').css({width:2*b+"px",height:c+"px",position:"relative",left:this.options.direction=="left"?"0px":-b+"px","z-index":101}).css3({"transition-duration":"600ms","transition-timing-function":"ease-in","transition-property":"all"});this.slideContainer.append(d).append(e);this.slider.image1.append(this.slideContainer)},execute:function(){var a=this,b=this.slider.image1.width();if(this.options.direction=="left")b=-b;this.slideContainer.transitionEnd(function(){a.finished()});setTimeout(function(){a.slideContainer.css3({transform:flux.browser.translate(b)})},50)}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.swipe=function(b,c){return new flux.transition(b,a.extend({setup:function(){var b=a("<div></div>").css({width:"100%",height:"100%","background-image":this.slider.image1.css("background-image")}).css3({"transition-duration":"1600ms","transition-timing-function":"ease-in","transition-property":"all","mask-image":"-webkit-linear-gradient(left, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 48%, rgba(0,0,0,1) 52%, rgba(0,0,0,1) 100%)","mask-position":"70%","mask-size":"400%"});this.slider.image1.append(b)},execute:function(){var b=this,c=this.slider.image1.find("div");a(c).transitionEnd(function(){b.finished()});setTimeout(function(){a(c).css3({"mask-position":"30%"})},50)},compatibilityCheck:function(){return flux.browser.supportsCSSProperty("MaskImage")}},c))}})(window.jQuery||window.Zepto);(function(a){flux.transitions.dissolve=function(b,c){return new flux.transition(b,a.extend({setup:function(){var b=a('<div class="image"></div>').css({width:"100%",height:"100%","background-image":this.slider.image1.css("background-image")}).css3({"transition-duration":"600ms","transition-timing-function":"ease-in","transition-property":"opacity"});this.slider.image1.append(b)},execute:function(){var b=this,c=this.slider.image1.find("div.image");a(c).transitionEnd(function(){b.finished()});setTimeout(function(){a(c).css({opacity:"0.0"})},50)}},c))}})(window.jQuery||window.Zepto)

/**
 * Client side language layer
 * @author Jesper Lindström
 * @package FusionCMS
 * @version 6.0
 */

var Language = (function()
{
	var self = {},
		items = {};

	/** 
	 * Add a language string
	 * @param String key
	 * @param String file
	 * @param String value
	 */
	self.add = function(key, file, value)
	{
		if(typeof items[file] == "undefined")
		{
			items[file] = [];
		}

		items[file][key] = value;
	};

	/**
	 * Set the language items
	 * @param String data
	 */
	self.set = function(data)
	{
		data = data.replace(/\//, "");

		items = JSON.parse(data);
	};

	/**
	 * Get a language string
	 * @param String id
	 * @param String file
	 * @return String
	 */
	self.get = function(id, file)
	{
		// Default to "main"
		if(!file)
		{
			file = "main";
		}

		// Make sure it exists
		if(typeof items[file][id] != "undefined")
		{
			return items[file][id];
		}
		else
		{
			console.log("Language string " + id + " in " + file + " was not found");

			return false;
		}
	};

	return self;
}());

/**
 * Shortcut function for Language.get()
 * @param String id
 * @param String file
 * @return String
 */
function lang(id, file)
{
	return Language.get(id, file);
}
function getCookie(c_name)
{
    var i, x, y, ARRcookies = document.cookie.split(";");

    for(i = 0; i < ARRcookies.length;i++)
    {
        x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x = x.replace(/^\s+|\s+$/g,"");

        if(x==c_name)
        {
            return unescape(y);
        }
    }
}

function setCookie(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays === null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}

/* This notice must be untouched at all times.

wz_tooltip.js    v. 3.45

The latest version is available at
http://www.walterzorn.com
or http://www.devira.com
or http://www.walterzorn.de

Copyright (c) 2002-2005 Walter Zorn. All rights reserved.
Created 1. 12. 2002 by Walter Zorn (Web: http://www.walterzorn.com )
Last modified: 17. 2. 2007

Cross-browser tooltips working even in Opera 5 and 6,
as well as in NN 4, Gecko-Browsers, IE4+, Opera 7+ and Konqueror.
No onmouseouts required.
Appearance of tooltips can be individually configured
via commands within the onmouseovers.

LICENSE: LGPL

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License (LGPL) as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

For more details on the GNU Lesser General Public License,
see http://www.gnu.org/copyleft/lesser.html
*/



////////////////  GLOBAL TOOPTIP CONFIGURATION  /////////////////////
var ttAbove       = false;        // tooltip above mousepointer? Alternative: true
var ttBgColor     = "#9999FF";
var ttBgImg       = "";           // path to background image;
var ttBorderColor = "#000066";
var ttBorderWidth = 1;
var ttClickClose  = false;
var ttDelay       = 100;          // time span until tooltip shows up [milliseconds]
var ttFontColor   = "#000066";
var ttFontFace    = "arial,helvetica,sans-serif";
var ttFontSize    = "11px";
var ttFontWeight  = "normal";     // alternative: "bold";
var ttLeft        = false;        // tooltip on the left of the mouse? Alternative: true
var ttOffsetX     = 12;           // horizontal offset of left-top corner from mousepointer
var ttOffsetY     = 15;           // vertical offset                   "
var ttOpacity     = 100;          // opacity of tooltip in percent (must be integer between 0 and 100)
var ttPadding     = 3;            // spacing between border and content
var ttShadowColor = "";
var ttShadowWidth = 0;
var ttStatic      = false;        // tooltip NOT move with the mouse? Alternative: true
var ttSticky      = false;        // do NOT hide tooltip on mouseout? Alternative: true
var ttTemp        = 0;            // time span after which the tooltip disappears; 0 (zero) means "infinite timespan"
var ttTextAlign   = "left";
var ttTitleColor  = "#ffffff";    // color of caption text
var ttWidth       = 100;
////////////////////  END OF TOOLTIP CONFIG  ////////////////////////



//////////////  TAGS WITH TOOLTIP FUNCTIONALITY  ////////////////////
// List may be extended or shortened:
var tt_tags = new Array("a","area","b","big","caption","center","code","dd","div","dl","dt","em","h1","h2","h3","h4","h5","h6","i","img","input","li","map","ol","p","pre","s", "select", "small","span","strike","strong","sub","sup","table","td","textarea","th","tr","tt","u","var","ul","layer");
/////////////////////////////////////////////////////////////////////



///////// DON'T CHANGE ANYTHING BELOW THIS LINE /////////////////////
var tt_obj = null,         // current tooltip
tt_ifrm = null,            // iframe to cover windowed controls in IE
tt_objW = 0, tt_objH = 0,  // width and height of tt_obj
tt_objX = 0, tt_objY = 0,
tt_offX = 0, tt_offY = 0,
xlim = 0, ylim = 0,        // right and bottom borders of visible client area
tt_sup = false,            // true if T_ABOVE cmd
tt_sticky = false,         // tt_obj sticky?
tt_wait = false,
tt_act = false,            // tooltip visibility flag
tt_sub = false,            // true while tooltip below mousepointer
tt_u = "undefined",
tt_mf = null,              // stores previous mousemove evthandler
// Opera: disable href when hovering <a>
tt_tag = null;             // stores hovered dom node, href and previous statusbar txt


var tt_db = (document.compatMode && document.compatMode != "BackCompat")? document.documentElement : document.body? document.body : null,
tt_n = navigator.userAgent.toLowerCase(),
tt_nv = navigator.appVersion;
// Browser flags
var tt_op = !!(window.opera && document.getElementById),
tt_op6 = tt_op && !document.defaultView,
tt_op7 = tt_op && !tt_op6,
tt_ie = tt_n.indexOf("msie") != -1 && document.all && tt_db && !tt_op,
tt_ie7 = tt_ie && typeof document.body.style.maxHeight != tt_u,
tt_ie6 = tt_ie && !tt_ie7 && parseFloat(tt_nv.substring(tt_nv.indexOf("MSIE")+5)) >= 5.5,
tt_n4 = (document.layers && typeof document.classes != tt_u),
tt_n6 = (!tt_op && document.defaultView && typeof document.defaultView.getComputedStyle != tt_u),
tt_w3c = !tt_ie && !tt_n6 && !tt_op && document.getElementById,
tt_ce = document.captureEvents && !tt_n6;

function tt_Int(t_x)
{
	var t_y;
	return isNaN(t_y = parseInt(t_x))? 0 : t_y;
}
function wzReplace(t_x, t_y)
{
	var t_ret = "",
	t_str = this,
	t_xI;
	while((t_xI = t_str.indexOf(t_x)) != -1)
	{
		t_ret += t_str.substring(0, t_xI) + t_y;
		t_str = t_str.substring(t_xI + t_x.length);
	}
	return t_ret+t_str;
}
String.prototype.wzReplace = wzReplace;
function tt_N4Tags(tagtyp, t_d, t_y)
{
	t_d = t_d || document;
	t_y = t_y || new Array();
	var t_x = (tagtyp=="a")? t_d.links : t_d.layers;
	for(var z = t_x.length; z--;) t_y[t_y.length] = t_x[z];
	for(z = t_d.layers.length; z--;) t_y = tt_N4Tags(tagtyp, t_d.layers[z].document, t_y);
	return t_y;
}
function tt_Htm(tt, t_id, txt)
{
	var t_bgc = (typeof tt.T_BGCOLOR != tt_u)? tt.T_BGCOLOR : ttBgColor,
	t_bgimg   = (typeof tt.T_BGIMG != tt_u)? tt.T_BGIMG : ttBgImg,
	t_bc      = (typeof tt.T_BORDERCOLOR != tt_u)? tt.T_BORDERCOLOR : ttBorderColor,
	t_bw      = (typeof tt.T_BORDERWIDTH != tt_u)? tt.T_BORDERWIDTH : ttBorderWidth,
	t_ff      = (typeof tt.T_FONTFACE != tt_u)? tt.T_FONTFACE : ttFontFace,
	t_fc      = (typeof tt.T_FONTCOLOR != tt_u)? tt.T_FONTCOLOR : ttFontColor,
	t_fsz     = (typeof tt.T_FONTSIZE != tt_u)? tt.T_FONTSIZE : ttFontSize,
	t_fwght   = (typeof tt.T_FONTWEIGHT != tt_u)? tt.T_FONTWEIGHT : ttFontWeight,
	t_opa     = (typeof tt.T_OPACITY != tt_u)? tt.T_OPACITY : ttOpacity,
	t_padd    = (typeof tt.T_PADDING != tt_u)? tt.T_PADDING : ttPadding,
	t_shc     = (typeof tt.T_SHADOWCOLOR != tt_u)? tt.T_SHADOWCOLOR : (ttShadowColor || 0),
	t_shw     = (typeof tt.T_SHADOWWIDTH != tt_u)? tt.T_SHADOWWIDTH : (ttShadowWidth || 0),
	t_algn    = (typeof tt.T_TEXTALIGN != tt_u)? tt.T_TEXTALIGN : ttTextAlign,
	t_tit     = (typeof tt.T_TITLE != tt_u)? tt.T_TITLE : "",
	t_titc    = (typeof tt.T_TITLECOLOR != tt_u)? tt.T_TITLECOLOR : ttTitleColor,
	t_w       = (typeof tt.T_WIDTH != tt_u)? tt.T_WIDTH  : ttWidth;
	if(t_shc || t_shw)
	{
		t_shc = t_shc || "#c0c0c0";
		t_shw = t_shw || 5;
	}
	if(tt_n4 && (t_fsz == "10px" || t_fsz == "11px")) t_fsz = "12px";

	var t_optx = (tt_n4? '' : tt_n6? ('-moz-opacity:'+(t_opa/100.0)) : tt_ie? ('filter:Alpha(opacity='+t_opa+')') : ('opacity:'+(t_opa/100.0))) + ';';
	var t_y = '<div id="'+t_id+'" style="position:absolute;z-index:1010;';
	t_y += 'left:0px;top:0px;width:'+(t_w+t_shw)+'px;visibility:'+(tt_n4? 'hide' : 'hidden')+';'+t_optx+'">' +
		'<table border="0" cellpadding="0" cellspacing="0"'+(t_bc? (' bgcolor="'+t_bc+'" style="background:'+t_bc+';"') : '')+' width="'+t_w+'">';
	if(t_tit)
	{
		t_y += '<tr><td style="padding-left:3px;padding-right:3px;" align="'+t_algn+'"><font color="'+t_titc+'" face="'+t_ff+'" ' +
			'style="color:'+t_titc+';font-family:'+t_ff+';font-size:'+t_fsz+';"><b>' +
			(tt_n4? '&nbsp;' : '')+t_tit+'</b></font></td></tr>';
	}
	t_y += '<tr><td><table border="0" cellpadding="'+t_padd+'" cellspacing="'+t_bw+'" width="100%">' +
		'<tr><td'+(t_bgc? (' bgcolor="'+t_bgc+'"') : '')+(t_bgimg? ' background="'+t_bgimg+'"' : '')+' style="text-align:'+t_algn+';';
	if(tt_n6) t_y += 'padding:'+t_padd+'px;';
	t_y += '" align="'+t_algn+'"><font color="'+t_fc+'" face="'+t_ff+'"' +
		' style="color:'+t_fc+';font-family:'+t_ff+';font-size:'+t_fsz+';font-weight:'+t_fwght+';">';
	if(t_fwght == 'bold') t_y += '<b>';
	t_y += txt;
	if(t_fwght == 'bold') t_y += '</b>';
	t_y += '</font></td></tr></table></td></tr></table>';
	if(t_shw)
	{
		var t_spct = Math.round(t_shw*1.3);
		if(tt_n4)
		{
			t_y += '<layer bgcolor="'+t_shc+'" left="'+t_w+'" top="'+t_spct+'" width="'+t_shw+'" height="0"></layer>' +
				'<layer bgcolor="'+t_shc+'" left="'+t_spct+'" align="bottom" width="'+(t_w-t_spct)+'" height="'+t_shw+'"></layer>';
		}
		else
		{
			t_optx = tt_n6? '-moz-opacity:0.85;' : tt_ie? 'filter:Alpha(opacity=85);' : 'opacity:0.85;';
			t_y += '<div id="'+t_id+'R" style="position:absolute;background:'+t_shc+';left:'+t_w+'px;top:'+t_spct+'px;width:'+t_shw+'px;height:1px;overflow:hidden;'+t_optx+'"></div>' +
				'<div style="position:relative;background:'+t_shc+';left:'+t_spct+'px;top:0px;width:'+(t_w-t_spct)+'px;height:'+t_shw+'px;overflow:hidden;'+t_optx+'"></div>';
		}
	}
	return(t_y+'</div>');
}
function tt_EvX(t_e)
{
	var t_y = tt_Int(t_e.pageX || t_e.clientX || 0) +
		tt_Int(tt_ie? tt_db.scrollLeft : 0) +
		tt_offX;
	if(t_y > xlim) t_y = xlim;
	var t_scr = tt_Int(window.pageXOffset || (tt_db? tt_db.scrollLeft : 0) || 0);
	if(t_y < t_scr) t_y = t_scr;
	return t_y;
}
function tt_EvY(t_e)
{
	var t_y2;

	var t_y = tt_Int(t_e.pageY || t_e.clientY || 0) +
		tt_Int(tt_ie? tt_db.scrollTop : 0);
	if(tt_sup && (t_y2 = t_y - (tt_objH + tt_offY - 15)) >= tt_Int(window.pageYOffset || (tt_db? tt_db.scrollTop : 0) || 0))
		t_y -= (tt_objH + tt_offY - 15);
	else if(t_y > ylim || !tt_sub && t_y > ylim-24)
	{
		t_y -= (tt_objH + 5);
		tt_sub = false;
	}
	else
	{
		t_y += tt_offY;
		tt_sub = true;
	}
	return t_y;
}
function tt_ReleasMov()
{
	if(document.onmousemove == tt_Move)
	{
		if(!tt_mf && tt_ce) document.releaseEvents(Event.MOUSEMOVE);
		document.onmousemove = tt_mf;
	}
}
function tt_ShowIfrm(t_x)
{
	if(!tt_obj || !tt_ifrm) return;
	if(t_x)
	{
		tt_ifrm.style.width = tt_objW+'px';
		tt_ifrm.style.height = tt_objH+'px';
		tt_ifrm.style.display = "block";
	}
	else tt_ifrm.style.display = "none";
}
function tt_GetDiv(t_id)
{
	return(
		tt_n4? (document.layers[t_id] || null)
		: tt_ie? (document.all[t_id] || null)
		: (document.getElementById(t_id) || null)
	);
}
function tt_GetDivW()
{
	return tt_Int(
		tt_n4? tt_obj.clip.width
		: (tt_obj.offsetWidth || tt_obj.style.pixelWidth)
	);
}
function tt_GetDivH()
{
	return tt_Int(
		tt_n4? tt_obj.clip.height
		: (tt_obj.offsetHeight || tt_obj.style.pixelHeight)
	);
}

// Compat with DragDrop Lib: Ensure that z-index of tooltip is lifted beyond toplevel dragdrop element
function tt_SetDivZ()
{
	var t_i = tt_obj.style || tt_obj;
	if(t_i)
	{
		if(window.dd && dd.z)
			t_i.zIndex = Math.max(dd.z+1, t_i.zIndex);
		if(tt_ifrm) tt_ifrm.style.zIndex = t_i.zIndex-1;
	}
}
function tt_SetDivPos(t_x, t_y)
{
	var t_i = tt_obj.style || tt_obj;
	var t_px = (tt_op6 || tt_n4)? '' : 'px';
	t_i.left = (tt_objX = t_x) + t_px;
	t_i.top = (tt_objY = t_y) + t_px;
	//  window... to circumvent the FireFox Alzheimer Bug
	if(window.tt_ifrm)
	{
		tt_ifrm.style.left = t_i.left;
		tt_ifrm.style.top = t_i.top;
	}
}
function tt_ShowDiv(t_x)
{
	tt_ShowIfrm(t_x);
	if(tt_n4) tt_obj.visibility = t_x? 'show' : 'hide';
	else tt_obj.style.visibility = t_x? 'visible' : 'hidden';
	tt_act = t_x;
}
function tt_DeAlt(t_tag)
{
	if(t_tag)
	{
		if(t_tag.alt) t_tag.alt = "";
		if(t_tag.title) t_tag.title = "";
		var t_c = t_tag.children || t_tag.childNodes || null;
		if(t_c)
		{
			for(var t_i = t_c.length; t_i; )
				tt_DeAlt(t_c[--t_i]);
		}
	}
}
function tt_OpDeHref(t_e)
{
	var t_tag;
	if(t_e)
	{
		t_tag = t_e.target;
		while(t_tag)
		{
			if(t_tag.hasAttribute("href"))
			{
				tt_tag = t_tag
				tt_tag.t_href = tt_tag.getAttribute("href");
				tt_tag.removeAttribute("href");
				tt_tag.style.cursor = "hand";
				tt_tag.onmousedown = tt_OpReHref;
				tt_tag.stats = window.status;
				window.status = tt_tag.t_href;
				break;
			}
			t_tag = t_tag.parentElement;
		}
	}
}
function tt_OpReHref()
{
	if(tt_tag)
	{
		tt_tag.setAttribute("href", tt_tag.t_href);
		window.status = tt_tag.stats;
		tt_tag = null;
	}
}
function tt_Show(t_e, t_id, t_sup, t_clk, t_delay, t_fix, t_left, t_offx, t_offy, t_static, t_sticky, t_temp)
{
	if(tt_obj) tt_Hide();
	tt_mf = document.onmousemove || null;
	if(window.dd && (window.DRAG && tt_mf == DRAG || window.RESIZE && tt_mf == RESIZE)) return;
	var t_sh, t_h;

	tt_obj = tt_GetDiv(t_id);
	if(tt_obj)
	{
		t_e = t_e || window.event;
		tt_sub = !(tt_sup = t_sup);
		tt_sticky = t_sticky;
		tt_objW = tt_GetDivW();
		tt_objH = tt_GetDivH();
		tt_offX = t_left? -(tt_objW+t_offx) : t_offx;
		tt_offY = t_offy;
		if(tt_op7) tt_OpDeHref(t_e);
		if(tt_n4)
		{
			if(tt_obj.document.layers.length)
			{
				t_sh = tt_obj.document.layers[0];
				t_sh.clip.height = tt_objH - Math.round(t_sh.clip.width*1.3);
			}
		}
		else
		{
			t_sh = tt_GetDiv(t_id+'R');
			if(t_sh)
			{
				t_h = tt_objH - tt_Int(t_sh.style.pixelTop || t_sh.style.top || 0);
				if(typeof t_sh.style.pixelHeight != tt_u) t_sh.style.pixelHeight = t_h;
				else t_sh.style.height = t_h+'px';
			}
		}

		xlim = tt_Int((tt_db && tt_db.clientWidth)? tt_db.clientWidth : window.innerWidth) +
			tt_Int(window.pageXOffset || (tt_db? tt_db.scrollLeft : 0) || 0) -
			tt_objW -
			(tt_n4? 21 : 0);
		ylim = tt_Int(window.innerHeight || tt_db.clientHeight) +
			tt_Int(window.pageYOffset || (tt_db? tt_db.scrollTop : 0) || 0) -
			tt_objH - tt_offY;

		tt_SetDivZ();
		if(t_fix) tt_SetDivPos(tt_Int((t_fix = t_fix.split(','))[0]), tt_Int(t_fix[1]));
		else tt_SetDivPos(tt_EvX(t_e), tt_EvY(t_e));

		var t_txt = 'tt_ShowDiv(\'true\');';
		if(t_sticky) t_txt += '{'+
				'tt_ReleasMov();'+
				(t_clk? ('window.tt_upFunc = document.onmouseup || null;'+
				'if(tt_ce) document.captureEvents(Event.MOUSEUP);'+
				'document.onmouseup = new Function("window.setTimeout(\'tt_Hide();\', 10);");') : '')+
			'}';
		else if(t_static) t_txt += 'tt_ReleasMov();';
		if(t_temp > 0) t_txt += 'window.tt_rtm = window.setTimeout(\'tt_sticky = false; tt_Hide();\','+t_temp+');';
		window.tt_rdl = window.setTimeout(t_txt, t_delay);

		if(!t_fix)
		{
			if(tt_ce) document.captureEvents(Event.MOUSEMOVE);
			document.onmousemove = tt_Move;
		}
	}
}
var tt_area = false;
function tt_Move(t_ev)
{
	if(!tt_obj) return;
	if(tt_n6 || tt_w3c)
	{
		if(tt_wait) return;
		tt_wait = true;
		setTimeout('tt_wait = false;', 5);
	}
	var t_e = t_ev || window.event;
	tt_SetDivPos(tt_EvX(t_e), tt_EvY(t_e));
	if(window.tt_op6)
	{
		if(tt_area && t_e.target.tagName != 'AREA') tt_Hide();
		else if(t_e.target.tagName == 'AREA') tt_area = true;
	}
}
function tt_Hide()
{
	if(window.tt_obj)
	{
		if(window.tt_rdl) window.clearTimeout(tt_rdl);
		if(!tt_sticky || !tt_act)
		{
			if(window.tt_rtm) window.clearTimeout(tt_rtm);
			tt_ShowDiv(false);
			tt_SetDivPos(-tt_objW, -tt_objH);
			tt_obj = null;
			if(typeof window.tt_upFunc != tt_u) document.onmouseup = window.tt_upFunc;
		}
		tt_sticky = false;
		if(tt_op6 && tt_area) tt_area = false;
		tt_ReleasMov();
		if(tt_op7) tt_OpReHref();
	}
}
function tt_Init()
{
	if(!(tt_op || tt_n4 || tt_n6 || tt_ie || tt_w3c)) return;

	var htm = tt_n4? '<div style="position:absolute;"></div>' : '',
	tags,
	t_tj,
	over,
	t_b,
	esc = 'return escape(';
	for(var i = tt_tags.length; i;)
	{--i;
		tags = tt_ie? (document.all.tags(tt_tags[i]) || 1)
			: document.getElementsByTagName? (document.getElementsByTagName(tt_tags[i]) || 1)
			: (!tt_n4 && tt_tags[i]=="a")? document.links
			: 1;
		if(tt_n4 && (tt_tags[i] == "a" || tt_tags[i] == "layer")) tags = tt_N4Tags(tt_tags[i]);
		for(var j = tags.length; j;)
		{--j;
			if(typeof (t_tj = tags[j]).onmouseover == "function" && t_tj.onmouseover.toString().indexOf(esc) != -1 && !tt_n6 || tt_n6 && (over = t_tj.getAttribute("onmouseover")) && over.indexOf(esc) != -1)
			{
				if(over) t_tj.onmouseover = new Function(over);
				var txt = unescape(t_tj.onmouseover());
				htm += tt_Htm(
					t_tj,
					"tOoLtIp"+i+""+j,
					txt.wzReplace("& ","&")
				);
				// window. to circumvent the FF Alzheimer Bug
				t_tj.onmouseover = new Function('e',
					'if(window.tt_Show && tt_Show) tt_Show(e,'+
					'"tOoLtIp' +i+''+j+ '",'+
					((typeof t_tj.T_ABOVE != tt_u)? t_tj.T_ABOVE : ttAbove)+','+
					((typeof t_tj.T_CLICKCLOSE != tt_u)? t_tj.T_CLICKCLOSE : ttClickClose)+','+
					((typeof t_tj.T_DELAY != tt_u)? t_tj.T_DELAY : ttDelay)+','+
					((typeof t_tj.T_FIX != tt_u)? '"'+t_tj.T_FIX+'"' : '""')+','+
					((typeof t_tj.T_LEFT != tt_u)? t_tj.T_LEFT : ttLeft)+','+
					((typeof t_tj.T_OFFSETX != tt_u)? t_tj.T_OFFSETX : ttOffsetX)+','+
					((typeof t_tj.T_OFFSETY != tt_u)? t_tj.T_OFFSETY : ttOffsetY)+','+
					((typeof t_tj.T_STATIC != tt_u)? t_tj.T_STATIC : ttStatic)+','+
					((typeof t_tj.T_STICKY != tt_u)? t_tj.T_STICKY : ttSticky)+','+
					((typeof t_tj.T_TEMP != tt_u)? t_tj.T_TEMP : ttTemp)+
					');'
				);
				t_tj.onmouseout = tt_Hide;
				tt_DeAlt(t_tj);
			}
		}
	}
	if(tt_ie6) htm += '<iframe id="TTiEiFrM" src="javascript:false" scrolling="no" frameborder="0" style="filter:Alpha(opacity=0);position:absolute;top:0px;left:0px;display:none;"></iframe>';
	t_b = document.getElementsByTagName? document.getElementsByTagName("body")[0] : tt_db;
	if(t_b && t_b.insertAdjacentHTML) t_b.insertAdjacentHTML("AfterBegin", htm);
	else if(t_b && typeof t_b.innerHTML != tt_u && document.createElement && t_b.appendChild)
	{
		var t_el = document.createElement("div");
		t_b.appendChild(t_el);
		t_el.innerHTML = htm;
	}
	else
		document.write(htm);
	if(document.getElementById) tt_ifrm = document.getElementById("TTiEiFrM");
}
tt_Init();

jQuery(document).ready(function() {
    debug.setLevel(4);
    debug.log('log');
    debug.warn('warn');
    debug.info('info');
    debug.debug('debug');
});
