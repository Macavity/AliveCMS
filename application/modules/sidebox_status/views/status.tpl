{if count($realms) > 0}
    {foreach from=$realms item=realm}
        {if $realm.accessAllowed}
            <div class="realm">
                <div class="realm_online">
                    {if $realm.online}
                        {$realm.onlinePlayers} / {$realm.cap}
                    {else}
                        {lang("offline")}
                    {/if}
                </div>
                {$realm.name}

                <div class="realm_bar">
                    {if $realm.online}
                        <div class="realm_bar_fill" style="width:{$realm.onlinePercentage}%"></div>
                    {/if}
                </div>
            </div>
        {/if}
    {/foreach}
{/if}

<div id="realmlist">set realmlist {$realmlist}</div>