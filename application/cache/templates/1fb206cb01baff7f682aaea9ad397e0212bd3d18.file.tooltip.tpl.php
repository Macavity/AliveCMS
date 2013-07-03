<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:35
         compiled from "application\modules\tooltip\views\tooltip.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6716508437cb6a4ec7-86231671%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1fb206cb01baff7f682aaea9ad397e0212bd3d18' => 
    array (
      0 => 'application\\modules\\tooltip\\views\\tooltip.tpl',
      1 => 1347359725,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6716508437cb6a4ec7-86231671',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'item' => 0,
    'attribute' => 0,
    'spell' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437cb8f2739_17934476',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437cb8f2739_17934476')) {function content_508437cb8f2739_17934476($_smarty_tpl) {?><div style="max-width:350px;">
<?php if ($_smarty_tpl->tpl_vars['item']->value['specialColor']){?>
<span class='q<?php echo $_smarty_tpl->tpl_vars['item']->value['quality'];?>
' style='color:<?php echo $_smarty_tpl->tpl_vars['item']->value['specialColor'];?>
; font-size: 16px; '><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</span><br />
<?php }else{ ?>
<span class='q<?php echo $_smarty_tpl->tpl_vars['item']->value['quality'];?>
' style='font-size: 16px'><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</span><br />
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['item']->value['bind']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['bind'];?>
<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['unique']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['unique'];?>
<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['slot']){?><div style='float:left;'><?php echo $_smarty_tpl->tpl_vars['item']->value['slot'];?>
</div><?php }?>
<div style='float:right;'><?php echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
</div>
<div style="clear:both;"></div>
<?php if ($_smarty_tpl->tpl_vars['item']->value['armor']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['armor'];?>
 Armor<br /><?php }?>

<?php if ($_smarty_tpl->tpl_vars['item']->value['damage_min']){?>
	<div style='float:left;'><?php echo $_smarty_tpl->tpl_vars['item']->value['damage_min'];?>
 - <?php echo $_smarty_tpl->tpl_vars['item']->value['damage_max'];?>
 <?php echo $_smarty_tpl->tpl_vars['item']->value['damage_type'];?>
 Damage</div>
	<div style='float:right;margin-left:15px;'>Speed <?php echo $_smarty_tpl->tpl_vars['item']->value['speed'];?>
</div><br />
	(<?php echo $_smarty_tpl->tpl_vars['item']->value['dps'];?>
 damage per second)<br />
<?php }?>

<?php if (count($_smarty_tpl->tpl_vars['item']->value['attributes']['regular'])>0){?>
	<?php  $_smarty_tpl->tpl_vars['attribute'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attribute']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['item']->value['attributes']['regular']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attribute']->key => $_smarty_tpl->tpl_vars['attribute']->value){
$_smarty_tpl->tpl_vars['attribute']->_loop = true;
?>
		<?php echo $_smarty_tpl->tpl_vars['attribute']->value['text'];?>

	<?php } ?>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['item']->value['holy_res']){?>+ <?php echo $_smarty_tpl->tpl_vars['item']->value['holy_res'];?>
 Holy Resistance<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['nature_res']){?>+ <?php echo $_smarty_tpl->tpl_vars['item']->value['nature_res'];?>
 Nature Resistance<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['fire_res']){?>+ <?php echo $_smarty_tpl->tpl_vars['item']->value['fire_res'];?>
 Fire Resistance<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['frost_res']){?>+ <?php echo $_smarty_tpl->tpl_vars['item']->value['frost_res'];?>
 Frost Resistance<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['shadow_res']){?>+ <?php echo $_smarty_tpl->tpl_vars['item']->value['shadow_res'];?>
 Shadow Resistance<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['arcane_res']){?>+ <?php echo $_smarty_tpl->tpl_vars['item']->value['arcane_res'];?>
 Arcane Resistance<br /><?php }?>

<?php if ($_smarty_tpl->tpl_vars['item']->value['sockets']){?><?php echo $_smarty_tpl->tpl_vars['item']->value['sockets'];?>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['item']->value['durability']){?>Durability <?php echo $_smarty_tpl->tpl_vars['item']->value['durability'];?>
 / <?php echo $_smarty_tpl->tpl_vars['item']->value['durability'];?>
<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['required']){?>Requires Level <?php echo $_smarty_tpl->tpl_vars['item']->value['required'];?>
<br /><?php }?>
<?php if ($_smarty_tpl->tpl_vars['item']->value['level']){?>Item Level <?php echo $_smarty_tpl->tpl_vars['item']->value['level'];?>
<br /><?php }?>

<?php if (count($_smarty_tpl->tpl_vars['item']->value['attributes']['spells'])>0){?>
	<?php  $_smarty_tpl->tpl_vars['attribute'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attribute']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['item']->value['attributes']['spells']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attribute']->key => $_smarty_tpl->tpl_vars['attribute']->value){
$_smarty_tpl->tpl_vars['attribute']->_loop = true;
?>
		<?php echo $_smarty_tpl->tpl_vars['attribute']->value['text'];?>

	<?php } ?>
<?php }?>

<?php if (count($_smarty_tpl->tpl_vars['item']->value['spells'])>0){?>
	<?php  $_smarty_tpl->tpl_vars['spell'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['spell']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['item']->value['spells']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['spell']->key => $_smarty_tpl->tpl_vars['spell']->value){
$_smarty_tpl->tpl_vars['spell']->_loop = true;
?>
		<a class="q2" href="https://wowhead.com/?spell=<?php echo $_smarty_tpl->tpl_vars['spell']->value['id'];?>
" target="_blank">
			<?php echo $_smarty_tpl->tpl_vars['spell']->value['trigger'];?>

		
			<?php if (!strlen($_smarty_tpl->tpl_vars['spell']->value['text'])){?>
				Unknown effect
			<?php }else{ ?>
				<?php echo $_smarty_tpl->tpl_vars['spell']->value['text'];?>

			<?php }?>
		</a>
		<br />
	<?php } ?>
<?php }?>
</div><?php }} ?>