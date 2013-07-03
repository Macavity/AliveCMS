<section id="ucp_top">
	<a href="{$url}ucp/avatar" id="ucp_avatar">
		<div>Change avatar</div>
		<img src="{$avatar}"/>
	</a>

	<section id="ucp_info">
		<aside>
			<table width="100%">
				<tr>
					<td width="10%"><img src="{$url}application/images/icons/user.png" /></td>
					<td width="40%">Nickname</td>
					<td width="50%">
						<a href="{$url}ucp/settings" data-tip="Change nickname" style="float:right;margin-right:10px;"><img src="{$url}application/images/icons/pencil.png" align="absbottom" /></a>
						<a href="profile/{$id}" data-tip="View profile">{$username}</a>
					</td>
				</tr>
				<tr>
					<td width="10%"><img src="{$url}application/images/icons/world.png" /></td>
					<td width="40%">Location</td>
					<td width="50%">
						<a href="{$url}ucp/settings" data-tip="Change location" style="float:right;margin-right:10px;"><img src="{$url}application/images/icons/pencil.png" align="absbottom" /></a>
						{$location}
					</td>
				</tr>
				<tr>
					<td width="10%"><img src="{$url}application/images/icons/plugin.png" /></td>
					<td width="40%">Expansion</td>
					<td width="50%">
						<a href="{$url}ucp/expansion" data-tip="Change expansion" style="float:right;margin-right:10px;"><img src="{$url}application/images/icons/cog.png" align="absbottom" /></a>
						{$expansion}
					</td>
				</tr>
				<tr>
					<td width="10%"><img src="{$url}application/images/icons/award_star_bronze_1.png" /></td>
					<td width="40%">Account rank</td>
					<td width="50%">{$rank}</td>
				</tr>
			</table>
		</aside>

		<aside>
			<table width="100%">
				<tr data-tip="Earn voting points by voting for the server">
					<td width="10%"><img src="{$url}application/images/icons/lightning.png" /></td>
					<td width="40%">Voting points</td>
					<td width="50%">{$vp}</td>
				</tr>
				<tr data-tip="Earn donation points by donating money to the server">
					<td width="10%"><img src="{$url}application/images/icons/coins.png" /></td>
					<td width="40%">Donation points</td>
					<td width="50%">{$dp}</td>
				</tr>
				<tr>
					<td width="10%"><img src="{$url}application/images/icons/shield.png" /></td>
					<td width="40%">Account status</td>
					<td width="50%">{$status}</td>
				</tr>
				<tr>
					<td width="10%"><img src="{$url}application/images/icons/date.png" /></td>
					<td width="40%">Member since</td>
					<td width="50%">{$register_date}</td>
				</tr>
			</table>
		</aside>
	</section>

	<div class="clear"></div>	
</section>

<div class="ucp_divider"></div>

<section id="ucp_buttons">
	{if $config['vote']}
		<a href="{$url}{$config.vote}" style="background-image:url({$url}application/modules/ucp/images/vote_panel.jpg)"></a>
	{/if}

	{if $config['donate']}
	<a href="{$url}{$config.donate}" style="background-image:url({$url}application/modules/ucp/images/donate_panel.jpg)"></a>
	{/if}

	{if $config['store']}
		<a href="{$url}{$config.store}" style="background-image:url({$url}application/modules/ucp/images/item_store.jpg)"></a>
	{/if}

	{if $config['settings']}
		<a href="{$url}{$config.settings}" style="background-image:url({$url}application/modules/ucp/images/account_settings.jpg)"></a>
	{/if}

	{if $config['expansion']}
		<a href="{$url}{$config.expansion}" style="background-image:url({$url}application/modules/ucp/images/change_expansion.jpg)"></a>
	{/if}

	{if $config['teleport']}
		<a href="{$url}{$config.teleport}" style="background-image:url({$url}application/modules/ucp/images/teleport_hub.jpg)"></a>
	{/if}

	{if $is_gm && $config['gm']}
		<a href="{$url}{$config.gm}" style="background-image:url({$url}application/modules/ucp/images/gm_panel.jpg)"></a>
	{/if}
	
	{if ($is_admin || $is_owner) && $config['admin']}
		<a href="{$url}{$config.admin}" style="background-image:url({$url}application/modules/ucp/images/admin_panel.jpg)"></a>
	{/if}
	
	<div class="clear"></div>
</section>

{$characters}