<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:37
         compiled from "application\modules\armory\views\search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11002508437cd95ec96-40954889%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0be43312f0104615634d302c36bc4a2c10150c6b' => 
    array (
      0 => 'application\\modules\\armory\\views\\search.tpl',
      1 => 1341247886,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11002508437cd95ec96-40954889',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437cd9911a9_15240375',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437cd9911a9_15240375')) {function content_508437cd9911a9_15240375($_smarty_tpl) {?><script type="text/javascript">
	if(typeof Search != "undefined")
	{
		Search.current = null;
	}
</script>
<div id="search_box">
	<form onSubmit="Search.submit();return false;">
		<table width="100%">
			<tr>
				<td><input type="text" placeholder="Search characters, items and guilds..." id="search_field" /></td>
				<td width="70px"><input type="submit" value="Search" /></td>
			<tr>
		</table>
		<div class="clear"></div>
	</form>

	<div id="search_results"></div>
</div><?php }} ?>