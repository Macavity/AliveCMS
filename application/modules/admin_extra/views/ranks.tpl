<section class="box big" id="main_ranks">
	<h2>
		<img src="{$smarty.const.pageURL}application/themes/admin/images/icons/black16x16/ic_users.png"/>
		Ranks (<div style="display:inline;" id="ranks_count">{if !$ranks}0{else}{count($ranks)}{/if}</div>)
	</h2>

	<span>
		<a class="nice_button" href="javascript:void(0)" onclick="Ranks.add()">Create rank</a>
	</span>

	<ul>
		{if $ranks}
			{foreach from=$ranks item=rank}
				<li>
					<table width="100%">
						<tr>
							<td width="50%">{$rank.rank_name}</td>
							<td width="40%">{if $rank.access_id && $rank.access_id != -1}GM level: {$rank.access_id}{/if}</td>
							<td style="text-align:right;" width="10%">
								{if $rank.access_id && $rank.access_id != -1}
									<a href="{$url}admin_extra/ranks/edit/{$rank.id}" data-tip="Edit"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" /></a>&nbsp;
									<a href="javascript:void(0)" onClick="Ranks.remove({$rank.id}, this)" data-tip="Delete"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_minus.png" /></a>
								{/if}
							</td>
						</tr>
					</table>
				</li>
			{/foreach}
		{/if}
	</ul>
</section>

<section class="box big" id="add_ranks" style="display:none;">
	<h2><a href='javascript:void(0)' onClick="Ranks.add()" data-tip="Return to ranks">Ranks</a> &rarr; New rank</h2>

	<form onSubmit="Ranks.create(this); return false" id="submit_form">
		
		<label for="rank_name">Rank name</label>
		<input type="text" id="rank_name" name="rank_name"/>
		
		<label for="access_id">GM rank</label>
		<input type="text" id="access_id" name="access_id" />
		
		<label>Website access</label>

		<input type="checkbox" name="is_gm" id="is_gm" value="1"/>
		<label for="is_gm" class="inline_label" data-tip="GM panel">Game master</label>

		<input type="checkbox" name="is_dev" id="is_dev" value="1"/>
		<label for="is_dev" class="inline_label" data-tip="Changelog">Developer</label>

		<input type="checkbox" name="is_admin" id="is_admin" value="1"/>
		<label for="is_admin" class="inline_label" data-tip="Limited admin panel">Administrator</label>

		<input type="checkbox" name="is_owner" id="is_owner" value="1"/>
		<label for="is_owner" class="inline_label" data-tip="Full admin panel">Owner</label>
		
		<input type="submit" value="Submit rank" />
	</form>
</section>