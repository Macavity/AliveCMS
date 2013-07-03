<script type="text/javascript">
	var customPages = JSON.parse('{json_encode($pages)}');
</script>

<section class="box big" id="main_link">
	<h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_list.png"/>
		Menu links (<div style="display:inline;" id="link_count">{if !$links}0{else}{count($links)}{/if}</div>)
	</h2>

	<span>
		<a class="nice_button" href="javascript:void(0)" onClick="Menu.add()">Create link</a>
	</span>

	<ul id="link_list">
		{if $links}
		{foreach from=$links item=link}
			<li>
				<table width="100%">
					<tr>
						<td width="10%"><a href="javascript:void(0)" onClick="Menu.move('up', {$link.id}, this)" data-tip="Move up"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_up.png" /></a>
							<a href="javascript:void(0)" onClick="Menu.move('down', {$link.id}, this)" data-tip="Move down"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_down.png" /></a></td>
						<td width="30%"><span style="font-size:10px;padding:0px;display:inline;">{$link.side}&nbsp;&nbsp;</span> <b>{$link.name}</b></td>
						<td width="20%"><a href="{$link.link}" target="_blank">{$link.link_short}</a></td>
						<td width="30%">{if $link.specific_rank == 0}{$link.rank_name} or higher{else}{$link.specific_rank_name} only{/if}</td>
						<td style="text-align:right;">
							<a href="{$url}admin/menu/edit/{$link.id}" data-tip="Edit"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" /></a>&nbsp;
							<a href="javascript:void(0)" onClick="Menu.remove({$link.id}, this)" data-tip="Delete"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_minus.png" /></a>
						</td>
					</tr>
				</table>
			</li>
		{/foreach}
		{/if}
	</ul>
</section>

<section class="box big" id="add_link" style="display:none;">
	<h2><a href='javascript:void(0)' onClick="Menu.add()" data-tip="Return to menu links">Menu links</a> &rarr; New link</h2>

	<form onSubmit="Menu.create(this); return false" id="submit_form">
		<label for="name">Title</label>
		<input type="text" name="name" id="name" placeholder="My link" />

		<label for="type">URL (or <a href="javascript:void(0)" onClick="Menu.selectCustom()">select from custom pages</a>)</label>
		<input type="text" name="link" id="link" placeholder="http://"/>

		<label for="side">Menu location</label>
		<select name="side" id="side">
				<option value="top">Top</option>
				<option value="side">Side</option>
		</select>
		
		<label for="direct_link">Direct Link?</label>
		<select name="direct_link" id="direct_link">
				<option value="0">No</option>
				<option value="1">Yes</option>
		</select>

		<label for="specific_rank">Specific user rank</label>
		<select name="specific_rank" id="specific_rank" onChange="Menu.toggleRank(this)">
			<option value="0">Allow all above the minimum (recommended)</option>
			{foreach from=$ranks item=rank}
				<option value="{$rank.id}">{$rank.rank_name}</option>
			{/foreach}
		</select>

		<div id="rank_field">
			<label for="rank">Minimum user rank</label>
			<select name="rank" id="rank">
				{foreach from=$ranks item=rank}
					<option value="{$rank.id}">{$rank.rank_name}</option>
				{/foreach}
			</select>
		</div>
	
		<input type="submit" value="Submit link" />
	</form>
</section>