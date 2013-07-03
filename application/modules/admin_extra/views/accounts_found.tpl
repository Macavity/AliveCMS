<form onSubmit="Accounts.save(this, {$external_details.id}); return false" id="submit_form">
	<label>Account</label>
	({$external_details.id}) <b>{$external_details.username}</b>

	<label>Last log in</label>
	<b>{$external_details.last_login}</b> by <b>{$external_details.last_ip}</b>

	<label for="vp">VP</label>
	<input type="text" id="vp" name="vp" value="{$internal_details.vp}" />

	<label for="dp">DP</label>
	<input type="text" id="dp" name="dp" value="{$internal_details.dp}" />

	<label for="nickname">Nickname</label>
	<input type="text" id="nickname" name="nickname" value="{$internal_details.nickname}" />

	<label for="email">Email</label>
	<input type="text" id="email" name="email" value="{$external_details.email}" />

	<label for="expansion">Expansion</label>
	<select id="expansion" name="expansion">
		{foreach from=$expansions key=id item=expansion}
			<option value="{$id}" {if $external_details.expansion == $id}selected{/if}>{$expansion}</option>
		{/foreach}
	</select>

	<label for="password">Change password</label>
	<input type="text" id="password" name="password" placeholder="Enter a new password"/>

	<label for="gm_level">GM level</label>
	<input type="text" id="gm_level" name="gm_level" value="{$access_id.gmlevel}" />

	<input type="submit" value="Save account" />
</form>