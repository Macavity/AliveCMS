<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
      <title>{$title}</title>
      <link href="{$favicon}" type="image/x-icon" rel="shortcut icon" />
        
        
      <!-- CSS -->
      <link rel="stylesheet" href="{$style_path}main.css" type="text/css" />
      {if $extra_css}<link rel="stylesheet" href="{$path}{$extra_css}" type="text/css" />{/if}
        <!-- / CSS Stylesheet -->
        
        <!-- Search engine related -->
        <meta name="description" content="{$description}" />
        <meta name="keywords" content="{$keywords}" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
        
        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        
        <!-- TODO compile scripts -->
        {if $stage == "production"}
          <script type="text/javascript" src="{$path}js/main.js"></script>
        {else}
          <script type="text/javascript" src="{$path}js/map_static.js"></script>
          <script type="text/javascript" src="{$path}js/swfobject.js"></script>
          <script type="text/javascript" src="{$path}js/function_debug.js"></script>
          <script type="text/javascript" src="{$path}js/function_console.js"></script>
          <script type="text/javascript" src="{$path}js/misc.js"></script>
          <script type="text/javascript" src="{$path}js/debug.dev.js"></script>
          <script type="text/javascript" src="{$js_path}core.js"></script>
          <script type="text/javascript" src="{$js_path}wow.js"></script>
          <script type="text/javascript" src="{$js_path}login.js"></script>
          <script type="text/javascript" src="{$js_path}tooltip.js"></script>
          <script type="text/javascript" src="{$path}js/router.js"></script>
          <script type="text/javascript" src="{$path}js/require.js"></script>
        {/if}

        <script type="text/javascript">

            var Config = {
                URL: "{$url}",            
                image_path: "{$image_path}",
                CSRF: getCookie('csrf_cookie_name'),

                UseFusionTooltip: {if $use_fcms_tooltip}1{else}0{/if},

                Slider: {
                    interval: {$slider_interval},
                    effect: "{$slider_style}",
                    id: "{$slider_id}"
                },
                
                voteReminder: {if $vote_reminder}1{else}0{/if},

                Theme: {
                    next: "{$slider.next}",
                    previous: "{$slider.previous}"
                }
            };

            var scripts = [
                "{$path}js/ui.js",
                "{$path}js/fusioneditor.js",
                "{$path}js/flux.min.js",
                "{$path}js/jquery.placeholder.min.js",
                "{$path}js/jquery.sort.js",
                "{$path}js/jquery.transit.min.js",
                "{$path}js/language.js",
                {if $extra_js},"{$path}{$extra_js}"{/if}
            ];

            if(typeof JSON == "undefined")
            {
                scripts.push("{$path}js/json2.js");
            }

            require(scripts, function()
            {
              $(document).ready(function()
              {
                {if $client_language}
                Language.set("{addslashes($client_language)}");
                {/if}

                UI.initialize();

                {if $extra_css}
                Router.loadedCSS.push("{$extra_css}");
                {/if}

                {if $extra_js}
                Router.loadedJS.push("{$extra_js}");
                {/if}
              });
            });
        </script>
        <script type="text/javascript">
        //<![CDATA[
            var SITE_HREF = '/';
            var DOMAIN_PATH = '{$path}';
            var SITE_PATH = '/';
            
            var global_nav_lang = ''; 
            var site_name = '{$server_name}';
            var site_link = 'http://www.wow-alive.de/" ?>';
            var forum_link = 'http://forum.wow-alive.de/forum.php" ?>';
            var armory_link = 'http://arsenal.wow-alive.de/" ?>';
            var xsToken = Config.CSRF;
        
            Core.staticUrl = 'http://forum.wow-alive.de/static-wow';
            Core.baseUrl = 'http://cms.wow-alive.de';
            Core.cdnUrl = 'http://cms.wow-alive.de';
            Core.project = 'wow';
            Core.locale = 'de-de';
            Core.buildRegion = 'eu';
            Core.loggedIn = false;
        //]]>
        </script>

        {if $analytics}
          <script type="text/javascript">
            // Google Analytics
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '{$analytics}']);
            _gaq.push(['_trackPageview']);

            (function() {
              var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
              ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

          </script>
        {/if}
        <!--[if IE 6]>
        <script type="text/javascript">
          //<![CDATA[
            try { document.execCommand('BackgroundImageCache', false, true) } catch(e) {}
          //]]>
        </script>
        <![endif]-->

        <!-- TS Viewer Sideboard -->
        <script type="text/javascript" src="{$path}js/wz_tooltip.js"></script>
        <script type="text/javascript" src="http://static.tsviewer.com/short_expire/js/ts3viewer_loader.js"></script>
        <script type="text/javascript" src="{$path}js/sideboard.js"></script>
        <!-- /TS Viewer Sideboard -->

    </head>