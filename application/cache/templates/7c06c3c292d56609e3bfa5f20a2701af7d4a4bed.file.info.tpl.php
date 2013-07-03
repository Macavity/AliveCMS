<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\sidebox_info_login\views\info.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3675508437c7aa5ac2-80023394%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7c06c3c292d56609e3bfa5f20a2701af7d4a4bed' => 
    array (
      0 => 'application\\modules\\sidebox_info_login\\views\\info.tpl',
      1 => 1338389268,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3675508437c7aa5ac2-80023394',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'url' => 0,
    'expansion' => 0,
    'lastIp' => 0,
    'currentIp' => 0,
    'vp' => 0,
    'dp' => 0,
    'forum' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7b13b59_52247247',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7b13b59_52247247')) {function content_508437c7b13b59_52247247($_smarty_tpl) {?><section class="sidebox_info">
	<table width="100%">
		<tr>
			<td width="50%"><img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/plugin.png" align="absmiddle" /> Expansion</td>
			<td>
				<a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
ucp/expansion" data-tip="Change expansion" style="float:right;margin-right:10px;">
					<img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/cog.png" align="absbottom" />
				</a>
				<?php echo $_smarty_tpl->tpl_vars['expansion']->value;?>

			</td>
		</tr>
		<tr>
			<td><img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/computer_error.png" align="absmiddle" /> Last IP</td>
			<td><?php echo $_smarty_tpl->tpl_vars['lastIp']->value;?>
</td>
		</tr>
		<tr>
			<td><img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/computer.png" align="absmiddle" /> Current IP</td>
			<td><?php echo $_smarty_tpl->tpl_vars['currentIp']->value;?>
</td>
		</tr>
		<tr>
			<td><img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/lightning.png" align="absmiddle" /> VP</td>
			<td id="info_vp"><?php echo $_smarty_tpl->tpl_vars['vp']->value;?>
</td>
		</tr>
		<tr>
			<td><img src="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
application/images/icons/coins.png" align="absmiddle" /> DP</td>
			<td id="info_dp"><?php echo $_smarty_tpl->tpl_vars['dp']->value;?>
</td>
		</tr>

		<?php if ($_smarty_tpl->tpl_vars['forum']->value){?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><b>Forum rank:</b></td>
				<td>To do</td>
			</tr>
			<tr>
				<td><b>Reputation:</b></td>
				<td>To do</td>
			</tr>
			<tr>
				<td><b>Threads:</b></td>
				<td>To do</td>
			</tr>
			<tr>
				<td><b>Posts:</b></td>
				<td>To do</td>
			</tr>
		<?php }?>
	</table>
	<center>
		<a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
ucp" class="nice_button">User panel</a>
		<a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
logout" class="nice_button">Log out</a>
	</center>
</section><?php }} ?>