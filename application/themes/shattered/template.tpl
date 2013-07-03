{$head}
    <body id="{$controller}-{$method}">
        
        <!-- Teamspeak -->
        <div class="sb_overlay sb_slide" id="ts_overlay">
            <img class="ts_waitload_overlay" src="{$image_path}ajax-loader.gif"> 
        </div>
        
        <div id="ts_control" class="sb_passive_container"> 
            <div id="ts_button">     
                <div id="ts_label"></div>     
                <!--<span id="sb_passive_count" class="sb_passive_1n" style="">4</span> -->  
                <div id="sb_passive_large"></div> 
            </div> 
            <img class="sb_waitload" src="{$image_path}ajax-loader.gif"> 
        </div>
        <!-- /Teamspeak -->
        
        
        <div id="wrapper">
            {$modals}
            
            <!-- header -->
            <div id="header">
                <a name="top"></a>
                <div id="search-bar">
                    <form action="/search/" method="get" id="search-form">
                        <div>
                            <input type="submit" id="search-button" value="" tabindex="41" />
                            <input type="text" name="q" id="search-field" maxlength="200"
                                tabindex="40" alt="Durchsucht das Arsenal und mehr..."
                                value="Durchsucht das Arsenal und mehr..." />
                        </div>
                    </form>
                </div>
                <h1 id="logo">
                    <a href="/news">World of Warcraft Alive</a>
                </h1>
                <div class="header-plate">
                    <ul id="menu">
                     <li class="menu-home"><a href="/news"><span>Hauptseite</span></a></li>
                        <li class="menu-game"><a href="/game"><span>Spiel</span></a></li>
                        <li class="menu-community"><a href="/server"><span>Server</span></a></li>
                        <li class="menu-media"><a href="/ucp"><span>Account</span></a></li>
                        <li class="menu-forums"><a href="http://forum.wow-alive.de/forum.php"><span>Forum</span></a></li>
                        <li class="menu-services"><a href="/store"><span>Vote Shop</span></a></li>
                    </ul>
    
                    {$userplate}
                </div>
            </div>
            <!-- header -->
            
            <!-- content table -->
            <div id="content">
                <div class="content-top">
                    {$breadcrumbs}
                    <div class="content-bot">
                    {if $controller == "news" && $method == "index"}
                        <div id="homepage">
                            <div id="left">
                                {$slider}
                                {$page}
                                <span class="clear"><!-- --></span>
                            </div>
                            <div id="right">
                            {foreach from=$sideboxes item=sidebox}
                                <div id="{$sidebox.css_id}" class="sidebar-module">
                                    <div class="sidebar-title">
                                        <h3>{$sidebox.name}</h3>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                    {$sidebox.data}
                                    <span class="clear"><!-- --></span>
                                </div>
                            {/foreach}
                                <span class="clear"><!-- --></span>
                            </div>
                            <span class="clear"><!-- --></span>
                        </div>
                        <span class="clear"><!-- --></span>
                    {elseif $show_sidebar}
                        {if !empty($topheader)}
                        <div class="top-banner">
                            <div class="section-title">
                                <span>{$topheader}</span>
                            </div>
                            <span class="clear"><!-- --></span>
                        </div>
                        <div class="bg-body">
                            <div class="body-wrapper">
                                <div class="contents-wrapper">
                        {/if}
                        <div class="left-col">
                            {$slider}
                            {$page}
                            <span class="clear"><!-- --></span>
                        </div> 
                        <div class="right-col">
                            {foreach from=$sideboxes item=sidebox}
                            <div id="{$sidebox.css_id}" class="sidebar-module">
                                <div class="sidebar-title">
                                    <h3>{$sidebox.name}</h3>
                                </div>
                                <span class="clear"><!-- --></span>
                                {$sidebox.data}
                                <span class="clear"><!-- --></span>
                            </div>
                            {/foreach}
                            <span class="clear"><!-- --></span>
                        </div>
                        <span class="clear"><!-- --></span>
                        {if !empty($topheader)}
                                </div><!-- /.contents-wrapper -->
                                <span class="clear"><!-- --></span>
                            </div><!-- /.body-wrapper -->
                            <span class="clear"><!-- --></span>
                        </div><!-- /.bg-body -->
                        <span class="clear"><!-- --></span>
                        {/if}
                    {else}
                            {$slider}
                            {$page}
                    {/if}
                    </div> <!-- /content-bot -->
                </div> <!-- /content-top -->
            </div> <!-- /content -->
            <!-- /content area table -->

  <div id="footer">
    <div id="sitemap">
        <div class="column">
            <h3 class="bnet">
                <a href="http://www.wow-alive.de/" tabindex="100">{$serverName}</a>
            </h3>
            <ul>
                <li><a href="/vote">Voten!</a></li>
                <li><a href="/rules">Serverregeln</a></li>
                <li><a href="/news">Portal</a></li>
                <li><a href="http://forum.wow-alive.de/">Forum</a></li>
                <li><a href="/armory">Arsenal</a></li>
                <li><a href="http://forum.wow-alive.de/showgroups.php">Das {$serverName} Team</a></li>
            </ul>
        </div>
        <div class="column">
            <h3 class="games">
                <a href="/game" tabindex="100">Das Spiel</a>
            </h3>
            <ul>
                <li><a href="/register" tabindex="100">Account erstellen</a></li>
                <li><a href="/page/transferanleitung">Transfer</a></li>
                <li><a href="/page/howto">Installationsanleitung</a></li>
                <li><a href="http://arsenal.wow-alive.de/talent-calc.php">Talentrechner</a></li>
            </ul>
        </div>
        <div class="column">
            <h3 class="account">
                <a href="/ucp" tabindex="100">Accounts</a>
            </h3>
            <ul>
                <li><a href="/register" tabindex="100">Spiel Account erstellen</a></li>
                <li><a href="/ucp">Spiel Account verwalten</a></li>
                <li><a href="http://forum.wow-alive.de/usercp.php">Forum Account verwalten</a></li>
                <li><a href="/store">Item Shop</a></li>
            </ul>
        </div>
        <div class="column">
            <h3 class="support">Support</h3>
            <ul>
                <li><a href="http://forum.wow-alive.de/showgroups.php">Das {$serverName} Team</a></li>
                <li><a href="/bugtracker">Bugtracker</a></li>
                <li><a href="/server/realmstatus">Realmstatus</a></li>
                <li><a href="https://twitter.com/#!/erd_geist" target="_blank">Twitter</a></li>
            </ul>
        </div>
        <div id="copyright">
          &copy;2012 Blizzard Entertainment, Inc. Alle Rechte vorbehalten
          <a href="http://forum.wow-alive.de/sendmessage.php" rel="nofollow" accesskey="9">Kontakt</a></if>
          {if $is_admin || $is_owner}
            <a href="/admin">Admin Panel</a>
          {/if}
          {if $is_gm}
            <a href="/gm">GM Panel</a>
          {/if}
          <a href="http://forum.wow-alive.de/archive/index.php">Forumarchiv</a>

          <div class="smallfont">
            <strong>
                <a href="#top" onclick="self.scrollTo(0, 0); return false;">nach oben</a>
            </strong>
          </div>
        </div>
        <div id="legal">
            <div class="smallfont" align="center">
                {* {$pageGeneratedTime} sec.*}
            </div>
            <div id="blizzard" class="png-fix">&nbsp;</div>
            <span class="clear"><!-- --></span>
        </div>
    </div>
  </div> <!-- footer -->
  
<!-- Service Bar -->
<div id="service">
    <ul class="service-bar">
    {if false}
        <li class="service-cell service-home service-maintenance"><a href="http://portal.wow-alive.de/admin/tickets/" tabindex="50" accesskey="1" data-tooltip="Es gibt offene Tickets">&nbsp;</a></li>
    {else}
        <li class="service-cell service-home"><a href="http://portal.wow-alive.de/" tabindex="50" accesskey="1" title="ALive">&nbsp;</a></li>
    {/if}  
    {if $isOnline}
        <li class="service-cell service-welcome">
            Willkommen, {if $is_gm}<span class="employee"></span>{/if}{$user_name} | <a href="/logout">Abmelden</a>
        </li>
        <li class="service-cell service-account">
            <a href="http://forum.wow-alive.de/private.php" class="service-link" tabindex="50" accesskey="2">Nachrichten</a>
        </li>
    {else}
        <li class="service-cell service-welcome">
            <a href="/register" accesskey="1">Registrieren</a>
        </li>
    {/if}
    <li class="service-cell service-support">
        <a href="/bugtracker" class="service-link" tabindex="50" accesskey="4">Bugtracker</a></li>
    <li class="service-cell service-explore">
        <a href="#explore" tabindex="50" accesskey="5" class="dropdown" id="explore-link" onclick="return false" style="cursor: progress" rel="javascript">Erkunden</a>
        <div class="explore-menu" id="explore-menu" style="display:none;">
            <div class="explore-primary">
                <ul class="explore-nav">
                {if $is_gm}
                <li>
                    <a href="/admin" tabindex="55">
                        <strong class="explore-caption">Administration</strong>
                        GameMaster-Tools
                    </a>
                </li>
                {/if}
                <li>
                    <a href="http://www.wow-alive.de" tabindex="55">
                        <strong class="explore-caption">ALive</strong>
                        Verbinden. Spielen. Leben.
                    </a>
                </li>
                {if $isOnline}
                <li>
                    <a href="http://forum.wow-alive.de/private.php">
                        <strong class="explore-caption">Nachrichten</strong>
                    </a>
                </li>
                <li>
                    <a href="/ucp" tabindex="55">
                        <strong class="explore-caption">Account</strong>
                        Account verwalten
                    </a>
                </li>
                {else}
                <li>
                    <a href="/register" tabindex="55">
                        <strong class="explore-caption">Account</strong>
                        Account registrieren
                    </a>
                </li>
                {/if}
                <li>
                    <a href="/bugtracker" tabindex="55">
                        <strong class="explore-caption">Bugtracker</strong>
                        Melde Bugs die du gefunden hast damit wir sie l&ouml;sen k&ouml;nnen.
                    </a>
                </li>
                <li>
                    <a href="http://forum.wow-alive.de/misc.php?do=cfrules">
                        <strong class="explore-caption">Forum Regeln</strong>
                    </a>
                </li>
                <li>
                    <a rel="help" href="http://forum.wow-alive.de/faq.php" accesskey="5">
                        <strong class="explore-caption">FAQ</strong>
                        Fragen zum Forum
                    </a>
                </li>
        
                </ul>
                
                <div class="explore-links">
                    <h2 class="explore-caption">Community</h2>
                    <ul>
                        <li><a href="http://forum.wow-alive.de/showgroups.php">GameMaster</a></li>
                        <!-- community link menu -->
                        <li><a href="http://forum.wow-alive.de/group.php?">Interessengemeinschaften</a></li>
                        <li><a href="http://forum.wow-alive.de/album.php">Bilder &amp; Alben</a></li>
                        <li><a href="http://forum.wow-alive.de/profile.php?do=buddylist">Kontakte &amp; Freunde</a></li>
                        <li><a href="http://forum.wow-alive.de/memberlist.php">Benutzerliste</a></li>                 
                        <!-- / community link menu -->
                    
                    </ul>
                    <br />
                    
                    <h2 class="explore-caption">N&uuml;tzliche Links</h2>
                    <!-- user cp tools menu -->
                    <ul>
                    {if $isOnline}
                        <li><a href="http://forum.wow-alive.de/search.php?do=getnew">Neue Beiträge</a></li>
                    {/if}
                        <li><a href="http://forum.wow-alive.de/search.php?do=getdaily">Heutige Beträge</a></li>
                    {if $isOnline}
                        <li><a href="http://forum.wow-alive.de/subscription.php" rel="nofollow">Abonnierte Themen</a></li>
                    {/if}
                        <li><a href="http://forum.wow-alive.de/online.php">Wer ist online</a></li>
                    {if $isOnline}
                        <li><a href="http://forum.wow-alive.de/forumdisplay.php?do=markread">Alle Foren als gelesen markieren</a></li>
                        <li><a href="#" onclick="window.open('http://forum.wow-alive.de/misc.php?do=buddylist&amp;focus=1','buddylist','statusbar=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes,width=250,height=300'); return false;">Kontakte</a></li>
                    {/if}
                    </ul>
                    
                    
                    {if $isOnline}
                    <br />
                    <h2 class="explore-caption">Benutzerkontrollzentrum</h2>
                    <ul>
                        <li><a href="http://forum.wow-alive.de/profile.php?do=editsignature">Signatur bearbeiten</a></li>
                        <li><a href="http://forum.wow-alive.de/profile.php?do=editavatar">Benutzerbild ändern</a></li>
                        <li><a href="http://forum.wow-alive.de/profile.php?do=editprofile">Profil bearbeiten</a></li>
                        <li><a href="http://forum.wow-alive.de/profile.php?do=editoptions">Einstellungen ändern</a></li>
                    </ul>
                    {/if}
                    <!-- / user cp tools menu -->
                </div>
                <span class="clear"><!-- --></span>
                </div>
            </div>
        </li>
    </ul>
</div> <!-- /service -->
  
<div id="warnings-wrapper">
    <!--[if lt IE 8]>
    <div id="browser-warning" class="warning warning-red">
        <div class="warning-inner2">
            Sie benutzen eine veraltete Browserversion.<br />
            <a href="http://www.mozilla.org/de/firefox">Aktualisieren Sie Ihren Browser</a>.
            <a href="#close" class="warning-close" onclick="App.closeWarning('#browser-warning', 'browserWarning'); return false;"></a>
        </div>
    </div>
    <![endif]-->
    <!--[if lte IE 8]>
        <script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/third-party/CFInstall.min.js?v15"></script>
        <script type="text/javascript">
        //<![CDATA[
        $(function(){
            var age = 365 * 24 * 60 * 60 * 1000;
            var src = 'https://www.google.com/chromeframe/?hl=de-DE';
            if ('http:' == document.location.protocol) {
                src = 'http://www.google.com/chromeframe/?hl=de-DE';
            }
            document.cookie = "disableGCFCheck=0;path=/;max-age="+age;
            $('#chrome-frame-link').bind({
                'click': function() {
                    App.closeWarning('#browser-warning');
                    CFInstall.check({
                        mode: 'overlay',
                        url: src
                    });
                    return false;
                }
            });
        });
        //]]>
        </script>
    <![endif]-->

    <noscript>
        <div id="javascript-warning" class="warning warning-red">
            <div class="warning-inner2">
                Javascript muss aktiviert sein, um diese Seite nutzen zu k&ouml;nnen!
            </div>
        </div>
    </noscript>
</div> <!-- /warnings -->
        
        </div> <!-- /wrapper -->

<script type="text/javascript">
//<![CDATA[
    var Msg = {
        ui: {
            submit: 'Abschicken',
            cancel: 'Abbrechen',
            reset: 'Zurücksetzen',
            viewInGallery: 'Zur Galerie',
            loading: 'Lade…',
            unexpectedError: 'Ein Fehler ist aufgetreten',
            fansiteFind: 'Finde dies auf…',
            fansiteFindType: 'Finde {0} auf…',
            fansiteNone: 'Keine Fanseiten verfügbar.'
        },
        grammar: {
            colon: '{0}:',
            first: 'Erster',
            last: 'Letzter'
        },
        cms: {
            {*
        {foreach from=$js_messageStrings key=$key item=$string}
            {$key}: '{$string}',
        {/foreach}
            *}
            requestError: 'Deine Anfrage konnte nicht bearbeitet werden',
            ignoreNot: 'Dieser Benutzer wird nicht ignoriert',
            ignoreAlready: 'Dieser Benutzer wird bereits ignoriert',
            stickyRequested: 'Sticky beantragt',
            postAdded: 'Beitrag zur Beobachtung hinzugefügt',
            postRemoved: 'Beitrag von der Beobachtung gestrichen',
            userAdded: 'Benutzer zur Beobachtung hinzugefügt',
            userRemoved: 'Benutzer von der Beobachtung gestrichen',
            validationError: 'Ein benötigtes Feld ist nicht ausgefüllt',
            characterExceed: 'Der Inhalt des Textfeldes überschreitet XXXXXX Buchstaben.',
            searchFor: "Suche nach",
            searchTags: "Artikel markiert:",
            characterAjaxError: "Du bist möglicherweise nicht mehr eingeloggt. Bitte aktualisiere die Seite und versuch es erneut.",
            ilvl: "Gegenstandsstufe",
            shortQuery: "Die Suchanfrage muss mindest zwei Buchstaben lang sein"
        },
        fansite: {
            achievement: 'Erfolg',
            character: 'Charakter',
            faction: 'Fraktion',
            'class': 'Klasse',
            object: 'Objekt',
            talentcalc: 'Talente',
            skill: 'Beruf',
            quest: 'Quest',
            spell: 'Zauber',
            event: 'Event',
            title: 'Titel',
            arena: 'Arenateam',
            guild: 'Gilde',
            zone: 'Zone',
            item: 'Gegenstand',
            race: 'Volk',
            npc: 'NPC',
            pet: 'Haustier'
        },
    };
//]]>
</script>
<!--[if lt IE 8]> 
<script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/third-party/jquery.pngFix.pack.js?v15"></script>
<script type="text/javascript">$('.png-fix').pngFix();</script>
<![endif]-->

<script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/third-party/jquery-ui-1.8.6.custom.min.js?v15"></script>
<script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/overlay.js?v15"></script>
<script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/search.js?v15"></script>
<script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/third-party/jquery.mousewheel.min.js?v15"></script>
<script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/third-party/jquery.tinyscrollbar.min.js?v15"></script>

{if not empty($refreshCacheItems)}
<script type="text/javascript">
var _urls = [
             {foreach from=$refreshCacheItems item=$url name=refresh}
                {$url}{if not $smarty.foreach.refresh.last},{/if}
             {/foreach}
];

function _loadUrl(count)
{
    if (count < _urls.length) {
        $.ajax({
            'url': _urls[count],
            'success': function() {
                _loadUrl(count + 1);
            }
        });
    } 
}
$(document).ready(function() {
    _loadUrl(0);
});
</script>
{/if}

    </body>
</html>