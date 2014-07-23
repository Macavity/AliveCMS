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