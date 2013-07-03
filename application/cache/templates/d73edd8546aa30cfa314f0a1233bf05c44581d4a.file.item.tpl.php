<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:35
         compiled from "application\modules\item\views\item.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6437508437cba08111-75778502%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd73edd8546aa30cfa314f0a1233bf05c44581d4a' => 
    array (
      0 => 'application\\modules\\item\\views\\item.tpl',
      1 => 1335708738,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6437508437cba08111-75778502',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'icon' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437cba4c5a2_67094481',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437cba4c5a2_67094481')) {function content_508437cba4c5a2_67094481($_smarty_tpl) {?><div id="item_space">
<?php echo $_smarty_tpl->tpl_vars['icon']->value;?>

<div class="item_bg tooltip"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</div>
<div class="clear"></div>
</div><?php }} ?>