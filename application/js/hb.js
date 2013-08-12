/*

Copyright (C) 2011 by Yehuda Katz

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

// lib/handlebars/browser-prefix.js
var Handlebars = {};

(function(Handlebars, undefined) {
;
// lib/handlebars/base.js

Handlebars.VERSION = "1.0.0";
Handlebars.COMPILER_REVISION = 4;

Handlebars.REVISION_CHANGES = {
  1: '<= 1.0.rc.2', // 1.0.rc.2 is actually rev2 but doesn't report it
  2: '== 1.0.0-rc.3',
  3: '== 1.0.0-rc.4',
  4: '>= 1.0.0'
};

Handlebars.helpers  = {};
Handlebars.partials = {};

var toString = Object.prototype.toString,
    functionType = '[object Function]',
    objectType = '[object Object]';

Handlebars.registerHelper = function(name, fn, inverse) {
  if (toString.call(name) === objectType) {
    if (inverse || fn) { throw new Handlebars.Exception('Arg not supported with multiple helpers'); }
    Handlebars.Utils.extend(this.helpers, name);
  } else {
    if (inverse) { fn.not = inverse; }
    this.helpers[name] = fn;
  }
};

Handlebars.registerPartial = function(name, str) {
  if (toString.call(name) === objectType) {
    Handlebars.Utils.extend(this.partials,  name);
  } else {
    this.partials[name] = str;
  }
};

Handlebars.registerHelper('helperMissing', function(arg) {
  if(arguments.length === 2) {
    return undefined;
  } else {
    throw new Error("Missing helper: '" + arg + "'");
  }
});

Handlebars.registerHelper('blockHelperMissing', function(context, options) {
  var inverse = options.inverse || function() {}, fn = options.fn;

  var type = toString.call(context);

  if(type === functionType) { context = context.call(this); }

  if(context === true) {
    return fn(this);
  } else if(context === false || context == null) {
    return inverse(this);
  } else if(type === "[object Array]") {
    if(context.length > 0) {
      return Handlebars.helpers.each(context, options);
    } else {
      return inverse(this);
    }
  } else {
    return fn(context);
  }
});

Handlebars.K = function() {};

Handlebars.createFrame = Object.create || function(object) {
  Handlebars.K.prototype = object;
  var obj = new Handlebars.K();
  Handlebars.K.prototype = null;
  return obj;
};

Handlebars.logger = {
  DEBUG: 0, INFO: 1, WARN: 2, ERROR: 3, level: 3,

  methodMap: {0: 'debug', 1: 'info', 2: 'warn', 3: 'error'},

  // can be overridden in the host environment
  log: function(level, obj) {
    if (Handlebars.logger.level <= level) {
      var method = Handlebars.logger.methodMap[level];
      if (typeof console !== 'undefined' && console[method]) {
        console[method].call(console, obj);
      }
    }
  }
};

Handlebars.log = function(level, obj) { Handlebars.logger.log(level, obj); };

Handlebars.registerHelper('each', function(context, options) {
  var fn = options.fn, inverse = options.inverse;
  var i = 0, ret = "", data;

  var type = toString.call(context);
  if(type === functionType) { context = context.call(this); }

  if (options.data) {
    data = Handlebars.createFrame(options.data);
  }

  if(context && typeof context === 'object') {
    if(context instanceof Array){
      for(var j = context.length; i<j; i++) {
        if (data) { data.index = i; }
        ret = ret + fn(context[i], { data: data });
      }
    } else {
      for(var key in context) {
        if(context.hasOwnProperty(key)) {
          if(data) { data.key = key; }
          ret = ret + fn(context[key], {data: data});
          i++;
        }
      }
    }
  }

  if(i === 0){
    ret = inverse(this);
  }

  return ret;
});

Handlebars.registerHelper('if', function(conditional, options) {
  var type = toString.call(conditional);
  if(type === functionType) { conditional = conditional.call(this); }

  if(!conditional || Handlebars.Utils.isEmpty(conditional)) {
    return options.inverse(this);
  } else {
    return options.fn(this);
  }
});

Handlebars.registerHelper('unless', function(conditional, options) {
  return Handlebars.helpers['if'].call(this, conditional, {fn: options.inverse, inverse: options.fn});
});

Handlebars.registerHelper('with', function(context, options) {
  var type = toString.call(context);
  if(type === functionType) { context = context.call(this); }

  if (!Handlebars.Utils.isEmpty(context)) return options.fn(context);
});

Handlebars.registerHelper('log', function(context, options) {
  var level = options.data && options.data.level != null ? parseInt(options.data.level, 10) : 1;
  Handlebars.log(level, context);
});
;
// lib/handlebars/utils.js

var errorProps = ['description', 'fileName', 'lineNumber', 'message', 'name', 'number', 'stack'];

Handlebars.Exception = function(message) {
  var tmp = Error.prototype.constructor.apply(this, arguments);

  // Unfortunately errors are not enumerable in Chrome (at least), so `for prop in tmp` doesn't work.
  for (var idx = 0; idx < errorProps.length; idx++) {
    this[errorProps[idx]] = tmp[errorProps[idx]];
  }
};
Handlebars.Exception.prototype = new Error();

// Build out our basic SafeString type
Handlebars.SafeString = function(string) {
  this.string = string;
};
Handlebars.SafeString.prototype.toString = function() {
  return this.string.toString();
};

var escape = {
  "&": "&amp;",
  "<": "&lt;",
  ">": "&gt;",
  '"': "&quot;",
  "'": "&#x27;",
  "`": "&#x60;"
};

var badChars = /[&<>"'`]/g;
var possible = /[&<>"'`]/;

var escapeChar = function(chr) {
  return escape[chr] || "&amp;";
};

Handlebars.Utils = {
  extend: function(obj, value) {
    for(var key in value) {
      if(value.hasOwnProperty(key)) {
        obj[key] = value[key];
      }
    }
  },

  escapeExpression: function(string) {
    // don't escape SafeStrings, since they're already safe
    if (string instanceof Handlebars.SafeString) {
      return string.toString();
    } else if (string == null || string === false) {
      return "";
    }

    // Force a string conversion as this will be done by the append regardless and
    // the regex test will do this transparently behind the scenes, causing issues if
    // an object's to string has escaped characters in it.
    string = string.toString();

    if(!possible.test(string)) { return string; }
    return string.replace(badChars, escapeChar);
  },

  isEmpty: function(value) {
    if (!value && value !== 0) {
      return true;
    } else if(toString.call(value) === "[object Array]" && value.length === 0) {
      return true;
    } else {
      return false;
    }
  }
};
;
// lib/handlebars/runtime.js

Handlebars.VM = {
  template: function(templateSpec) {
    // Just add water
    var container = {
      escapeExpression: Handlebars.Utils.escapeExpression,
      invokePartial: Handlebars.VM.invokePartial,
      programs: [],
      program: function(i, fn, data) {
        var programWrapper = this.programs[i];
        if(data) {
          programWrapper = Handlebars.VM.program(i, fn, data);
        } else if (!programWrapper) {
          programWrapper = this.programs[i] = Handlebars.VM.program(i, fn);
        }
        return programWrapper;
      },
      merge: function(param, common) {
        var ret = param || common;

        if (param && common) {
          ret = {};
          Handlebars.Utils.extend(ret, common);
          Handlebars.Utils.extend(ret, param);
        }
        return ret;
      },
      programWithDepth: Handlebars.VM.programWithDepth,
      noop: Handlebars.VM.noop,
      compilerInfo: null
    };

    return function(context, options) {
      options = options || {};
      var result = templateSpec.call(container, Handlebars, context, options.helpers, options.partials, options.data);

      var compilerInfo = container.compilerInfo || [],
          compilerRevision = compilerInfo[0] || 1,
          currentRevision = Handlebars.COMPILER_REVISION;

      if (compilerRevision !== currentRevision) {
        if (compilerRevision < currentRevision) {
          var runtimeVersions = Handlebars.REVISION_CHANGES[currentRevision],
              compilerVersions = Handlebars.REVISION_CHANGES[compilerRevision];
          throw "Template was precompiled with an older version of Handlebars than the current runtime. "+
                "Please update your precompiler to a newer version ("+runtimeVersions+") or downgrade your runtime to an older version ("+compilerVersions+").";
        } else {
          // Use the embedded version info since the runtime doesn't know about this revision yet
          throw "Template was precompiled with a newer version of Handlebars than the current runtime. "+
                "Please update your runtime to a newer version ("+compilerInfo[1]+").";
        }
      }

      return result;
    };
  },

  programWithDepth: function(i, fn, data /*, $depth */) {
    var args = Array.prototype.slice.call(arguments, 3);

    var program = function(context, options) {
      options = options || {};

      return fn.apply(this, [context, options.data || data].concat(args));
    };
    program.program = i;
    program.depth = args.length;
    return program;
  },
  program: function(i, fn, data) {
    var program = function(context, options) {
      options = options || {};

      return fn(context, options.data || data);
    };
    program.program = i;
    program.depth = 0;
    return program;
  },
  noop: function() { return ""; },
  invokePartial: function(partial, name, context, helpers, partials, data) {
    var options = { helpers: helpers, partials: partials, data: data };

    if(partial === undefined) {
      throw new Handlebars.Exception("The partial " + name + " could not be found");
    } else if(partial instanceof Function) {
      return partial(context, options);
    } else if (!Handlebars.compile) {
      throw new Handlebars.Exception("The partial " + name + " could not be compiled when running in runtime-only mode");
    } else {
      partials[name] = Handlebars.compile(partial, {data: data !== undefined});
      return partials[name](context, options);
    }
  }
};

Handlebars.template = Handlebars.VM.template;
;
// lib/handlebars/browser-suffix.js
})(Handlebars);
;

(function(Handlebars) {


  /**
   * Added Helper for creating html lists
   */
  Handlebars.registerHelper('list', function(context, options) {
    var ret = "<ul>";

    for(var i=0, j=context.length; i<j; i++) {
      ret = ret + "<li>" + options.fn(context[i]) + "</li>";
    }

    return ret + "</ul>";
  });

  /**
   * Added Helper for easier usage of precompiled templates as partials
   * instead of {{> partialName}} use {{partial "templateName"}}
   * @param templateName Name of the template that is used as a partial
   * @param context
   */
  Handlebars.registerHelper('partial', function(templateName, context){
    return new Handlebars.SafeString(Handlebars.templates[templateName](this));
  });

}(this.Handlebars));
(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['alert'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"alert alert-";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.message) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.message; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</div>";
  return buffer;
  });
templates['store_article'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<article id=\"cart-item-"
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.uniqueKey)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" class=\"cart-item\">\n    <div class=\"divider\"></div>\n    <div class=\"item-icon\">\n        <a href=\"/item/"
    + escapeExpression(((stack1 = ((stack1 = depth0.realm),stack1 == null || stack1 === false ? stack1 : stack1.id)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "/"
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.itemid)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" class=\"item-link\">\n            <span class=\"icon-frame frame-36\" style=\"background-image: url(/application/themes/shattered/images/icons/36/"
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.icon)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + ".jpg);\"></span>\n        </a>\n    </div>\n    <div class=\"item-price\">\n        <img src=\"";
  if (stack2 = helpers.url) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.url; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "application/images/icons/lightning.png\" align=\"absmiddle\" />\n        "
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.vp_price)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n    </div>\n    <div class=\"item-name\">\n        <a href=\"#\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</a>\n        <span class=\"qty\" id=\"cart-quantity-"
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.uniqueKey)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\">[<span>x"
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.count)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</span>]</span>\n    </div>\n    <span class=\"item-nick\">";
  if (stack2 = helpers.recipient) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.recipient; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + " @ "
    + escapeExpression(((stack1 = ((stack1 = depth0.realm),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</span>\n    <a href=\"#\" class=\"jsDeleteFromCart\" data-itemkey=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.item),stack1 == null || stack1 === false ? stack1 : stack1.uniqueKey)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\">Entfernen</a>\n</article>";
  return buffer;
  });
templates['store_checkout'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n        <div class=\"alert alert-danger\">\n            "
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1.cant_afford)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "<br />\n        </div>\n    ";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n        <p>"
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1.want_to_buy)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + " <img src=\"";
  if (stack2 = helpers.url) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.url; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "application/images/icons/lightning.png\" align=\"absmiddle\" /> ";
  if (stack2 = helpers.vp_sum) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.vp_sum; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + " VP</p>\n    ";
  return buffer;
  }

  buffer += "<div class=\"modal-header\">\n    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n    <h3>"
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1.checkout)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</h3>\n</div>\n<div class=\"modal-body\">\n    ";
  stack2 = helpers['if'].call(depth0, depth0.error, {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n</div>\n<div class=\"modal-footer\">\n    <button class=\"btn\" data-dismiss=\"modal\" aria-hidden=\"true\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1.cancel)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</button>\n    <button class=\"btn btn-primary jsStorePay\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1.buy)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</button>\n</div>";
  return buffer;
  });
templates['userplate'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n                                <a href=\"";
  if (stack1 = helpers.url) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.url; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" onclick=\"CharSelect.pin(";
  if (stack1 = helpers.guid) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.guid; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ", ";
  if (stack1 = helpers.realmId) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.realmId; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ", this); return false;\" class=\"char\" rel=\"np\">\n                                    <span class=\"pin\"></span>\n                                    <span class=\"name\">";
  if (stack1 = helpers.name) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.name; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\n                                    <span class=\"class wow-class-";
  if (stack1 = helpers['class']) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0['class']; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.level) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.level; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " ";
  if (stack1 = helpers.raceString) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.raceString; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " ";
  if (stack1 = helpers.classString) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.classString; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\n                                    <span class=\"realm up\">";
  if (stack1 = helpers.realmName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.realmName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\n                                </a>\n                                ";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n                <a class=\"guild-name\" href=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.guildUrl)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.guildName)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</a>\n              ";
  return buffer;
  }

  buffer += "<div class=\"user-plate ajax-update\">\n    <a id=\"user-plate\" class=\"card-character plate-";
  if (stack1 = helpers.factionString) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.factionString; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" rel=\"np\" href=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.url)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\">\n        <span class=\"card-portrait\" style=\"background-image:url("
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.avatarUrl)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + ")\"></span>\n    </a>\n    <div class=\"meta-wrapper meta-";
  if (stack2 = helpers.factionString) { stack2 = stack2.call(depth0, {hash:{},data:data}); }
  else { stack2 = depth0.factionString; stack2 = typeof stack2 === functionType ? stack2.apply(depth0) : stack2; }
  buffer += escapeExpression(stack2)
    + "\">\n        <div class=\"meta\">\n            <div class=\"player-name\">{$nickname}</div>\n            <div class=\"character\">\n                <a class=\"character-name context-link\" rel=\"np\" href=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.url)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" data-tooltip=\"Charakter wechseln\" data-tooltip-options=\"{&quot;location&quot;: &quot;topCenter&quot;}\">\n                    "
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n                    <span class=\"arrow\"></span>\n                </a>\n                <div id=\"context-1\" class=\"ui-context character-select\">\n                    <div class=\"context\">\n                        <a href=\"javascript:;\" class=\"close\" onclick=\"return CharSelect.close(this);\"></a>\n                        <div class=\"context-user\">\n                            <strong>"
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</strong><br />\n                            <span class=\"realm up\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.realmName)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</span>\n                        </div>\n                        <div class=\"context-links\">\n                            <a href=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.url)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" title=\"Profil\" rel=\"np\" class=\"icn-profile link-first\">Profil</a>\n                            <a href=\"/\" title=\"Meine Beiträge ansehen\" rel=\"np\" class=\"icon-posts\"><!--  --></a>\n                            <a href=\"/server/auction/alliance/\" title=\"Auktionen einsehen\" rel=\"np\" class=\"icon-auctions\"><!--  --></a>\n                            <a href=\"/server/events/\" title=\"Events einsehen\" rel=\"np\" class=\"icon-events link-last\"><!--  --></a>\n                        </div>\n                    </div>\n                    <div class=\"character-list\">\n                        <div class=\"primary chars-pane\">\n                            <div class=\"char-wrapper\">\n                                <a href=\""
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.url)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\" class=\"char pinned\" rel=\"np\">\n                                    <span class=\"pin\"></span>\n                                    <span class=\"name\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.name)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</span>\n                                    <span class=\"class wow-class-"
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1['class'])),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.level)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + " "
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.raceString)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + " "
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.classString)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</span>\n                                    <span class=\"realm up\">"
    + escapeExpression(((stack1 = ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.realmName)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</span>\n                                </a>\n                                ";
  stack2 = helpers.each.call(depth0, depth0.charList, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            </div>\n            <div class=\"guild\">\n              ";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.activeChar),stack1 == null || stack1 === false ? stack1 : stack1.hasGuild), {hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n            </div>\n        </div>\n    </div>\n</div>";
  return buffer;
  });
})();
