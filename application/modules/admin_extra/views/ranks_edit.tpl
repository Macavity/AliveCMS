<section class="box big">
	<h2>Edit rank</h2>

	<form onSubmit="Ranks.save(this, {$rank.id}); return false" id="submit_form">
		
		<label for="rank_name">Rank name</label>
		<input type="text" id="rank_name" name="rank_name" value="{$rank.rank_name}"/>
		
		<label for="access_id">GM rank</label>
		<input type="text" id="access_id" name="access_id" value="{$rank.access_id}"/>
		
		<label>Website access</label>

		<input type="checkbox" name="is_gm" id="is_gm" {if $rank.is_gm}checked="checked"{/if} value="1"/>
		<label for="is_gm" class="inline_label" data-tip="GM panel">Game master</label>

		<input type="checkbox" name="is_dev" id="is_dev" {if $rank.is_dev}checked="checked"{/if} value="1"/>
		<label for="is_dev" class="inline_label" data-tip="Changelog">Developer</label>

		<input type="checkbox" name="is_admin" id="is_admin" {if $rank.is_admin}checked="checked"{/if} value="1"/>
		<label for="is_admin" class="inline_label" data-tip="Limited admin panel">Administrator</label>

		<input type="checkbox" name="is_owner" id="is_owner" {if $rank.is_owner}checked="checked"{/if} value="1"/>
		<label for="is_owner" class="inline_label" data-tip="Full admin panel">Owner</label>
		
		<input type="submit" value="Save rank" />
	</form>
</section>