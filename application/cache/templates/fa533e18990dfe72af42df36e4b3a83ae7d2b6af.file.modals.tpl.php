<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:32
         compiled from "application\themes\raxezwow\views\modals.tpl" */ ?>
<?php /*%%SmartyHeaderCode:28115508437c81e1932-71003481%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa533e18990dfe72af42df36e4b3a83ae7d2b6af' => 
    array (
      0 => 'application\\themes\\raxezwow\\views\\modals.tpl',
      1 => 1345043821,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28115508437c81e1932-71003481',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'vote_reminder' => 0,
    'url' => 0,
    'vote_reminder_image' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c81fc3b7_12058734',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c81fc3b7_12058734')) {function content_508437c81fc3b7_12058734($_smarty_tpl) {?><div id="popup_bg"></div>

<!-- confirm box -->
<div id="confirm" class="popup">
	<h1 class="popup_question" id="confirm_question"></h1>

	<div class="popup_links">
		<a href="javascript:void(0)" class="popup_button" id="confirm_button"></a>
		<a href="javascript:void(0)" class="popup_hide" id="confirm_hide" onClick="UI.hidePopup()">
			Cancel
		</a>
		<div style="clear:both;"></div>
	</div>
</div>

<!-- alert box -->
<div id="alert" class="popup">
	<h1 class="popup_message" id="alert_message"></h1>

	<div class="popup_links">
		<a href="javascript:void(0)" class="popup_button" id="alert_button">Okay</a>
		<div style="clear:both;"></div>
	</div>
</div>

<?php if ($_smarty_tpl->tpl_vars['vote_reminder']->value){?>
	<!-- Vote reminder popup -->
	<div id="vote_reminder">
		<a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
vote">
			<img src="<?php echo $_smarty_tpl->tpl_vars['vote_reminder_image']->value;?>
" />
		</a>
	</div>
<?php }?><?php }} ?>