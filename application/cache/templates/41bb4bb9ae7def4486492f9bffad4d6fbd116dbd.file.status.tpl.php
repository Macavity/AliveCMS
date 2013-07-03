<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:34
         compiled from "application\modules\sidebox_status\views\status.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5167508437ca0a6592-72520397%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '41bb4bb9ae7def4486492f9bffad4d6fbd116dbd' => 
    array (
      0 => 'application\\modules\\sidebox_status\\views\\status.tpl',
      1 => 1344546250,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5167508437ca0a6592-72520397',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'realms' => 0,
    'realm' => 0,
    'realmlist' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437ca136729_17480973',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437ca136729_17480973')) {function content_508437ca136729_17480973($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars['realm'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['realm']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['realms']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['realm']->key => $_smarty_tpl->tpl_vars['realm']->value){
$_smarty_tpl->tpl_vars['realm']->_loop = true;
?>
	<div class="realm">
		<div class="realm_online">
			<?php if ($_smarty_tpl->tpl_vars['realm']->value->isOnline()){?>
				<?php echo $_smarty_tpl->tpl_vars['realm']->value->getOnline();?>
 / <?php echo $_smarty_tpl->tpl_vars['realm']->value->getCap();?>

			<?php }else{ ?>
				Offline
			<?php }?>
		</div>
		<?php echo $_smarty_tpl->tpl_vars['realm']->value->getName();?>

		
		<div class="realm_bar">
			<?php if ($_smarty_tpl->tpl_vars['realm']->value->isOnline()){?>
				<div class="realm_bar_fill" style="width:<?php echo $_smarty_tpl->tpl_vars['realm']->value->getPercentage();?>
%"></div>
			<?php }?>
		</div>

		<!--
			Other values, for designers:

			$realm->getOnline("horde")
			$realm->getPercentage("horde")

			$realm->getOnline("alliance")
			$realm->getPercentage("alliance")

		-->

	</div>
<?php } ?>
<div id="realmlist">set realmlist <?php echo $_smarty_tpl->tpl_vars['realmlist']->value;?>
</div><?php }} ?>