<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\sidebox_shoutbox\views\shouts.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14289508437c7d93b62-21078963%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aac2d9a38bb8694289f913ed516e64ec0e146671' => 
    array (
      0 => 'application\\modules\\sidebox_shoutbox\\views\\shouts.tpl',
      1 => 1341232518,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14289508437c7d93b62-21078963',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'shouts' => 0,
    'shout' => 0,
    'user_is_gm' => 0,
    'url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7e0f448_42521537',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7e0f448_42521537')) {function content_508437c7e0f448_42521537($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['shouts']->value){?>
	<?php  $_smarty_tpl->tpl_vars['shout'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['shout']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['shouts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['shout']->key => $_smarty_tpl->tpl_vars['shout']->value){
$_smarty_tpl->tpl_vars['shout']->_loop = true;
?>
		<div class="shout">
			<span class="shout_date"><?php echo $_smarty_tpl->tpl_vars['shout']->value['date'];?>
 ago <?php if ($_smarty_tpl->tpl_vars['user_is_gm']->value){?><a href="javascript:void(0)" onClick="Shoutbox.remove(this, <?php echo $_smarty_tpl->tpl_vars['shout']->value['id'];?>
)"><img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/delete.png" align="absmiddle"/></a><?php }?></span>
			<div class="shout_author <?php if ($_smarty_tpl->tpl_vars['shout']->value['is_gm']){?>shout_staff<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
profile/<?php echo $_smarty_tpl->tpl_vars['shout']->value['author'];?>
" data-tip="View profile"><?php if ($_smarty_tpl->tpl_vars['shout']->value['is_gm']){?><img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/icon_blizzard.gif" align="absmiddle"/>&nbsp;<?php }?> <?php echo $_smarty_tpl->tpl_vars['shout']->value['nickname'];?>
</a> said:</div>
			<?php echo $_smarty_tpl->tpl_vars['shout']->value['content'];?>

		</div>
	<?php } ?>
<?php }?><?php }} ?>