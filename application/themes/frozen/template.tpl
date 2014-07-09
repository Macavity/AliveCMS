{$head}
	<body>
		<section id="wrapper">
			{$modals}
            
            <div class="accp_register">
            	{if $isOnline}
                	<a href="./ucp" id="accp_button"><h1>Account Panel</h1></a>
                	{else}
                    <a href="./register" id="register_button"><h1>Register</h1></a> 
                {/if}
            </div>
            
            <a id="server-logo" href="./" title=""><!--{$serverName}--></a>
            <div class="top_menu">
                <ul id="top_menu">
                    {foreach from=$menu_top item=menu_1}
                        <li><a {$menu_1.link}>{$menu_1.name}<p></p></a><span></span></li>
                    {/foreach}
                </ul>
            </div>
            	
			<div id="main">
            	<div class="ice_ornament_slider"></div>
                <div class="ice_ornament_left_menu"></div>
                {if $show_sidebar}
                    <aside id="left">
                        <article>
                            <ul id="left_menu">
                                {foreach from=$menu_side item=menu_2}
                                    <li><a {$menu_2.link}><img src="{$image_path}bullet.png">{$menu_2.name}</a></li>
                                {/foreach}
                                <li class="bot_shadow"></li>
                            </ul>
                        </article>
                        {foreach from=$sideboxes item=sidebox}
                            <article id="{$sidebox.css_id}" class="sidebar-module">
                                <h1 class="top"><p>{$sidebox.name}</p></h1>
                                <section class="body">
                                    {$sidebox.data}
                                </section>
                            </article>
                        {/foreach}
                    </aside>
                {/if}

				<aside id="right">
                    {if $show_slider}
                        <section id="slider_bg">
                            <div id="slider">
                                {foreach from=$slider item=image}
                                    <a href="{$image.link}"><img src="{$image.image}" title="{$image.text}"/></a>
                                {/foreach}
                            </div>
                        </section>
                    {/if}

                    {if $show_sidebar == false}
                        {$breadcrumbs}
                    {/if}

					{$page}
				</aside>

				<div class="clear"></div>
			</div>
			<footer>
             	<a href="http://evil.duloclan.com" id="evil-logo" target="_blank" title="Design by EvilSystem"><p></p><span></span></a>
				<a href="http://raxezdev.com/fusioncms" id="fcms-logo" target="_blank"><p></p><span></span></a>
				<h3>{$serverName} &copy; Copyright 2014 </h3>
			</footer>

            <!-- Service Bar -->
            <section id="service">
                <ul class="service-bar">
                    <li class="service-cell service-home"><a href="/" tabindex="50" accesskey="1" title="Senzaii"><span class="glyphicon glyphicon-home"></span></a></li>
                    {if $isOnline}
                        <li class="service-cell service-welcome">
                            Willkommen, {if $is_staff}<span class="employee"></span>{/if}{$user_name}
                        </li>
                    {/if}
                    {foreach from=$menu_explore item=menu_item}
                        <li class="service-cell {$menu_item.css_class}"><a {$menu_item.link} class="service-link">{$menu_item.name}</a></li>
                    {/foreach}
                </ul>
            </section>

        </section>
	</body>
</html>