<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\sidebox_visitors\views\visitors.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13623508437c7e778f8-09385521%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '365d1d967b5574f81090c78de6d44db7a55e822c' => 
    array (
      0 => 'application\\modules\\sidebox_visitors\\views\\visitors.tpl',
      1 => 1343253778,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13623508437c7e778f8-09385521',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'count' => 0,
    'word' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7e8d126_85820754',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7e8d126_85820754')) {function content_508437c7e8d126_85820754($_smarty_tpl) {?><script type="text/javascript">
	var Visitors = {

		show: function(link)
		{
			$(link).parent().fadeOut(100);
			$("#all_visitors").html('<center><img src="' + Config.image_path + 'ajax.gif" /></center>').show();

			$.get(Config.URL + "sidebox_visitors/visitors/getAll", function(data)
			{
				$("#all_visitors").fadeOut(100, function()
				{
					$(this).html(data).fadeIn(100);
				});
			});
		}
	}
</script>

There are <b><?php echo $_smarty_tpl->tpl_vars['count']->value;?>
</b> <?php echo $_smarty_tpl->tpl_vars['word']->value;?>
 online <span>(<a href="javascript:void(0)" onClick="Visitors.show(this)">who?</a>)</span>
<div id="all_visitors" style="margin-top:10px;display:none;"></div><?php }} ?>