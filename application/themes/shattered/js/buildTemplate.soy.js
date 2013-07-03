// This file was automatically generated from buildTemplate.soy.
// Please don't edit this file by hand.

goog.provide('fusion.buildTemplates');

goog.require('soy');
goog.require('soy.StringBuilder');


/**
 * @param {Object.<string, *>=} opt_data
 * @param {soy.StringBuilder=} opt_sb
 * @return {string}
 * @notypecheck
 */
fusion.buildTemplates.build_userPlateNotLoggedIn = function(opt_data, opt_sb) {
  var output = opt_sb || new soy.StringBuilder();
  output.append('<div class="user-plate ajax-update"><div class="user-meta meta-login"><a href="/login"> <strong>Loggt euch</strong></a> mit eurem Account ein, um Kommentare abzugeben und die Seiteninhalte für euch anzupassen.</div></div>');
  return opt_sb ? '' : output.toString();
};


/**
 * @param {Object.<string, *>=} opt_data
 * @param {soy.StringBuilder=} opt_sb
 * @return {string}
 * @notypecheck
 */
fusion.buildTemplates.build_userPlateLoggedIn = function(opt_data, opt_sb) {
  var output = opt_sb || new soy.StringBuilder();
  if (opt_data.user.chars.length == 0) {
    output.append('<div id="user-plate" class="user-plate plate-nochars ajax-update"><div class="card-overlay"><!-- --></div><a href="/ucp" rel="np" class="profile-link"> <span class="hover"><!-- --></span></a><div class="user-meta"><div class="player-name"><a href="/ucp">', soy.$$escapeHtml(opt_data.user.name), '</a></div><div class="character"><!-- --></div><div class="guild"><!-- --></div></div></div>');
  } else {
    output.append('<div id="user-plate" class="user-plate ', soy.$$escapeHtml(opt_data.user.faction_css), ' ajax-update" style="', soy.$$escapeHtml(opt_data.user.chars[0].bg), '"><div class="card-overlay"><!-- --></div><a href="/ucp" rel="np" class="profile-link"><span class="hover"><!-- --></span></a><div class="user-meta"><div class="player-name"><a href="/ucp">', soy.$$escapeHtml(opt_data.user.name), '</a></div><div class="character"><a class="character-name context-link" rel="np" href="', soy.$$escapeHtml(opt_data.user.chars[0].url), '">', soy.$$escapeHtml(opt_data.user.chars[0].name), '<span class="arrow"><!-- --></span></a><div id="context-1" class="ui-context character-select"><div class="context"><a href="javascript:;" class="close" onclick="return CharSelect.close(this);"><!-- --></a><div class="context-user"><strong>', soy.$$escapeHtml(opt_data.user.chars[0].name), '</strong><br /></div><div class="context-links"><a href="', soy.$$escapeHtml(opt_data.user.chars[0].url), '" title="Profile" rel="np" class="icon-profile link-first">Profil</a><!--<a href="search.php?$session[sessionurl]do=finduser&u=$bbuserinfo[userid]" title="Zeige meine Beiträge" rel="np" class="icon-posts"> </a> --><a href="/ucp" title="Charakter Tools" rel="np" class="icon-auctions"><!-- --></a><!--<a href="calendar.php" title="$vbphrase[calendar]" rel="np" class="icon-events link-last"></a>--></div></div><div class="character-list"><div class="primary chars-pane"><div class="char-wrapper"><a href="javascript:;" class="char pinned" rel="np"><span class="pin"><!-- --></span><span class="name">', soy.$$escapeHtml(opt_data.user.chars[0].name), '</span><span class="class wow-class-', soy.$$escapeHtml(opt_data.user.chars[0]['class']), '">', soy.$$escapeHtml(opt_data.user.chars[0].level), ' ', soy.$$escapeHtml(opt_data.user.chars[0].race), ' ', soy.$$escapeHtml(opt_data.user.chars[0].class_label), '</span></a>');
    var charList36 = opt_data.user.chars;
    var charListLen36 = charList36.length;
    for (var charIndex36 = 0; charIndex36 < charListLen36; charIndex36++) {
      var charData36 = charList36[charIndex36];
      output.append((charData36.active == false) ? '<a href="" class="char" onclick="CharSelect.pin(' + soy.$$escapeHtml(charData36.guid) + ', this); return false;" rel="np"><span class="pin"><!-- --></span><span class="name">' + soy.$$escapeHtml(charData36.name) + '</span><span class="class wow-class-' + soy.$$escapeHtml(charData36['class']) + '">' + soy.$$escapeHtml(charData36.level) + ' ' + soy.$$escapeHtml(charData36.race) + ' ' + soy.$$escapeHtml(charData36.class_label) + '</span></a>' : '');
    }
    output.append('</div></div><div class="secondary chars-pane" style="display: none"><!-- --></div></div> <!-- /character-list --></div>  <!-- /context-1 --></div> <!-- /character --><div class="guild">', (! opt_data.user.chars[0].guild_name.length == 0) ? '<a class="guild-name" href="' + soy.$$escapeHtml(opt_data.user.chars[0].guild_url) + '">' + soy.$$escapeHtml(opt_data.user.chars[0].guild_name) + '</a>' : '', '</div></div></div>');
  }
  return opt_sb ? '' : output.toString();
};
