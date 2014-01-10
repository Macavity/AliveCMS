
<p class="lead">
  <strong>Vorgehensweise</strong>
  <ol style="list-style: decimal">
    <li>Logge dich auf dem Cata-Realm ein und erstelle einen neuen Charakter</li>
    <li>Logge dich dann wieder aus und schließe World of Warcraft</li>
    <li>Wähle hier auf der Homepage, rechts neben dem Hauptmenü den neuerstellten Charakter aus, damit markierst du den Ziel-Charakter</li>
    <li>Klicke dann unten in der Liste auf den Charakter den du kopieren willst, beachte dass die Klasse des Ursprungs-Charakters zählt und der Ziel-Charakter größtenteils überschrieben wird.</li>
  </ol>
</p>

<div class="alert alert-danger">
  Jeder Ursprungs-Charakter kann nur einmal kopiert werden.
</div>

<div class="table">
    <table cellpadding="3" cellspacing="0" width="100%">
        <tr>
            <th align="center" nowrap="nowrap" width="53">{lang('Status','migration')}</th>
            <th align="center" nowrap="nowrap" colspan="2">{lang('Character','migration')}</th>
            <th align="center" nowrap="nowrap">{lang('Realm','migration')}</th>
            <th align="center" nowrap="nowrap" width="120">&nbsp;</th>
        </tr>
        {foreach $realmChars as $realmRow}
            {foreach $realmRow as $charGuid => $row}
            <tr class="{cycle values='row1,row2'}">
                <td align="center">
                    {if $row.isUsable}
                        <i class="icon-white icon-ok"></i>
                    {else}
                        <i class="icon-white icon-remove"></i>
                    {/if}
                </td>
                <td width="48">
                  <span class="icon-frame frame-18" data-tooltip="{$row.raceLabel}">
                    <img src="{$theme_path}images/icons/18/race_{$row.race}_{$row.gender}.jpg" height="18" width="18">
                  </span>
                  <span class="icon-frame frame-18" data-tooltip="{$row.classLabel}">
                    <img src="{$theme_path}images/icons/18/class_{$row.class}.jpg" height="18" width="18">
                  </span>
                </td>
                <td class="wow-class-{$row.class}">
                    {$row.name}
                </td>
                <td align="center">
                    {$row.realmName}
                </td>
                <td align="center">
                    <a href="{base_url()}migration/confirm_copy/{$row.realmId}/{$charGuid}" class="ui-button button1 button1-next float-right">
                        <span><span>Kopieren</span></span>
                    </a>
                </td>
            </tr>
            {/foreach}
        {/foreach}
    </table>
</div>