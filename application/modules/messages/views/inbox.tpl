<div id="pm_controls">
	<div id="pm_controls_right">
		<a href="{$url}messages/create" class="nice_button">Compose a message</a>
		<a href="javascript:void(0)" onClick="Messages.clearInbox()" class="nice_button" id="pm_empty">Empty inbox</a>
	</div>
	
	<a href="javascript:void(0)" onClick="Messages.showTab('inbox', this)" class="nice_button {if !$is_sent}nice_active{/if}">Inbox ({$inbox_count})</a>
	<a href="javascript:void(0)" onClick="Messages.showTab('sent', this)" class="nice_button {if $is_sent}nice_active{/if}">Sent ({$sent_count})</a>
</div>
<div class="ucp_divider"></div>

<div id="pm_inbox" class="pm_spot" {if $is_sent}style="display:none;"{/if}>
	{if $messages}
		<table class="nice_table" width="100%">
			<tr>
				<td width="18%">Sender</td>
				<td>Message title</td>
				<td width="18%" align="center">Date</td>
			</tr>
			{foreach from=$messages item=message}
				<tr>
					<td><a href="{$url}profile/{$message.sender_id}" data-tip="View profile">{$message.sender_name}</td>
					<td><a href="{$url}messages/read/{$message.id}" data-tip="Read message" {if $message.read == 0}class="pm_new"{/if}>{$message.title}</a></td>
					<td align="center">{date("Y-m-d", $message.time)}</td>
				</tr>
			{/foreach}
		</table>
		<div style="height:10px;"></div>
		{$pagination}
	{else}
		<div style="text-align:center;padding:10px;">You have no messages.</div>
	{/if}
</div>

<div id="pm_sent" class="pm_spot" {if !$is_sent}style="display:none;"{/if}>
	{if $sent}
		<table class="nice_table" width="100%">
			<tr>
				<td width="18%">Receiver</td>
				<td>Message title</td>
				<td width="18%" align="center">Date</td>
			</tr>
			{foreach from=$sent item=message}
				<tr>
					<td><a href="{$url}profile/{$message.user_id}" data-tip="View profile">{$message.receiver_name}</td>
					<td><a href="{$url}messages/read/{$message.id}" data-tip="Read message">{$message.title}</a></td>
					<td align="center">{date("Y-m-d", $message.time)}</td>
				</tr>
			{/foreach}
		</table>
		<div style="height:10px;"></div>
		{$sent_pagination}
	{else}
		<div style="text-align:center;padding:10px;">You have no messages.</div>
	{/if}
</div>