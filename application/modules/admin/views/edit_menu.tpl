<section class="box big">
	<h2>Edit link</h2>

	<form onSubmit="Menu.save(this, {$link.id}); return false" id="submit_form">
		<label for="name">Title</label>
		<input type="text" name="name" id="name" placeholder="My link" value="{$link.name}" />

		<label for="type">URL</label>
		<input type="text" name="link" id="link" placeholder="http://" value="{$link.link}"/>

		<label for="side">Menu location</label>
		<select name="side" id="side">
				<option value="top" {if $link.side == "top"}selected{/if}>Top</option>
				<option value="side" {if $link.side == "side"}selected{/if}>Side</option>
		</select>
		
		<label for="direct_link">Direct Link</label>
		<select name="direct_link" id="direct_link">
				<option value="0" {if $link.direct_link == "0"}selected{/if}>No</option>
				<option value="1" {if $link.direct_link == "1"}selected{/if}>Yes</option>
		</select>

		<label for="specific_rank">Specific user rank</label>
		<select name="specific_rank" id="specific_rank" onChange="Menu.toggleRank(this)">
			<option value="0">Allow all above the minimum (recommended)</option>
			{foreach from=$ranks item=rank}
				<option value="{$rank.id}" {if $link.specific_rank == $rank.id}selected{/if}>{$rank.rank_name}</option>
			{/foreach}
		</select>

		<div id="rank_field" {if $link.specific_rank != 0}style="display:none"{/if}>
			<label for="rank">Minimum user rank</label>
			<select name="rank" id="rank">
				{foreach from=$ranks item=rank}
					<option value="{$rank.id}" {if $link.rank == $rank.id}selected{/if}>{$rank.rank_name}</option>
				{/foreach}
			</select>
		</div>
	
		<input type="submit" value="Save link" />
	</form>
</section>