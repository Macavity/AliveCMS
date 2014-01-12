{if $reason == "limit"}
    <div class="alert alert-danger">
        Du kannst keine Transfere erstellen, weil du bereits das Maximum an Transferen erreicht hast.
    </div>
{elseif $reason == "cash"}
    <div class="alert alert-danger">
        Du kannst keine Transfere erstellen, weil du nicht über die benötigten {$cash_needed} Votepunkte verfügst.
    </div>
{elseif $reason == "realmcopy_source_realm"}
  <div class="alert alert-danger">
    Du kannst <strong>von diesem Realm</strong> aus keine Charaktere kopieren.<br>
  </div>
  <a href="{base_url()}/migration/realmcopy/" class="ui-button button1 button1-previous">
    <span><span>Zurück zur Liste</span></span>
  </a>
{elseif $reason == "realmcopy_target_realm"}
  <div class="alert alert-danger">
    Du kannst <strong>zu diesem Realm</strong> keine Charaktere kopieren.<br>
  </div>
  <a href="{base_url()}/migration/realmcopy/" class="ui-button button1 button1-previous">
    <span><span>Zurück zur Liste</span></span>
  </a>
{elseif $reason == "realmcopy_wrong_source_char"}
  <div class="alert alert-danger">
    Du kannst <strong>diesen Character</strong> nicht kopieren, er gehört dir nicht.<br>
  </div>
  <a href="{base_url()}/migration/realmcopy/" class="ui-button button1 button1-previous">
    <span><span>Zurück zur Liste</span></span>
  </a>
{elseif $reason == "realmcopy_char_online"}
  <div class="alert alert-danger">
    Du musst mit dem Zielcharakter vorher offlines gehen.<br>
  </div>
  <a href="{base_url()}/migration/realmcopy/" class="ui-button button1 button1-previous">
    <span><span>Zurück zur Liste</span></span>
  </a>
{else}
    <div class="alert alert-danger">
        Du kannst keine Transfere erstellen, deine Benutzergruppe hat nicht die benötigten Rechte dafür.
    </div>
{/if}

{if count($open_migrations) > 0}
    <h2>Deine offenen Transfere</h2>
    <div class="table">
        <table width="100%">
            <tr>
                <th>ID</th>
                <th>Char</th>
                <th>Server</th>
                <th>Status</th>
            </tr>
            {foreach from=$open_migrations item=transfer}
                <tr class="{cycle values="row1,row2"}">
                    <td><a href="{$url}migration/admin/detail/{$transfer.id}" target="_blank">{$transfer.id}</a></td>
                    <td>{$transfer.character_name}</td>
                    <td>{$transfer.server_name}</td>
                    <td>{$transfer.state_label}</td>
                </tr>
            {/foreach}
        </table>
    </div>
{/if}