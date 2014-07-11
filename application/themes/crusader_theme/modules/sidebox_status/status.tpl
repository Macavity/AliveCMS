{foreach from=$realms item=realm}
	<div class="realm">
		<div class="realm_online">
			{if $realm.online}
				{$realm.onlinePlayers} / {$realm.cap}
			{else}
				Offline
			{/if}
		</div>
		{$realm.name}
		
		<div class="realm_bar">
			{if $realm.online}
				<div class="realm_bar_fill" style="width:{$realm->getPercentage()}%"></div>
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
<div id="realmlist">set realmlist {$realmlist}</div>