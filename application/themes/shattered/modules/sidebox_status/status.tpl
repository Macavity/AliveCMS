{foreach from=$realms item=realm}
	<div class="realm">
		<div class="realm_bar">
			{if $realm->isOnline()}
				<table width="100%" height="37" cellspacing="0" cellpadding="0">
				<tbody>
				<tr>
					<td width="40%" height="37">
						<center><img height="25" border="0" align="absmiddle" src="application/themes/shattered/images/sidebar_status/horde.png">&nbsp;&nbsp;{$realm->getOnline("horde")}</center>
					</td>
					<td width="20%" height="37">
						<center><img height="15" border="0" align="absmiddle" src="application/themes/shattered/images/sidebar_status/employee.png" style="top: -2px; position: relative;">&nbsp;&nbsp;{$realm->getOnline("gm")}</center>
					</td>
					<td width="40%" height="37">
						<center><img height="25" border="0" align="absmiddle" src="application/themes/shattered/images/sidebar_status/alliance.png">&nbsp;&nbsp;{$realm->getOnline("alliance")}</center>
					</td>
				</tr>
				</tbody></table>
			{/if}
		</div>

		<!--
			Other values, for designers:

			$realm->getOnline("horde")
			$realm->getPercentage("horde")

			$realm->getOnline("alliance")
			$realm->getPercentage("alliance")

		-->

	</div>

	<div class="side_divider"></div>
{/foreach}
<!-- <div id="realmlist">set realmlist {$realmlist}</div> -->