<section class="box big" id="main_sidebox">
	<h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_grid.png"/>
		Sideboxes (<div style="display:inline;" id="sidebox_count">{if !$sideboxes}0{else}{count($sideboxes)}{/if}</div>)
	</h2>

	<span>
		<a class="nice_button" href="javascript:void(0)" onClick="Sidebox.add()">Create sidebox</a>
	</span>

	<ul id="sidebox_list">
		{if $sideboxes}
		{foreach from=$sideboxes item=sidebox}
			<li>
				<table width="100%">
					<tr>
						<td width="45"><a href="javascript:void(0)" onClick="Sidebox.move('up', {$sidebox.id}, this)" data-tip="Move up"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_up.png" /></a>
							<a href="javascript:void(0)" onClick="Sidebox.move('down', {$sidebox.id}, this)" data-tip="Move down"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_down.png" /></a></td>
						<td width="150">{$sidebox.name}</td>
                        <td><b>{$sidebox.displayName}</b></td>
						<td width="100">{$sidebox.page}</td>
                        <td width="50">{$sidebox.rank_name}</td>
						<td width="50" style="text-align:right;">
							<a href="{$url}admin/sidebox/edit/{$sidebox.id}" data-tip="Edit"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" /></a>&nbsp;
							<a href="javascript:void(0)" onClick="Sidebox.remove({$sidebox.id}, this)" data-tip="Delete"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_minus.png" /></a>
						</td>
					</tr>
				</table>
			</li>
		{/foreach}
		{/if}
	</ul>
</section>

<section class="box big" id="add_sidebox" style="display:none;">
	<h2><a href='javascript:void(0)' onClick="Sidebox.add()" data-tip="Return to sideboxes">Sideboxes</a> &rarr; New sidebox</h2>

	<form onSubmit="Sidebox.create(this); return false" id="submit_form">
		<label for="displayName">Headline</label>
		<input type="text" name="displayName" id="displayName" />

		<label for="type">Sidebox module</label>
		<select id="type" name="type" onChange="Sidebox.toggleType(this)">
			{foreach from=$sideboxModules item=module key=name}
				<option value="{$name}">{$module.name}</option>
			{/foreach}
		</select>
        
        <label for="page">Page</label>
        <input type="text" name="page" id="page" />

        <label for="css_id">CSS ID (optional)</label>
        <input type="text" name="css_id" id="css_id" />

		<label for="rank_needed">Minimum user rank</label>
		<select name="rank_needed" id="rank_needed">
			{foreach from=$ranks item=rank}
				<option value="{$rank.id}">{$rank.rank_name}</option>
			{/foreach}
		</select>
	</form>

	<span id="custom_field" style="padding-top:0px;padding-bottom:0px;">
		<label for="text">Content</label>
		{$fusionEditor}
	</span>

	<form onSubmit="Sidebox.create(document.getElementById('submit_form')); return false">
		<input type="submit" value="Submit sidebox" />
	</form>
</section>