
<p class="lead">
  Bitte überprüfe ob das so deinen Wünschen entspricht:<br>
  <br>
  <table class="table">
  <tr>
    <td><strong>Ursprung</strong></td>
    <td class="color-ex{$sourceRealmExpansion}">{$sourceRealmName}</td>
    <td>{$sourceCharName}</td>
  </tr>
  <tr>
    <td><strong>Ziel</strong></td>
    <td class="color-ex{$targetRealmExpansion}">{$targetRealmName}</td>
    <td>{$targetCharName}</td>
  </tr>
  </table>
</p>

<a href="{base_url()}migration/realmcopy/" class="ui-button button1 button1-previous float-left">
  <span><span>NEIN, zurück zur Liste</span></span>
</a>

<a href="{base_url()}migration/copy/{$sourceRealmId}/{$sourceGuid}/" class="ui-button button1 button1-next float-right">
  <span><span>JA, bitte durchführen</span></span>
</a>

{*
<div class="alert alert-danger">
  Jeder Ursprungs-Charakter kann nur einmal kopiert werden.
</div>
*}
