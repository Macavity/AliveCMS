<section class="sidebox_info">
	<table width="100%">
		<tr>
			<td width="50%"><img src="{$url}application/images/icons/plugin.png" align="absmiddle" /> Expansion</td>
			<td>
				<a href="{$url}ucp/expansion" data-tip="Change expansion" style="float:right;margin-right:10px;">
					<img src="{$url}application/images/icons/cog.png" align="absbottom" />
				</a>
				{$expansion}
			</td>
		</tr>
		<tr>
			<td><img src="{$url}application/images/icons/computer_error.png" align="absmiddle" /> Last IP</td>
			<td>{$lastIp}</td>
		</tr>
		<tr>
			<td><img src="{$url}application/images/icons/computer.png" align="absmiddle" /> Current IP</td>
			<td>{$currentIp}</td>
		</tr>
		<tr>
			<td><img src="{$url}application/images/icons/lightning.png" align="absmiddle" /> VP</td>
			<td id="info_vp">{$vp}</td>
		</tr>
		<tr>
			<td><img src="{$url}application/images/icons/coins.png" align="absmiddle" /> DP</td>
			<td id="info_dp">{$dp}</td>
		</tr>

		{if $forum}
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><b>Forum rank:</b></td>
				<td>To do</td>
			</tr>
			<tr>
				<td><b>Reputation:</b></td>
				<td>To do</td>
			</tr>
			<tr>
				<td><b>Threads:</b></td>
				<td>To do</td>
			</tr>
			<tr>
				<td><b>Posts:</b></td>
				<td>To do</td>
			</tr>
		{/if}
	</table>
	<center>
		<a href="{$url}ucp" class="nice_button">User panel</a>
		<a href="{$url}logout" class="nice_button">Log out</a>
	</center>
</section>