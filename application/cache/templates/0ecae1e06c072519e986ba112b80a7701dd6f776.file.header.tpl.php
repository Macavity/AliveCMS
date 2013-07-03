<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:32
         compiled from "application\views\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11202508437c8085e81-80990055%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0ecae1e06c072519e986ba112b80a7701dd6f776' => 
    array (
      0 => 'application\\views\\header.tpl',
      1 => 1350398852,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11202508437c8085e81-80990055',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'path' => 0,
    'style_path' => 0,
    'extra_css' => 0,
    'favicon' => 0,
    'description' => 0,
    'keywords' => 0,
    'cdn' => 0,
    'cookie_law' => 0,
    'url' => 0,
    'image_path' => 0,
    'use_fcms_tooltip' => 0,
    'slider_interval' => 0,
    'slider_style' => 0,
    'slider_id' => 0,
    'vote_reminder' => 0,
    'slider' => 0,
    'extra_js' => 0,
    'analytics' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c81d3c36_50770136',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c81d3c36_50770136')) {function content_508437c81d3c36_50770136($_smarty_tpl) {?><!DOCTYPE html>

<!--

 This website is powered by
  ______         _              _____ __  __  _____ 
 |  ____|       (_)            / ____|  \/  |/ ____|
 | |__ _   _ ___ _  ___  _ __ | |    | \  / | (___  
 |  __| | | / __| |/ _ \| '_ \| |    | |\/| |\___ \ 
 | |  | |_| \__ \ | (_) | | | | |____| |  | |____) |
 |_|   \__,_|___/_|\___/|_| |_|\_____|_|  |_|_____/ 

 raxezdev.com/fusioncms

-->

<html>
	<head>
		<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
		
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
css/default.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['style_path']->value;?>
cms.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['style_path']->value;?>
main.css" type="text/css" />
		<?php if ($_smarty_tpl->tpl_vars['extra_css']->value){?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
<?php echo $_smarty_tpl->tpl_vars['extra_css']->value;?>
" type="text/css" /><?php }?>
		
		<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" />
		
		<!-- Search engine related -->
		<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
		<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		
		<!-- Load scripts -->
		<script src="<?php if ($_smarty_tpl->tpl_vars['cdn']->value){?>//html5shiv.googlecode.com/svn/trunk/html5.js<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/html5shiv.js<?php }?>"></script>
		<script type="text/javascript" src="<?php if ($_smarty_tpl->tpl_vars['cdn']->value){?>https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/jquery.min.js<?php }?>"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/router.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/require.js"></script>
		<script type="text/javascript">

			if(!window.console)
			{
				var console = {
				
					log: function()
					{
						// Prevent stupid browsers from doing stupid things
					}
				};
			}

			function getCookie(c_name)
			{
				var i, x, y, ARRcookies = document.cookie.split(";");

				for(i = 0; i < ARRcookies.length;i++)
				{
					x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
					y = ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
					x = x.replace(/^\s+|\s+$/g,"");
					
					if(x==c_name)
					{
						return unescape(y);
					}
				}
			}

			function setCookie(c_name,value,exdays)
			{
				var exdate = new Date();
				exdate.setDate(exdate.getDate() + exdays);
				var c_value = escape(value) + ((exdays == null) ? "" : "; expires="+exdate.toUTCString());
				document.cookie = c_name + "=" + c_value;
			}

			var Config = {
				cookieLaw: "<?php echo $_smarty_tpl->tpl_vars['cookie_law']->value;?>
",
				URL: "<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
",			
				image_path: "<?php echo $_smarty_tpl->tpl_vars['image_path']->value;?>
",
				CSRF: getCookie('csrf_cookie_name'),

				UseFusionTooltip: <?php if ($_smarty_tpl->tpl_vars['use_fcms_tooltip']->value){?>1<?php }else{ ?>0<?php }?>,

				Slider: {
					interval: <?php echo $_smarty_tpl->tpl_vars['slider_interval']->value;?>
,
					effect: "<?php echo $_smarty_tpl->tpl_vars['slider_style']->value;?>
",
					id: "<?php echo $_smarty_tpl->tpl_vars['slider_id']->value;?>
"
				},
				
				voteReminder: <?php if ($_smarty_tpl->tpl_vars['vote_reminder']->value){?>1<?php }else{ ?>0<?php }?>,

				Theme: {
					next: "<?php echo $_smarty_tpl->tpl_vars['slider']->value['next'];?>
",
					previous: "<?php echo $_smarty_tpl->tpl_vars['slider']->value['previous'];?>
"
				}
			};

			var scripts = [
				"<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/ui.js",
				"<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/fusioneditor.js",
				"<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/flux.min.js",
				"<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/jquery.placeholder.min.js",
				"<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/jquery.sort.js",
				"<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/jquery.transit.min.js",
				<?php if ($_smarty_tpl->tpl_vars['extra_js']->value){?>,"<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
<?php echo $_smarty_tpl->tpl_vars['extra_js']->value;?>
"<?php }?>
			];

			if(typeof JSON == "undefined")
			{
				scripts.push("<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
js/json2.js");
			}

			require(scripts, function()
			{
				$(document).ready(function()
				{
					UI.initialize();

					<?php if ($_smarty_tpl->tpl_vars['extra_css']->value){?>
						Router.loadedCSS.push("<?php echo $_smarty_tpl->tpl_vars['extra_css']->value;?>
");
					<?php }?>

					<?php if ($_smarty_tpl->tpl_vars['extra_js']->value){?>
						Router.loadedJS.push("<?php echo $_smarty_tpl->tpl_vars['extra_js']->value;?>
");
					<?php }?>
				});
			});
		</script>

		<?php if ($_smarty_tpl->tpl_vars['analytics']->value){?>
		<script type="text/javascript">
		// Google Analytics
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<?php echo $_smarty_tpl->tpl_vars['analytics']->value;?>
']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

		</script>
		<?php }?>
	</head><?php }} ?>