<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\themes\raxezwow\views\page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:22890508437c7a82683-21401264%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b51e7a96d174d65d063b8125949d4011b9c59cec' => 
    array (
      0 => 'application\\themes\\raxezwow\\views\\page.tpl',
      1 => 1345043821,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22890508437c7a82683-21401264',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'headline' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7a90f24_08061168',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7a90f24_08061168')) {function content_508437c7a90f24_08061168($_smarty_tpl) {?><article class="main_box">
	<a class="main_box_top"><?php echo $_smarty_tpl->tpl_vars['headline']->value;?>
</a>
	<div class="main_box_body">
		<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

	</div>
</article><?php }} ?>