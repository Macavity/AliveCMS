<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:33
         compiled from "application\modules\character\views\icon_ajax.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4105508437c950f479-54284026%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5eba139d7d7b650be6c75cc43639637acef4c475' => 
    array (
      0 => 'application\\modules\\character\\views\\icon_ajax.tpl',
      1 => 1335700414,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4105508437c950f479-54284026',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'id' => 0,
    'url' => 0,
    'realm' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c957c545_74753225',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c957c545_74753225')) {function content_508437c957c545_74753225($_smarty_tpl) {?><span class="get_icon_<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
">
	<div class='item'>
		<a></a>
		<img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/armory/default/loading.gif" />
	</div>
</span>

<script type="text/javascript">
	function Interval<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
()
	{
		if(typeof Character != "undefined")
		{
			Character.getIcon(<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['realm']->value;?>
);
		}
		else
		{
			setTimeout(Interval<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
, 100);
		}
	}

	$(document).ready(function()
	{
		Interval<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
();
	});
</script><?php }} ?>