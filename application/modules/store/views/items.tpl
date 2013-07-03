<section class="box big" id="main_item">
	<h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_tag.png"/>
		Items (<div style="display:inline;" id="item_count">{if !$items}0{else}{count($items)}{/if}</div>)
	</h2>

	<span>
		<a class="nice_button" href="javascript:void(0)" onClick="Items.add()">Create item</a>&nbsp;
		<a class="nice_button" href="javascript:void(0)" onClick="Items.addGroup()">Create group</a>
	</span>

	<ul id="item_list">
		{if $items}
		{foreach from=$items item=item}
			<li>
				<table width="100%">
					<tr>
						<td width="5%"><img style="opacity:1;" src="https://wow.zamimg.com/images/wow/icons/small/{$item.icon}.jpg" /></td>
						<td width="30%" data-tip="{$item.description}"><b class="q{$item.quality}">{character_limiter($item.name, 20)}</b></td>
						<td width="20%" {if array_key_exists("title", $item) && $item.title}class="item_group"{/if}>
							{if array_key_exists("title", $item) && $item.title}
								<div class="group_actions" style="display:none;">
									<a href="javascript:void(0)" onClick="Items.renameGroup({$item.group}, this)" data-tip="Rename group"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" /></a>&nbsp;
									<a href="javascript:void(0)" onClick="Items.removeGroup({$item.group}, this, true)" data-tip="Delete group and all of it's items">
										<img src="{$url}application/themes/admin/images/icons/black16x16/ic_minus.png" />
									</a>
								</div>
								<div class="group_title">{$item.title}</div>
							{/if}
						</td>
						<td width="30%">
							{if $item.vp_price}
								<img src="{$url}application/images/icons/lightning.png" style="opacity:1;margin-top:3px;position:absolute;" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$item.vp_price} VP
							{/if}
							{if $item.dp_price}
								<img src="{$url}application/images/icons/coins.png" style="opacity:1;margin-top:3px;position:absolute;"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								{$item.dp_price} DP
							{/if}
						</td>
						<td style="text-align:right;">
							<a href="{$url}store/admin_items/edit/{$item.id}" data-tip="Edit"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" /></a>&nbsp;
							<a href="javascript:void(0)" onClick="Items.remove({$item.id}, this)" data-tip="Delete"><img src="{$url}application/themes/admin/images/icons/black16x16/ic_minus.png" /></a>
						</td>
					</tr>
				</table>
			</li>
		{/foreach}
		{/if}
	</ul>
</section>

<section class="box big" id="add_item" style="display:none;">
	<h2><a href='javascript:void(0)' onClick="Items.add()" data-tip="Return to items">Items</a> &rarr; New item</h2>

	<form>
		<label for="item_type">Item type</label>
		<select id="item_type" name="item_type" onChange="Items.changeType(this)">
			<option value="item" selected>Item</option>
			<option value="query">Query</option>
		</select>
	</form>

	<form onSubmit="Items.create(this); return false" id="query_form" style="display:none;">

		<label for="name">Name</label>
		<input type="text" name="name" id="name" />

		<label for="description">Description (very short; displayed below item name)</label>
		<input type="text" name="description" id="description" />

		<label for="quality">Item quality</label>
		<select id="quality" name="quality">
			<option value="0" class="q0">Poor</option>
			<option value="1" class="q1">Common</option>
			<option value="2" class="q2">Uncommon</option>
			<option value="3" class="q3">Rare</option>
			<option value="4" class="q4">Epic</option>
			<option value="5" class="q5">Legendary</option>
			<option value="6" class="q6">Artifact</option>
			<option value="7" class="q7">Heirloom</option>
		</select>

		<label for="query_database">Database</label>
		<select id="query_database" name="query_database">
			<option value="cms">CMS</option>
			<option value="realm">Realm</option>
			<option value="realmd">Realmd (Accounts/Logon)</option>
		</select>

		<label>Need character</label>
		<input type="checkbox" id="query_need_character" name="query_need_character" checked="yes" value="1"/>
		<label for="query_need_character" class="inline_label">Make the user select a character</label>

		<label for="query">SQL query</label>
		<textarea id="query" name="query"></textarea>
		<span>
			{literal}
				<b>{ACCOUNT}</b> = Account ID, 
				<b>{CHARACTER}</b> = Character ID, 
				<b>{REALM}</b> = Realm ID
			{/literal}
		</span>

		<label for="realm">Realm</label>
		<select name="realm" id="realm">
			{foreach from=$realms item=realm}
				<option value="{$realm->getId()}">{$realm->getName()}</option>
			{/foreach}
		</select>

		<label for="group">Item group</label>
		<select name="group" id="group">
			<option value="0">None</option>
			{foreach from=$groups item=group}
				<option value="{$group.id}">{$group.title}</option>
			{/foreach}
		</select>

		<div class="vp_price">
			<label for="vpCost">VP price</label>
			<input type="text" name="vpCost" id="vpCost" value="0"/>
		</div>

		<div class="dp_price">
			<label for="dpCost">DP price</label>
			<input type="text" name="dpCost" id="dpCost" value="0"/>
		</div>

		<label for="icon">Icon name</label>
		<input type="text" name="icon" id="icon" value="inv_misc_questionmark" />

		<input type="submit" value="Submit query" />
	</form>

	<form onSubmit="Items.create(this); return false" id="item_form">

		<label for="name">Name (only required for multiple items)</label>
		<input type="text" name="name" id="name" placeholder="Will be added automatically if you only specify one item ID" />

		<label for="itemid">Item ID (tip: separate ids with , (comma) to add multiple as one)</label>
		<input type="text" name="itemid" id="itemid" placeholder="12345" />

		<label for="description">Description (very short; displayed below item name)</label>
		<input type="text" name="description" id="description" placeholder="For example, 'Head (Plate)'" />

		<label for="realm">Realm</label>
		<select name="realm" id="realm">
			{foreach from=$realms item=realm}
				<option value="{$realm->getId()}">{$realm->getName()}</option>
			{/foreach}
		</select>

		<label for="group">Item group</label>
		<select name="group" id="group">
			<option value="0">None</option>
			{foreach from=$groups item=group}
				<option value="{$group.id}">{$group.title}</option>
			{/foreach}
		</select>

		<div class="vp_price">
			<label for="vpCost">VP price</label>
			<input type="text" name="vpCost" id="vpCost" value="0"/>
		</div>

		<div class="dp_price">
			<label for="dpCost">DP price</label>
			<input type="text" name="dpCost" id="dpCost" value="0"/>
		</div>

		<input type="submit" value="Submit item" />
	</form>
</section>

<section class="box big" id="add_group" style="display:none;">
	<h2><a href='javascript:void(0)' onClick="Items.addGroup()" data-tip="Return to items">Items</a> &rarr; New group</h2>

	<form onSubmit="Items.create(this, true); return false">
		<label for="title">Group name</label>
		<input type="text" name="title" id="title" />

		<input type="submit" value="Submit group" />
	</form>
</section>