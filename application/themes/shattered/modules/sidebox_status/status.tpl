{foreach from=$realms item=realm}
	<div class="realm">
		<div class="realm_bar">
			{if $realm.online}
				<table width="100%" height="37" cellspacing="0" cellpadding="0">
				<tbody>
        <tr>
          <th colspan="3">{$realm.name}</th>
        </tr>
				<tr>
					<td width="40%" height="37">
						<center><img height="25" border="0" align="absmiddle" src="application/themes/shattered/images/sidebar_status/horde.png">&nbsp;&nbsp;{$realm.horde}</center>
					</td>
					<td width="20%" height="37">
						<center><img height="15" border="0" align="absmiddle" src="application/themes/shattered/images/sidebar_status/employee.png" style="top: -2px; position: relative;">&nbsp;&nbsp;{$realm.gm}</center>
					</td>
					<td width="40%" height="37">
						<center><img height="25" border="0" align="absmiddle" src="application/themes/shattered/images/sidebar_status/alliance.png">&nbsp;&nbsp;{$realm.alliance}</center>
					</td>
				</tr>
				</tbody></table>
			{/if}
		</div>
	</div>
  <div id="realmlist">set realmlist {$realmlist}</div>

	<div class="side_divider"></div>
{/foreach}