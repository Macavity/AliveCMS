var COMPILED = !0, goog = goog || {};
goog.global = this;
goog.DEBUG = !0;
goog.LOCALE = "en";
goog.provide = function(a) {
  if(!COMPILED) {
    if(goog.isProvided_(a)) {
      throw Error('Namespace "' + a + '" already declared.');
    }
    delete goog.implicitNamespaces_[a];
    for(var b = a;(b = b.substring(0, b.lastIndexOf("."))) && !goog.getObjectByName(b);) {
      goog.implicitNamespaces_[b] = !0
    }
  }
  goog.exportPath_(a)
};
goog.setTestOnly = function(a) {
  if(COMPILED && !goog.DEBUG) {
    throw a = a || "", Error("Importing test-only code into non-debug environment" + a ? ": " + a : ".");
  }
};
COMPILED || (goog.isProvided_ = function(a) {
  return!goog.implicitNamespaces_[a] && !!goog.getObjectByName(a)
}, goog.implicitNamespaces_ = {});
goog.exportPath_ = function(a, b, d) {
  a = a.split(".");
  d = d || goog.global;
  !(a[0] in d) && d.execScript && d.execScript("var " + a[0]);
  for(var c;a.length && (c = a.shift());) {
    !a.length && goog.isDef(b) ? d[c] = b : d = d[c] ? d[c] : d[c] = {}
  }
};
goog.getObjectByName = function(a, b) {
  for(var d = a.split("."), c = b || goog.global, e;e = d.shift();) {
    if(goog.isDefAndNotNull(c[e])) {
      c = c[e]
    }else {
      return null
    }
  }
  return c
};
goog.globalize = function(a, b) {
  var d = b || goog.global, c;
  for(c in a) {
    d[c] = a[c]
  }
};
goog.addDependency = function(a, b, d) {
  if(!COMPILED) {
    for(var c, a = a.replace(/\\/g, "/"), e = goog.dependencies_, g = 0;c = b[g];g++) {
      e.nameToPath[c] = a, a in e.pathToNames || (e.pathToNames[a] = {}), e.pathToNames[a][c] = !0
    }
    for(c = 0;b = d[c];c++) {
      a in e.requires || (e.requires[a] = {}), e.requires[a][b] = !0
    }
  }
};
goog.ENABLE_DEBUG_LOADER = !0;
goog.require = function(a) {
  if(!COMPILED && !goog.isProvided_(a)) {
    if(goog.ENABLE_DEBUG_LOADER) {
      var b = goog.getPathFromDeps_(a);
      if(b) {
        goog.included_[b] = !0;
        goog.writeScripts_();
        return
      }
    }
    a = "goog.require could not find: " + a;
    goog.global.console && goog.global.console.error(a);
    throw Error(a);
  }
};
goog.basePath = "";
goog.nullFunction = function() {
};
goog.identityFunction = function(a) {
  return a
};
goog.abstractMethod = function() {
  throw Error("unimplemented abstract method");
};
goog.addSingletonGetter = function(a) {
  a.getInstance = function() {
    return a.instance_ || (a.instance_ = new a)
  }
};
!COMPILED && goog.ENABLE_DEBUG_LOADER && (goog.included_ = {}, goog.dependencies_ = {pathToNames:{}, nameToPath:{}, requires:{}, visited:{}, written:{}}, goog.inHtmlDocument_ = function() {
  var a = goog.global.document;
  return"undefined" != typeof a && "write" in a
}, goog.findBasePath_ = function() {
  if(goog.global.CLOSURE_BASE_PATH) {
    goog.basePath = goog.global.CLOSURE_BASE_PATH
  }else {
    if(goog.inHtmlDocument_()) {
      for(var a = goog.global.document.getElementsByTagName("script"), b = a.length - 1;0 <= b;--b) {
        var d = a[b].src, c = d.lastIndexOf("?"), c = -1 == c ? d.length : c;
        if("base.js" == d.substr(c - 7, 7)) {
          goog.basePath = d.substr(0, c - 7);
          break
        }
      }
    }
  }
}, goog.importScript_ = function(a) {
  var b = goog.global.CLOSURE_IMPORT_SCRIPT || goog.writeScriptTag_;
  !goog.dependencies_.written[a] && b(a) && (goog.dependencies_.written[a] = !0)
}, goog.writeScriptTag_ = function(a) {
  return goog.inHtmlDocument_() ? (goog.global.document.write('<script type="text/javascript" src="' + a + '"><\/script>'), !0) : !1
}, goog.writeScripts_ = function() {
  function a(e) {
    if(!(e in c.written)) {
      if(!(e in c.visited) && (c.visited[e] = !0, e in c.requires)) {
        for(var f in c.requires[e]) {
          if(!goog.isProvided_(f)) {
            if(f in c.nameToPath) {
              a(c.nameToPath[f])
            }else {
              throw Error("Undefined nameToPath for " + f);
            }
          }
        }
      }
      e in d || (d[e] = !0, b.push(e))
    }
  }
  var b = [], d = {}, c = goog.dependencies_, e;
  for(e in goog.included_) {
    c.written[e] || a(e)
  }
  for(e = 0;e < b.length;e++) {
    if(b[e]) {
      goog.importScript_(goog.basePath + b[e])
    }else {
      throw Error("Undefined script input");
    }
  }
}, goog.getPathFromDeps_ = function(a) {
  return a in goog.dependencies_.nameToPath ? goog.dependencies_.nameToPath[a] : null
}, goog.findBasePath_(), goog.global.CLOSURE_NO_DEPS || goog.importScript_(goog.basePath + "deps.js"));
goog.typeOf = function(a) {
  var b = typeof a;
  if("object" == b) {
    if(a) {
      if(a instanceof Array) {
        return"array"
      }
      if(a instanceof Object) {
        return b
      }
      var d = Object.prototype.toString.call(a);
      if("[object Window]" == d) {
        return"object"
      }
      if("[object Array]" == d || "number" == typeof a.length && "undefined" != typeof a.splice && "undefined" != typeof a.propertyIsEnumerable && !a.propertyIsEnumerable("splice")) {
        return"array"
      }
      if("[object Function]" == d || "undefined" != typeof a.call && "undefined" != typeof a.propertyIsEnumerable && !a.propertyIsEnumerable("call")) {
        return"function"
      }
    }else {
      return"null"
    }
  }else {
    if("function" == b && "undefined" == typeof a.call) {
      return"object"
    }
  }
  return b
};
goog.isDef = function(a) {
  return void 0 !== a
};
goog.isNull = function(a) {
  return null === a
};
goog.isDefAndNotNull = function(a) {
  return null != a
};
goog.isArray = function(a) {
  return"array" == goog.typeOf(a)
};
goog.isArrayLike = function(a) {
  var b = goog.typeOf(a);
  return"array" == b || "object" == b && "number" == typeof a.length
};
goog.isDateLike = function(a) {
  return goog.isObject(a) && "function" == typeof a.getFullYear
};
goog.isString = function(a) {
  return"string" == typeof a
};
goog.isBoolean = function(a) {
  return"boolean" == typeof a
};
goog.isNumber = function(a) {
  return"number" == typeof a
};
goog.isFunction = function(a) {
  return"function" == goog.typeOf(a)
};
goog.isObject = function(a) {
  var b = typeof a;
  return"object" == b && null != a || "function" == b
};
goog.getUid = function(a) {
  return a[goog.UID_PROPERTY_] || (a[goog.UID_PROPERTY_] = ++goog.uidCounter_)
};
goog.removeUid = function(a) {
  "removeAttribute" in a && a.removeAttribute(goog.UID_PROPERTY_);
  try {
    delete a[goog.UID_PROPERTY_]
  }catch(b) {
  }
};
goog.UID_PROPERTY_ = "closure_uid_" + Math.floor(2147483648 * Math.random()).toString(36);
goog.uidCounter_ = 0;
goog.getHashCode = goog.getUid;
goog.removeHashCode = goog.removeUid;
goog.cloneObject = function(a) {
  var b = goog.typeOf(a);
  if("object" == b || "array" == b) {
    if(a.clone) {
      return a.clone()
    }
    var b = "array" == b ? [] : {}, d;
    for(d in a) {
      b[d] = goog.cloneObject(a[d])
    }
    return b
  }
  return a
};
goog.bindNative_ = function(a, b, d) {
  return a.call.apply(a.bind, arguments)
};
goog.bindJs_ = function(a, b, d) {
  if(!a) {
    throw Error();
  }
  if(2 < arguments.length) {
    var c = Array.prototype.slice.call(arguments, 2);
    return function() {
      var d = Array.prototype.slice.call(arguments);
      Array.prototype.unshift.apply(d, c);
      return a.apply(b, d)
    }
  }
  return function() {
    return a.apply(b, arguments)
  }
};
goog.bind = function(a, b, d) {
  goog.bind = Function.prototype.bind && -1 != Function.prototype.bind.toString().indexOf("native code") ? goog.bindNative_ : goog.bindJs_;
  return goog.bind.apply(null, arguments)
};
goog.partial = function(a, b) {
  var d = Array.prototype.slice.call(arguments, 1);
  return function() {
    var b = Array.prototype.slice.call(arguments);
    b.unshift.apply(b, d);
    return a.apply(this, b)
  }
};
goog.mixin = function(a, b) {
  for(var d in b) {
    a[d] = b[d]
  }
};
goog.now = Date.now || function() {
  return+new Date
};
goog.globalEval = function(a) {
  if(goog.global.execScript) {
    goog.global.execScript(a, "JavaScript")
  }else {
    if(goog.global.eval) {
      if(null == goog.evalWorksForGlobals_ && (goog.global.eval("var _et_ = 1;"), "undefined" != typeof goog.global._et_ ? (delete goog.global._et_, goog.evalWorksForGlobals_ = !0) : goog.evalWorksForGlobals_ = !1), goog.evalWorksForGlobals_) {
        goog.global.eval(a)
      }else {
        var b = goog.global.document, d = b.createElement("script");
        d.type = "text/javascript";
        d.defer = !1;
        d.appendChild(b.createTextNode(a));
        b.body.appendChild(d);
        b.body.removeChild(d)
      }
    }else {
      throw Error("goog.globalEval not available");
    }
  }
};
goog.evalWorksForGlobals_ = null;
goog.getCssName = function(a, b) {
  var d = function(a) {
    return goog.cssNameMapping_[a] || a
  }, c;
  c = goog.cssNameMapping_ ? "BY_WHOLE" == goog.cssNameMappingStyle_ ? d : function(a) {
    for(var a = a.split("-"), b = [], c = 0;c < a.length;c++) {
      b.push(d(a[c]))
    }
    return b.join("-")
  } : function(a) {
    return a
  };
  return b ? a + "-" + c(b) : c(a)
};
goog.setCssNameMapping = function(a, b) {
  goog.cssNameMapping_ = a;
  goog.cssNameMappingStyle_ = b
};
!COMPILED && goog.global.CLOSURE_CSS_NAME_MAPPING && (goog.cssNameMapping_ = goog.global.CLOSURE_CSS_NAME_MAPPING);
goog.getMsg = function(a, b) {
  var d = b || {}, c;
  for(c in d) {
    var e = ("" + d[c]).replace(/\$/g, "$$$$"), a = a.replace(RegExp("\\{\\$" + c + "\\}", "gi"), e)
  }
  return a
};
goog.exportSymbol = function(a, b, d) {
  goog.exportPath_(a, b, d)
};
goog.exportProperty = function(a, b, d) {
  a[b] = d
};
goog.inherits = function(a, b) {
  function d() {
  }
  d.prototype = b.prototype;
  a.superClass_ = b.prototype;
  a.prototype = new d;
  a.prototype.constructor = a
};
goog.base = function(a, b, d) {
  var c = arguments.callee.caller;
  if(c.superClass_) {
    return c.superClass_.constructor.apply(a, Array.prototype.slice.call(arguments, 1))
  }
  for(var e = Array.prototype.slice.call(arguments, 2), g = !1, f = a.constructor;f;f = f.superClass_ && f.superClass_.constructor) {
    if(f.prototype[b] === c) {
      g = !0
    }else {
      if(g) {
        return f.prototype[b].apply(a, e)
      }
    }
  }
  if(a[b] === c) {
    return a.constructor.prototype[b].apply(a, e)
  }
  throw Error("goog.base called from a method of one name to a method of a different name");
};
goog.scope = function(a) {
  a.call(goog.global)
};
window.debug = function() {
  function a(a) {
    g && (f || !c || !c.log) && g.apply(b, a)
  }
  for(var b = this, d = Array.prototype.slice, c = b.console, e = {}, g, f, j = 0, k = ["error", "warn", "info", "debug", "log"], m = "assert,clear,count,dir,dirxml,exception,group,groupCollapsed,groupEnd,profile,profileEnd,table,time,timeEnd,trace".split(","), h = m.length, l = [];0 <= --h;) {
    (function(a) {
      e[a] = function() {
        0 !== j && c && c[a] && c[a].apply(c, arguments)
      }
    })(m[h])
  }
  for(h = k.length;0 <= --h;) {
    (function(f, g) {
      e[g] = function() {
        var e = d.call(arguments), h = [g].concat(e);
        l.push(h);
        a(h);
        if(c && (0 < j ? j > f : k.length + j <= f)) {
          c.firebug ? c[g].apply(b, e) : c[g] ? c[g](e) : c.log(e)
        }
      }
    })(h, k[h])
  }
  e.setLevel = function(a) {
    j = "number" === typeof a ? a : 9
  };
  e.setCallback = function() {
    var b = d.call(arguments), c = l.length, e = c;
    g = b.shift() || null;
    f = "boolean" === typeof b[0] ? b.shift() : !1;
    for(e -= "number" === typeof b[0] ? b.shift() : c;e < c;) {
      a(l[e++])
    }
  };
  return e
}();
Function.prototype.bind && console && "object" == typeof console.log && (debug.info("Function.prototype.bind"), "log,info,warn,error,assert,dir,clear,profile,profileEnd".split(",").forEach(function(a) {
  console[a] = this.call(console[a], console)
}, Function.prototype.bind));
if(!window.log) {
  debug.info("!log");
  var log = function() {
    log.history = log.history || [];
    log.history.push(arguments);
    if("undefined" != typeof console && "function" == typeof console.log) {
      if(window.opera) {
        for(var a = 0;a < arguments.length;) {
          console.log("Item " + (a + 1) + ": " + arguments[a]), a++
        }
      }else {
        1 === Array.prototype.slice.call(arguments).length && "string" === typeof Array.prototype.slice.call(arguments)[0] ? console.log(Array.prototype.slice.call(arguments).toString()) : console.log(Array.prototype.slice.call(arguments))
      }
    }else {
      !Function.prototype.bind && "undefined" != typeof console && "object" == typeof console.log ? Function.prototype.call.call(console.log, console, Array.prototype.slice.call(arguments)) : document.getElementById("firebug-lite") ? setTimeout(function() {
        log(Array.prototype.slice.call(arguments))
      }, 500) : (a = document.createElement("script"), a.type = "text/javascript", a.id = "firebug-lite", a.src = "//getfirebug.com/firebug-lite.js", document.getElementsByTagName("HEAD")[0].appendChild(a), setTimeout(function() {
        log(Array.prototype.slice.call(arguments))
      }, 2E3))
    }
  }
}
;Array.prototype.find = function(a) {
  var b = !1;
  for(i = 0;i < this.length;i++) {
    "function" == typeof a ? a.test(this[i]) && (b || (b = []), b.push(i)) : this[i] === a && (b || (b = []), b.push(i))
  }
  return b
};
Array.prototype.indexOf || (Array.prototype.indexOf = function(a, b) {
  var d = this.length >>> 0, c = Number(b) || 0, c = 0 > c ? Math.ceil(c) : Math.floor(c);
  for(0 > c && (c += d);c < d;c++) {
    if(c in this && this[c] === a) {
      return c
    }
  }
  return-1
});

