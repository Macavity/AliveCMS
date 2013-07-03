<section class="box big">
	<h2>Edit sidebox</h2>

	<form onSubmit="Sidebox.save(this, {$sidebox.id}); return false" id="submit_form">
		<label for="displayName">Headline</label>
		<input type="text" name="displayName" id="displayName" value="{$sidebox.displayName}"/>

		<label for="type">Sidebox module</label>
		<select id="type" name="type" onChange="Sidebox.toggleCustom(this)">
			{foreach from=$sideboxModules item=module key=name}
				<option value="{$name}" {if $sidebox.type == preg_replace("/sidebox_/", "", $name)}selected{/if}>{$module.name}</option>
			{/foreach}
		</select>
        
        <label for="page">Page</label>
        <input type="text" name="page" id="page" value="{$sidebox.page}" />

		<label for="css_id">CSS ID (optional)</label>
        <input type="text" name="css_id" id="css_id" value="{$sidebox.css_id}" />

        <label for="rank_needed">Minimum user rank</label>
		<select name="rank_needed" id="rank_needed">
			{foreach from=$ranks item=rank}
				<option value="{$rank.id}" {if $sidebox.rank_needed == $rank.id}selected{/if}>{$rank.rank_name}</option>
			{/foreach}
		</select>
	</form>

	<span id="custom_field" style="padding-top:0px;padding-bottom:0px;{if $sidebox.type != "custom"}display:none{/if}" >
		<label for="text">Content</label>
		{$fusionEditor}
	</span>

	<form onSubmit="Sidebox.save(document.getElementById('submit_form'), {$sidebox.id}); return false">
		<input type="submit" value="Save sidebox" />
	</form>
</section>