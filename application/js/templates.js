(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['alert'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n        ";
  if (stack1 = helpers.header) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.header; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\n    ";
  return buffer;
  }

  buffer += "<div class=\"alert alert-";
  if (stack1 = helpers.type) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.type; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n    ";
  stack1 = helpers['if'].call(depth0, depth0.header, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n    ";
  if (stack1 = helpers.message) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.message; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\n</div>";
  return buffer;
  });
templates['bugtracker_linklist'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<tr id=\"";
  if (stack1 = helpers.uid) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.uid; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" class=\"";
  if (stack1 = helpers.css) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.css; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n    <td>\n        <input type=\"hidden\" name=\"links[]\" value=\"";
  if (stack1 = helpers.link) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.link; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n        <a href=\"";
  if (stack1 = helpers.link) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.link; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" target=\"_blank\">";
  if (stack1 = helpers.link) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.link; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</a>\n    </td>\n    <td><button class=\"btn btn-mini jsDeleteLink\" data-target=\"";
  if (stack1 = helpers.uid) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.uid; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"><i class=\"icon icon-remove\"></i> "
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1['delete'])),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</button></td>\n</tr>";
  return buffer;
  });
templates['bugtracker_similar_bugs'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n        <br><a href=\"/bugtracker/bug/";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" target=\"_blank\"> Bug #";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " - ";
  if (stack1 = helpers.title) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.title; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</a>\n    ";
  return buffer;
  }

  buffer += "<div class=\"alert alert-danger\">\n    <strong>"
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1.attention)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "</strong> "
    + escapeExpression(((stack1 = ((stack1 = depth0.lang),stack1 == null || stack1 === false ? stack1 : stack1.similarBugsExist)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n    ";
  stack2 = helpers.each.call(depth0, depth0.results, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n</div>";
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
