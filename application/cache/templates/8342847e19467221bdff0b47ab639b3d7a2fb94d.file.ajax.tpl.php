<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\online\views\ajax.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15189508437c7a2d1e1-68764428%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8342847e19467221bdff0b47ab639b3d7a2fb94d' => 
    array (
      0 => 'application\\modules\\online\\views\\ajax.tpl',
      1 => 1335569908,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15189508437c7a2d1e1-68764428',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'image_path' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7a75103_91703419',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7a75103_91703419')) {function content_508437c7a75103_91703419($_smarty_tpl) {?><section id="update_online_module">
	<div style="text-align:center;margin-top:10px;margin-bottom:10px;">
		<img src="<?php echo $_smarty_tpl->tpl_vars['image_path']->value;?>
ajax.gif" />
	</div>
</section>

<script type="text/javascript">
	var OnlineModule = {
		field: $("#update_online_module"),
		
		/**
		 * Refresh the realm status
		 */
		update: function()
		{
			$.get(Config.URL + "online/online_refresh", function(data)
			{
				OnlineModule.field.fadeOut(300, function()
				{
					OnlineModule.field.html(data);
					OnlineModule.field.fadeIn(300, function()
					{
						Tooltip.refresh();
					});
				})
			});
		}
	}

	OnlineModule.update();
</script><?php }} ?>