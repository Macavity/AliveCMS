<div class="table">
  <table cellpadding="3" cellspacing="0" width="100%">
    <tr>
      <th align="center" nowrap="nowrap" width="53">{lang('status','server')}</th>
      <th align="center" nowrap="nowrap">{lang('uptime','server')}</th>
      <th align="center" nowrap="nowrap">{lang('realm_name','server')}</th>
      <th align="center" nowrap="nowrap" width="120">{lang('server_type','server')}</th>
      <th align="center" nowrap="nowrap" width="120">{lang('online_player','server')}</th>
    </tr>
    {foreach $realmData as $realm_id => $realm}
    <tr class="{cycle values='row1,row2'}">
      <td align="center">
        {if $realm.isOnline}
          <i class="icon-white icon-ok"></i>
        {else}
          <i class="icon-white icon-remove"></i>
        {/if}
      </td>
      <td width="168" align="center" nowrap="nowrap">
        {$realm.uptimeDHMS}
      </td>
      <td width="802" class="{$realm.cssClass}">
        {$realm.name}
      </td>
      <td align="center">
        {$realm.type}
      </td>
      <td align="center">
        {$realm.playerOnline}
      </td>
    </tr>
    {/foreach}
    </tbody>
  </table>
</div>