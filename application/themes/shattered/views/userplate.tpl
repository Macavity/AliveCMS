
{if !$isOnline}
<div class="user-plate ajax-update">
    <a href="/login/" class="card-character plate-logged-out">
        <span class="card-portrait"></span>
        <span class="wow-login-key"></span>
        <span class="login-msg"><strong>Loggt euch ein</strong>, um auf zusätzliche Funktionen zuzugreifen.</span>
    </a>
</div>
{else}
<div class="user-plate ajax-update">
    <a id="user-plate" class="card-character plate-{$factionString}" rel="np" href="{$activeChar.url}">
        <span class="card-portrait" style="background-image:url(/{$activeChar.avatarUrl})"></span>
    </a>
    <div class="meta-wrapper meta-{$factionString}">
        <div class="meta">
            <div class="player-name">{$nickname}</div>
            <div class="character">
                <a class="character-name context-link" rel="np" href="{$activeChar.url}" data-tooltip="Charakter wechseln" data-tooltip-options="{literal}{&quot;location&quot;: &quot;topCenter&quot;}{/literal}">
                    {$activeChar.name}
                    <span class="arrow"></span>
                </a>
                <div id="context-1" class="ui-context character-select">
                    <div class="context">
                        <a href="javascript:;" class="close" onclick="return CharSelect.close(this);"></a>
                        <div class="context-user">
                            <strong>{$activeChar.name}</strong>
                            <br />
                            <span class="realm up">{$activeChar.realmName}</span>
                        </div>
                        <div class="context-links">
                            <a href="{$activeChar.url}" title="Profil" rel="np" class="icon-profile link-first">
                                Profil
                            </a>
                            <a href="/" title="Meine Beiträge ansehen" rel="np" class="icon-posts"><!--  --></a>
                            <a href="/server/auction/alliance/" title="Auktionen einsehen" rel="np" class="icon-auctions"><!--  --></a>
                            <a href="/server/events/" title="Events einsehen" rel="np" class="icon-events link-last"><!--  --></a>
                        </div>
                    </div>
                    <div class="character-list">
                        <div class="primary chars-pane">
                            <div class="char-wrapper">
                                <a href="{$activeChar.url}" class="char pinned" rel="np">
                                    <span class="pin"></span>
                                    <span class="name">{$activeChar.name}</span>
                                    <span class="class wow-class-{$activeChar.class}">{$activeChar.level} {$activeChar.raceString} {$activeChar.classString}</span>
                                    <span class="realm up">{$activeChar.realmName}</span>
                                </a>
                                {foreach from=$charList item=charRow name=charList}
                                <a href="{$charRow.url}" onclick="CharSelect.pin({$charRow.guid}, {$charRow.realmId}, this); return false;" class="char" rel="np">
                                    <span class="pin"></span>
                                    <span class="name">{$charRow.name}</span>
                                    <span class="class wow-class-{$charRow.class}">{$charRow.level} {$charRow.raceString} {$charRow.classString}</span>
                                    <span class="realm up">{$charRow.realmName}</span>
                                </a>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="guild">
            {if $activeChar.hasGuild}
                <a class="guild-name" href="{$activeChar.guildUrl}">{$activeChar.guildName}</a>
            {/if}
            </div>
        </div>
    </div>
</div>
{/if}