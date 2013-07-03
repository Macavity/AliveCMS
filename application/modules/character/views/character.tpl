<!-- Top part -->
<section id="armory_top">
	<section id="armory_bars">
		{if $stats.maxhealth && $stats.maxhealth != "Unknown"}
			<div id="armory_health">Health: <b>{$stats.maxhealth}</b></div>
		{/if}

		
		{if $secondBarValue && $secondBarValue != "Unknown"}
			<div id="armory_{$secondBar}">{ucfirst($secondBar)}: <b>{$secondBarValue}</b></div>
		{/if}
	</section>

	<img class="avatar" src="{$url}{$avatar}"/>
	
	<section id="armory_name">
		<h1>{$name} <a href="{$url}guild/{$realmId}/{$guild}">{$guildName}</a></h1>
		<h2><b>{$level}</b> {$raceName} {$className}, <i>{$realmName}</i></h2>
	</section>

	<div class="clear"></div>
</section>

<div class="ucp_divider"></div>

<!-- Main part -->
<section id="armory" style="background-image:url({$url}application/images/armory/{$bg}.png)">
	<section id="armory_left">
		<div class="item"><a></a>{$items.head}</div>
		<div class="item"><a></a>{$items.neck}</div>
		<div class="item"><a></a>{$items.shoulders}</div>
		<div class="item"><a></a>{$items.back}</div>
		<div class="item"><a></a>{$items.chest}</div>
		<div class="item"><a></a>{$items.body}</div>
		<div class="item"><a></a>{$items.tabard}</div>
		<div class="item"><a></a>{$items.wrists}</div>
	</section>

	<!--[if LT IE 8]>
		<script type="text/javascript">
			function noIE()
			{
				if(typeof UI != "undefined")
				{
					UI.alert("The armory is not fully compatible with Internet Explorer 8 or below!");
				}
				else
				{
					setTimeout(noIE, 100);
				}
			}

			$(document).ready(function()
			{
				noIE();
			});
		</script>
	<![endif]-->

	<section id="armory_stats">
		<center id="armory_stats_top">
			{if $has_stats}<a href="javascript:void(0)" onClick="Character.tab('stats', this)" class="armory_current_tab">Attributes</a>{/if}
			{if $pvp.kills || $pvp.honor || $pvp.arena}
				<a href="javascript:void(0)" onClick="Character.tab('pvp', this)" {if !$has_stats}class="armory_current_tab"{/if}>
					Player vs Player
				</a>
			{/if}
		</center>
		
		{if $has_stats}
		<section id="tab_stats" style="display:block;">
			<div style="width:1200px;height:194px;" id="attributes_wrapper">
				<div id="tab_armory_1" style="float:left;">
					<table width="367px" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%">Strength</td>
							<td>{if strlen($stats.strength)}{$stats.strength}{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Stamina</td>
							<td>{if strlen($stats.stamina)}{$stats.stamina}{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Intellect</td>
							<td>{if strlen($stats.intellect)}{$stats.intellect}{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Spell power</td>
							<td>{if strlen($stats.spellPower)}{$stats.spellPower}{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Attack power</td>
							<td>{if strlen($stats.attackPower)}{$stats.attackPower}{else}Unknown{/if}</td>
						</tr>
					</table>

					<center id="armory_stats_next"><a href="javascript:void(0)" onClick="Character.attributes(2)">Next &rarr;</a></center>
				</div>

				<div id="tab_armory_2" style="float:left;">
					<table width="367px" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%">Resilience</td>
							<td>{if strlen($stats.resilience)}{$stats.resilience}{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Armor</td>
							<td>{if strlen($stats.armor)}{$stats.armor}{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Block</td>
							<td>{if strlen($stats.blockPct)}{$stats.blockPct}%{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Dodge</td>
							<td>{if strlen($stats.dodgePct)}{$stats.dodgePct}%{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Parry</td>
							<td>{if strlen($stats.parryPct)}{$stats.parryPct}%{else}Unknown{/if}</td>
						</tr>
					</table>

					<center id="armory_stats_next">
						<a href="javascript:void(0)" onClick="Character.attributes(1)">&larr; Previous</a>
						<a href="javascript:void(0)" onClick="Character.attributes(3)">Next &rarr;</a>
					</center>
				</div>

				<div id="tab_armory_3" style="float:left;">
					<table width="367px" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%">Crit chance</td>
							<td>{if strlen($stats.critPct)}{$stats.critPct}%{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Ranged crit chance</td>
							<td>{if strlen($stats.rangedCritPct)}{$stats.rangedCritPct}%{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Spell crit chance</td>
							<td>{if strlen($stats.spellCritPct)}{$stats.spellCritPct}%{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">Spirit</td>
							<td>{if strlen($stats.spirit)}{$stats.spirit}{else}Unknown{/if}</td>
						</tr>
						<tr>
							<td width="50%">&nbsp;</td>
							<td></td>
						</tr>
					</table>

					<center id="armory_stats_next"><a href="javascript:void(0)" onClick="Character.attributes(2)">&larr; Previous</a></center>
				</div>
			</div>
		</section>
		{/if}

		<section id="tab_pvp" {if !$has_stats}style="display:block;"{/if}>
			<table width="367px" cellspacing="0" cellpadding="0">
				{if $pvp.kills !== false}
				<tr>
					<td width="50%">Total kills</td>
					<td>{if strlen($pvp.kills)}{$pvp.kills}{else}Unknown{/if}</td>
				</tr>
				{/if}

				{if $pvp.honor !== false}
				<tr>
					<td width="50%">Honor points</td>
					<td>{if strlen($pvp.honor)}{$pvp.honor}{else}Unknown{/if}</td>
				</tr>
				{/if}

				{if $pvp.arena !== false}
				<tr>
					<td width="50%">Arena points</td>
					<td>{if strlen($pvp.arena)}{$pvp.arena}{else}Unknown{/if}</td>
				</tr>
				{/if}
			</table>
		</section>
	</section>

	<section id="armory_right">
		<div class="item"><a></a>{$items.hands}</div>
		<div class="item"><a></a>{$items.waist}</div>
		<div class="item"><a></a>{$items.legs}</div>
		<div class="item"><a></a>{$items.feet}</div>
		<div class="item"><a></a>{$items.finger1}</div>
		<div class="item"><a></a>{$items.finger2}</div>
		<div class="item"><a></a>{$items.trinket1}</div>
		<div class="item"><a></a>{$items.trinket2}</div>
	</section>

	<section id="armory_bottom">
		<div class="item"><a></a>{$items.mainhand}</div>
		<div class="item"><a></a>{$items.offhand}</div>
		<div class="item"><a></a>{$items.ranged}</div>
	</section>
</section>

<!-- Load wowhead tooltip -->
{if !$fcms_tooltip}<script type="text/javascript" src="https://static.wowhead.com/widgets/power.js"></script>{/if}