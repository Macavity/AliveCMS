{$head}
<body id="{$controller}-{$method}" class="module-{$controller}">
<section id="wrapper">
    {$modals}
    <header>
        <a name="top"></a>
        <div id="search-bar">
            <form action="/search/" method="get" id="search-form">
                <div>
                    <div class="input-group">
                        <input type="text" name="q" id="search-field" maxlength="200"
                               tabindex="40" alt="Durchsucht das Arsenal und mehr..."
                               value="Durchsucht das Arsenal und mehr..." />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></spanSuchen</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <h1 class="logo">
            <a href="/news">Alive</a>
        </h1>
        <nav class="main-menu">
            <ul id="top_menu">
                {foreach from=$menu_top item=menu_1}
                    <li><a {$menu_1.link}>{$menu_1.name}</a></li>
                {/foreach}
            </ul>
            {$userplate}
        </nav>
    </header>
    <div id="main">
        {if $show_sidebar}
            <aside id="left" class="container">

            <!-- REGISTER Banner -->
            <a href="register" class="sidebar-banner">
                <h2>Account erstellen</h2>
                <p>Werde Teil unserer Community</p>
            </a>
            <!-- REGISTER Banner . End -->

            {foreach from=$sideboxes item=sidebox}
                <article id="{$sidebox.css_id}">
                    <h2 class="top">{$sidebox.name}</h2>
                    <section class="body">
                        {$sidebox.data}
                    </section>
                </article>
            {/foreach}

            <!-- VOTE Banner -->
            <a href="vote" class="vote-b"><p></p><span></span></a>
            <!-- VOTE Banner . End -->

            <ul id="left_menu">
                {foreach from=$menu_side item=menu_2}
                    <li><a {$menu_2.link}><img src="{$image_path}bullet.png">{$menu_2.name}</a></li>
                {/foreach}
            </ul>

            {if $show_external_more}
                <article class="sidebar-module" id="sidebar-forums">
                    <h2 class="title-forums"><a href="forum.php">Letzte Forendiskussionen</a></h2>

                    <div class="sidebar-content poptopic-list">
                        {$external_forum_posts|unescape}
                    </div>
                </article>
            {/if}

        </aside>
        {/if}

        <aside id="{if $show_sidebar}right{else}full{/if}" class="container">
            {if $show_slider}
                <section id="slider_bg">
                    <div id="slider">
                        {foreach from=$slider item=image}
                            <a href="{$image.link}"><img src="{$image.image}" title="{$image.text}"/></a>
                        {/foreach}
                    </div>
                </section>
            {/if}

            {$breadcrumbs}

            {if !empty($section_title)}
                <div class="row">
                    <h3 class="section-title col-md-12">{$section_title}</h3>
                </div>
            {/if}

            {$page}
        </aside>

        <div class="clear"></div>
    </div>
    <footer>
        <center>
            <div id="logos">
                <!--<a href="http://www.wow-alive.de/" id="alivelogo" target="_blank"></a>-->
                <a href="http://fusion-hub.com" id="cmslogo" target="_blank"></a>
            </div>
            <div id="siteinfo">
                &copy; <span color="#695946">WoW Alive</span> <br/>
            </div>
        </center>
    </footer>

    <!-- Service Bar -->
    <section id="service">
        <ul class="service-bar">
            <li class="service-cell service-home"><a href="/" tabindex="50" accesskey="1" title="ALive"><span class="glyphicon glyphicon-home"></span></a></li>
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