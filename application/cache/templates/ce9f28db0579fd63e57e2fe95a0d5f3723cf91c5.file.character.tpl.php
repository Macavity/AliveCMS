<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:33
         compiled from "application\modules\character\views\character.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16910508437c958d9d1-42233005%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce9f28db0579fd63e57e2fe95a0d5f3723cf91c5' => 
    array (
      0 => 'application\\modules\\character\\views\\character.tpl',
      1 => 1350480908,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16910508437c958d9d1-42233005',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'stats' => 0,
    'secondBarValue' => 0,
    'secondBar' => 0,
    'url' => 0,
    'avatar' => 0,
    'name' => 0,
    'realmId' => 0,
    'guild' => 0,
    'guildName' => 0,
    'level' => 0,
    'raceName' => 0,
    'className' => 0,
    'realmName' => 0,
    'bg' => 0,
    'items' => 0,
    'has_stats' => 0,
    'pvp' => 0,
    'fcms_tooltip' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c98a3929_27390418',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c98a3929_27390418')) {function content_508437c98a3929_27390418($_smarty_tpl) {?><!-- Top part -->
<section id="armory_top">
	<section id="armory_bars">
		<?php if ($_smarty_tpl->tpl_vars['stats']->value['maxhealth']&&$_smarty_tpl->tpl_vars['stats']->value['maxhealth']!="Unknown"){?>
			<div id="armory_health">Health: <b><?php echo $_smarty_tpl->tpl_vars['stats']->value['maxhealth'];?>
</b></div>
		<?php }?>

		
		<?php if ($_smarty_tpl->tpl_vars['secondBarValue']->value&&$_smarty_tpl->tpl_vars['secondBarValue']->value!="Unknown"){?>
			<div id="armory_<?php echo $_smarty_tpl->tpl_vars['secondBar']->value;?>
"><?php echo ucfirst($_smarty_tpl->tpl_vars['secondBar']->value);?>
: <b><?php echo $_smarty_tpl->tpl_vars['secondBarValue']->value;?>
</b></div>
		<?php }?>
	</section>

	<img class="avatar" src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/avatars/<?php echo $_smarty_tpl->tpl_vars['avatar']->value;?>
.gif"/>
	
	<section id="armory_name">
		<h1><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
 <a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
guild/<?php echo $_smarty_tpl->tpl_vars['realmId']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['guild']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['guildName']->value;?>
</a></h1>
		<h2><b><?php echo $_smarty_tpl->tpl_vars['level']->value;?>
</b> <?php echo $_smarty_tpl->tpl_vars['raceName']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['className']->value;?>
, <i><?php echo $_smarty_tpl->tpl_vars['realmName']->value;?>
</i></h2>
	</section>

	<div class="clear"></div>
</section>

<div class="ucp_divider"></div>

<!-- Main part -->
<section id="armory" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/armory/<?php echo $_smarty_tpl->tpl_vars['bg']->value;?>
.png)">
	<section id="armory_left">
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['head'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['neck'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['shoulders'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['back'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['chest'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['body'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['tabard'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['wrists'];?>
</div>
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
			<?php if ($_smarty_tpl->tpl_vars['has_stats']->value){?><a href="javascript:void(0)" onClick="Character.tab('stats', this)" class="armory_current_tab">Attributes</a><?php }?>
			<a href="javascript:void(0)" onClick="Character.tab('pvp', this)" <?php if (!$_smarty_tpl->tpl_vars['has_stats']->value){?>class="armory_current_tab"<?php }?>>Player vs Player</a>
		</center>
		
		<?php if ($_smarty_tpl->tpl_vars['has_stats']->value){?>
		<section id="tab_stats" style="display:block;">
			<div style="width:1200px;height:194px;" id="attributes_wrapper">
				<div id="tab_armory_1" style="float:left;">
					<table width="367px" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%">Strength</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['strength'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['strength'];?>
<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Stamina</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['stamina'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['stamina'];?>
<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Intellect</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['intellect'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['intellect'];?>
<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Spell power</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['spellPower'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['spellPower'];?>
<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Attack power</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['attackPower'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['attackPower'];?>
<?php }else{ ?>Unknown<?php }?></td>
						</tr>
					</table>

					<center id="armory_stats_next"><a href="javascript:void(0)" onClick="Character.attributes(2)">Next &rarr;</a></center>
				</div>

				<div id="tab_armory_2" style="float:left;">
					<table width="367px" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%">Resilience</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['resilience'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['resilience'];?>
<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Armor</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['armor'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['armor'];?>
<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Block</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['blockPct'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['blockPct'];?>
%<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Dodge</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['dodgePct'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['dodgePct'];?>
%<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Parry</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['parryPct'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['parryPct'];?>
%<?php }else{ ?>Unknown<?php }?></td>
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
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['critPct'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['critPct'];?>
%<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Ranged crit chance</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['rangedCritPct'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['rangedCritPct'];?>
%<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Spell crit chance</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['spellCritPct'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['spellCritPct'];?>
%<?php }else{ ?>Unknown<?php }?></td>
						</tr>
						<tr>
							<td width="50%">Spirit</td>
							<td><?php if (strlen($_smarty_tpl->tpl_vars['stats']->value['spirit'])){?><?php echo $_smarty_tpl->tpl_vars['stats']->value['spirit'];?>
<?php }else{ ?>Unknown<?php }?></td>
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
		<?php }?>

		<section id="tab_pvp" <?php if (!$_smarty_tpl->tpl_vars['has_stats']->value){?>style="display:block;"<?php }?>>
			<table width="367px" cellspacing="0" cellpadding="0">
				<tr>
					<td width="50%">Total kills</td>
					<td><?php if (strlen($_smarty_tpl->tpl_vars['pvp']->value['kills'])){?><?php echo $_smarty_tpl->tpl_vars['pvp']->value['kills'];?>
<?php }else{ ?>Unknown<?php }?></td>
				</tr>

				<?php if ($_smarty_tpl->tpl_vars['pvp']->value['honor']!==false){?>
				<tr>
					<td width="50%">Honor points</td>
					<td><?php if (strlen($_smarty_tpl->tpl_vars['pvp']->value['honor'])){?><?php echo $_smarty_tpl->tpl_vars['pvp']->value['honor'];?>
<?php }else{ ?>Unknown<?php }?></td>
				</tr>
				<?php }?>

				<?php if ($_smarty_tpl->tpl_vars['pvp']->value['arena']!==false){?>
				<tr>
					<td width="50%">Arena points</td>
					<td><?php if (strlen($_smarty_tpl->tpl_vars['pvp']->value['arena'])){?><?php echo $_smarty_tpl->tpl_vars['pvp']->value['arena'];?>
<?php }else{ ?>Unknown<?php }?></td>
				</tr>
				<?php }?>
			</table>
		</section>
	</section>

	<section id="armory_right">
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['hands'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['waist'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['legs'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['feet'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['finger1'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['finger2'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['trinket1'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['trinket2'];?>
</div>
	</section>

	<section id="armory_bottom">
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['mainhand'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['offhand'];?>
</div>
		<div class="item"><a></a><?php echo $_smarty_tpl->tpl_vars['items']->value['ranged'];?>
</div>
	</section>
</section>

<!-- Load wowhead tooltip -->
<?php if (!$_smarty_tpl->tpl_vars['fcms_tooltip']->value){?><script type="text/javascript" src="https://static.wowhead.com/widgets/power.js"></script><?php }?><?php }} ?>