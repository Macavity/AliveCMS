<section id="store_wrapper">
	<script type="text/javascript">
		$(document).ready(function()
		{
			function checkIfLoaded()
			{
				if(typeof Store != "undefined")
				{
					Store.Customer.initialize({$vp}, {$dp});
				}
				else
				{
					setTimeout(checkIfLoaded, 50);
				}
			}

			checkIfLoaded();
		});
	</script>

	<section id="checkout"></section>

	<section id="store">
		<form onSubmit="return false">
			<section class="filter_field">
				<label for="sort_by">Sort by</label>
				<select id="sort_by" name="sort_by" onChange="Store.Filter.sort(this.value)">
					<option value="standard" selected>Default</option>
					<option value="name">Name</option>
					<option value="priceVp">Price (VP)</option>
					<option value="priceDp">Price (DP)</option>
					<option value="quality">Item quality</option>
				</select>
			</section>
			<section class="filter_field">
				<select id="item_quality" name="item_quality" onChange="Store.Filter.setQuality(this.value)">
					<option value="ALL" selected>All items</option>
					<option value="0" class="q0">Poor</option>
					<option value="1" class="q1">Common</option>
					<option value="2" class="q2">Uncommon</option>
					<option value="3" class="q3">Rare</option>
					<option value="4" class="q4">Epic</option>
					<option value="5" class="q5">Legendary</option>
					<option value="6" class="q6">Artifact</option>
					<option value="7" class="q7">Heirloom</option>
				</select>
			</section>
			<section class="filter_field">
				<input type="text" id="filter_name" placeholder="Filter by name..." onKeyUp="Store.Filter.setName(this.value)" />
			</section>
			<section class="filter_field">
				<a href="javascript:void(0)" onClick="Store.Filter.toggleVote(this)" class="nice_button nice_active">
					<img src="{$url}application/images/icons/lightning.png" align="absmiddle" /> VP
				</a>

				<a href="javascript:void(0)" onClick="Store.Filter.toggleDonate(this)" class="nice_button nice_active">
					<img src="{$url}application/images/icons/coins.png" align="absmiddle" /> DP
				</a>
			</section>
			<div class="clear"></div>
		</form>

		<div class="ucp_divider"></div>

		<section id="store_content">
			<section id="cart">
				<div class="online_realm_button">Cart (<span id="cart_item_count">0</span> items)</div>
				<div id="empty_cart">Your cart is empty</div>
				<section id="cart_items"></section>

				<div id="cart_price">
					<div id="cart_price_divider"></div>

					<a href="javascript:void(0)" onClick="Store.Cart.checkout(this)" class="nice_button">Checkout</a>

					<div id="vp_price_full">
						<img src="{$url}application/images/icons/lightning.png" align="absmiddle" /> <span id="vp_price">0</span> VP
					</div>

					<div id="dp_price_full">
						<img src="{$url}application/images/icons/coins.png" align="absmiddle" /> <span id="dp_price">0</span> DP
					</div>

					<div class="clear"></div>
				</div>
			</section>

			<section id="store_realms">
				{foreach from=$data item=realm key=realmId}
				<a href="javascript:void(0)" onClick="Store.Filter.toggle({$realmId})" class="online_realm_button">
					<div style="float:right;" id="realm_indicator_{$realmId}">[-]</div>
					{$realm.name}
				</a>

				<section class="realm_items" id="realm_items_{$realmId}">
					{if count($realm.items.groups)}
						<div style="padding:0px 12px;padding-top:12px;"><a href="javascript:void(0)" onClick="Store.toggleAllGroups(this)">{if $minimize}[+] Maximize all groups{else}[-] Minimize all groups{/if}</a></div>
					{/if}

					{foreach from=$realm.items.groups item=group}
					<div class="item_group_title"><a data-tip="Hide group" class="hide_group" href="javascript:void(0)" onClick="Store.toggleGroup(this)">{if $minimize}[+]{else}[-]{/if}</a> {$group.title}</div>
					<section class="item_group" {if $minimize}style="display:none"{/if}>
						{foreach from=$group.items item=item}
						<div class="store_item" id="item_{$item.id}">
							<section class="store_buttons">
								{if $item.vp_price}
								<a href="javascript:void(0)" onClick="Store.Cart.add({$item.id}, '{$item.itemid}', '{addslashes($item.name)}', {$item.vp_price}, 'vp', '{addslashes($realm.name)}', {$realmId}, {$item.quality}, {$item.tooltip})" class="nice_button vp_button">
									<img src="{$url}application/images/icons/lightning.png" align="absmiddle" /> <span class="vp_price_value">{$item.vp_price}</span> VP
								</a>
								{/if}

								{if $item.dp_price}
								<a href="javascript:void(0)" onClick="Store.Cart.add({$item.id}, '{$item.itemid}', '{addslashes($item.name)}', {$item.dp_price}, 'dp', '{addslashes($realm.name)}', {$realmId}, {$item.quality}, {$item.tooltip})" class="nice_button dp_button">
									<img src="{$url}application/images/icons/coins.png" align="absmiddle" /> <span class="dp_price_value">{$item.dp_price}</span> DP
								</a>
								{/if}
							</section>

							<img class="item_icon" src="https://wow.zamimg.com/images/wow/icons/medium/{$item.icon}.jpg" align="absmiddle" {if $item.tooltip}data-realm="{$item.realm}" rel="item={$item.itemid}"{/if}>
							<a {if $item.tooltip}href="{$url}item/{$item.realm}/{$item.itemid}" data-realm="{$item.realm}" rel="item={$item.itemid}"{/if} class="item_name q{$item.quality}">
								{character_limiter($item.name, 20)}
							</a>
							<br />{character_limiter($item.description, 25)}
							<div class="clear"></div>
						</div>
						{/foreach}
					</section> <!-- group end -->
					{/foreach}

					{foreach from=$realm.items.items item=item}
					<div class="store_item" id="item_{$item.id}">
						<section class="store_buttons">
							{if $item.vp_price}
								<a href="javascript:void(0)" onClick="Store.Cart.add({$item.id}, '{$item.itemid}', '{addslashes(preg_replace('/"/', "'", $item.name))}', {$item.vp_price}, 'vp', '{addslashes($realm.name)}', {$realmId}, {$item.quality}, {$item.tooltip})" class="nice_button vp_button">
									<img src="{$url}application/images/icons/lightning.png" align="absmiddle" /> <span class="vp_price_value">{$item.vp_price}</span> VP
								</a>
								{/if}

								{if $item.dp_price}
								<a href="javascript:void(0)" onClick="Store.Cart.add({$item.id}, '{$item.itemid}', '{addslashes(preg_replace('/"/', "'", $item.name))}', {$item.dp_price}, 'dp', '{addslashes($realm.name)}', {$realmId}, {$item.quality}, {$item.tooltip})" class="nice_button dp_button">
									<img src="{$url}application/images/icons/coins.png" align="absmiddle" /> <span class="dp_price_value">{$item.dp_price}</span> DP
								</a>
								{/if}
						</section>

						<img class="item_icon" src="https://wow.zamimg.com/images/wow/icons/medium/{$item.icon}.jpg" align="absmiddle" {if $item.tooltip}data-realm="{$item.realm}" rel="item={$item.itemid}"{/if}>
						<a {if $item.tooltip}href="{$url}item/{$item.realm}/{$item.itemid}" data-realm="{$item.realm}" rel="item={$item.itemid}"{/if} class="item_name q{$item.quality}">
							{character_limiter($item.name, 20)}
						</a>
						<br />{character_limiter($item.description, 25)}
						<div class="clear"></div>
					</div>
					{/foreach}

				</section> <!-- realm end -->
				{/foreach}
			</section>

			<div class="clear"></div>
		</section>

	</section>
</section>