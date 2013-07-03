<table id="vote" class="nice_table" cellspacing="0" cellpadding="0">
	<tr>
		<td width="30%">Topsite</td>
		<td width="30%">Value</td>
		<td width="40%">&nbsp;</td>
	</tr>

	{if $vote_sites}
	{foreach from=$vote_sites item=vote_site}
		<tr>
			<td>{if $vote_site.vote_image}<img src="{$vote_site.vote_image}" />{else}{$vote_site.vote_sitename}{/if}</td>
			<td>{$vote_site.points_per_vote} voting point{if $vote_site.points_per_vote > 1}s{/if}</td>
			<td id="vote_field_{$vote_site.id}">
				{if $vote_site.canVote}
					<input type="submit" onClick="Vote.open({$vote_site.id}, {$vote_site.hour_interval});" value="Vote now!"/>
				{else}
					{$vote_site.nextVote} remaining
				{/if}
			</td>
		</tr>
	{/foreach}
	{/if}
</table>