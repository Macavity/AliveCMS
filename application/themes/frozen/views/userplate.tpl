
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
                    <a id="selected-character" class="character-name context-link" data-realmid="{$activeChar.realmId}" data-realmname="{$activeChar.realmName}" data-charid="{$activeChar.guid}" data-name="{$activeChar.name}" href="{$activeChar.url}" data-tooltip="Charakter wechseln" data-tooltip-options="{literal}{&quot;location&quot;: &quot;topCenter&quot;}{/literal}">
                        {$activeChar.name}
                        <span class="arrow glyphicon glyphicon-chevron-down"></span>
                    </a>
                    <div id="context-1" class="ui-context character-select">
                        <div class="context">
                            <a href="javascript:;" class="close"><i class="glyphicon glyphicon-remove"></i></a>
                            <div class="context-user">
                                <strong>{$activeChar.name}</strong>
                                <br />
                                <span class="realm up">{$activeChar.realmName}</span>
                            </div>
                            <div class="context-links">
                                <a href="{$activeChar.url}" title="Profil" rel="np" class="link-first">
                                    <i class="glyphicon glyphicon-eye-open"></i> Arsenal
                                </a>
                                {if $activeChar.hasGuild}
                                <a href="{$activeChar.guildUrl}" title="Profil" rel="np" class="link-last">
                                    <i class="glyphicon glyphicon-tower"></i> Gilde
                                </a>
                                {/if}
                            </div>
                        </div>
                        <div class="character-list">
                            <div class="primary chars-pane">
                                <div class="char-wrapper">
                                  {*<a href="{$activeChar.url}" class="char pinned" rel="np">
                                    <span class="pin"></span>
                                    <span class="name">{$activeChar.name}</span>
                                    <span class="class wow-class-{$activeChar.class}">{$activeChar.level} {$activeChar.raceString} {$activeChar.classString}</span>
                                    <span class="realm">{$activeChar.realmName}</span>
                                  </a>*}
                                  {foreach $realmChars as $realmRow}
                                    {if count($realmRow.characters)}
                                      <span class="realm {if $realmRow.online}up{else}down{/if}">
                                        <i class="glyphicon {if $realmRow.online}glyphicon-ok-sign{else}glyphicon-remove-sign{/if}"></i>
                                        {$realmRow.realmName}
                                      </span>
                                      {foreach $realmRow.characters as $charRow}
                                        <a href="{$charRow.url}" class="char" rel="np" data-guid="{$charRow.guid}" data-realm="{$charRow.realmId}">
                                          <span class="pin"><i class="glyphicon glyphicon-pushpin"></i></span>
                                          <span class="name wow-class-{$charRow.class}">
                                            {$charRow.level}
                                            {$charRow.raceIcon}{$charRow.classIcon}
                                            {$charRow.name}</span>
                                          <span class="class"></span>
                                        </a>
                                      {/foreach}
                                    {/if}
                                  {/foreach}
                                  {* foreach from=$charList item=charRow name=charList}
                                      <a href="{$charRow.url}" class="char" rel="np" data-guid="{$charRow.guid}" data-realm="{$charRow.realmId}">
                                          <span class="pin glyphicon glyphicon-pushpin"></span>
                                          <span class="name wow-class-{$charRow.class}">{$charRow.name}</span>
                                          <span class="class">{$charRow.level} {$charRow.raceString} {$charRow.classString}</span>
                                          <span class="realm up">{$charRow.realmName}</span>
                                      </a>
                                  {/foreach *}
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