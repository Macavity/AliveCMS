{if $is_dev}
	<div id="changelog_add">

		<form id="change_form" onSubmit="Changelog.addChange(); return false" style="display:none;">
			<input type="text" placeholder="What have you done?" id="change_text" name="change" style="width:62%" />
			<select style="width:20%" name="category" id="changelog_types">
				{foreach from=$categories item=category}
					<option value="{$category.id}">{$category.typeName}</option>
				{/foreach}
			</select>
			<input type="submit" value="Add"/>
		</form>

		{form_open('changelog/addCategory', $attributes)}
			<input type="text" placeholder="Category name" name="category" style="width:83%" />
			<input type="submit" value="Add"/>
		</form>

		<a href="javascript:void(0)" onClick="$('#category_form').hide();$('#change_form').fadeToggle(150)" class="nice_button">New change</a>
		<a href="javascript:void(0)" onClick="$('#change_form').hide();$('#category_form').fadeToggle(150)" class="nice_button">New category</a>
	</div>
{/if}

{if $changes}
<div id="changelog">
	{foreach from=$changes key=k item=change_time}
		<table class="nice_table">
			<tr>
				<td><div class="changelog_info">Changes made on {$k}</div></td>
			</tr>
			{foreach from=$change_time key=k_type item=change_type}
				
				<tr>
					<td><a>{htmlspecialchars($k_type)}</a></td>
				</tr>

				{foreach from=$change_type item=change}
					<tr>
						<td>{if $is_dev}<a href="{$url}changelog/remove/{$change.change_id}" style="display:inline !important;margin:0px !important;"><img src="{$url}application/images/icons/delete.png" align="absmiddle" /></a>{/if} &nbsp;{htmlspecialchars($change.changelog)}</td>
					</tr>
				{/foreach}
				
			{/foreach}
		</table>
	{/foreach}
</div>
{else}
	<div id="changelog">
		<center style="padding:10px;">There are no changes to show.</center>
	</div>
{/if}