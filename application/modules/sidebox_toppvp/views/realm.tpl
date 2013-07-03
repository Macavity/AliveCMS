{if $characters}
	{foreach from=$characters key=key item=character}
		<div class="toppvp_character">
			<div style="float:right">{$character.totalKills} kills</div>
			<b>{$key + 1}</b>
			{if $showRace}<img align="absbottom" src="{$url}application/images/stats/{$character.race}-{$character.gender}.gif" />{/if}
			{if $showClass}<img align="absbottom" src="{$url}application/images/stats/{$character.class}.gif" />{/if}
			&nbsp;&nbsp;<a data-tip="View character profile" href="{$url}character/{$realm}/{$character.guid}">{$character.name}</a> 
		</div>
	{/foreach}
{else}
<br />There are no PvP stats to display<br /><br />
{/if}