<div id="gm">
	<div id="top_tools">
		{if $hasConsole}
		<a href="javascript:void(0)" onClick="Gm.kick({$realmId})" class="nice_button">
			<img src="{$url}application/images/icons/door_out.png" align="absmiddle">
			Kick a player
		</a>
		{/if}
		<a href="javascript:void(0)" onClick="Gm.ban()" class="nice_button">
			<img src="{$url}application/images/icons/cross.png" align="absmiddle">
			Ban an account
		</a>
	</div>
	{if $tickets}
		{foreach from=$tickets item=ticket}
		<div class="gm_ticket">
			<div class="gm_ticket_info">
				<table class="nice_table" cellspacing="0" cellpadding="0">
					<tr>
						<td width="30%">Ticket</td>
						<td width="25%">Time</td>
						<td width="30%">Message</td>
						<td>&nbsp;</td>
					</tr>

					<tr>
						<td>#{$ticket.ticketId} by <a href="{$url}character/{$realmId}/{$ticket.guid}" target="_blank">{$ticket.name}</a></td>
						<td>{$ticket.ago}</td>
						<td>{$ticket.message_short}</td>
						<td style="text-align:right;">
							<a class="nice_button" onClick="Gm.view(this)" href="javascript:void(0)"><img src="{$url}application/images/icons/bullet_toggle_plus.png" align="absmiddle"> View</a>
						</td>
					</tr>
				</table>
			</div>

			<div class="gm_ticket_info_full">
				<table class="nice_table" cellspacing="0" cellpadding="0">
					<tr>
						<td width="30%">Ticket</td>
						<td width="25%">Time</td>
						<td>&nbsp;</td>
					</tr>

					<tr>
						<td>#{$ticket.ticketId} by <a href="{$url}character/{$realmId}/{$ticket.guid}" target="_blank">{$ticket.name}</a></td>
						<td>{$ticket.ago}</td>
						<td style="text-align:right;">
							<a class="nice_button" onClick="Gm.hide(this)" href="javascript:void(0)"><img src="{$url}application/images/icons/bullet_toggle_minus.png" align="absmiddle"> Hide</a>
						</td>
					</tr>
				</table>
				<div class="gm_ticket_text">{$ticket.message}</div>
			</div>

			<div class="gm_tools">
				<a href="javascript:void(0)" onClick="Gm.close({$realmId}, {$ticket.ticketId}, this)" class="nice_button"><img src="{$url}application/images/icons/accept.png" align="absmiddle"> Close ticket</a>
				<a href="javascript:void(0)" onClick="Gm.answer({$realmId}, {$ticket.ticketId}, this)" class="nice_button"><img src="{$url}application/images/icons/email.png" align="absmiddle"> Answer</a>
				<a href="javascript:void(0)" onClick="Gm.unstuck({$realmId}, {$ticket.ticketId}, this)" class="nice_button"><img src="{$url}application/images/icons/wand.png" align="absmiddle"> Unstuck</a>
				<a href="javascript:void(0)" onClick="Gm.sendItem({$realmId}, {$ticket.ticketId}, this)" class="nice_button"><img src="{$url}application/images/icons/lorry.png" align="absmiddle"> Send item</a>
			</div>
		</div>
		{/foreach}
	{else}
		<div style="padding:20px;">No tickets</div>
	{/if}
</div>