<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:31
         compiled from "application\modules\sidebox_shoutbox\views\shoutbox_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8758508437c7e1f527-16461287%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4f977813caf761aa997989a534583670a1104d96' => 
    array (
      0 => 'application\\modules\\sidebox_shoutbox\\views\\shoutbox_view.tpl',
      1 => 1344185378,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8758508437c7e1f527-16461287',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'count' => 0,
    'shoutsPerPage' => 0,
    'logged_in' => 0,
    'shouts' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c7e57631_69899292',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c7e57631_69899292')) {function content_508437c7e57631_69899292($_smarty_tpl) {?><script type="text/javascript">
	var shoutCount = <?php echo $_smarty_tpl->tpl_vars['count']->value;?>
,
		shoutsPerPage = <?php echo $_smarty_tpl->tpl_vars['shoutsPerPage']->value;?>
,
		currentShout = 0;

	
	var Shoutbox = {

		/**
		 * Load more shouts
		 * @param number
		 */
		load: function(number)
		{
			var element = $("#the_shouts");

			currentShout = number;

			element.slideUp(500, function()
			{
				$.get(Config.URL + "sidebox_shoutbox/shoutbox/get/" + number, function(data)
				{
					element.html(data).slideDown(300);

					if(currentShout != 0)
					{
						$("#shoutbox_newer").show();
					}
					else
					{
						$("#shoutbox_newer").hide();
					}

					if(currentShout + shoutsPerPage >= shoutCount)
					{
						$("#shoutbox_older").hide();
					}
					else
					{
						$("#shoutbox_older").show();
					}

				});
			});
		},

		submit: function()
		{
			var message = $("#shoutbox_content");

			if(message.val().length == 0
			|| message.val().length > 255)
			{
				UI.alert("The message must be between 0-255 characters long!");
			}
			else
			{
				// Disable fields
				message.attr("disabled", "disabled");
				$("#shoutbox_submit").attr("disabled", "disabled");

				$.post(Config.URL + "sidebox_shoutbox/shoutbox/submit", {message: message.val(), csrf_token_name: Config.CSRF}, function(data)
				{
					message.val("");
					message.removeAttr("disabled");
					$("#shoutbox_submit").removeAttr("disabled");
					$("#shoutbox_characters_remaining").html("0 / 255");

					var content = JSON.parse(data);

					$("#the_shouts").prepend('<div class="shout" id="my_shout_' + content.uniqueId + '" style="display:none">'+
												'<span class="shout_date">' + content.time + ' ago</span>' +
												'<div class="shout_author"><a href="' + Config.URL + 'profile/' + content.id + '" data-tip="View profile">' + content.name + '</a> said:</div>' +
												content.message +
											'</div>');

					$("#my_shout_" + content.uniqueId).slideDown(300, function()
					{
						Tooltip.refresh();
					});
				});
			}
		},

		remove: function(field, id)
		{
			$(field).parent().parent().slideUp(150, function()
			{
				$(this).remove();
			});
			
			$.get(Config.URL + "sidebox_shoutbox/shoutbox/delete/" + id, function(data)
			{
				console.log(data);
			});
		}
	};
	
</script>

<div id="shoutbox">
<?php if ($_smarty_tpl->tpl_vars['logged_in']->value==false){?>
	<form onSubmit="UI.alert('Please log in to shout!');return false;">
		<textarea name="shoutbox_content" placeholder="Please log in to shout!" disabled="disabled"></textarea>
		<div class="shout_characters_remaining"><span id="shoutbox_characters_remaining">0 / 255</span></div>
		<input type="submit" id="shoutbox_submit" value="Submit message"/>
	</form>
<?php }else{ ?>
	<form onSubmit="Shoutbox.submit(); return false">
		<textarea
			id="shoutbox_content"
			placeholder="Enter a message..."
			onFocus="this.style.height='70px';"
			onBlur="this.style.height='16px'"
			onkeyup="UI.limitCharacters(this, 'shoutbox_characters_remaining')"
			maxlength="255"
			spellcheck="false"></textarea>
		<div class="shout_characters_remaining"><span id="shoutbox_characters_remaining">0 / 255</span></div>
		<input type="submit" name="shoutbox_submit" value="Submit message" />
	</form>
<?php }?>

<div id="the_shouts"><?php echo $_smarty_tpl->tpl_vars['shouts']->value;?>
</div>

<?php if ($_smarty_tpl->tpl_vars['count']->value>5){?>
	<div id="shoutbox_view">
		<a href="javascript:void(0)" onClick="Shoutbox.load(currentShout - shoutsPerPage)" id="shoutbox_newer" style="display:none;">&larr; Newer</a>&nbsp;
		&nbsp;<a href="javascript:void(0)" onClick="Shoutbox.load(currentShout + shoutsPerPage)" id="shoutbox_older">Older &rarr;</a>
	</div>
<?php }?>
</div><?php }} ?>