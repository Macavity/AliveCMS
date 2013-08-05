{foreach from=$migrations item=transfer}
<tr class="{cycle values="row1,row2"} {$transfer.classes}">
    <td class="w20" data-raw="{$transfer.status}">&nbsp;</td>
    <td class="w50" data-raw="{$transfer.id}">
        <a href="{$url}migration/admin/detail/{$transfer.id}" target="_blank">{$transfer.id}</a>
    </td>
    <td class="w80">{$transfer.account_id}</td>
    <td class="w110">{$transfer.character_name}</td>
    <td class="w110">{$transfer.server_name}</td>
    <td class="w110">{$transfer.date}</td>
    <td class="w110">{$transfer.message}</td>
</tr>{/foreach}