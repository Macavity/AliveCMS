<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\sidebox_toppvp\views\realm.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6820508437c7ea7966-90554732%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a1090a6aad5c3fd0cb4f667c0ce20ef49a543ef1' => 
    array (
      0 => 'application\\modules\\sidebox_toppvp\\views\\realm.tpl',
      1 => 1343661122,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6820508437c7ea7966-90554732',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'characters' => 0,
    'character' => 0,
    'key' => 0,
    'showRace' => 0,
    'url' => 0,
    'showClass' => 0,
    'realm' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7f25416_95834031',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7f25416_95834031')) {function content_508437c7f25416_95834031($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['characters']->value){?>
	<?php  $_smarty_tpl->tpl_vars['character'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['character']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['characters']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['character']->key => $_smarty_tpl->tpl_vars['character']->value){
$_smarty_tpl->tpl_vars['character']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['character']->key;
?>
		<div class="toppvp_character">
			<div style="float:right"><?php echo $_smarty_tpl->tpl_vars['character']->value['totalKills'];?>
 kills</div>
			<b><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</b>
			<?php if ($_smarty_tpl->tpl_vars['showRace']->value){?><img align="absbottom" src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/stats/<?php echo $_smarty_tpl->tpl_vars['character']->value['race'];?>
-<?php echo $_smarty_tpl->tpl_vars['character']->value['gender'];?>
.gif" /><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['showClass']->value){?><img align="absbottom" src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/stats/<?php echo $_smarty_tpl->tpl_vars['character']->value['class'];?>
.gif" /><?php }?>
			&nbsp;&nbsp;<a data-tip="View character profile" href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
character/<?php echo $_smarty_tpl->tpl_vars['realm']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['character']->value['guid'];?>
"><?php echo $_smarty_tpl->tpl_vars['character']->value['name'];?>
</a> 
		</div>
	<?php } ?>
<?php }else{ ?>
<br />There are no PvP stats to display<br /><br />
<?php }?><?php }} ?>