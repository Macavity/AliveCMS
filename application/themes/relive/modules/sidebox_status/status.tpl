{foreach from=$realms item=realm}
    {if $realm.accessAllowed}
        <div class="realm">
            <div class="realm_bar">
                <table width="100%" height="37" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th colspan="3" class="{$realm.css}">{$realm.name}</th>
                    </tr>
                    {if $realm.online}
                        <tr>
                            <td width="40%" height="37">
                                <center><img height="31" border="0" align="absmiddle" src="/application/themes/shattered/images/sidebar_status/horde.png">&nbsp;&nbsp;{$realm.horde}</center>
                            </td>
                            <td width="20%" height="37">
                                <center><img height="21" border="0" align="absmiddle" src="/application/themes/shattered/images/sidebar_status/employee.png" style="top: -2px; position: relative;">&nbsp;&nbsp;{$realm.gm}</center>
                            </td>
                            <td width="40%" height="37">
                                <center><img height="31" border="0" align="absmiddle" src="/application/themes/shattered/images/sidebar_status/alliance.png">&nbsp;&nbsp;{$realm.alliance}</center>
                            </td>
                        </tr>
                    {else}
                        <tr>
                            <td colspan="3" class="realm-offline" style="text-align: center;"><i class="icon-white icon-remove"></i> Realm offline</td>
                        </tr>
                    {/if}
                    </tbody>
                </table>
            </div>
        </div>
        <div id="realmlist">set realmlist {$realmlist}</div>

        <div class="side_divider"></div><br/>
        <script>
            $(document).ready(function(){
                _paq.push(['trackEvent', 'Realm', '{$realm.name}', 'PlayersOnline', {$realm.onlinePlayers}]);
            });
        </script>
    {/if}
{/foreach}