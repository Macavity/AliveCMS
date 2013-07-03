<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\sidebox_toppvp\views\pvp.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13508437c7f30566-18994033%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ee30dca75b6857280ead820976201d596b8d346f' => 
    array (
      0 => 'application\\modules\\sidebox_toppvp\\views\\pvp.tpl',
      1 => 1341687680,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13508437c7f30566-18994033',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'min_realm' => 0,
    'realms' => 0,
    'key' => 0,
    'max_realm' => 0,
    'realm' => 0,
    'realm_html' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c8055987_38841987',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c8055987_38841987')) {function content_508437c8055987_38841987($_smarty_tpl) {?><script type="text/javascript">
	
	var TopPvP = {

		current: 0,

		show: function(id)
		{	
			$("#toppvp_realm_" + this.current).fadeOut(150, function()
			{
				TopPvP.current = id;
				$("#toppvp_realm_" + id).fadeIn(150);
			});
		}
	};
	

	$(document).ready(function()
	{
		TopPvP.current = <?php echo $_smarty_tpl->tpl_vars['min_realm']->value-1;?>
;
		$("#toppvp_realm_<?php echo $_smarty_tpl->tpl_vars['min_realm']->value-1;?>
").fadeIn(150);
	});
</script>
<div id="toppvp">
	<?php  $_smarty_tpl->tpl_vars['realm'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['realm']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['realms']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['realm']->key => $_smarty_tpl->tpl_vars['realm']->value){
$_smarty_tpl->tpl_vars['realm']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['realm']->key;
?>
		<div class="toppvp_realm" id="toppvp_realm_<?php echo $_smarty_tpl->tpl_vars['key']->value-1;?>
" style="display:none;">
			<div class="toppvp_select">
				<?php if ($_smarty_tpl->tpl_vars['key']->value!=$_smarty_tpl->tpl_vars['max_realm']->value){?><a href="javascript:void(0)" onClick="TopPvP.show(TopPvP.current + 1)" class="toppvp_next" data-tip="Next realm">&rarr;</a><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['key']->value!=$_smarty_tpl->tpl_vars['min_realm']->value&&$_smarty_tpl->tpl_vars['max_realm']->value!=1){?><a href="javascript:void(0)" onClick="TopPvP.show(TopPvP.current - 1)" class="toppvp_previous"  data-tip="Previous realm">&larr;</a><?php }?>
				<?php echo $_smarty_tpl->tpl_vars['realm']->value->getName();?>

			</div>
			<div class="toppvp_data">
				<?php echo $_smarty_tpl->tpl_vars['realm_html']->value[$_smarty_tpl->tpl_vars['key']->value];?>

			</div>
		</div>
	<?php } ?>
	<div style="clear:both;"></div>
</div><?php }} ?>