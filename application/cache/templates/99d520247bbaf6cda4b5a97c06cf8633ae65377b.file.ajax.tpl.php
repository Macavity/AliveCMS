<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\sidebox_status\views\ajax.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1784508437c7d3a850-24429192%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99d520247bbaf6cda4b5a97c06cf8633ae65377b' => 
    array (
      0 => 'application\\modules\\sidebox_status\\views\\ajax.tpl',
      1 => 1333470628,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1784508437c7d3a850-24429192',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'image_path' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7d4bfa9_11278373',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7d4bfa9_11278373')) {function content_508437c7d4bfa9_11278373($_smarty_tpl) {?><section id="update_status">
	<div style="text-align:center;margin-top:10px;margin-bottom:10px;">
		<img src="<?php echo $_smarty_tpl->tpl_vars['image_path']->value;?>
ajax.gif" />
	</div>
</section>

<script type="text/javascript">
	var Status = {
		statusField: $("#update_status"),
		
		/**
		 * Refresh the realm status
		 */
		update: function()
		{
			$.get(Config.URL + "sidebox_status/status_refresh", function(data)
			{
				Status.statusField.fadeOut(300, function()
				{
					Status.statusField.html(data);
					Status.statusField.fadeIn(300);
				})
			});
		}
	}

	Status.update();
</script><?php }} ?>